<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            {{ $blogPostId ? 'Edit Blog Post' : 'Create New Blog Post' }}
        </h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            Share your knowledge and insights with the community
        </p>
    </div>

    <form wire:submit="save" class="space-y-6">
        <!-- Title -->
        <div>
            <label for="title" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                Title <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                id="title" 
                wire:model="title"
                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-lg py-3"
                placeholder="Enter an engaging title..."
            >
            @error('title')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Excerpt -->
        <div>
            <label for="excerpt" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                Excerpt <span class="text-gray-400 font-normal">(Optional)</span>
            </label>
            <textarea 
                id="excerpt" 
                wire:model="excerpt"
                rows="2"
                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 py-3"
                placeholder="Brief summary that appears in blog listings..."
            ></textarea>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                {{ strlen($excerpt ?? '') }}/500 characters
            </p>
            @error('excerpt')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Content -->
        <div>
            <label for="content" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                Content <span class="text-red-500">*</span>
            </label>
            <textarea 
                id="content" 
                wire:model="content"
                rows="15"
                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 py-3 font-mono text-sm"
                placeholder="Write your blog post content here... (minimum 100 characters)"
            ></textarea>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                {{ str_word_count($content ?? '') }} words · {{ strlen($content ?? '') }} characters
            </p>
            @error('content')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Featured Image -->
        <div>
            <label for="featured_image" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                Featured Image <span class="text-red-500">{{ $blogPostId ? '' : '*' }}</span>
            </label>
            <input 
                type="file" 
                id="featured_image" 
                wire:model="featured_image"
                accept="image/*"
                class="block w-full text-sm text-gray-500 dark:text-gray-400
                    file:mr-4 file:py-3 file:px-6
                    file:rounded-lg file:border-0
                    file:text-sm file:font-semibold
                    file:bg-indigo-50 file:text-indigo-700
                    hover:file:bg-indigo-100
                    dark:file:bg-indigo-900 dark:file:text-indigo-300"
            >
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                PNG, JPG, WebP up to 5MB. Recommended size: 1200x630px
            </p>
            
            @if ($featured_image && !is_string($featured_image))
                <div class="mt-4">
                    <img src="{{ $featured_image->temporaryUrl() }}" alt="Preview" class="h-48 w-auto rounded-lg shadow-sm">
                </div>
            @endif
            
            <div wire:loading wire:target="featured_image" class="mt-2 text-sm text-indigo-600 dark:text-indigo-400">
                Uploading image...
            </div>
            
            @error('featured_image')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Product Link -->
        <div>
            <label for="product_id" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                Link to Product <span class="text-gray-400 font-normal">(Optional)</span>
            </label>
            <select 
                id="product_id" 
                wire:model="product_id"
                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 py-3"
            >
                <option value="">No product linked</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->title }} - ${{ number_format($product->price, 2) }}</option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Feature one of your products in this blog post
            </p>
            @error('product_id')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Publish Toggle -->
        <div class="flex items-center">
            <input 
                type="checkbox" 
                id="is_published" 
                wire:model="is_published"
                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
            >
            <label for="is_published" class="ml-2 block text-sm text-gray-900 dark:text-white">
                Publish immediately after approval
            </label>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                        Approval Required
                    </h3>
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                        <p>Your blog post will be reviewed by our team before it goes live. This usually takes 24-48 hours.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
            <a 
                href="{{ route('seller.blog.index') }}"
                class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 dark:border-gray-600 shadow-sm text-base font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
            >
                Cancel
            </a>
            <button 
                type="submit"
                wire:loading.attr="disabled"
                wire:target="save, featured_image"
                class="inline-flex justify-center items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
                <span wire:loading.remove wire:target="save">
                    {{ $blogPostId ? 'Update Post' : 'Submit for Review' }}
                </span>
                <span wire:loading wire:target="save">
                    <svg class="animate-spin h-5 w-5 mr-2 inline" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving...
                </span>
            </button>
        </div>
    </form>
</div>
