<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PurchaseConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Transaction $transaction,
        public array $downloadLinks
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
        $message = (new MailMessage)
            ->subject('Purchase Confirmation - Order #' . $this->transaction->id)
            ->greeting('Thank you for your purchase!')
            ->line('Your order has been confirmed and your digital products are ready to download.')
            ->line('Order ID: #' . $this->transaction->id)
            ->line('Total Amount: $' . number_format($this->transaction->amount, 2))
            ->line('Payment Method: ' . ucfirst($this->transaction->payment_gateway));

        if (!empty($this->downloadLinks)) {
            $message->line('**Your Download Links:**');
            foreach ($this->downloadLinks as $link) {
                $message->line('• ' . $link['product_title'] . ': [Download](' . $link['url'] . ')');
            }
            $message->line('Download links will expire in 24 hours.');
        }

        return $message
            ->action('View Purchase History', route('customer.purchases'))
            ->line('Thank you for shopping with us!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'transaction_id' => $this->transaction->id,
            'amount' => $this->transaction->amount,
            'payment_gateway' => $this->transaction->payment_gateway,
            'download_links' => $this->downloadLinks,
            'message' => 'Your purchase of $' . number_format($this->transaction->amount, 2) . ' has been confirmed.',
        ];
    }
}
