<?php

namespace App\Livewire\Cart;

use App\Services\CartService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class CartIcon extends Component
{
    /**
     * Get the cart item count
     */
    #[Computed]
    public function itemCount(): int
    {
        return app(CartService::class)->getCount();
    }

    /**
     * Refresh the cart icon when cart changes
     */
    #[On('cart-updated')]
    public function refreshCart(): void
    {
        // This method triggers a re-render which will recompute itemCount
        $this->dispatch('$refresh');
    }

    public function render()
    {
        return view('livewire.cart.cart-icon');
    }
}
