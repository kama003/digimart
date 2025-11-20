<div class="space-y-6">
    <!-- Reviews Summary -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Customer Reviews</h2>
        
        <div class="grid md:grid-cols-2 gap-8">
            <!-- Average Rating -->
            <div class="text-center md:text-left">
                <div class="flex items-center justify-center md:justify-start mb-2">
                    <span class="text-5xl font-bold text-gray-900 dark:text-white">{{ number_format($averageRating, 1) }}</span>
                    <span class="text-2xl text-gray-500 dark:text-gray-400 ml-2">/ 5</span>
                </div>
                <div class="flex items-center justify-center md:justify-start mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-6 h-6 {{ $i <= round($averageRating) ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    @endfor
                </div>
                <p class="text-gray-600 dark:text-gray-400">Based on {{ $totalReviews }} {{ Str::plural('review', $totalReviews) }}</p>
            </div>

            <!-- Rating Distribution -->
            <div class="space-y-2">
                @foreach($ratingDistribution as $stars => $count)
                    <button 
                        wire:click="filterByRating({{ $stars }})"
                        class="w-full flex items-center space-x-3 hover:bg-gray-50 dark:hover:bg-gray-700 p-2 rounded transition-colors {{ $filterRating === $stars ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}"
                    >
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 w-12">{{ $stars }} star</span>
                        <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div 
                                class="bg-yellow-400 h-2 rounded-full transition-all duration-300"
                                style="width: {{ $totalReviews > 0 ? ($count / $totalReviews * 100) : 0 }}%"
                            ></div>
                        </div>
                        <span class="text-sm text-gray-600 dark:text-gray-400 w-12 text-right">{{ $count }}</span>
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Filters and Sort -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <!-- Filter -->
            <div class="flex items-center space-x-2">
                @if($filterRating)
                    <span class="text-sm text-gray-600 dark:text-gray-400">Showing {{ $filterRating }}-star reviews</span>
                    <button 
                        wire:click="clearFilter"
                        class="text-sm text-blue-600 dark:text-blue-400 hover:underline"
                    >
                        Clear filter
                    </button>
                @else
                    <span class="text-sm text-gray-600 dark:text-gray-400">Showing all reviews</span>
                @endif
            </div>

            <!-- Sort -->
            <div class="flex items-center space-x-2">
                <label class="text-sm text-gray-600 dark:text-gray-400">Sort by:</label>
                <select 
                    wire:model.live="sortBy"
                    class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500"
                >
                    <option value="recent">Most Recent</option>
                    <option value="rating_high">Highest Rating</option>
                    <option value="rating_low">Lowest Rating</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Reviews List -->
    @if($reviews->count() > 0)
        <div class="space-y-4">
            @foreach($reviews as $review)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <!-- Review Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-start space-x-4">
                            <!-- User Avatar -->
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-lg">
                                    {{ $review->user->initials() }}
                                </div>
                            </div>

                            <!-- User Info and Rating -->
                            <div>
                                <div class="flex items-center space-x-2 mb-1">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ $review->user->name }}</h4>
                                    @if($review->is_verified_purchase)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Verified Purchase
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="flex">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Content -->
                    @if($review->title)
                        <h5 class="font-semibold text-gray-900 dark:text-white mb-2">{{ $review->title }}</h5>
                    @endif
                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $review->comment }}</p>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $reviews->links() }}
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No reviews yet</h3>
            <p class="text-gray-600 dark:text-gray-400">
                @if($filterRating)
                    No {{ $filterRating }}-star reviews found. <button wire:click="clearFilter" class="text-blue-600 dark:text-blue-400 hover:underline">Show all reviews</button>
                @else
                    Be the first to review this product!
                @endif
            </p>
        </div>
    @endif
</div>
