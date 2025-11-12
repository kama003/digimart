<?php

namespace App\Notifications;

use App\Models\Withdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WithdrawalRejected extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Withdrawal $withdrawal,
        public string $reason
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
            ->subject('Withdrawal Request Rejected')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Unfortunately, your withdrawal request has been rejected.')
            ->line('Amount: $' . number_format($this->withdrawal->amount, 2))
            ->line('Method: ' . ucfirst(str_replace('_', ' ', $this->withdrawal->method)))
            ->line('Reason: ' . $this->reason)
            ->line('If you have any questions, please contact our support team.')
            ->action('View Withdrawal History', route('seller.withdrawals'))
            ->line('Thank you for your understanding.');
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
            'reason' => $this->reason,
            'message' => 'Your withdrawal request for $' . number_format($this->withdrawal->amount, 2) . ' has been rejected.',
        ];
    }
}
