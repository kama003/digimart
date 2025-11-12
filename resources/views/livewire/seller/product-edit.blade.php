<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Product</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            Update your product details
        </p>
    </div>

    @if($product->rejection_reason)
        <div class="mb-6 rounded-md bg-red-50 dark:bg-red-900/20 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                        Product Rejected
                    </h3>
                    <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                        <p>{{ $product->rejection_reason }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <form wire:submit="update" class="space-y-6">
        <!-- Title -->
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Product Title <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                id="title" 
                wire:model.blur="title"
                class="p-2 p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white sm:text-sm"
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
                wire:model.blur="description"
                rows="6"
                class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white sm:text-sm"
                placeholder="Detailed description of your product (minimum 50 characters)"
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
                        wire:model.blur="price"
                        step="0.01"
                        min="0.01"
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
                href="{{ route('seller.products.index') }}" 
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
</div>
