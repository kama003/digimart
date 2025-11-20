<?php

namespace App\Livewire\Product;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductSearch extends Component
{
    use WithPagination;

    public $slug = ''; // Category slug from route parameter
    public $keyword = '';
    public $category_id = '';
    public $min_price = '';
    public $max_price = '';
    public $product_type = '';

    protected $queryString = [
        'keyword' => ['except' => ''],
        'min_price' => ['except' => ''],
        'max_price' => ['except' => ''],
        'product_type' => ['except' => ''],
    ];

    /**
     * Reset pagination when filters change
     */
    public function updated($propertyName)
    {
        $this->resetPage();
    }

    /**
     * Mount the component
     */
    public function mount($slug = null)
    {
        $this->slug = $slug;
        
        // If slug is provided, find the category and set category_id
        if ($this->slug) {
            $category = Category::where('slug', $this->slug)->first();
            if ($category) {
                $this->category_id = $category->id;
            }
        }
    }

    /**
     * Handle category change from dropdown
     */
    #[\Livewire\Attributes\On('category-changed')]
    public function handleCategoryChange($slug)
    {
        if (empty($slug)) {
            // Redirect to search page without category
            return redirect()->route('products.search', [
                'keyword' => $this->keyword,
                'min_price' => $this->min_price,
                'max_price' => $this->max_price,
                'product_type' => $this->product_type,
            ]);
        } else {
            // Redirect to category page
            return redirect()->route('category.show', [
                'slug' => $slug,
                'keyword' => $this->keyword,
                'min_price' => $this->min_price,
                'max_price' => $this->max_price,
                'product_type' => $this->product_type,
            ]);
        }
    }

    /**
     * Reset all filters
     */
    public function resetFilters()
    {
        $this->keyword = '';
        $this->min_price = '';
        $this->max_price = '';
        $this->product_type = '';
        $this->resetPage();
        
        // If on category page, redirect to search page
        if ($this->slug) {
            return redirect()->route('products.search');
        }
    }

    /**
     * Render the component
     */
    public function render()
    {
        // Use select() to limit columns for better performance
        $query = Product::select([
                'id', 'user_id', 'category_id', 'title', 'slug', 
                'short_description', 'price', 'thumbnail_path', 
                'product_type', 'downloads_count', 'created_at'
            ])
            ->with(['category:id,name,slug', 'user:id,name'])
            ->where('is_approved', true)
            ->where('is_active', true);

        // Filter by keyword (search in title and description)
        if (!empty($this->keyword)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->keyword . '%')
                    ->orWhere('description', 'like', '%' . $this->keyword . '%')
                    ->orWhere('short_description', 'like', '%' . $this->keyword . '%');
            });
        }

        // Filter by category
        if (!empty($this->category_id)) {
            $query->where('category_id', $this->category_id);
        }

        // Filter by price range
        if (!empty($this->min_price) && is_numeric($this->min_price)) {
            $query->where('price', '>=', $this->min_price);
        }

        if (!empty($this->max_price) && is_numeric($this->max_price)) {
            $query->where('price', '<=', $this->max_price);
        }

        // Filter by product type
        if (!empty($this->product_type)) {
            $query->where('product_type', $this->product_type);
        }

        // Order by newest first
        $query->orderBy('created_at', 'desc');

        $products = $query->paginate(12);
        
        // Cache categories for 1 hour
        $categories = cache()->remember('search.categories', 3600, function () {
            return Category::orderBy('order')->orderBy('name')->get();
        });

        // Get current category if slug is provided
        $currentCategory = null;
        if ($this->slug) {
            $currentCategory = Category::where('slug', $this->slug)->first();
        }

        $title = $currentCategory ? $currentCategory->name : 'Search Products';

        return view('livewire.product.product-search', [
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $currentCategory,
        ])->layout('components.layouts.guest', ['title' => $title]);
    }
}
