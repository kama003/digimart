<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Static pages
Route::get('/about', App\Livewire\Pages\AboutPage::class)->name('about');
Route::get('/contact', App\Livewire\Pages\ContactPage::class)->name('contact');
Route::get('/terms', App\Livewire\Pages\TermsPage::class)->name('terms');
Route::get('/privacy', App\Livewire\Pages\PrivacyPage::class)->name('privacy');

// Support pages
Route::get('/help', App\Livewire\Pages\HelpCenterPage::class)->name('help-center');
Route::get('/faq', App\Livewire\Pages\FaqPage::class)->name('faq');
Route::get('/seller-guide', App\Livewire\Pages\SellerGuidePage::class)->name('seller-guide');

// Product routes
Route::get('/search', App\Livewire\Product\ProductSearch::class)->name('products.search');
Route::get('/category/{slug}', App\Livewire\Product\ProductSearch::class)->name('category.show');
Route::get('/product/{slug}', App\Livewire\Product\ProductDetail::class)->name('product.show');

// Blog routes
Route::get('/blog', App\Livewire\Blog\BlogList::class)->name('blog.index');
Route::get('/blog/{slug}', App\Livewire\Blog\BlogDetail::class)->name('blog.show');

// Cart routes
Route::get('/cart', App\Livewire\Cart\CartPage::class)->name('cart.index');

// Checkout routes
Route::get('/checkout', App\Livewire\Checkout\CheckoutForm::class)->middleware('auth')->name('checkout');
Route::get('/checkout/success/{transaction}', App\Livewire\Checkout\CheckoutSuccess::class)->middleware('auth')->name('checkout.success');
Route::post('/paytm/callback', [App\Http\Controllers\PaytmCallbackController::class, 'handle'])->name('paytm.callback');

// Download routes
Route::get('/download/{download}', [App\Http\Controllers\DownloadController::class, 'download'])
    ->middleware(['auth', 'throttle:10,60'])
    ->scopeBindings()
    ->name('download');

// Customer routes
Route::middleware(['auth'])->prefix('customer')->group(function () {
    Route::get('/purchases', App\Livewire\Customer\PurchaseHistory::class)->name('customer.purchases');
    Route::get('/billing', App\Livewire\Customer\BillingHistory::class)->name('customer.billing');
});

// Notification routes
Route::get('/notifications', App\Livewire\Notifications\NotificationCenter::class)
    ->middleware('auth')
    ->name('notifications');

// Invoice routes
Route::get('/invoice/{transaction}', [App\Http\Controllers\InvoiceController::class, 'download'])
    ->middleware('auth')
    ->name('invoice.download');

// Webhook routes (excluded from CSRF protection in bootstrap/app.php)
Route::post('/webhook/stripe', [App\Http\Controllers\WebhookController::class, 'handleStripeWebhook'])->name('webhook.stripe');
Route::post('/webhook/paytm', [App\Http\Controllers\WebhookController::class, 'handlePaytmWebhook'])->name('webhook.paytm');

// Seller routes
Route::middleware(['auth', 'role:seller,admin'])->prefix('seller')->group(function () {
    Route::get('/dashboard', App\Livewire\Seller\SellerDashboard::class)->name('seller.dashboard');
    Route::get('/analytics', App\Livewire\Seller\SellerAnalytics::class)->name('seller.analytics');
    Route::get('/products', App\Livewire\Seller\SellerProducts::class)->name('seller.products.index');
    Route::get('/products/create', App\Livewire\Seller\ProductUpload::class)->name('seller.products.create');
    Route::get('/products/{product}/edit', App\Livewire\Seller\ProductEdit::class)->name('seller.products.edit');
    Route::get('/withdrawals/create', App\Livewire\Seller\WithdrawalRequest::class)->name('seller.withdrawals.create');
    Route::get('/withdrawals', App\Livewire\Seller\WithdrawalHistory::class)->name('seller.withdrawals');
    Route::get('/blog', App\Livewire\Seller\BlogManagement::class)->name('seller.blog.index');
    Route::get('/blog/create', App\Livewire\Seller\BlogCreate::class)->name('seller.blog.create');
    Route::get('/blog/{id}/edit', App\Livewire\Seller\BlogCreate::class)->name('seller.blog.edit');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', App\Livewire\Admin\AdminDashboard::class)->name('admin.dashboard');
    Route::get('/users', App\Livewire\Admin\UserManagement::class)->name('admin.users');
    Route::get('/seller-requests', App\Livewire\Admin\SellerRoleRequests::class)->name('admin.seller-requests');
    Route::get('/products', App\Livewire\Admin\ProductModeration::class)->name('admin.products');
    Route::get('/products/{product}/edit', App\Livewire\Admin\ProductEdit::class)->name('admin.products.edit');
    Route::get('/categories', App\Livewire\Admin\CategoryManagement::class)->name('admin.categories');
    Route::get('/transactions', App\Livewire\Admin\TransactionList::class)->name('admin.transactions');
    Route::get('/withdrawals', App\Livewire\Admin\WithdrawalManagement::class)->name('admin.withdrawals');
    Route::get('/reviews', App\Livewire\Admin\ReviewManagement::class)->name('admin.reviews');
    Route::get('/blog', App\Livewire\Admin\BlogModeration::class)->name('admin.blog');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');
    Route::get('settings/seller-request', App\Livewire\Profile\RequestSellerRole::class)->name('seller-request');
    Route::get('settings/api-tokens', App\Livewire\Settings\ApiTokens::class)->name('api-tokens');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
