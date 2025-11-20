<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Write a Review</h3>

    @guest
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-4">
            <p class="text-gray-700 dark:text-gray-300">
                Please <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-semibold">log in</a> to write a review.
            </p>
        </div>
    @else
        @if($hasReviewed)
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                <p class="text-gray-700 dark:text-gray-300">
                    Thank you! You have already reviewed this product.
                </p>
            </div>
        @else
            @if(session()->has('success'))
                <div class="mb-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            @if(session()->has('error'))
                <div class="mb-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif

            @if(!$hasPurchased)
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-4">
                    <p class="text-gray-700 dark:text-gray-300 text-sm">
                        <svg class="w-5 h-5 inline mr-1 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        You haven't purchased this product yet. Your review will not be marked as "Verified Purchase".
                    </p>
                </div>
            @endif

            <form wire:submit="submit" class="space-y-6">
                <!-- Rating -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Rating <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center space-x-2">
                        @for($i = 1; $i <= 5; $i++)
                            <button 
                                type="button"
                                wire:click="setRating({{ $i }})"
                                class="focus:outline-none focus:ring-2 focus:ring-yellow-500 rounded"
                            >
                                <svg class="w-8 h-8 {{ $rating >= $i ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }} hover:text-yellow-400 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </button>
                        @endfor
                        @if($rating > 0)
                            <span class="text-sm text-gray-600 dark:text-gray-400 ml-2">{{ $rating }} star{{ $rating > 1 ? 's' : '' }}</span>
                        @endif
                    </div>
                    @error('rating') 
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Review Title (Optional)
                    </label>
                    <input 
                        type="text" 
                        id="title" 
                        wire:model="title"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                        placeholder="Sum up your experience in one line"
                        maxlength="255"
                    >
                    @error('title') 
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Comment -->
                <div>
                    <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Your Review <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        id="comment" 
                        wire:model="comment"
                        rows="5"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                        placeholder="Share your thoughts about this product... (minimum 10 characters)"
                        maxlength="1000"
                    ></textarea>
                    <div class="flex justify-between mt-1">
                        @error('comment') 
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">Minimum 10 characters</p>
                        @enderror
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ strlen($comment) }}/1000</p>
                    </div>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove>Submit Review</span>
                    <span wire:loading>Submitting...</span>
                </button>
            </form>
        @endif
    @endguest
</div>
