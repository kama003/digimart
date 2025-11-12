<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Product routes
Route::get('/search', App\Livewire\Product\ProductSearch::class)->name('products.search');
Route::get('/product/{slug}', App\Livewire\Product\ProductDetail::class)->name('product.show');

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
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', App\Livewire\Admin\AdminDashboard::class)->name('admin.dashboard');
    Route::get('/users', App\Livewire\Admin\UserManagement::class)->name('admin.users');
    Route::get('/seller-requests', App\Livewire\Admin\SellerRoleRequests::class)->name('admin.seller-requests');
    Route::get('/products', App\Livewire\Admin\ProductModeration::class)->name('admin.products');
    Route::get('/products/{product}/edit', App\Livewire\Admin\ProductEdit::class)->name('admin.products.edit');
    Route::get('/transactions', App\Livewire\Admin\TransactionList::class)->name('admin.transactions');
    Route::get('/withdrawals', App\Livewire\Admin\WithdrawalManagement::class)->name('admin.withdrawals');
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
