<?php

namespace App\Notifications;

use App\Models\ServiceLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ServiceLogCreated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ServiceLog $serviceLog)
    {
        $this->serviceLog = $serviceLog;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Service Log Created - ' . $this->serviceLog->id)
                    ->line('A new service log has been created.')
                    ->line('Details: ')
                    ->line('Date Expected: ' . $this->serviceLog->date_expected->format('d/m/Y') . " (".$this->serviceLog->date_expected->diffForHumans().")")
                    ->line('Site: ' . "{$this->serviceLog->machine->site->name}")
                    ->line('Address: ' . "{$this->serviceLog->fullAddress}")
                    ->line('Machine: ' . $this->serviceLog->machine->machine_number)
                    ->line('Machine Details: ' . $this->serviceLog->machine->summary)
                    ->line('Service Description:  ' . $this->serviceLog->description)
                    ->action('View Full Details', url($this->serviceLog->url));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
