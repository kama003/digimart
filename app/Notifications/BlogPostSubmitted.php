<?php

namespace App\Notifications;

use App\Models\BlogPost;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BlogPostSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public BlogPost $blogPost)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Blog Post Submitted for Review')
            ->line('A new blog post has been submitted and requires your review.')
            ->line('Title: ' . $this->blogPost->title)
            ->line('Author: ' . $this->blogPost->user->name)
            ->action('Review Blog Post', url('/admin/blog'))
            ->line('Please review and approve or reject this post.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'blog_post_id' => $this->blogPost->id,
            'title' => $this->blogPost->title,
            'author' => $this->blogPost->user->name,
            'message' => 'New blog post submitted for review',
        ];
    }
}
