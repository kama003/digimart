<?php

namespace App\Livewire\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    public int $perPage = 12;

    public function loadMore()
    {
        $this->perPage += 12;
    }

    public function render()
    {
        // Use select() to limit columns for better performance
        $products = Product::select([
                'id', 'user_id', 'category_id', 'title', 'slug', 
                'short_description', 'price', 'thumbnail_path', 
                'product_type', 'downloads_count', 'created_at'
            ])
            ->with(['category:id,name,slug', 'user:id,name'])
            ->where('is_approved', true)
            ->where('is_active', true)
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.product.product-list', [
            'products' => $products,
        ]);
    }
}
