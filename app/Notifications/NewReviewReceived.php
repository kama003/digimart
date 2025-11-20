<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReviewReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Review $review)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Review on Your Product')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You received a new ' . $this->review->rating . '-star review on your product.')
            ->line('**Product:** ' . $this->review->product->title)
            ->line('**Rating:** ' . str_repeat('⭐', $this->review->rating))
            ->when($this->review->title, function ($mail) {
                return $mail->line('**Title:** ' . $this->review->title);
            })
            ->line('**Review:** ' . $this->review->comment)
            ->action('View Product', route('product.show', $this->review->product->slug))
            ->line('Thank you for being a valued seller!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'review_id' => $this->review->id,
            'product_id' => $this->review->product_id,
            'product_title' => $this->review->product->title,
            'product_slug' => $this->review->product->slug,
            'rating' => $this->review->rating,
            'reviewer_name' => $this->review->user->name,
            'message' => $this->review->user->name . ' left a ' . $this->review->rating . '-star review on ' . $this->review->product->title,
        ];
    }
}
