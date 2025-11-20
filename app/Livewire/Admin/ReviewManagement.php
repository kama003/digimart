<?php

namespace App\Livewire\Admin;

use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Review Management')]
class ReviewManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $filterRating = '';
    public $filterStatus = '';
    public $sortBy = 'recent';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterRating' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'sortBy' => ['except' => 'recent'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function approveReview($reviewId)
    {
        $review = Review::findOrFail($reviewId);
        $review->update([
            'is_approved' => true,
            'approved_at' => now(),
        ]);

        session()->flash('success', 'Review approved successfully.');
    }

    public function rejectReview($reviewId)
    {
        $review = Review::findOrFail($reviewId);
        $review->update([
            'is_approved' => false,
            'approved_at' => null,
        ]);

        session()->flash('success', 'Review rejected successfully.');
    }

    public function deleteReview($reviewId)
    {
        Review::findOrFail($reviewId)->delete();
        session()->flash('success', 'Review deleted successfully.');
    }

    public function render()
    {
        $query = Review::with(['product', 'user']);

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('comment', 'like', '%' . $this->search . '%')
                    ->orWhere('title', 'like', '%' . $this->search . '%')
                    ->orWhereHas('product', function ($q) {
                        $q->where('title', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('user', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        // Filter by rating
        if ($this->filterRating) {
            $query->where('rating', $this->filterRating);
        }

        // Filter by status
        if ($this->filterStatus === 'approved') {
            $query->where('is_approved', true);
        } elseif ($this->filterStatus === 'pending') {
            $query->where('is_approved', false);
        }

        // Sort
        switch ($this->sortBy) {
            case 'rating_high':
                $query->orderBy('rating', 'desc');
                break;
            case 'rating_low':
                $query->orderBy('rating', 'asc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'recent':
            default:
                $query->latest();
                break;
        }

        $reviews = $query->paginate(20);

        $stats = [
            'total' => Review::count(),
            'approved' => Review::where('is_approved', true)->count(),
            'pending' => Review::where('is_approved', false)->count(),
            'average_rating' => round(Review::where('is_approved', true)->avg('rating'), 1),
        ];

        return view('livewire.admin.review-management', [
            'reviews' => $reviews,
            'stats' => $stats,
        ]);
    }
}
