<div class="min-h-screen bg-brand-black text-zinc-300 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-6 tracking-tight">Contact <span class="text-gradient-blue">Support</span></h1>
            <p class="text-xl text-zinc-400 max-w-3xl mx-auto leading-relaxed">
                Have a question or need help? We're here for you. Reach out to our team and we'll get back to you shortly.
            </p>
        </div>

        <div class="grid lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div>
                <div class="bg-brand-surface border border-zinc-800 rounded-3xl p-8 shadow-xl">
                    <h2 class="text-2xl font-bold text-white mb-8">Send us a Message</h2>
                    
                    @if (session()->has('success'))
                        <div class="mb-8 p-4 bg-green-500/10 border border-green-500/20 text-green-400 rounded-xl flex items-center">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    <form wire:submit="submit" class="space-y-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-zinc-400 mb-2">
                                Your Name *
                            </label>
                            <input 
                                type="text" 
                                id="name" 
                                wire:model="name"
                                class="w-full px-4 py-3 border border-zinc-700 rounded-xl bg-brand-surface-highlight text-white focus:ring-2 focus:ring-brand-blue focus:border-transparent transition-all placeholder-zinc-600"
                                placeholder="John Doe"
                            >
                            @error('name')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-zinc-400 mb-2">
                                Email Address *
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                wire:model="email"
                                class="w-full px-4 py-3 border border-zinc-700 rounded-xl bg-brand-surface-highlight text-white focus:ring-2 focus:ring-brand-blue focus:border-transparent transition-all placeholder-zinc-600"
                                placeholder="john@example.com"
                            >
                            @error('email')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Subject -->
                        <div>
                            <label for="subject" class="block text-sm font-medium text-zinc-400 mb-2">
                                Subject *
                            </label>
                            <input 
                                type="text" 
                                id="subject" 
                                wire:model="subject"
                                class="w-full px-4 py-3 border border-zinc-700 rounded-xl bg-brand-surface-highlight text-white focus:ring-2 focus:ring-brand-blue focus:border-transparent transition-all placeholder-zinc-600"
                                placeholder="How can we help?"
                            >
                            @error('subject')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-sm font-medium text-zinc-400 mb-2">
                                Message *
                            </label>
                            <textarea 
                                id="message" 
                                wire:model="message"
                                rows="6"
                                class="w-full px-4 py-3 border border-zinc-700 rounded-xl bg-brand-surface-highlight text-white focus:ring-2 focus:ring-brand-blue focus:border-transparent transition-all placeholder-zinc-600 resize-none"
                                placeholder="Tell us more about your inquiry..."
                            ></textarea>
                            @error('message')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit"
                            class="w-full px-6 py-4 bg-brand-blue hover:bg-brand-blue-hover text-white font-bold rounded-xl transition-all transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 shadow-lg shadow-blue-900/20"
                            wire:loading.attr="disabled"
                        >
                            <span wire:loading.remove>Send Message</span>
                            <span wire:loading class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Sending...
                            </span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="space-y-8">
                <!-- Contact Details -->
                <div class="bg-brand-surface border border-zinc-800 rounded-3xl p-8 shadow-xl">
                    <h2 class="text-2xl font-bold text-white mb-8">Get in Touch</h2>
                    
                    <div class="space-y-8">
                        <!-- Email -->
                        <div class="flex items-start group">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center group-hover:bg-blue-500/20 transition-colors">
                                    <svg class="w-6 h-6 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5">
                                <h3 class="text-lg font-semibold text-white mb-1">Email</h3>
                                <p class="text-zinc-400">{{ config('mail.from.address') }}</p>
                            </div>
                        </div>

                        <!-- Response Time -->
                        <div class="flex items-start group">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-green-500/10 rounded-xl flex items-center justify-center group-hover:bg-green-500/20 transition-colors">
                                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5">
                                <h3 class="text-lg font-semibold text-white mb-1">Response Time</h3>
                                <p class="text-zinc-400">We typically respond within 24 hours</p>
                            </div>
                        </div>

                        <!-- Support Hours -->
                        <div class="flex items-start group">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-purple-500/10 rounded-xl flex items-center justify-center group-hover:bg-purple-500/20 transition-colors">
                                    <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5">
                                <h3 class="text-lg font-semibold text-white mb-1">Support Hours</h3>
                                <p class="text-zinc-400">Monday - Friday: 9:00 AM - 6:00 PM</p>
                                <p class="text-zinc-400">Saturday - Sunday: 10:00 AM - 4:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ Link -->
                <div class="bg-gradient-to-r from-brand-blue to-brand-purple rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-10 -mt-10 pointer-events-none"></div>
                    <h3 class="text-xl font-bold mb-3 relative z-10">Looking for Quick Answers?</h3>
                    <p class="mb-6 text-blue-50 relative z-10">Check out our FAQ page for instant answers to common questions.</p>
                    <a href="{{ route('faq') }}" class="inline-flex items-center px-6 py-3 bg-white text-brand-blue font-bold rounded-xl hover:bg-zinc-100 transition-all shadow-lg relative z-10">
                        Visit FAQ
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>

                <!-- Help Center Link -->
                <div class="bg-brand-surface border border-zinc-800 rounded-3xl p-8 shadow-xl hover:border-zinc-700 transition-colors">
                    <h3 class="text-xl font-bold text-white mb-3">Need More Help?</h3>
                    <p class="text-zinc-400 mb-6">Browse our comprehensive help center for guides and tutorials.</p>
                    <a href="{{ route('help-center') }}" class="inline-flex items-center text-brand-blue font-semibold hover:text-brand-blue-hover transition-colors">
                        Visit Help Center
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>