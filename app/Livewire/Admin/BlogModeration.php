<?php

namespace App\Livewire\Admin;

use App\Models\BlogPost;
use App\Notifications\BlogPostApproved;
use Livewire\Component;
use Livewire\WithPagination;

class BlogModeration extends Component
{
    use WithPagination;

    public $filter = 'pending';
    public $reviewingId = null;
    public $rejectionReason = '';

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function startReview($id)
    {
        $this->reviewingId = $id;
        $this->rejectionReason = '';
    }

    public function approve()
    {
        if (!$this->reviewingId) {
            return;
        }

        try {
            $post = BlogPost::findOrFail($this->reviewingId);
            $post->update([
                'is_approved' => true,
                'rejection_reason' => null,
            ]);

            $post->user->notify(new BlogPostApproved($post, true));

            session()->flash('success', 'Blog post approved successfully.');
            $this->reviewingId = null;
            $this->resetPage();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to approve blog post.');
        }
    }

    public function reject()
    {
        $this->validate([
            'rejectionReason' => 'required|string|max:500',
        ]);

        try {
            $post = BlogPost::findOrFail($this->reviewingId);
            $post->update([
                'is_approved' => false,
                'rejection_reason' => $this->rejectionReason,
            ]);

            $post->user->notify(new BlogPostApproved($post, false, $this->rejectionReason));

            session()->flash('success', 'Blog post rejected.');
            $this->reviewingId = null;
            $this->rejectionReason = '';
            $this->resetPage();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to reject blog post.');
        }
    }

    public function cancelReview()
    {
        $this->reviewingId = null;
        $this->rejectionReason = '';
    }

    public function render()
    {
        $query = BlogPost::with(['user', 'product']);

        switch ($this->filter) {
            case 'pending':
                $query->where('is_approved', false);
                break;
            case 'approved':
                $query->where('is_approved', true);
                break;
            case 'all':
                // No filter
                break;
        }

        $posts = $query->orderBy('created_at', 'desc')->paginate(10);

        $stats = [
            'pending' => BlogPost::where('is_approved', false)->count(),
            'approved' => BlogPost::where('is_approved', true)->count(),
            'total' => BlogPost::count(),
        ];

        return view('livewire.admin.blog-moderation', [
            'posts' => $posts,
            'stats' => $stats,
        ]);
    }
}
