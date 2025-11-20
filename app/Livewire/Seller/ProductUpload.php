<?php

namespace App\Livewire\Seller;

use App\Enums\ProductType;
use App\Models\Category;
use App\Models\Product;
use App\Services\StorageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductUpload extends Component
{
    use WithFileUploads;

    public $title = '';
    public $description = '';
    public $short_description = '';
    public $category_id = '';
    public $product_type = '';
    public $price = '';
    public $license_type = '';
    public $thumbnail;
    public $file;
    public $preview;

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
            'thumbnail' => 'required|image|mimes:jpeg,jpg,png,webp|max:5120', // 5MB max
            'file' => 'required|file|max:512000', // 500MB max
            'preview' => 'nullable|file|max:51200', // 50MB max for preview
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
        'thumbnail.required' => 'Please upload a thumbnail image.',
        'thumbnail.image' => 'The thumbnail must be an image file.',
        'thumbnail.mimes' => 'Thumbnail must be a JPEG, JPG, PNG, or WebP file.',
        'thumbnail.max' => 'Thumbnail size cannot exceed 5MB.',
        'file.required' => 'Please upload the product file.',
        'file.max' => 'File size cannot exceed 500MB.',
        'preview.max' => 'Preview file size cannot exceed 50MB.',
    ];

    /**
     * Validate MIME types for additional security
     */
    protected function validateMimeTypes(): void
    {
        // Allowed MIME types for thumbnails
        $allowedThumbnailMimes = [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/webp',
        ];

        // Allowed MIME types for product files based on type
        $allowedFileMimes = [
            'audio' => ['audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/mp3', 'audio/x-wav'],
            'video' => ['video/mp4', 'video/mpeg', 'video/quicktime', 'video/x-msvideo', 'video/webm'],
            '3d' => ['application/octet-stream', 'model/gltf-binary', 'model/gltf+json', 'application/zip'],
            'template' => ['application/zip', 'application/x-zip-compressed', 'application/octet-stream'],
            'graphic' => ['image/png', 'image/jpeg', 'image/svg+xml', 'application/zip', 'application/postscript'],
        ];

        // Validate thumbnail MIME type
        $thumbnailMime = $this->thumbnail->getMimeType();
        if (!in_array($thumbnailMime, $allowedThumbnailMimes)) {
            throw new \Illuminate\Validation\ValidationException(
                validator([], []),
                ['thumbnail' => ['Invalid thumbnail file type. Only JPEG, PNG, and WebP images are allowed.']]
            );
        }

        // Validate product file MIME type
        $fileMime = $this->file->getMimeType();
        $allowedMimes = $allowedFileMimes[$this->product_type] ?? [];
        
        if (!in_array($fileMime, $allowedMimes)) {
            throw new \Illuminate\Validation\ValidationException(
                validator([], []),
                ['file' => ['Invalid file type for the selected product type.']]
            );
        }
    }

    public function save()
    {
        $this->validate();

        try {
            // Additional MIME type validation for security
            $this->validateMimeTypes();

            $storageService = app(StorageService::class);

            // Upload thumbnail (public so it can be displayed)
            $thumbnailPath = $storageService->uploadFile($this->thumbnail, 'products/thumbnails', 'public');

            // Upload product file (private for security)
            $filePath = $storageService->uploadFile($this->file, 'products/files', 'private');

            // Upload preview file (public but watermarked/limited)
            $previewPath = null;
            if ($this->preview) {
                $previewPath = $storageService->uploadFile($this->preview, 'products/previews', 'public');
            }

            // Get file size
            $fileSize = $this->file->getSize();

            // Create product
            $product = Product::create([
                'user_id' => Auth::id(),
                'title' => $this->title,
                'slug' => Str::slug($this->title),
                'description' => $this->description,
                'short_description' => $this->short_description,
                'category_id' => $this->category_id,
                'product_type' => $this->product_type,
                'price' => $this->price,
                'license_type' => $this->license_type,
                'thumbnail_path' => $thumbnailPath,
                'file_path' => $filePath,
                'preview_path' => $previewPath,
                'file_size' => $fileSize,
                'is_approved' => false,
                'is_active' => true,
            ]);

            \Illuminate\Support\Facades\Log::info('Product uploaded successfully', [
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'title' => $this->title,
                'product_type' => $this->product_type,
                'file_size' => $fileSize,
            ]);

            session()->flash('success', 'Product uploaded successfully! It will be reviewed by our team before going live.');

            return redirect()->route('seller.products.index');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Product upload failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'title' => $this->title,
                'product_type' => $this->product_type,
                'timestamp' => now()->toIso8601String(),
            ]);

            session()->flash('error', 'Failed to upload product. Please try again or contact support if the problem persists.');
            
            // Don't redirect, stay on the form so user can retry
        }
    }

    public function render()
    {
        $categories = Category::orderBy('order')->orderBy('name')->get();
        $productTypes = ProductType::cases();

        return view('livewire.seller.product-upload', [
            'categories' => $categories,
            'productTypes' => $productTypes,
        ]);
    }
}
