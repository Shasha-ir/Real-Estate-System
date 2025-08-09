<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class UserRegisteredNotification extends Notification
{
    protected $customId;

    public function __construct($customId)
    {
        $this->customId = $customId;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('IR Real Estates - Registration Successful')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("Your registration was successful.")
            ->line("Your issued ID is: **{$this->customId}**")
            ->line("Please keep this ID safe. You will need it to log in to the system.")
            ->action('Go to Login', url('/login'))
            ->line('Thank you for using IR Real Estates!');
    }
}
