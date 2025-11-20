<?php

namespace App\Livewire\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class ProductReviews extends Component
{
    use WithPagination;

    public Product $product;
    public $filterRating = null;
    public $sortBy = 'recent'; // recent, helpful, rating_high, rating_low

    protected $queryString = [
        'filterRating' => ['except' => null],
        'sortBy' => ['except' => 'recent'],
    ];

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function filterByRating($rating)
    {
        $this->filterRating = $rating === $this->filterRating ? null : $rating;
        $this->resetPage();
    }

    public function clearFilter()
    {
        $this->filterRating = null;
        $this->resetPage();
    }

    public function setSortBy($sortBy)
    {
        $this->sortBy = $sortBy;
        $this->resetPage();
    }

    #[On('review-submitted')]
    public function refreshReviews()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = $this->product->approvedReviews()->with('user');

        // Filter by rating
        if ($this->filterRating) {
            $query->where('rating', $this->filterRating);
        }

        // Sort
        switch ($this->sortBy) {
            case 'rating_high':
                $query->orderBy('rating', 'desc');
                break;
            case 'rating_low':
                $query->orderBy('rating', 'asc');
                break;
            case 'recent':
            default:
                $query->latest();
                break;
        }

        $reviews = $query->paginate(10);

        $averageRating = $this->product->averageRating();
        $totalReviews = $this->product->reviewsCount();
        $ratingDistribution = $this->product->ratingDistribution();

        return view('livewire.product.product-reviews', [
            'reviews' => $reviews,
            'averageRating' => $averageRating,
            'totalReviews' => $totalReviews,
            'ratingDistribution' => $ratingDistribution,
        ]);
    }
}
