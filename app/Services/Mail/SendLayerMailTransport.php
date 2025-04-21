<?php

namespace App\Services\Mail;

use App\Http\Integrations\SendLayer\Requests\SendEmailRequest;
use App\Http\Integrations\SendLayer\SendLayerConnector;
use function count;

use const FILTER_VALIDATE_BOOL;

use Illuminate\Support\Str;

use function in_array;
use function is_resource;

use const JSON_THROW_ON_ERROR;

use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\MessageConverter;

class SendLayerMailTransport extends AbstractTransport
{
    private const FORBIDDEN_HEADERS = [
        'Date', 'X-CSA-Complaints', 'Message-Id', 'X-MJ-StatisticsContactsListID',
        'DomainKey-Status', 'Received-SPF', 'Authentication-Results', 'Received',
        'From', 'Sender', 'Subject', 'To', 'Cc', 'Bcc', 'Reply-To', 'Return-Path', 'Delivered-To', 'DKIM-Signature',
        'X-Feedback-Id', 'X-Mailjet-Segmentation', 'List-Id', 'X-MJ-MID', 'X-MJ-ErrorMessage',
        'X-Mailjet-Debug', 'User-Agent', 'X-Mailer', 'X-MJ-WorkflowID',
    ];

    private const HEADER_TO_MESSAGE = [
        'X-MJ-TemplateLanguage' => ['TemplateLanguage', 'bool'],
        'X-MJ-TemplateID' => ['TemplateID', 'int'],
        'X-MJ-TemplateErrorReporting' => ['TemplateErrorReporting', 'json'],
        'X-MJ-TemplateErrorDeliver' => ['TemplateErrorDeliver', 'bool'],
        'X-MJ-Vars' => ['Variables', 'json'],
        'X-MJ-CustomID' => ['CustomID', 'string'],
        'X-MJ-EventPayload' => ['EventPayload', 'string'],
        'X-Mailjet-Campaign' => ['CustomCampaign', 'string'],
        'X-Mailjet-DeduplicateCampaign' => ['DeduplicateCampaign', 'bool'],
        'X-Mailjet-Prio' => ['Priority', 'int'],
        'X-Mailjet-TrackClick' => ['TrackClick', 'string'],
        'X-Mailjet-TrackOpen' => ['TrackOpen', 'string'],
    ];

    public function __construct(
        protected SendLayerConnector $client,
    ) {
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $this->setAuth($message->getEnvelope()->getSender()->getAddress());

        $payload = $this->getPayload($email, $message->getEnvelope());

        $response = $this->client->send(
            request: new SendEmailRequest($payload),
        );

        $message->setMessageId($response->json('Messages.0.To.0.MessageID') ?? '');
    }

    public function __toString(): string
    {
        return 'sendlayer';
    }

    private function setAuth(string $fromEmail): void
    {
        $this->client->withHeaderAuth(
            'Bearer ' . config('mail.mailers.sendlayer.api_key')
        );
    }

    /**
     * @return array<string, string>
     */
    private function getPayload(Email $email, Envelope $envelope): array
    {
        $html = $email->getHtmlBody();
        if ($html !== null && is_resource($html)) {
            if (stream_get_meta_data($html)['seekable'] ?? false) {
                rewind($html);
            }
            $html = stream_get_contents($html);
        }
        [$attachments, $inlines, $html] = $this->prepareAttachments($email, $html);
        $message = [
            'From' => $this->formatAddress($envelope->getSender()),
            'ReplyTo' => [$this->formatAddress($envelope->getSender())],
            'To' => $this->formatAddresses($this->getRecipients($email, $envelope)),
            'Subject' => $email->getSubject(),
            'Attachments' => $attachments,
        ];
        if ($emails = $email->getCc()) {
            $message['Cc'] = $this->formatAddresses($emails);
        }
        if ($emails = $email->getBcc()) {
            $message['Bcc'] = $this->formatAddresses($emails);
        }
        if ($emails = $email->getReplyTo()) {
            if (1 < $length = count($emails)) {
                throw new TransportException(sprintf(
                    'Sendlayers\'s API only supports one Reply-To email, %d given.',
                    $length
                ));
            }
            $message['ReplyTo'] = $this->formatReplyTo($emails[0]);
        }
        //If from address hasn't been included add it from the envelope without @mail
        if (! isset($message['ReplyTo'])) {
            $message['ReplyTo'] = $this->formatReplyTo($envelope->getSender());
        }


        $message['ContentType'] = 'HTML';
        $message['HTMLContent'] = $html ?? $email->getHtmlBody() ?? $email->getTextBody();



        foreach ($email->getHeaders()->all() as $header) {
            if ($convertConf = self::HEADER_TO_MESSAGE[$header->getName()] ?? false) {
                $message[$convertConf[0]] = $this->castCustomHeader($header->getBodyAsString(), $convertConf[1]);

                continue;
            }
            if (in_array($header->getName(), self::FORBIDDEN_HEADERS, true)) {
                continue;
            }

            $message['Headers'][$header->getName()] = $header->getBodyAsString();
        }

        return $message;
    }

    /**
     * @param  array<string, string>  $addresses
     * @return array<string, string>
     */
    private function formatAddresses(array $addresses): array
    {
        return array_map($this->formatAddress(...), $addresses);
    }

    /**
     * @return array<string, string>
     */
    private function formatAddress(Address $address): array
    {
        return [
            'email' => $address->getAddress(),
            'name' => $address->getName(),
        ];
    }

    /**
     * All from addresses with Mailjet need to be from @mail.tmstor.es or @mail.townsend-music.com.
     *
     * @promotional from addresses are allowed to be from @promotional.tmstor.es
     *
     * @return array<string, string>
     */
    private function formatFromAddress(Address $address): array
    {
        $email = $address->getAddress();
        $name = $this->getOverrideName($address->getName());

        if (Str::contains($email, '@promotional.')) {
            return [
                'Email' => $email,
                'Name' => $name,
            ];
        }

        if (! Str::contains($email, '@mail.')) {
            $email = Str::replaceFirst('@', '@mail.', $email);
        }

        return [
            'Email' => $email,
            'Name' => $name,
        ];
    }

    /**
     * When sending from @promotional the replyTo should not include this subdomain.
     *
     * @return array<string, string>
     */
    private function formatReplyTo(Address $address): array
    {
        return [
            'Email' => $address->getAddress(),
            'Name' => $address->getName(),
        ];
    }

    /**
     * @return array<string, string>
     */
    private function prepareAttachments(Email $email, ?string $html): array
    {
        $attachments = $inlines = [];
        foreach ($email->getAttachments() as $attachment) {
            $headers = $attachment->getPreparedHeaders();
            $filename = $headers->getHeaderParameter('Content-Disposition', 'filename');
            $formattedAttachment = [
                'ContentType' => $attachment->getMediaType() . '/' . $attachment->getMediaSubtype(),
                'Filename' => $filename,
                'Base64Content' => $attachment->bodyToString(),
            ];
            if ($headers->getHeaderBody('Content-Disposition') === 'inline') {
                $formattedAttachment['ContentID'] = $headers->getHeaderParameter('Content-Disposition', 'name');
                $inlines[] = $formattedAttachment;
            } else {
                $attachments[] = $formattedAttachment;
            }
        }

        return [$attachments, $inlines, $html];
    }

    /**
     * @return array<string, string>
     */
    protected function getRecipients(Email $email, Envelope $envelope): array
    {
        return array_filter(
            $envelope->getRecipients(),
            fn (Address $address) => in_array($address, array_merge($email->getCc(), $email->getBcc()), true) === false
        );
    }

    private function castCustomHeader(string $value, string $type): mixed
    {
        return match ($type) {
            'bool' => filter_var($value, FILTER_VALIDATE_BOOL),
            'int' => (int) $value,
            'json' => json_decode($value, true, 512, JSON_THROW_ON_ERROR),
            'string' => $value,
        };
    }

    private function getOverrideName(string $name): string
    {
        return $name;
    }
}
