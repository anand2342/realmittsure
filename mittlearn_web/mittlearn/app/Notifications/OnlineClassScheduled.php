<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OnlineClassScheduled extends Notification
{
    use Queueable;

    protected $classDetails;

    public function __construct($classDetails)
    {
        $this->classDetails = $classDetails;
    }

   
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
        ->line('A new online class has been scheduled.')
        ->line('Title: ' . $this->classDetails['title'])
        ->line('Date: ' . $this->classDetails['class_date'])
        ->line('Time: ' . $this->classDetails['start_time'] . ' - ' . $this->classDetails['end_time'])
        ->action('Join Class', $this->classDetails['join_link'])
        ->line('Thank you for using our application!');
    }

    
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
