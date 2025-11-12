<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Product (Admin)</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Update product details and manage approval status
            </p>
        </div>
        <flux:button 
            variant="danger" 
            size="sm"
            wire:click="openDeleteModal"
        >
            Delete Product
        </flux:button>
    </div>

    <form wire:submit="update" class="space-y-6">
        <!-- Admin Controls -->
        <div class="rounded-lg bg-blue-50 dark:bg-blue-900/20 p-4 border border-blue-200 dark:border-blue-800">
            <h3 class="text-sm font-medium text-blue-900 dark:text-blue-200 mb-4">Admin Controls</h3>
            
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        id="is_approved" 
                        wire:model="is_approved"
                        class="p-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700"
                    >
                    <label for="is_approved" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                        Approved
                    </label>
                </div>

                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        id="is_active" 
                        wire:model="is_active"
                        class="p-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700"
                    >
                    <label for="is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                        Active
                    </label>
                </div>
            </div>

            <div class="mt-4">
                <label for="rejection_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Rejection Reason (Optional)
                </label>
                <textarea 
                    id="rejection_reason" 
                    wire:model="rejection_reason"
                    rows="2"
                    class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white sm:text-sm"
                    placeholder="Provide reason if rejecting this product"
                ></textarea>
                @error('rejection_reason')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Seller Information -->
        <div class="rounded-lg bg-gray-50 dark:bg-gray-900 p-4">
            <h3 class="text-sm font-medium text-gray-900 dark:text-gray-200 mb-2">Seller Information</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                <strong>Name:</strong> {{ $product->user->name }}<br>
                <strong>Email:</strong> {{ $product->user->email }}<br>
                <strong>Submitted:</strong> {{ $product->created_at->format('M d, Y H:i') }}
            </p>
        </div>

        <!-- Title -->
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Product Title <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                id="title" 
                wire:model="title"
                class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white sm:text-sm"
                placeholder="Enter product title"
            >
            @error('title')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Short Description -->
        <div>
            <label for="short_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Short Description
            </label>
            <input 
                type="text" 
                id="short_description" 
                wire:model="short_description"
                class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white sm:text-sm"
                placeholder="Brief description (max 500 characters)"
                maxlength="500"
            >
            @error('short_description')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Full Description <span class="text-red-500">*</span>
            </label>
            <textarea 
                id="description" 
                wire:model="description"
                rows="6"
                class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white sm:text-sm"
                placeholder="Detailed description of the product"
            ></textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Category and Product Type -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Category <span class="text-red-500">*</span>
                </label>
                <select 
                    id="category_id" 
                    wire:model="category_id"
                    class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white sm:text-sm"
                >
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="product_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Product Type <span class="text-red-500">*</span>
                </label>
                <select 
                    id="product_type" 
                    wire:model="product_type"
                    class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white sm:text-sm"
                >
                    <option value="">Select a type</option>
                    @foreach($productTypes as $type)
                        <option value="{{ $type->value }}">{{ $type->label() }}</option>
                    @endforeach
                </select>
                @error('product_type')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Price and License Type -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Price (USD) <span class="text-red-500">*</span>
                </label>
                <div class="relative mt-1 rounded-md shadow-sm">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">$</span>
                    </div>
                    <input 
                        type="number" 
                        id="price" 
                        wire:model="price"
                        step="0.01"
                        min="0"
                        class="p-2 block w-full rounded-md border-gray-300 pl-7 pr-12 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white sm:text-sm"
                        placeholder="0.00"
                    >
                </div>
                @error('price')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="license_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    License Type <span class="text-red-500">*</span>
                </label>
                <select 
                    id="license_type" 
                    wire:model="license_type"
                    class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white sm:text-sm"
                >
                    <option value="">Select a license</option>
                    <option value="Personal">Personal Use</option>
                    <option value="Commercial">Commercial Use</option>
                    <option value="Extended">Extended License</option>
                </select>
                @error('license_type')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Current Thumbnail -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Current Thumbnail
            </label>
            @if($product->thumbnail_path)
                <div class="mt-2">
                    <img src="{{ Storage::url($product->thumbnail_path) }}" alt="Current thumbnail" class="h-32 w-auto rounded-md">
                </div>
            @endif
        </div>

        <!-- New Thumbnail Upload -->
        <div>
            <label for="thumbnail" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Update Thumbnail (Optional)
            </label>
            <div class="mt-1">
                <input 
                    type="file" 
                    id="thumbnail" 
                    wire:model="thumbnail"
                    accept="image/*"
                    class="block w-full text-sm text-gray-500 dark:text-gray-400
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-indigo-50 file:text-indigo-700
                        hover:file:bg-indigo-100
                        dark:file:bg-indigo-900 dark:file:text-indigo-300"
                >
            </div>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF up to 5MB</p>
            
            @if ($thumbnail)
                <div class="mt-2">
                    <p class="text-sm text-gray-700 dark:text-gray-300 mb-2">New thumbnail preview:</p>
                    <img src="{{ $thumbnail->temporaryUrl() }}" alt="New thumbnail preview" class="h-32 w-auto rounded-md">
                </div>
            @endif
            
            <div wire:loading wire:target="thumbnail" class="mt-2 text-sm text-indigo-600 dark:text-indigo-400">
                Uploading thumbnail...
            </div>
            
            @error('thumbnail')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- New Product File Upload -->
        <div>
            <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Update Product File (Optional)
            </label>
            <div class="mt-1">
                <input 
                    type="file" 
                    id="file" 
                    wire:model="file"
                    class="block w-full text-sm text-gray-500 dark:text-gray-400
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-indigo-50 file:text-indigo-700
                        hover:file:bg-indigo-100
                        dark:file:bg-indigo-900 dark:file:text-indigo-300"
                >
            </div>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Current file size: {{ number_format($product->file_size / 1024 / 1024, 2) }} MB | Maximum: 500MB
            </p>
            
            @if ($file)
                <div class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                    New file: {{ $file->getClientOriginalName() }} ({{ number_format($file->getSize() / 1024 / 1024, 2) }} MB)
                </div>
            @endif
            
            <div wire:loading wire:target="file" class="mt-2 text-sm text-indigo-600 dark:text-indigo-400">
                Uploading file...
            </div>
            
            @error('file')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end space-x-4">
            <a 
                href="{{ route('admin.products') }}" 
                wire:navigate
                class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700"
            >
                Cancel
            </a>
            <button 
                type="submit" 
                wire:loading.attr="disabled"
                wire:target="update, thumbnail, file"
                class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span wire:loading.remove wire:target="update">Update Product</span>
                <span wire:loading wire:target="update">Updating...</span>
            </button>
        </div>
    </form>

    {{-- Delete Confirmation Modal --}}
    <flux:modal name="delete-product" :open="$showDeleteModal" wire:model="showDeleteModal">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                Delete Product
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                Are you sure you want to delete <strong>{{ $product->title }}</strong>? 
                This action will soft delete the product and it can be restored later if needed.
            </p>
            <div class="flex justify-end gap-3">
                <flux:button variant="ghost" wire:click="closeDeleteModal">Cancel</flux:button>
                <flux:button 
                    variant="danger" 
                    wire:click="deleteProduct" 
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove wire:target="deleteProduct">Delete Product</span>
                    <span wire:loading wire:target="deleteProduct">Deleting...</span>
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
