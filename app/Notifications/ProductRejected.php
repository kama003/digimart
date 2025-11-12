<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductRejected extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Product $product, public string $reason)
    {
        //
    }

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
            ->subject('Product Rejected - ' . $this->product->title)
            ->greeting('Product Review Update')
            ->line('Your product "' . $this->product->title . '" has been reviewed and unfortunately cannot be approved at this time.')
            ->line('**Reason for rejection:**')
            ->line($this->reason)
            ->line('You can edit your product and resubmit it for review.')
            ->action('Edit Product', route('seller.products.edit', $this->product))
            ->line('If you have any questions, please contact our support team.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'product_id' => $this->product->id,
            'product_title' => $this->product->title,
            'product_slug' => $this->product->slug,
            'rejection_reason' => $this->reason,
            'message' => 'Your product "' . $this->product->title . '" has been rejected.',
        ];
    }
}
