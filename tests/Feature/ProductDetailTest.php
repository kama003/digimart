<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProductDetailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a seller user
        $this->seller = User::factory()->create([
            'role' => 'seller',
            'email_verified_at' => now(),
        ]);

        // Create a category
        $this->category = Category::create([
            'name' => 'Audio',
            'slug' => 'audio',
            'description' => 'Audio files',
            'order' => 1,
        ]);
    }

    public function test_product_detail_page_displays_approved_product()
    {
        $product = Product::create([
            'user_id' => $this->seller->id,
            'category_id' => $this->category->id,
            'title' => 'Test Product',
            'slug' => 'test-product',
            'description' => 'This is a test product description.',
            'short_description' => 'Test product',
            'product_type' => 'audio',
            'price' => 29.99,
            'license_type' => 'Commercial',
            'thumbnail_path' => 'products/thumbnails/test.jpg',
            'file_path' => 'products/audio/test.zip',
            'file_size' => 150000000,
            'is_approved' => true,
            'is_active' => true,
            'downloads_count' => 10,
        ]);

        $response = $this->get(route('product.show', $product->slug));

        $response->assertStatus(200);
        $response->assertSee($product->title);
        $response->assertSee($product->short_description);
        $response->assertSee($product->description);
        $response->assertSee('$' . number_format($product->price, 2));
        $response->assertSee($this->seller->name);
        $response->assertSee($this->category->name);
    }

    public function test_product_detail_page_returns_404_for_unapproved_product()
    {
        $product = Product::create([
            'user_id' => $this->seller->id,
            'category_id' => $this->category->id,
            'title' => 'Unapproved Product',
            'slug' => 'unapproved-product',
            'description' => 'This product is not approved.',
            'product_type' => 'audio',
            'price' => 19.99,
            'license_type' => 'Commercial',
            'thumbnail_path' => 'products/thumbnails/test.jpg',
            'file_path' => 'products/audio/test.zip',
            'file_size' => 100000000,
            'is_approved' => false,
            'is_active' => true,
        ]);

        $response = $this->get(route('product.show', $product->slug));

        $response->assertStatus(404);
    }

    public function test_product_detail_page_returns_404_for_inactive_product()
    {
        $product = Product::create([
            'user_id' => $this->seller->id,
            'category_id' => $this->category->id,
            'title' => 'Inactive Product',
            'slug' => 'inactive-product',
            'description' => 'This product is inactive.',
            'product_type' => 'audio',
            'price' => 19.99,
            'license_type' => 'Commercial',
            'thumbnail_path' => 'products/thumbnails/test.jpg',
            'file_path' => 'products/audio/test.zip',
            'file_size' => 100000000,
            'is_approved' => true,
            'is_active' => false,
        ]);

        $response = $this->get(route('product.show', $product->slug));

        $response->assertStatus(404);
    }

    public function test_product_detail_page_returns_404_for_nonexistent_product()
    {
        $response = $this->get(route('product.show', 'nonexistent-product'));

        $response->assertStatus(404);
    }

    public function test_add_to_cart_redirects_guest_to_login()
    {
        $product = Product::create([
            'user_id' => $this->seller->id,
            'category_id' => $this->category->id,
            'title' => 'Test Product',
            'slug' => 'test-product',
            'description' => 'Test description',
            'product_type' => 'audio',
            'price' => 29.99,
            'license_type' => 'Commercial',
            'thumbnail_path' => 'products/thumbnails/test.jpg',
            'file_path' => 'products/audio/test.zip',
            'file_size' => 150000000,
            'is_approved' => true,
            'is_active' => true,
        ]);

        Livewire::test(\App\Livewire\Product\ProductDetail::class, ['slug' => $product->slug])
            ->call('addToCart')
            ->assertRedirect(route('login'));
    }

    public function test_add_to_cart_shows_success_message_for_authenticated_user()
    {
        $customer = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        $product = Product::create([
            'user_id' => $this->seller->id,
            'category_id' => $this->category->id,
            'title' => 'Test Product',
            'slug' => 'test-product',
            'description' => 'Test description',
            'product_type' => 'audio',
            'price' => 29.99,
            'license_type' => 'Commercial',
            'thumbnail_path' => 'products/thumbnails/test.jpg',
            'file_path' => 'products/audio/test.zip',
            'file_size' => 150000000,
            'is_approved' => true,
            'is_active' => true,
        ]);

        $this->actingAs($customer);

        Livewire::test(\App\Livewire\Product\ProductDetail::class, ['slug' => $product->slug])
            ->call('addToCart')
            ->assertHasNoErrors()
            ->assertDispatched('cart-updated');
    }
}
