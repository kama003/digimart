<?php

namespace App\Livewire\Seller;

use App\Enums\ProductType;
use App\Models\Category;
use App\Models\Product;
use App\Services\StorageService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductEdit extends Component
{
    use WithFileUploads, AuthorizesRequests;

    public Product $product;
    
    public $title = '';
    public $description = '';
    public $short_description = '';
    public $category_id = '';
    public $product_type = '';
    public $price = '';
    public $license_type = '';
    public $thumbnail;
    public $file;

    public function mount(Product $product)
    {
        // Authorization check - user must own the product or be admin
        if ($product->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $this->product = $product;
        $this->title = $product->title;
        $this->description = $product->description;
        $this->short_description = $product->short_description;
        $this->category_id = $product->category_id;
        $this->product_type = $product->product_type->value;
        $this->price = $product->price;
        $this->license_type = $product->license_type;
    }

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'short_description' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'product_type' => 'required|in:audio,video,3d,template,graphic',
            'price' => 'required|numeric|min:0.01|max:999999.99',
            'license_type' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120', // 5MB max
            'file' => 'nullable|file|max:512000', // 500MB max
        ];
    }

    protected $messages = [
        'title.required' => 'Please provide a product title.',
        'title.max' => 'Product title must not exceed 255 characters.',
        'description.required' => 'Please provide a detailed description.',
        'description.min' => 'Description must be at least 50 characters.',
        'short_description.max' => 'Short description must not exceed 500 characters.',
        'category_id.required' => 'Please select a category.',
        'category_id.exists' => 'The selected category is invalid.',
        'product_type.required' => 'Please select a product type.',
        'product_type.in' => 'Invalid product type selected.',
        'price.required' => 'Please set a price for your product.',
        'price.min' => 'Price must be at least $0.01.',
        'price.max' => 'Price cannot exceed $999,999.99.',
        'license_type.required' => 'Please specify the license type.',
        'thumbnail.image' => 'The thumbnail must be an image file.',
        'thumbnail.mimes' => 'Thumbnail must be a JPEG, JPG, PNG, or WebP file.',
        'thumbnail.max' => 'Thumbnail size cannot exceed 5MB.',
        'file.max' => 'File size cannot exceed 500MB.',
    ];

    public function update()
    {
        $this->validate();

        try {
            $storageService = app(StorageService::class);
            $data = [
                'title' => $this->title,
                'description' => $this->description,
                'short_description' => $this->short_description,
                'category_id' => $this->category_id,
                'product_type' => $this->product_type,
                'price' => $this->price,
                'license_type' => $this->license_type,
            ];

            // Regenerate slug if title changed
            if ($this->product->title !== $this->title) {
                $data['slug'] = Str::slug($this->title);
            }

            // Upload new thumbnail if provided
            if ($this->thumbnail) {
                // Delete old thumbnail
                if ($this->product->thumbnail_path) {
                    $storageService->deleteFile($this->product->thumbnail_path);
                }
                
                $data['thumbnail_path'] = $storageService->uploadFile($this->thumbnail, 'products/thumbnails', 'public');
            }

            // Upload new file if provided
            if ($this->file) {
                // Delete old file
                if ($this->product->file_path) {
                    $storageService->deleteFile($this->product->file_path);
                }
                
                $data['file_path'] = $storageService->uploadFile($this->file, 'products/files', 'private');
                $data['file_size'] = $this->file->getSize();
            }

            $this->product->update($data);

            \Illuminate\Support\Facades\Log::info('Product updated successfully', [
                'product_id' => $this->product->id,
                'user_id' => Auth::id(),
                'title' => $this->title,
                'thumbnail_updated' => $this->thumbnail !== null,
                'file_updated' => $this->file !== null,
            ]);

            session()->flash('success', 'Product updated successfully.');

            return redirect()->route('seller.products.index');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Product update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'product_id' => $this->product->id,
                'user_id' => Auth::id(),
                'title' => $this->title,
                'timestamp' => now()->toIso8601String(),
            ]);

            session()->flash('error', 'Failed to update product. Please try again or contact support if the problem persists.');
            
            // Don't redirect, stay on the form so user can retry
        }
    }

    public function render()
    {
        $categories = Category::orderBy('order')->orderBy('name')->get();
        $productTypes = ProductType::cases();

        return view('livewire.seller.product-edit', [
            'categories' => $categories,
            'productTypes' => $productTypes,
        ]);
    }
}
