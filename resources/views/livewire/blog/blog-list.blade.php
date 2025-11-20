<div class="min-h-screen bg-brand-black pt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Page Header -->
        <div class="mb-12 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-6 tracking-tight">Blog &<span class="text-gradient-blue"> Insights</span></h1>
        
            <p class="text-lg text-zinc-400 max-w-2xl mx-auto">
                Discover insights, tutorials, and stories from our community of creators and developers.
            </p>
        </div>

        <!-- Search and Filter Bar -->
        <div class="bg-brand-surface rounded-2xl p-4 mb-12 border border-white/5 shadow-lg">
            <div class="flex flex-col sm:flex-row gap-4">
                <!-- Search -->
                <div class="flex-1 relative">
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search blog posts..."
                        class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 pl-11 text-white placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-brand-blue focus:border-transparent transition-all"
                    >
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg class="w-5 h-5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Sort -->
                <div class="sm:w-56">
                    <select 
                        wire:model.live="sortBy"
                        class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-brand-blue focus:border-transparent transition-all appearance-none"
                    >
                        <option value="latest">Latest</option>
                        <option value="popular">Most Viewed</option>
                        <option value="liked">Most Liked</option>
                        <option value="commented">Most Commented</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Blog Posts Grid -->
        @if($posts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                @foreach($posts as $post)
                    <article class="group bg-brand-surface rounded-2xl shadow-sm hover:shadow-[0_0_30px_rgba(0,122,255,0.15)] overflow-hidden border border-white/5 hover:border-brand-blue/30 hover:-translate-y-1 transition-all duration-300 h-full flex flex-col">
                        <a href="{{ route('blog.show', $post->slug) }}" class="block flex-1 flex flex-col">
                            <!-- Featured Image -->
                            <div class="aspect-video bg-zinc-900 overflow-hidden relative">
                                @if($post->featured_image)
                                    <img 
                                        src="{{ Storage::url($post->featured_image) }}" 
                                        alt="{{ $post->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                    >
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-brand-blue/20 to-brand-purple/20 flex items-center justify-center group-hover:scale-105 transition-transform duration-500">
                                        <svg class="w-16 h-16 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="p-6 flex-1 flex flex-col">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="text-xs font-medium px-2.5 py-0.5 rounded-full bg-brand-blue/10 text-brand-blue border border-brand-blue/20">
                                        Article
                                    </span>
                                    <span class="text-xs text-zinc-500">{{ $post->published_at->diffForHumans() }}</span>
                                </div>

                                <h2 class="text-xl font-bold text-white mb-3 line-clamp-2 group-hover:text-brand-blue transition-colors leading-tight">
                                    {{ $post->title }}
                                </h2>

                                @if($post->excerpt)
                                    <p class="text-zinc-400 mb-6 line-clamp-3 text-sm leading-relaxed flex-1">
                                        {{ $post->excerpt }}
                                    </p>
                                @endif

                                <!-- Meta -->
                                <div class="flex items-center justify-between pt-4 border-t border-white/5 mt-auto">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-zinc-800 flex items-center justify-center text-xs font-bold text-zinc-400">
                                            {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                        </div>
                                        <span class="text-sm text-zinc-400">{{ $post->user->name }}</span>
                                    </div>
                                    
                                    <div class="flex items-center gap-4 text-xs text-zinc-500">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            {{ number_format($post->views_count) }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                            {{ number_format($post->likes_count) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $posts->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-brand-surface rounded-2xl border border-white/5 p-16 text-center">
                <div class="w-20 h-20 bg-zinc-900 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="h-10 w-10 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">No blog posts found</h3>
                <p class="text-zinc-400 mb-6">
                    @if($search)
                        We couldn't find any posts matching "{{ $search }}".
                    @else
                        Check back later for new content!
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
