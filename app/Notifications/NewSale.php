<?php

namespace App\Notifications;

use App\Models\Product;
use App\Models\TransactionItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewSale extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public TransactionItem $transactionItem
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
        $product = $this->transactionItem->product;
        
        return (new MailMessage)
            ->subject('New Sale - ' . $product->title)
            ->greeting('Congratulations!')
            ->line('You have made a new sale!')
            ->line('Product: ' . $product->title)
            ->line('Sale Price: $' . number_format($this->transactionItem->price, 2))
            ->line('Your Earnings: $' . number_format($this->transactionItem->seller_amount, 2))
            ->line('Platform Commission: $' . number_format($this->transactionItem->commission, 2))
            ->action('View Dashboard', route('seller.dashboard'))
            ->line('Keep up the great work!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $product = $this->transactionItem->product;
        
        return [
            'transaction_item_id' => $this->transactionItem->id,
            'product_id' => $product->id,
            'product_title' => $product->title,
            'price' => $this->transactionItem->price,
            'seller_amount' => $this->transactionItem->seller_amount,
            'commission' => $this->transactionItem->commission,
            'message' => 'New sale: ' . $product->title . ' - You earned $' . number_format($this->transactionItem->seller_amount, 2),
        ];
    }
}
