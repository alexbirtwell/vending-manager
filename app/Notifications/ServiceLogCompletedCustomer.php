<?php

namespace App\Notifications;

use App\Models\ServiceLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ServiceLogCompletedCustomer extends Notification
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
                    ->subject('Service Log Completed - ' . $this->serviceLog->id)
                    ->line('This service log has been completed by ' . $this->serviceLog->assignee->name)
                    ->line('Machine: ' . $this->serviceLog->machine->machine_number)
                    ->line('Service Description:  ' . $this->serviceLog->description)
                    ->line('Latest Note: ' . $this->serviceLog->latestNote)
                    ->line('Please contact us if there are any problems.');

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
