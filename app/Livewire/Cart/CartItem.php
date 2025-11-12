<?php

namespace App\Livewire\Cart;

use App\Services\CartService;
use Livewire\Component;

class CartItem extends Component
{
    public array $item;

    public function mount(array $item): void
    {
        $this->item = $item;
    }

    /**
     * Update the quantity of this cart item
     */
    public function updateQuantity(int $quantity): void
    {
        if ($quantity < 1) {
            return;
        }

        app(CartService::class)->update($this->item['product_id'], $quantity);
        
        $this->dispatch('cart-updated');
        $this->dispatch('item-updated', productId: $this->item['product_id']);
    }

    /**
     * Remove this item from the cart
     */
    public function remove(): void
    {
        app(CartService::class)->remove($this->item['product_id']);
        
        $this->dispatch('cart-updated');
        $this->dispatch('item-removed', productId: $this->item['product_id']);
    }

    public function render()
    {
        return view('livewire.cart.cart-item');
    }
}
