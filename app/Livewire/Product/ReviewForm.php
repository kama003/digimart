<?php

namespace App\Livewire\Product;

use App\Models\Product;
use App\Models\Review;
use Livewire\Component;
use Livewire\Attributes\Validate;

class ReviewForm extends Component
{
    public Product $product;

    #[Validate('required|integer|min:1|max:5')]
    public $rating = 0;

    #[Validate('nullable|string|max:255')]
    public $title = '';

    #[Validate('required|string|min:10|max:1000')]
    public $comment = '';

    public $hasPurchased = false;
    public $hasReviewed = false;

    public function mount(Product $product)
    {
        $this->product = $product;
        
        if (auth()->check()) {
            $this->hasPurchased = auth()->user()->hasPurchased($product);
            $this->hasReviewed = auth()->user()->hasReviewed($product);
        }
    }

    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    public function submit()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if ($this->hasReviewed) {
            session()->flash('error', 'You have already reviewed this product.');
            return;
        }

        $this->validate();

        $transaction = auth()->user()->transactions()
            ->where('status', 'completed')
            ->whereHas('transactionItems', function ($query) {
                $query->where('product_id', $this->product->id);
            })
            ->first();

        $review = Review::create([
            'product_id' => $this->product->id,
            'user_id' => auth()->id(),
            'transaction_id' => $transaction?->id,
            'rating' => $this->rating,
            'title' => $this->title,
            'comment' => $this->comment,
            'is_verified_purchase' => $this->hasPurchased,
            'is_approved' => true,
            'approved_at' => now(),
        ]);

        // Notify the seller
        $this->product->user->notify(new \App\Notifications\NewReviewReceived($review));

        session()->flash('success', 'Thank you for your review!');

        $this->reset(['rating', 'title', 'comment']);
        $this->hasReviewed = true;

        $this->dispatch('review-submitted');
    }

    public function render()
    {
        return view('livewire.product.review-form');
    }
}
