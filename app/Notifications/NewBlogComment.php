<?php

namespace App\Notifications;

use App\Models\BlogComment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBlogComment extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public BlogComment $comment)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'blog_post_id' => $this->comment->blog_post_id,
            'comment_id' => $this->comment->id,
            'commenter' => $this->comment->user->name,
            'post_title' => $this->comment->blogPost->title,
            'message' => $this->comment->user->name . ' commented on your blog post',
        ];
    }
}
