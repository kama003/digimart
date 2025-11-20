<div class="min-h-screen bg-brand-black text-zinc-300 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-6 tracking-tight">Help <span class="text-gradient-blue">Center</span></h1>
            <p class="text-xl text-zinc-400">Find answers and get support</p>
        </div>

        <!-- Search Box -->
        <div class="max-w-2xl mx-auto mb-16">
            <div class="relative group">
                <div class="absolute-inset-1 bg-gradient-to-r from-brand-blue to-brand-purple rounded-xl blur opacity-25 group-hover:opacity-50 transition duration-200"></div>
                <div class="relative">
                    <input 
                        type="text" 
                        placeholder="Search for help..."
                        class="w-full px-6 py-5 pr-12 text-lg border border-zinc-700 rounded-xl bg-brand-surface text-white placeholder-zinc-500 focus:ring-2 focus:ring-brand-blue focus:border-transparent transition-all shadow-xl"
                    >
                    <svg class="absolute right-5 top-1/2 transform -translate-y-1/2 w-6 h-6 text-zinc-500 group-hover:text-brand-blue transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="grid md:grid-cols-3 gap-8 mb-16">
            <a href="{{ route('faq') }}" class="bg-brand-surface border border-zinc-800 rounded-2xl p-8 hover:border-brand-blue/50 hover:bg-brand-surface-highlight transition-all duration-300 group shadow-lg">
                <div class="w-16 h-16 bg-blue-500/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-500/20 transition-colors">
                    <svg class="w-8 h-8 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2 group-hover:text-brand-blue transition-colors">FAQ</h3>
                <p class="text-zinc-400">Common questions and answers about our platform</p>
            </a>

            <a href="{{ route('seller-guide') }}" class="bg-brand-surface border border-zinc-800 rounded-2xl p-8 hover:border-green-500/50 hover:bg-brand-surface-highlight transition-all duration-300 group shadow-lg">
                <div class="w-16 h-16 bg-green-500/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-green-500/20 transition-colors">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2 group-hover:text-green-500 transition-colors">Seller Guide</h3>
                <p class="text-zinc-400">Learn how to sell and monetize your work</p>
            </a>

            <a href="{{ route('contact') }}" class="bg-brand-surface border border-zinc-800 rounded-2xl p-8 hover:border-purple-500/50 hover:bg-brand-surface-highlight transition-all duration-300 group shadow-lg">
                <div class="w-16 h-16 bg-purple-500/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-purple-500/20 transition-colors">
                    <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2 group-hover:text-purple-500 transition-colors">Contact Support</h3>
                <p class="text-zinc-400">Get in touch with our friendly support team</p>
            </a>
        </div>

        <!-- Help Topics -->
        <div class="bg-brand-surface border border-zinc-800 rounded-3xl p-8 md:p-12 mb-12 shadow-xl">
            <h2 class="text-2xl font-bold text-white mb-8">Popular Topics</h2>
            
            <div class="grid md:grid-cols-2 gap-10">
                <!-- Getting Started -->
                <div>
                    <h3 class="text-lg font-semibold text-white mb-6 flex items-center">
                        <span class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-brand-blue" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        Getting Started
                    </h3>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-zinc-400 hover:text-brand-blue transition-colors flex items-center"><span class="w-1.5 h-1.5 bg-zinc-600 rounded-full mr-3"></span>Creating an account</a></li>
                        <li><a href="#" class="text-zinc-400 hover:text-brand-blue transition-colors flex items-center"><span class="w-1.5 h-1.5 bg-zinc-600 rounded-full mr-3"></span>Browsing products</a></li>
                        <li><a href="#" class="text-zinc-400 hover:text-brand-blue transition-colors flex items-center"><span class="w-1.5 h-1.5 bg-zinc-600 rounded-full mr-3"></span>Making your first purchase</a></li>
                        <li><a href="#" class="text-zinc-400 hover:text-brand-blue transition-colors flex items-center"><span class="w-1.5 h-1.5 bg-zinc-600 rounded-full mr-3"></span>Downloading your files</a></li>
                    </ul>
                </div>

                <!-- Buying -->
                <div>
                    <h3 class="text-lg font-semibold text-white mb-6 flex items-center">
                        <span class="w-8 h-8 rounded-lg bg-green-500/10 flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                            </svg>
                        </span>
                        Buying
                    </h3>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-zinc-400 hover:text-brand-blue transition-colors flex items-center"><span class="w-1.5 h-1.5 bg-zinc-600 rounded-full mr-3"></span>Payment methods</a></li>
                        <li><a href="#" class="text-zinc-400 hover:text-brand-blue transition-colors flex items-center"><span class="w-1.5 h-1.5 bg-zinc-600 rounded-full mr-3"></span>Order history</a></li>
                        <li><a href="#" class="text-zinc-400 hover:text-brand-blue transition-colors flex items-center"><span class="w-1.5 h-1.5 bg-zinc-600 rounded-full mr-3"></span>Refund policy</a></li>
                        <li><a href="#" class="text-zinc-400 hover:text-brand-blue transition-colors flex items-center"><span class="w-1.5 h-1.5 bg-zinc-600 rounded-full mr-3"></span>Download issues</a></li>
                    </ul>
                </div>

                <!-- Selling -->
                <div class="mt-5">
                    <h3 class="text-lg font-semibold text-white mb-6 flex items-center">
                        <span class="w-8 h-8 rounded-lg bg-purple-500/10 flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        Selling
                    </h3>
                    <ul class="space-y-4">
                        <li><a href="{{ route('seller-guide') }}" class="text-zinc-400 hover:text-brand-blue transition-colors flex items-center"><span class="w-1.5 h-1.5 bg-zinc-600 rounded-full mr-3"></span>Becoming a seller</a></li>
                        <li><a href="#" class="text-zinc-400 hover:text-brand-blue transition-colors flex items-center"><span class="w-1.5 h-1.5 bg-zinc-600 rounded-full mr-3"></span>Uploading products</a></li>
                        <li><a href="#" class="text-zinc-400 hover:text-brand-blue transition-colors flex items-center"><span class="w-1.5 h-1.5 bg-zinc-600 rounded-full mr-3"></span>Pricing guidelines</a></li>
                        <li><a href="#" class="text-zinc-400 hover:text-brand-blue transition-colors flex items-center"><span class="w-1.5 h-1.5 bg-zinc-600 rounded-full mr-3"></span>Withdrawal process</a></li>
                    </ul>
                </div>

                <!-- Account & Security -->
                <div class="mt-5">
                    <h3 class="text-lg font-semibold text-white mb-6 flex items-center">
                        <span class="w-8 h-8 rounded-lg bg-red-500/10 flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        Account & Security
                    </h3>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-zinc-400 hover:text-brand-blue transition-colors flex items-center"><span class="w-1.5 h-1.5 bg-zinc-600 rounded-full mr-3"></span>Account settings</a></li>
                        <li><a href="#" class="text-zinc-400 hover:text-brand-blue transition-colors flex items-center"><span class="w-1.5 h-1.5 bg-zinc-600 rounded-full mr-3"></span>Password reset</a></li>
                        <li><a href="#" class="text-zinc-400 hover:text-brand-blue transition-colors flex items-center"><span class="w-1.5 h-1.5 bg-zinc-600 rounded-full mr-3"></span>Two-factor authentication</a></li>
                        <li><a href="#" class="text-zinc-400 hover:text-brand-blue transition-colors flex items-center"><span class="w-1.5 h-1.5 bg-zinc-600 rounded-full mr-3"></span>Privacy settings</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Still Need Help -->
        <div class="text-center bg-gradient-to-r from-brand-blue to-brand-purple rounded-3xl p-10 md:p-12 text-white shadow-2xl relative overflow-hidden">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="relative z-10">
                <h2 class="text-3xl font-bold mb-4">Still need help?</h2>
                <p class="text-blue-50 mb-8 text-lg">Our support team is here to assist you with any questions or issues.</p>
                <a href="{{ route('contact') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white text-brand-blue font-bold rounded-xl hover:bg-zinc-100 transition-all transform hover:scale-105 shadow-lg">
                    Contact Support
                </a>
            </div>
        </div>
    </div>
</div>
