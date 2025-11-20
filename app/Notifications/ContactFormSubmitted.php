<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactFormSubmitted extends Notification
{
    use Queueable;

    public function __construct(public array $data)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Contact Form Submission: ' . $this->data['subject'])
            ->greeting('New Contact Form Message')
            ->line('You have received a new message from the contact form.')
            ->line('**Name:** ' . $this->data['name'])
            ->line('**Email:** ' . $this->data['email'])
            ->line('**Subject:** ' . $this->data['subject'])
            ->line('**Message:**')
            ->line($this->data['message'])
            ->line('Please respond to: ' . $this->data['email']);
    }
}
