<?php

namespace App\Livewire\Admin;

use App\Enums\ProductType;
use App\Models\Category;
use App\Models\Product;
use App\Services\StorageService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
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
    public $is_approved = false;
    public $is_active = true;
    public $rejection_reason = '';
    public $thumbnail;
    public $file;
    
    public $showDeleteModal = false;

    public function mount(Product $product)
    {
        // Authorization check - admin only
        Gate::authorize('update', $product);

        $this->product = $product;
        $this->title = $product->title;
        $this->description = $product->description;
        $this->short_description = $product->short_description;
        $this->category_id = $product->category_id;
        $this->product_type = $product->product_type->value;
        $this->price = $product->price;
        $this->license_type = $product->license_type;
        $this->is_approved = $product->is_approved;
        $this->is_active = $product->is_active;
        $this->rejection_reason = $product->rejection_reason ?? '';
    }

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'product_type' => 'required|in:audio,video,3d,template,graphic',
            'price' => 'required|numeric|min:0|max:999999.99',
            'license_type' => 'required|string|max:255',
            'is_approved' => 'boolean',
            'is_active' => 'boolean',
            'rejection_reason' => 'nullable|string|max:500',
            'thumbnail' => 'nullable|image|max:5120', // 5MB max
            'file' => 'nullable|file|max:512000', // 500MB max
        ];
    }

    protected $messages = [
        'thumbnail.image' => 'The thumbnail must be an image file.',
        'thumbnail.max' => 'The thumbnail must not exceed 5MB.',
        'file.max' => 'The product file must not exceed 500MB.',
    ];

    public function update()
    {
        $this->validate();

        $storageService = app(StorageService::class);
        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'category_id' => $this->category_id,
            'product_type' => $this->product_type,
            'price' => $this->price,
            'license_type' => $this->license_type,
            'is_approved' => $this->is_approved,
            'is_active' => $this->is_active,
            'rejection_reason' => $this->rejection_reason ?: null,
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

        session()->flash('success', 'Product updated successfully.');

        return redirect()->route('admin.products');
    }

    public function openDeleteModal()
    {
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
    }

    public function deleteProduct()
    {
        // Authorize the action
        Gate::authorize('delete', $this->product);

        // Soft delete the product
        $this->product->delete();

        session()->flash('success', 'Product has been deleted successfully.');

        return redirect()->route('admin.products');
    }

    public function render()
    {
        $categories = Category::orderBy('order')->orderBy('name')->get();
        $productTypes = ProductType::cases();

        return view('livewire.admin.product-edit', [
            'categories' => $categories,
            'productTypes' => $productTypes,
        ]);
    }
}
