<?php

namespace App\Livewire\Product;

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class ProductDetail extends Component
{
    public Product $product;

    public function mount($slug)
    {
        // Load product with eager loading for category and user (seller) relationships
        $this->product = Product::with(['category', 'user'])
            ->where('slug', $slug)
            ->where('is_approved', true)
            ->where('is_active', true)
            ->firstOrFail();
    }

    public function addToCart()
    {
        // Add product to cart using CartService
        app(\App\Services\CartService::class)->add($this->product->id, 1);
        
        session()->flash('success', 'Product added to cart successfully!');
        
        // Dispatch event to update cart icon
        $this->dispatch('cart-updated');
    }

    #[Layout('components.layouts.guest')]
    #[Title('Product Detail')]
    public function render()
    {
        return view('livewire.product.product-detail');
    }
}
