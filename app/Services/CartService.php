<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    private const SESSION_KEY = 'cart';

    /**
     * Add a product to the cart
     */
    public function add(int $productId, int $quantity = 1): void
    {
        if (Auth::check()) {
            $this->addToDatabase($productId, $quantity);
        } else {
            $this->addToSession($productId, $quantity);
        }
    }

    /**
     * Remove a product from the cart
     */
    public function remove(int $productId): void
    {
        if (Auth::check()) {
            Cart::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->delete();
        } else {
            $cart = $this->getSessionCart();
            unset($cart[$productId]);
            Session::put(self::SESSION_KEY, $cart);
        }
    }

    /**
     * Update product quantity in the cart
     */
    public function update(int $productId, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->remove($productId);
            return;
        }

        if (Auth::check()) {
            Cart::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->update(['quantity' => $quantity]);
        } else {
            $cart = $this->getSessionCart();
            if (isset($cart[$productId])) {
                $cart[$productId] = $quantity;
                Session::put(self::SESSION_KEY, $cart);
            }
        }
    }

    /**
     * Clear all items from the cart
     */
    public function clear(): void
    {
        if (Auth::check()) {
            Cart::where('user_id', Auth::id())->delete();
        } else {
            Session::forget(self::SESSION_KEY);
        }
    }

    /**
     * Get all cart items with product details
     */
    public function getItems(): Collection
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())
                ->with('product.category', 'product.user')
                ->get()
                ->map(function ($cartItem) {
                    return [
                        'cart_id' => $cartItem->id,
                        'product_id' => $cartItem->product_id,
                        'product' => $cartItem->product,
                        'quantity' => $cartItem->quantity,
                        'subtotal' => $cartItem->product->price * $cartItem->quantity,
                    ];
                });
        } else {
            $cart = $this->getSessionCart();
            $productIds = array_keys($cart);
            
            if (empty($productIds)) {
                return collect([]);
            }

            $products = Product::with('category', 'user')
                ->whereIn('id', $productIds)
                ->where('is_approved', true)
                ->where('is_active', true)
                ->get()
                ->keyBy('id');

            return collect($cart)->map(function ($quantity, $productId) use ($products) {
                $product = $products->get($productId);
                
                if (!$product) {
                    return null;
                }

                return [
                    'cart_id' => null,
                    'product_id' => $productId,
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $product->price * $quantity,
                ];
            })->filter()->values();
        }
    }

    /**
     * Get the total price of all items in the cart
     */
    public function getTotal(): float
    {
        return $this->getItems()->sum('subtotal');
    }

    /**
     * Get the total number of items in the cart
     */
    public function getCount(): int
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())->sum('quantity');
        } else {
            return array_sum($this->getSessionCart());
        }
    }

    /**
     * Merge session cart into database when user logs in
     */
    public function mergeSessionCart(): void
    {
        if (!Auth::check()) {
            return;
        }

        $sessionCart = $this->getSessionCart();
        
        if (empty($sessionCart)) {
            return;
        }

        foreach ($sessionCart as $productId => $quantity) {
            $this->addToDatabase($productId, $quantity);
        }

        Session::forget(self::SESSION_KEY);
    }

    /**
     * Add product to database cart
     */
    private function addToDatabase(int $productId, int $quantity): void
    {
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }
    }

    /**
     * Add product to session cart
     */
    private function addToSession(int $productId, int $quantity): void
    {
        $cart = $this->getSessionCart();
        
        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }

        Session::put(self::SESSION_KEY, $cart);
    }

    /**
     * Get session cart data
     */
    private function getSessionCart(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }
}
