<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Product Moderation</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Review and manage all products on the platform</p>
    </div>

    {{-- Filters --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex-1 max-w-md">
            <flux:input 
                wire:model.live.debounce.300ms="search" 
                type="search" 
                placeholder="Search by title, description, or seller..."
                icon="magnifying-glass"
            />
        </div>

        <div class="w-full sm:w-48">
            <flux:select wire:model.live="status_filter" placeholder="All Products">
                <option value="">All Products</option>
                <option value="pending">Pending Approval</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </flux:select>
        </div>
    </div>

    {{-- Products Table --}}
    <div class="overflow-hidden bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Product
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Seller
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Category
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Price
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Submitted
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-16 w-16">
                                        @if($product->thumbnail_path)
                                            <img 
                                                src="{{ Storage::url($product->thumbnail_path) }}" 
                                                alt="{{ $product->title }}"
                                                class="h-16 w-16 rounded object-cover"
                                                loading="lazy"
                                            >
                                        @else
                                            <div class="h-16 w-16 rounded bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                                <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ Str::limit($product->title, 40) }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            <flux:badge size="sm" color="gray">
                                                {{ ucfirst(str_replace('_', ' ', $product->product_type->value)) }}
                                            </flux:badge>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $product->user->name }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $product->user->email }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $product->category->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                ${{ number_format($product->price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($product->trashed())
                                    <flux:badge color="zinc" size="sm">Deleted</flux:badge>
                                @elseif($product->is_approved)
                                    <flux:badge color="green" size="sm">Approved</flux:badge>
                                @elseif($product->rejection_reason)
                                    <flux:badge color="red" size="sm">Rejected</flux:badge>
                                @else
                                    <flux:badge color="yellow" size="sm">Pending</flux:badge>
                                @endif
                                
                                @if(!$product->is_active && !$product->trashed())
                                    <flux:badge color="gray" size="sm" class="ml-1">Inactive</flux:badge>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $product->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <flux:button 
                                        size="sm" 
                                        variant="ghost"
                                        href="{{ route('product.show', $product->slug) }}"
                                        target="_blank"
                                    >
                                        View
                                    </flux:button>
                                    
                                    @if(!$product->trashed())
                                        @if(!$product->is_approved && !$product->rejection_reason)
                                            <flux:button 
                                                size="sm" 
                                                variant="primary"
                                                wire:click="openApproveModal({{ $product->id }})"
                                            >
                                                Approve
                                            </flux:button>
                                            <flux:button 
                                                size="sm" 
                                                variant="danger"
                                                wire:click="openRejectModal({{ $product->id }})"
                                            >
                                                Reject
                                            </flux:button>
                                        @endif
                                        
                                        <flux:button 
                                            size="sm" 
                                            variant="ghost"
                                            href="{{ route('admin.products.edit', $product) }}"
                                            wire:navigate
                                        >
                                            Edit
                                        </flux:button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="mt-2 text-sm">No products found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($products->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    {{-- Approve Modal --}}
    <flux:modal name="approve-product" :open="$showApproveModal" wire:model="showApproveModal">
        <div class="p-6">
            @if($selectedProduct)
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Approve Product
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                    Are you sure you want to approve <strong>{{ $selectedProduct->title }}</strong>? 
                    This will make the product publicly visible and available for purchase.
                </p>
                
                <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                    <div class="flex items-start gap-3">
                        @if($selectedProduct->thumbnail_path)
                            <img 
                                src="{{ Storage::url($selectedProduct->thumbnail_path) }}" 
                                alt="{{ $selectedProduct->title }}"
                                class="h-16 w-16 rounded object-cover"
                            >
                        @endif
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $selectedProduct->title }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                by {{ $selectedProduct->user->name }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Price: ${{ number_format($selectedProduct->price, 2) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <flux:button variant="ghost" wire:click="closeModals">Cancel</flux:button>
                    <flux:button 
                        variant="primary" 
                        wire:click="approve" 
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove wire:target="approve">Approve Product</span>
                        <span wire:loading wire:target="approve">Approving...</span>
                    </flux:button>
                </div>
            @endif
        </div>
    </flux:modal>

    {{-- Reject Modal --}}
    <flux:modal name="reject-product" :open="$showRejectModal" wire:model="showRejectModal">
        <div class="p-6">
            @if($selectedProduct)
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Reject Product
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    Please provide a detailed reason for rejecting <strong>{{ $selectedProduct->title }}</strong>. 
                    This will help the seller understand what needs to be improved.
                </p>
                
                <div class="mb-4">
                    <flux:textarea 
                        wire:model="rejectionReason" 
                        label="Rejection Reason" 
                        placeholder="Explain why this product cannot be approved..."
                        rows="4"
                    />
                    @error('rejectionReason')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3">
                    <flux:button variant="ghost" wire:click="closeModals">Cancel</flux:button>
                    <flux:button 
                        variant="danger" 
                        wire:click="reject" 
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove wire:target="reject">Reject Product</span>
                        <span wire:loading wire:target="reject">Rejecting...</span>
                    </flux:button>
                </div>
            @endif
        </div>
    </flux:modal>
</div>
