<?php

use App\Livewire\Product\ProductSearch;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Livewire\Livewire;

uses(Tests\TestCase::class);

test('product search page can be rendered', function () {
    $response = $this->get(route('products.search'));
    
    $response->assertStatus(200);
    $response->assertSeeLivewire(ProductSearch::class);
});

test('search filters products by keyword', function () {
    $seller = User::factory()->create(['role' => 'seller']);
    $category = Category::factory()->create();
    
    Product::factory()->create([
        'user_id' => $seller->id,
        'category_id' => $category->id,
        'title' => 'Epic Music Pack',
        'is_approved' => true,
        'is_active' => true,
    ]);
    
    Product::factory()->create([
        'user_id' => $seller->id,
        'category_id' => $category->id,
        'title' => 'Video Template',
        'is_approved' => true,
        'is_active' => true,
    ]);
    
    Livewire::test(ProductSearch::class)
        ->set('keyword', 'Epic')
        ->assertSee('Epic Music Pack')
        ->assertDontSee('Video Template');
});

test('search filters products by category', function () {
    $seller = User::factory()->create(['role' => 'seller']);
    $audioCategory = Category::factory()->create(['name' => 'Audio']);
    $videoCategory = Category::factory()->create(['name' => 'Video']);
    
    $audioProduct = Product::factory()->create([
        'user_id' => $seller->id,
        'category_id' => $audioCategory->id,
        'title' => 'Audio Product',
        'is_approved' => true,
        'is_active' => true,
    ]);
    
    $videoProduct = Product::factory()->create([
        'user_id' => $seller->id,
        'category_id' => $videoCategory->id,
        'title' => 'Video Product',
        'is_approved' => true,
        'is_active' => true,
    ]);
    
    Livewire::test(ProductSearch::class)
        ->set('category_id', $audioCategory->id)
        ->assertSee('Audio Product')
        ->assertDontSee('Video Product');
});

test('search filters products by price range', function () {
    $seller = User::factory()->create(['role' => 'seller']);
    $category = Category::factory()->create();
    
    Product::factory()->create([
        'user_id' => $seller->id,
        'category_id' => $category->id,
        'title' => 'Cheap Product',
        'price' => 10.00,
        'is_approved' => true,
        'is_active' => true,
    ]);
    
    Product::factory()->create([
        'user_id' => $seller->id,
        'category_id' => $category->id,
        'title' => 'Expensive Product',
        'price' => 100.00,
        'is_approved' => true,
        'is_active' => true,
    ]);
    
    Livewire::test(ProductSearch::class)
        ->set('min_price', 50)
        ->set('max_price', 150)
        ->assertSee('Expensive Product')
        ->assertDontSee('Cheap Product');
});

test('search filters products by product type', function () {
    $seller = User::factory()->create(['role' => 'seller']);
    $category = Category::factory()->create();
    
    Product::factory()->create([
        'user_id' => $seller->id,
        'category_id' => $category->id,
        'title' => 'Audio File',
        'product_type' => 'audio',
        'is_approved' => true,
        'is_active' => true,
    ]);
    
    Product::factory()->create([
        'user_id' => $seller->id,
        'category_id' => $category->id,
        'title' => 'Video File',
        'product_type' => 'video',
        'is_approved' => true,
        'is_active' => true,
    ]);
    
    Livewire::test(ProductSearch::class)
        ->set('product_type', 'audio')
        ->assertSee('Audio File')
        ->assertDontSee('Video File');
});

test('search only shows approved and active products', function () {
    $seller = User::factory()->create(['role' => 'seller']);
    $category = Category::factory()->create();
    
    $approvedProduct = Product::factory()->create([
        'user_id' => $seller->id,
        'category_id' => $category->id,
        'title' => 'Approved Product',
        'is_approved' => true,
        'is_active' => true,
    ]);
    
    $unapprovedProduct = Product::factory()->create([
        'user_id' => $seller->id,
        'category_id' => $category->id,
        'title' => 'Unapproved Product',
        'is_approved' => false,
        'is_active' => true,
    ]);
    
    $inactiveProduct = Product::factory()->create([
        'user_id' => $seller->id,
        'category_id' => $category->id,
        'title' => 'Inactive Product',
        'is_approved' => true,
        'is_active' => false,
    ]);
    
    Livewire::test(ProductSearch::class)
        ->assertSee('Approved Product')
        ->assertDontSee('Unapproved Product')
        ->assertDontSee('Inactive Product');
});

test('reset filters clears all search parameters', function () {
    Livewire::test(ProductSearch::class)
        ->set('keyword', 'test')
        ->set('category_id', 1)
        ->set('min_price', 10)
        ->set('max_price', 100)
        ->set('product_type', 'audio')
        ->call('resetFilters')
        ->assertSet('keyword', '')
        ->assertSet('category_id', '')
        ->assertSet('min_price', '')
        ->assertSet('max_price', '')
        ->assertSet('product_type', '');
});
