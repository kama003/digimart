<?php

namespace App\Livewire\Cart;

use App\Services\CartService;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Shopping Cart')]
class CartPage extends Component
{
    /**
     * Get cart items
     */
    #[Computed]
    public function items(): Collection
    {
        return app(CartService::class)->getItems();
    }

    /**
     * Get cart total
     */
    #[Computed]
    public function total(): float
    {
        return app(CartService::class)->getTotal();
    }

    /**
     * Remove an item from the cart
     */
    public function removeItem(int $productId): void
    {
        app(CartService::class)->remove($productId);
        
        $this->dispatch('cart-updated');
        
        session()->flash('success', 'Item removed from cart');
    }

    /**
     * Update item quantity
     */
    public function updateQuantity(int $productId, int $quantity): void
    {
        if ($quantity < 1) {
            $this->removeItem($productId);
            return;
        }

        app(CartService::class)->update($productId, $quantity);
        
        $this->dispatch('cart-updated');
    }

    /**
     * Clear all items from the cart
     */
    public function clearCart(): void
    {
        app(CartService::class)->clear();
        
        $this->dispatch('cart-updated');
        
        session()->flash('success', 'Cart cleared');
    }

    /**
     * Refresh cart when items are updated
     */
    #[On('item-removed')]
    #[On('item-updated')]
    public function refreshCart(): void
    {
        unset($this->items);
        unset($this->total);
    }

    public function render()
    {
        return view('livewire.cart.cart-page')
            ->layout('components.layouts.guest', ['title' => 'Shopping Cart']);
    }
}
