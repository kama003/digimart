<div class="min-h-screen bg-brand-black text-zinc-300 py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-6 tracking-tight">Seller <span class="text-gradient-blue">Guide</span></h1>
            <p class="text-xl text-zinc-400">Everything you need to know about selling on our platform</p>
        </div>

        <!-- Quick Stats -->
        <div class="grid md:grid-cols-3 gap-6 mb-16">
            <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl shadow-lg p-8 text-white text-center transform hover:-translate-y-1 transition-transform duration-300">
                <div class="text-4xl font-bold mb-2">85%</div>
                <div class="text-blue-100 font-medium">You Keep</div>
            </div>
            <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-2xl shadow-lg p-8 text-white text-center transform hover:-translate-y-1 transition-transform duration-300">
                <div class="text-4xl font-bold mb-2">$50</div>
                <div class="text-green-100 font-medium">Min. Withdrawal</div>
            </div>
            <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl shadow-lg p-8 text-white text-center transform hover:-translate-y-1 transition-transform duration-300">
                <div class="text-4xl font-bold mb-2">5-7 Days</div>
                <div class="text-purple-100 font-medium">Payout Time</div>
            </div>
        </div>

        <!-- Getting Started -->
        <div class="bg-brand-surface border border-zinc-800 rounded-3xl p-8 md:p-12 mb-12 shadow-xl">
            <h2 class="text-2xl font-bold text-white mb-8 flex items-center">
                <span class="bg-brand-blue text-white rounded-xl w-10 h-10 flex items-center justify-center mr-4 text-lg shadow-lg shadow-blue-900/50">1</span>
                Getting Started
            </h2>
            
            <div class="space-y-8 pl-4 md:pl-14">
                <div class="relative border-l-2 border-zinc-800 pl-8 pb-2">
                    <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-zinc-700 border-2 border-brand-black"></div>
                    <h3 class="text-lg font-semibold text-white mb-3">Create Your Account</h3>
                    <p class="text-zinc-400 mb-4 leading-relaxed">
                        If you don't have an account yet, sign up for free. You'll need to provide basic information including your name, email, and create a secure password.
                    </p>
                    @guest
                        <a href="{{ route('register') }}" class="inline-block bg-brand-blue hover:bg-brand-blue-hover text-white font-semibold px-6 py-2.5 rounded-xl transition-colors">
                            Create Account
                        </a>
                    @endguest
                </div>

                <div class="relative border-l-2 border-zinc-800 pl-8 pb-2">
                    <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-zinc-700 border-2 border-brand-black"></div>
                    <h3 class="text-lg font-semibold text-white mb-3">Request Seller Access</h3>
                    <p class="text-zinc-400 mb-4 leading-relaxed">
                        Once logged in, go to your account settings and click "Request Seller Role". Our team will review your request, typically within 24-48 hours.
                    </p>
                    @auth
                        @if(!auth()->user()->isSeller() && !auth()->user()->isAdmin())
                            <a href="{{ route('seller-request') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2.5 rounded-xl transition-colors">
                                Request Seller Access
                            </a>
                        @endif
                    @endauth
                </div>

                <div class="relative border-l-2 border-zinc-800 pl-8">
                    <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-zinc-700 border-2 border-brand-black"></div>
                    <h3 class="text-lg font-semibold text-white mb-3">Set Up Your Profile</h3>
                    <p class="text-zinc-400 leading-relaxed">
                        Complete your seller profile with a professional bio, profile picture, and contact information. This helps build trust with potential buyers.
                    </p>
                </div>
            </div>
        </div>

        <!-- Uploading Products -->
        <div class="bg-brand-surface border border-zinc-800 rounded-3xl p-8 md:p-12 mb-12 shadow-xl">
            <h2 class="text-2xl font-bold text-white mb-8 flex items-center">
                <span class="bg-green-600 text-white rounded-xl w-10 h-10 flex items-center justify-center mr-4 text-lg shadow-lg shadow-green-900/50">2</span>
                Uploading Products
            </h2>
            
            <div class="space-y-8 pl-4 md:pl-14">
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4">Product Requirements</h3>
                    <ul class="space-y-3 text-zinc-400">
                        <li class="flex items-center"><span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-3"></span>You must own or have rights to sell the product</li>
                        <li class="flex items-center"><span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-3"></span>Products must be free of malware, viruses, or malicious code</li>
                        <li class="flex items-center"><span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-3"></span>File size limit: 500MB per product</li>
                        <li class="flex items-center"><span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-3"></span>Supported formats: ZIP, RAR, MP3, MP4, PDF, and more</li>
                        <li class="flex items-center"><span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-3"></span>Products must not violate copyright or trademark laws</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-white mb-4">Creating a Great Listing</h3>
                    <div class="bg-brand-surface-highlight border border-zinc-800 rounded-2xl p-6 space-y-4">
                        <div class="flex items-start">
                            <div class="bg-blue-500/10 p-2 rounded-lg mr-4">
                                <svg class="w-5 h-5 text-brand-blue" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <strong class="text-white block mb-1">Clear Title</strong>
                                <span class="text-zinc-400 text-sm">Use descriptive, searchable titles (e.g., "Professional Logo Templates Pack - 50 Designs")</span>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="bg-blue-500/10 p-2 rounded-lg mr-4">
                                <svg class="w-5 h-5 text-brand-blue" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <strong class="text-white block mb-1">Detailed Description</strong>
                                <span class="text-zinc-400 text-sm">Explain what's included, features, and how buyers can use it</span>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="bg-blue-500/10 p-2 rounded-lg mr-4">
                                <svg class="w-5 h-5 text-brand-blue" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <strong class="text-white block mb-1">High-Quality Images</strong>
                                <span class="text-zinc-400 text-sm">Upload clear preview images (minimum 800x600px)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing & Earnings -->
        <div class="bg-brand-surface border border-zinc-800 rounded-3xl p-8 md:p-12 mb-12 shadow-xl">
            <h2 class="text-2xl font-bold text-white mb-8 flex items-center">
                <span class="bg-purple-600 text-white rounded-xl w-10 h-10 flex items-center justify-center mr-4 text-lg shadow-lg shadow-purple-900/50">3</span>
                Pricing & Earnings
            </h2>
            
            <div class="space-y-8 pl-4 md:pl-14">
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4">Commission Structure</h3>
                    <p class="text-zinc-400 mb-4 leading-relaxed">
                        We charge a 15% commission on each sale. This covers:
                    </p>
                    <ul class="grid md:grid-cols-2 gap-3 text-zinc-400 mb-6">
                        <li class="flex items-center"><span class="w-1.5 h-1.5 bg-purple-500 rounded-full mr-3"></span>Payment processing fees</li>
                        <li class="flex items-center"><span class="w-1.5 h-1.5 bg-purple-500 rounded-full mr-3"></span>Secure file hosting and bandwidth</li>
                        <li class="flex items-center"><span class="w-1.5 h-1.5 bg-purple-500 rounded-full mr-3"></span>Platform maintenance</li>
                        <li class="flex items-center"><span class="w-1.5 h-1.5 bg-purple-500 rounded-full mr-3"></span>Customer support</li>
                        <li class="flex items-center"><span class="w-1.5 h-1.5 bg-purple-500 rounded-full mr-3"></span>Marketing and promotion</li>
                    </ul>
                </div>

                <div class="bg-brand-surface-highlight border border-zinc-800 rounded-2xl p-6">
                    <h4 class="font-semibold text-white mb-4">Example Calculation:</h4>
                    <div class="space-y-3 text-zinc-400">
                        <div class="flex justify-between items-center">
                            <span>Product Price:</span>
                            <span class="font-semibold text-white">$100.00</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Platform Commission (15%):</span>
                            <span class="text-red-400">-$15.00</span>
                        </div>
                        <div class="border-t border-zinc-700 pt-3 flex justify-between items-center font-bold text-white text-lg">
                            <span>Your Earnings:</span>
                            <span class="text-green-500">$85.00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Withdrawals -->
        <div class="bg-brand-surface border border-zinc-800 rounded-3xl p-8 md:p-12 mb-12 shadow-xl">
            <h2 class="text-2xl font-bold text-white mb-8 flex items-center">
                <span class="bg-orange-500 text-white rounded-xl w-10 h-10 flex items-center justify-center mr-4 text-lg shadow-lg shadow-orange-900/50">4</span>
                Getting Paid
            </h2>
            
            <div class="space-y-8 pl-4 md:pl-14">
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4">Withdrawal Process</h3>
                    <ol class="space-y-4 text-zinc-400">
                        <li class="flex items-start">
                            <span class="font-bold text-orange-500 mr-3">1.</span>
                            Earn at least $50 in your seller balance
                        </li>
                        <li class="flex items-start">
                            <span class="font-bold text-orange-500 mr-3">2.</span>
                            Go to your Seller Dashboard and click "Request Withdrawal"
                        </li>
                        <li class="flex items-start">
                            <span class="font-bold text-orange-500 mr-3">3.</span>
                            Enter your bank account or PayPal details
                        </li>
                        <li class="flex items-start">
                            <span class="font-bold text-orange-500 mr-3">4.</span>
                            Submit your withdrawal request
                        </li>
                        <li class="flex items-start">
                            <span class="font-bold text-orange-500 mr-3">5.</span>
                            Receive payment within 5-7 business days
                        </li>
                    </ol>
                </div>

                <div class="bg-orange-500/10 border border-orange-500/20 rounded-2xl p-6 flex items-start">
                    <svg class="w-6 h-6 text-orange-500 mr-4 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <strong class="text-white block mb-1">Important</strong>
                        <p class="text-zinc-400 text-sm leading-relaxed">
                            Ensure your payment details are accurate. Incorrect information may delay your payment. You're responsible for any taxes on your earnings.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Support -->
        <div class="text-center bg-gradient-to-r from-brand-blue to-brand-purple rounded-3xl p-10 md:p-12 text-white shadow-2xl relative overflow-hidden">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="relative z-10">
                <h2 class="text-3xl font-bold mb-4">Need Help?</h2>
                <p class="text-blue-50 mb-8 text-lg">Our team is here to support your success every step of the way.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('faq') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white text-brand-blue font-bold rounded-xl hover:bg-zinc-100 transition-all transform hover:scale-105 shadow-lg">
                        View FAQ
                    </a>
                    <a href="{{ route('contact') }}" class="inline-flex items-center justify-center px-8 py-4 bg-transparent border-2 border-white text-white font-bold rounded-xl hover:bg-white/10 transition-all">
                        Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
