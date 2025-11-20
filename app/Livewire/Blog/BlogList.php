<?php

namespace App\Livewire\Blog;

use App\Models\BlogPost;
use Livewire\Component;
use Livewire\WithPagination;

class BlogList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'latest';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'latest'],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSortBy()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = BlogPost::with(['user', 'product'])
            ->approved()
            ->published();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('excerpt', 'like', '%' . $this->search . '%')
                    ->orWhere('content', 'like', '%' . $this->search . '%');
            });
        }

        switch ($this->sortBy) {
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'liked':
                $query->orderBy('likes_count', 'desc');
                break;
            case 'commented':
                $query->orderBy('comments_count', 'desc');
                break;
            default:
                $query->orderBy('published_at', 'desc');
        }

        $posts = $query->paginate(12);

        return view('livewire.blog.blog-list', [
            'posts' => $posts,
        ])->layout('components.layouts.guest', ['title' => 'Blog']);
    }
}
