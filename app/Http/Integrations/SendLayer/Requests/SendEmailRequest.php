<?php

namespace App\Http\Integrations\SendLayer\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class SendEmailRequest extends Request implements HasBody
{
    use HasJsonBody;

    /**
     * The HTTP method of the request.
     */
    protected Method $method = Method::POST;

    public function __construct(
        private array $payload
    ) {
    }

    /**
     * The endpoint for the request.
     */
    public function resolveEndpoint(): string
    {
        return '/email';
    }

    protected function defaultBody(): array
    {
        return $this->payload;
    }
}
