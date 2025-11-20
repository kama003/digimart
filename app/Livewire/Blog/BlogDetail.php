<?php

namespace App\Livewire\Blog;

use App\Models\BlogComment;
use App\Models\BlogLike;
use App\Models\BlogPost;
use App\Notifications\NewBlogComment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BlogDetail extends Component
{
    public BlogPost $post;
    public $commentContent = '';
    public $replyingTo = null;

    public function mount($slug)
    {
        $this->post = BlogPost::with(['user', 'product', 'approvedComments.user', 'approvedComments.approvedReplies.user'])
            ->where('slug', $slug)
            ->approved()
            ->published()
            ->firstOrFail();

        // Increment views
        $this->post->incrementViews();
    }

    public function toggleLike()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $existingLike = BlogLike::where('blog_post_id', $this->post->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $this->post->decrement('likes_count');
        } else {
            BlogLike::create([
                'blog_post_id' => $this->post->id,
                'user_id' => Auth::id(),
            ]);
            $this->post->increment('likes_count');
        }

        $this->post->refresh();
    }

    public function postComment()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate([
            'commentContent' => 'required|string|max:1000',
        ]);

        try {
            $comment = BlogComment::create([
                'blog_post_id' => $this->post->id,
                'user_id' => Auth::id(),
                'parent_id' => $this->replyingTo,
                'content' => $this->commentContent,
                'is_approved' => true,
            ]);

            $this->post->increment('comments_count');

            // Notify post author
            if ($this->post->user_id !== Auth::id()) {
                $this->post->user->notify(new NewBlogComment($comment));
            }

            $this->commentContent = '';
            $this->replyingTo = null;
            $this->post->refresh();

            session()->flash('comment_success', 'Comment posted successfully!');
        } catch (\Exception $e) {
            session()->flash('comment_error', 'Failed to post comment.');
        }
    }

    public function replyTo($commentId)
    {
        $this->replyingTo = $commentId;
    }

    public function cancelReply()
    {
        $this->replyingTo = null;
        $this->commentContent = '';
    }

    public function render()
    {
        $isLiked = Auth::check() && $this->post->isLikedBy(Auth::user());

        return view('livewire.blog.blog-detail', [
            'isLiked' => $isLiked,
        ])->layout('components.layouts.guest', ['title' => $this->post->title]);
    }
}
