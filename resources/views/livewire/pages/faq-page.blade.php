<div class="min-h-screen bg-brand-black text-zinc-300 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-6 tracking-tight">Frequently Asked <span class="text-gradient-blue">Questions</span></h1>
            <p class="text-xl text-zinc-400 max-w-2xl mx-auto">
                Find answers to common questions about our platform, payments, and more.
            </p>
        </div>

        <!-- FAQ Categories -->
        <div class="space-y-4">
            <!-- General Questions -->
            <div class="bg-brand-surface border border-zinc-800 rounded-2xl overflow-hidden transition-all duration-300 {{ $openSection === 'general' ? 'ring-2 ring-brand-blue/50' : 'hover:border-zinc-700' }}">
                <button 
                    wire:click="toggleSection('general')"
                    class="w-full px-8 py-6 text-left flex items-center justify-between bg-brand-surface hover:bg-brand-surface-highlight transition-colors"
                >
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <span class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center mr-4 text-brand-blue">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </span>
                        General Questions
                    </h2>
                    <svg 
                        class="w-6 h-6 text-zinc-500 transform transition-transform duration-300 {{ $openSection === 'general' ? 'rotate-180 text-brand-blue' : '' }}"
                        fill="none" 
                        stroke="currentColor" 
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                @if($openSection === 'general')
                    <div class="px-8 pb-8 pt-2 space-y-8 border-t border-zinc-800/50 bg-brand-surface-highlight/30">
                        <div>
                            <h3 class="font-semibold text-white mb-3 text-lg">What is Digital Marketplace?</h3>
                            <p class="text-zinc-400 leading-relaxed">
                                Digital Marketplace is a platform where creators can sell digital products like audio files, videos, 3D models, templates, and graphics. Buyers get instant access to high-quality digital content.
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="font-semibold text-white mb-3 text-lg">How do I create an account?</h3>
                            <p class="text-zinc-400 leading-relaxed">
                                Click the "Sign Up" button in the top right corner, fill in your details, and verify your email address. You'll start as a customer and can request seller access from your profile.
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="font-semibold text-white mb-3 text-lg">Is my data secure?</h3>
                            <p class="text-zinc-400 leading-relaxed">
                                Yes! We use industry-standard encryption, PCI-compliant payment processing, and secure file storage. Your personal information and payment details are always protected.
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Buying Questions -->
            <div class="bg-brand-surface border border-zinc-800 rounded-2xl overflow-hidden transition-all duration-300 {{ $openSection === 'buying' ? 'ring-2 ring-brand-blue/50' : 'hover:border-zinc-700' }}">
                <button 
                    wire:click="toggleSection('buying')"
                    class="w-full px-8 py-6 text-left flex items-center justify-between bg-brand-surface hover:bg-brand-surface-highlight transition-colors"
                >
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <span class="w-8 h-8 rounded-lg bg-green-500/10 flex items-center justify-center mr-4 text-green-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </span>
                        Buying & Downloads
                    </h2>
                    <svg 
                        class="w-6 h-6 text-zinc-500 transform transition-transform duration-300 {{ $openSection === 'buying' ? 'rotate-180 text-brand-blue' : '' }}"
                        fill="none" 
                        stroke="currentColor" 
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                @if($openSection === 'buying')
                    <div class="px-8 pb-8 pt-2 space-y-8 border-t border-zinc-800/50 bg-brand-surface-highlight/30">
                        <div>
                            <h3 class="font-semibold text-white mb-3 text-lg">How do I purchase a product?</h3>
                            <p class="text-zinc-400 leading-relaxed">
                                Browse products, add items to your cart, and proceed to checkout. We accept payments via Stripe (credit/debit cards) and Paytm. After payment, you'll receive instant download links.
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="font-semibold text-white mb-3 text-lg">How long are download links valid?</h3>
                            <p class="text-zinc-400 leading-relaxed">
                                Download links are valid for 24 hours by default. You can access your purchase history anytime from your dashboard to generate new download links if needed.
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="font-semibold text-white mb-3 text-lg">Can I get a refund?</h3>
                            <p class="text-zinc-400 leading-relaxed">
                                Due to the nature of digital products, refunds are handled on a case-by-case basis. Contact us through the support page if you experience issues with your purchase.
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="font-semibold text-white mb-3 text-lg">What payment methods do you accept?</h3>
                            <p class="text-zinc-400 leading-relaxed">
                                We accept all major credit and debit cards through Stripe, as well as Paytm for customers in India. All transactions are secure and encrypted.
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Selling Questions -->
            <div class="bg-brand-surface border border-zinc-800 rounded-2xl overflow-hidden transition-all duration-300 {{ $openSection === 'selling' ? 'ring-2 ring-brand-blue/50' : 'hover:border-zinc-700' }}">
                <button 
                    wire:click="toggleSection('selling')"
                    class="w-full px-8 py-6 text-left flex items-center justify-between bg-brand-surface hover:bg-brand-surface-highlight transition-colors"
                >
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <span class="w-8 h-8 rounded-lg bg-purple-500/10 flex items-center justify-center mr-4 text-purple-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </span>
                        Selling Products
                    </h2>
                    <svg 
                        class="w-6 h-6 text-zinc-500 transform transition-transform duration-300 {{ $openSection === 'selling' ? 'rotate-180 text-brand-blue' : '' }}"
                        fill="none" 
                        stroke="currentColor" 
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                @if($openSection === 'selling')
                    <div class="px-8 pb-8 pt-2 space-y-8 border-t border-zinc-800/50 bg-brand-surface-highlight/30">
                        <div>
                            <h3 class="font-semibold text-white mb-3 text-lg">How do I become a seller?</h3>
                            <p class="text-zinc-400 leading-relaxed">
                                Go to your profile page and click "Request Seller Role". An admin will review your request. Once approved, you can start uploading and selling products.
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="font-semibold text-white mb-3 text-lg">What commission does the platform take?</h3>
                            <p class="text-zinc-400 leading-relaxed">
                                We charge a 10% platform fee on all sales. This means you keep 90% of the sale price. For example, if you sell a product for $100, you earn $90.
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="font-semibold text-white mb-3 text-lg">How do I withdraw my earnings?</h3>
                            <p class="text-zinc-400 leading-relaxed">
                                Visit your seller dashboard and go to the Withdrawals section. Submit a withdrawal request with your payment details. Admins typically process requests within 3-5 business days.
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="font-semibold text-white mb-3 text-lg">What file types can I upload?</h3>
                            <p class="text-zinc-400 leading-relaxed">
                                We support various digital file formats including audio (MP3, WAV), video (MP4, MOV), 3D models (OBJ, FBX), images (JPG, PNG), and archives (ZIP). Check the upload page for specific requirements.
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="font-semibold text-white mb-3 text-lg">Do products need approval?</h3>
                            <p class="text-zinc-400 leading-relaxed">
                                Yes, all products go through a moderation process to ensure quality and compliance with our guidelines. You'll be notified once your product is approved or if changes are needed.
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Technical Questions -->
            <div class="bg-brand-surface border border-zinc-800 rounded-2xl overflow-hidden transition-all duration-300 {{ $openSection === 'technical' ? 'ring-2 ring-brand-blue/50' : 'hover:border-zinc-700' }}">
                <button 
                    wire:click="toggleSection('technical')"
                    class="w-full px-8 py-6 text-left flex items-center justify-between bg-brand-surface hover:bg-brand-surface-highlight transition-colors"
                >
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <span class="w-8 h-8 rounded-lg bg-orange-500/10 flex items-center justify-center mr-4 text-orange-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </span>
                        Technical Support
                    </h2>
                    <svg 
                        class="w-6 h-6 text-zinc-500 transform transition-transform duration-300 {{ $openSection === 'technical' ? 'rotate-180 text-brand-blue' : '' }}"
                        fill="none" 
                        stroke="currentColor" 
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                @if($openSection === 'technical')
                    <div class="px-8 pb-8 pt-2 space-y-8 border-t border-zinc-800/50 bg-brand-surface-highlight/30">
                        <div>
                            <h3 class="font-semibold text-white mb-3 text-lg">My download link expired. What do I do?</h3>
                            <p class="text-zinc-400 leading-relaxed">
                                Go to your purchase history in your dashboard. Click on the product to generate a new download link. You can do this anytime for products you've purchased.
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="font-semibold text-white mb-3 text-lg">I'm having trouble uploading files. Help!</h3>
                            <p class="text-zinc-400 leading-relaxed">
                                Make sure your file meets the size and format requirements. Check your internet connection and try again. If issues persist, contact support with details about the error message.
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="font-semibold text-white mb-3 text-lg">How do I use the API?</h3>
                            <p class="text-zinc-400 leading-relaxed">
                                Visit your Settings > API Tokens page to generate an API token. Check our API documentation for endpoints and usage examples. The API uses token-based authentication.
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="font-semibold text-white mb-3 text-lg">Is there a mobile app?</h3>
                            <p class="text-zinc-400 leading-relaxed">
                                Currently, we offer a fully responsive web platform that works great on mobile devices. A dedicated mobile app is in our roadmap for future development.
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Account & Security -->
            <div class="bg-brand-surface border border-zinc-800 rounded-2xl overflow-hidden transition-all duration-300 {{ $openSection === 'account' ? 'ring-2 ring-brand-blue/50' : 'hover:border-zinc-700' }}">
                <button 
                    wire:click="toggleSection('account')"
                    class="w-full px-8 py-6 text-left flex items-center justify-between bg-brand-surface hover:bg-brand-surface-highlight transition-colors"
                >
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <span class="w-8 h-8 rounded-lg bg-red-500/10 flex items-center justify-center mr-4 text-red-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </span>
                        Account & Security
                    </h2>
                    <svg 
                        class="w-6 h-6 text-zinc-500 transform transition-transform duration-300 {{ $openSection === 'account' ? 'rotate-180 text-brand-blue' : '' }}"
                        fill="none" 
                        stroke="currentColor" 
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                @if($openSection === 'account')
                    <div class="px-8 pb-8 pt-2 space-y-8 border-t border-zinc-800/50 bg-brand-surface-highlight/30">
                        <div>
                            <h3 class="font-semibold text-white mb-3 text-lg">How do I reset my password?</h3>
                            <p class="text-zinc-400 leading-relaxed">
                                Click "Forgot Password" on the login page. Enter your email address and we'll send you a password reset link. Follow the instructions in the email to set a new password.
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="font-semibold text-white mb-3 text-lg">Can I change my email address?</h3>
                            <p class="text-zinc-400 leading-relaxed">
                                Yes, go to your profile settings to update your email address. You'll need to verify the new email address before the change takes effect.
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="font-semibold text-white mb-3 text-lg">How do I delete my account?</h3>
                            <p class="text-zinc-400 leading-relaxed">
                                Contact our support team to request account deletion. Note that this action is permanent and will remove all your data, including purchase history and uploaded products.
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="font-semibold text-white mb-3 text-lg">Do you offer two-factor authentication?</h3>
                            <p class="text-zinc-400 leading-relaxed">
                                Yes! Enable two-factor authentication (2FA) in your profile settings for an extra layer of security. We recommend all users enable this feature.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Still Have Questions -->
        <div class="mt-16 text-center bg-gradient-to-r from-brand-blue to-brand-purple rounded-3xl p-10 md:p-12 text-white shadow-2xl relative overflow-hidden">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="relative z-10">
                <h2 class="text-2xl md:text-3xl font-bold mb-4">Still Have Questions?</h2>
                <p class="mb-8 text-blue-50 text-lg">Can't find what you're looking for? We're here to help!</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('contact') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white text-brand-blue font-bold rounded-xl hover:bg-zinc-100 transition-all transform hover:scale-105 shadow-lg">
                        Contact Support
                    </a>
                    <a href="{{ route('help-center') }}" class="inline-flex items-center justify-center px-8 py-4 bg-transparent border-2 border-white text-white font-bold rounded-xl hover:bg-white/10 transition-all">
                        Visit Help Center
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>