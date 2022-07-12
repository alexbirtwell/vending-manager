<?php

namespace App\Notifications;

use App\Models\ServiceLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ServiceLogCreatedCustomer extends Notification
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
                    ->line('We have recieved your vending service request')
                    ->line('Details: ')
                    ->line('Machine: ' . $this->serviceLog->machine->machine_number)
                    ->line('Machine Details: ' . $this->serviceLog->machine->summary)
                    ->line('Service Description:  ' . $this->serviceLog->description)
                    ->line('We have assigned '. $this->serviceLog->assignee->name . ' to complete your request which should be completed by the end of the day '. $this->serviceLog->date_expected->format('d/m/Y'))
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
