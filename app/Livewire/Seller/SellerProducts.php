<?php

namespace App\Livewire\Seller;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class SellerProducts extends Component
{
    use WithPagination;

    public function toggleActive($productId)
    {
        $product = Product::where('id', $productId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $product->update([
            'is_active' => !$product->is_active,
        ]);

        session()->flash('success', 'Product status updated successfully.');
    }

    public function deleteProduct($productId)
    {
        $product = Product::where('id', $productId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $product->delete();

        session()->flash('success', 'Product deleted successfully.');
    }

    public function render()
    {
        $products = Product::where('user_id', Auth::id())
            ->withCount('downloads')
            ->with('category')
            ->latest()
            ->paginate(10);

        return view('livewire.seller.seller-products', [
            'products' => $products,
        ]);
    }
}
