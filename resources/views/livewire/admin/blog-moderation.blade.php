<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Blog Moderation</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Review and approve blog posts from sellers</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <p class="text-green-800 dark:text-green-200">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-100 dark:bg-yellow-900 rounded-lg p-3">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['pending'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 dark:bg-green-900 rounded-lg p-3">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Approved</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['approved'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900 rounded-lg p-3">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm mb-6">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <button 
                    wire:click="setFilter('pending')"
                    class="py-4 px-1 border-b-2 font-medium text-sm {{ $filter === 'pending' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400' }}"
                >
                    Pending ({{ $stats['pending'] }})
                </button>
                <button 
                    wire:click="setFilter('approved')"
                    class="py-4 px-1 border-b-2 font-medium text-sm {{ $filter === 'approved' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400' }}"
                >
                    Approved
                </button>
                <button 
                    wire:click="setFilter('all')"
                    class="py-4 px-1 border-b-2 font-medium text-sm {{ $filter === 'all' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400' }}"
                >
                    All Posts
                </button>
            </nav>
        </div>
    </div>

    <!-- Posts List -->
    <div class="space-y-6">
        @forelse($posts as $post)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-start space-x-4">
                    @if($post->featured_image)
                        <img src="{{ Storage::url($post->featured_image) }}" alt="" class="w-32 h-32 object-cover rounded-lg flex-shrink-0">
                    @endif
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $post->title }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                    By {{ $post->user->name }} · {{ $post->created_at->diffForHumans() }}
                                </p>
                                @if($post->excerpt)
                                    <p class="text-gray-700 dark:text-gray-300 mb-3">{{ Str::limit($post->excerpt, 200) }}</p>
                                @endif
                                @if($post->product)
                                    <p class="text-sm text-indigo-600 dark:text-indigo-400">🔗 Linked to: {{ $post->product->title }}</p>
                                @endif
                            </div>
                            <div class="ml-4">
                                @if($post->is_approved)
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                        Approved
                                    </span>
                                @else
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">
                                        Pending
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4 flex items-center space-x-4">
                            @if(!$post->is_approved)
                                <button 
                                    wire:click="startReview({{ $post->id }})"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg"
                                >
                                    Review Post
                                </button>
                            @endif
                            <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                View Full Post →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No posts found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No blog posts match the current filter.</p>
            </div>
        @endforelse
    </div>

    @if($posts->hasPages())
        <div class="mt-6">
            {{ $posts->links() }}
        </div>
    @endif

    <!-- Review Modal -->
    @if($reviewingId)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75" wire:click="cancelReview"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Review Blog Post</h3>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Rejection Reason (if rejecting)
                        </label>
                        <textarea 
                            wire:model="rejectionReason"
                            rows="3"
                            class="w-full px-4 py-2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            placeholder="Explain why this post is being rejected..."
                        ></textarea>
                        @error('rejectionReason')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button wire:click="cancelReview" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                            Cancel
                        </button>
                        <button wire:click="reject" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                            Reject
                        </button>
                        <button wire:click="approve" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                            Approve
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
