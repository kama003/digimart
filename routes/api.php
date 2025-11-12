<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API endpoints
Route::prefix('v1')->middleware('throttle:api-public')->group(function () {
    // Products
    Route::get('/products', [ProductController::class, 'index'])->name('api.products.index');
    Route::get('/products/{slug}', [ProductController::class, 'show'])->name('api.products.show');
    
    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('api.categories.index');
});

// Authenticated API endpoints
Route::prefix('v1')->middleware(['auth:sanctum', 'throttle:api-authenticated'])->group(function () {
    // User purchases
    Route::get('/user/purchases', [UserController::class, 'purchases'])->name('api.user.purchases');
    
    // Download generation (with stricter rate limiting)
    Route::post('/user/downloads/{download}', [UserController::class, 'generateDownload'])
        ->middleware('throttle:api-downloads')
        ->name('api.user.downloads.generate');
});
