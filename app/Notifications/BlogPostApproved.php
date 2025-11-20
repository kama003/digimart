<?php

namespace App\Notifications;

use App\Models\BlogPost;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BlogPostApproved extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public BlogPost $blogPost, public bool $approved = true, public ?string $reason = null)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject($this->approved ? 'Blog Post Approved' : 'Blog Post Rejected');

        if ($this->approved) {
            $message->line('Congratulations! Your blog post has been approved.')
                ->line('Title: ' . $this->blogPost->title)
                ->action('View Blog Post', route('blog.show', $this->blogPost->slug))
                ->line('Your post is now live and visible to all users.');
        } else {
            $message->line('Your blog post has been rejected.')
                ->line('Title: ' . $this->blogPost->title)
                ->line('Reason: ' . ($this->reason ?? 'No reason provided'))
                ->action('Edit Blog Post', route('seller.blog.edit', $this->blogPost->id))
                ->line('Please make the necessary changes and resubmit.');
        }

        return $message;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'blog_post_id' => $this->blogPost->id,
            'title' => $this->blogPost->title,
            'approved' => $this->approved,
            'reason' => $this->reason,
            'message' => $this->approved ? 'Your blog post has been approved' : 'Your blog post has been rejected',
        ];
    }
}
