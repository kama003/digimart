<div class="min-h-screen bg-brand-black pt-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Back Button -->
        <div class="mb-8">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center text-zinc-400 hover:text-white transition-colors group">
                <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center mr-3 group-hover:bg-brand-blue/20 group-hover:text-brand-blue transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </div>
                Back to Blog
            </a>
        </div>

        <!-- Article -->
        <article class="bg-brand-surface rounded-3xl shadow-lg overflow-hidden mb-12 border border-white/5">
            <!-- Featured Image -->
            @if($post->featured_image)
                <div class="aspect-video bg-zinc-900 relative">
                    <img 
                        src="{{ Storage::url($post->featured_image) }}" 
                        alt="{{ $post->title }}"
                        class="w-full h-full object-cover"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-brand-surface to-transparent opacity-60"></div>
                </div>
            @endif

            <div class="p-8 md:p-12 relative">
                <!-- Title -->
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-6 leading-tight">
                    {{ $post->title }}
                </h1>

                <!-- Meta -->
                <div class="flex items-center justify-between mb-10 pb-8 border-b border-white/5">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-brand-blue/10 rounded-full flex items-center justify-center text-brand-blue font-bold text-lg border border-brand-blue/20">
                            {{ strtoupper(substr($post->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-white">{{ $post->user->name }}</p>
                            <p class="text-sm text-zinc-400">{{ $post->published_at->format('M d, Y') }} · {{ ceil(str_word_count($post->content) / 200) }} min read</p>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="flex items-center space-x-4 text-sm text-zinc-400">
                        <span class="flex items-center bg-white/5 px-3 py-1 rounded-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            {{ number_format($post->views_count) }}
                        </span>
                    </div>
                </div>

                <!-- Content -->
                <div class="prose prose-lg prose-invert max-w-none mb-12 prose-headings:text-white prose-p:text-zinc-300 prose-a:text-brand-blue prose-strong:text-white prose-code:text-brand-blue prose-code:bg-brand-blue/10 prose-code:px-1 prose-code:rounded">
                    {!! nl2br(e($post->content)) !!}
                </div>

                <!-- Linked Product -->
                @if($post->product)
                    <div class="bg-gradient-to-br from-brand-blue/10 to-brand-purple/10 rounded-2xl p-6 mb-10 border border-brand-blue/20">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Featured Product
                        </h3>
                        <div class="flex items-center gap-6">
                            @if($post->product->thumbnail_path)
                                <img src="{{ Storage::url($post->product->thumbnail_path) }}" alt="{{ $post->product->title }}" class="w-24 h-24 object-cover rounded-xl shadow-lg">
                            @endif
                            <div class="flex-1">
                                <h4 class="font-bold text-white text-lg mb-1">{{ $post->product->title }}</h4>
                                <p class="text-sm text-zinc-400 mb-4 line-clamp-1">{{ $post->product->short_description }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-xl font-bold text-brand-blue">${{ number_format($post->product->price, 2) }}</span>
                                    <a href="{{ route('product.show', $post->product->slug) }}" class="inline-flex items-center px-4 py-2 bg-brand-blue hover:bg-brand-blue-hover text-white text-sm font-medium rounded-lg transition-all shadow-lg shadow-brand-blue/20">
                                        View Product
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Like Button -->
                <div class="flex items-center space-x-4 pt-8 border-t border-white/5">
                    <button 
                        wire:click="toggleLike"
                        class="inline-flex items-center px-6 py-3 rounded-xl font-medium transition-all {{ $isLiked ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 'bg-white/5 text-zinc-400 hover:bg-white/10 hover:text-white border border-white/5' }}"
                    >
                        <svg class="w-5 h-5 mr-2 {{ $isLiked ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        {{ $isLiked ? 'Liked' : 'Like' }} ({{ number_format($post->likes_count) }})
                    </button>
                </div>
            </div>
        </article>

        <!-- Comments Section -->
        <div class="bg-brand-surface rounded-3xl shadow-lg p-8 md:p-12 border border-white/5">
            <h2 class="text-2xl font-bold text-white mb-8 flex items-center">
                <svg class="w-6 h-6 mr-3 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                Comments ({{ number_format($post->comments_count) }})
            </h2>

            <!-- Comment Form -->
            @auth
                <div class="mb-10">
                    @if(session('comment_success'))
                        <div class="mb-4 p-4 bg-green-500/10 border border-green-500/20 rounded-xl text-green-400">
                            {{ session('comment_success') }}
                        </div>
                    @endif

                    @if($replyingTo)
                        <div class="mb-4 p-3 bg-brand-blue/10 border border-brand-blue/20 rounded-xl flex items-center justify-between">
                            <span class="text-sm text-brand-blue">Replying to comment</span>
                            <button wire:click="cancelReply" class="text-brand-blue hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @endif

                    <form wire:submit="postComment">
                        <textarea 
                            wire:model="commentContent"
                            rows="3"
                            placeholder="Write a comment..."
                            class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-brand-blue focus:border-transparent transition-all resize-none"
                        ></textarea>
                        @error('commentContent')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                        <div class="mt-4 flex justify-end">
                            <button 
                                type="submit"
                                class="px-6 py-2 bg-brand-blue hover:bg-brand-blue-hover text-white font-medium rounded-lg transition-all shadow-lg shadow-brand-blue/20"
                            >
                                Post Comment
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="mb-10 p-6 bg-white/5 rounded-xl text-center border border-white/5">
                    <p class="text-zinc-400">
                        <a href="{{ route('login') }}" class="text-brand-blue hover:text-brand-blue-hover hover:underline font-medium">Log in</a> to leave a comment
                    </p>
                </div>
            @endauth

            <!-- Comments List -->
            <div class="space-y-8">
                @forelse($post->approvedComments as $comment)
                    <div class="group">
                        <div class="flex items-start space-x-4">
                            <div class="w-10 h-10 bg-zinc-800 rounded-full flex items-center justify-center text-zinc-400 font-bold flex-shrink-0 border border-white/5">
                                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <div class="bg-white/5 rounded-2xl p-4 border border-white/5">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-bold text-white">{{ $comment->user->name }}</span>
                                        <span class="text-xs text-zinc-500">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-zinc-300 text-sm leading-relaxed">{{ $comment->content }}</p>
                                </div>
                                @auth
                                    <button 
                                        wire:click="replyTo({{ $comment->id }})"
                                        class="mt-2 ml-2 text-xs font-medium text-zinc-500 hover:text-brand-blue transition-colors"
                                    >
                                        Reply
                                    </button>
                                @endauth

                                <!-- Replies -->
                                @if($comment->approvedReplies->count() > 0)
                                    <div class="mt-4 space-y-4 pl-4 border-l-2 border-white/5 ml-4">
                                        @foreach($comment->approvedReplies as $reply)
                                            <div class="flex items-start space-x-3">
                                                <div class="w-8 h-8 bg-zinc-800 rounded-full flex items-center justify-center text-zinc-400 font-bold text-xs flex-shrink-0 border border-white/5">
                                                    {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                                </div>
                                                <div class="flex-1">
                                                    <div class="bg-white/5 rounded-2xl p-3 border border-white/5">
                                                        <div class="flex items-center justify-between mb-1">
                                                            <span class="font-bold text-white text-sm">{{ $reply->user->name }}</span>
                                                            <span class="text-xs text-zinc-500">{{ $reply->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        <p class="text-zinc-300 text-xs leading-relaxed">{{ $reply->content }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-zinc-900 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <p class="text-zinc-400">No comments yet. Be the first to share your thoughts!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
