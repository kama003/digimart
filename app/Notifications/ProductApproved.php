<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductApproved extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Product $product)
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
            ->subject('Product Approved - ' . $this->product->title)
            ->greeting('Great news!')
            ->line('Your product "' . $this->product->title . '" has been approved and is now live on the marketplace.')
            ->line('Customers can now discover and purchase your product.')
            ->action('View Product', route('product.show', $this->product->slug))
            ->line('Thank you for contributing to our marketplace!');
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
            'message' => 'Your product "' . $this->product->title . '" has been approved.',
        ];
    }
}
