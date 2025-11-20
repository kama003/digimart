<?php

namespace App\Livewire\Seller;

use App\Models\BlogPost;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class BlogManagement extends Component
{
    use WithPagination;

    public $deleteConfirmId = null;

    public function confirmDelete($id)
    {
        $this->deleteConfirmId = $id;
    }

    public function delete()
    {
        if (!$this->deleteConfirmId) {
            return;
        }

        try {
            $post = BlogPost::where('id', $this->deleteConfirmId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $post->delete();

            session()->flash('success', 'Blog post deleted successfully.');
            $this->deleteConfirmId = null;
            $this->resetPage();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete blog post.');
            $this->deleteConfirmId = null;
        }
    }

    public function cancelDelete()
    {
        $this->deleteConfirmId = null;
    }

    public function render()
    {
        $posts = BlogPost::where('user_id', Auth::id())
            ->with(['product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.seller.blog-management', [
            'posts' => $posts,
        ]);
    }
}
