<?php

namespace App\Notifications;

use App\Models\Withdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WithdrawalApproved extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Withdrawal $withdrawal
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Withdrawal Request Approved')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your withdrawal request has been approved.')
            ->line('Amount: $' . number_format($this->withdrawal->amount, 2))
            ->line('Method: ' . ucfirst(str_replace('_', ' ', $this->withdrawal->method)))
            ->line('The funds will be transferred to your account shortly.')
            ->action('View Withdrawal History', route('seller.withdrawals'))
            ->line('Thank you for being a valued seller on our platform!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'withdrawal_id' => $this->withdrawal->id,
            'amount' => $this->withdrawal->amount,
            'method' => $this->withdrawal->method,
            'status' => $this->withdrawal->status,
            'message' => 'Your withdrawal request for $' . number_format($this->withdrawal->amount, 2) . ' has been approved.',
        ];
    }
}
