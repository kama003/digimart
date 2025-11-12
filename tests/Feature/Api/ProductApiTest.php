<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_products(): void
    {
        // Create test data
        $user = User::factory()->create(['role' => 'seller']);
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Test description',
            'order' => 1,
        ]);
        
        for ($i = 1; $i <= 3; $i++) {
            Product::create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'title' => "Test Product {$i}",
                'slug' => "test-product-{$i}",
                'description' => 'Test description',
                'short_description' => 'Short description',
                'product_type' => 'audio',
                'price' => 10.00,
                'license_type' => 'Standard',
                'thumbnail_path' => 'test.jpg',
                'file_path' => 'test.zip',
                'file_size' => 1024,
                'is_approved' => true,
                'is_active' => true,
            ]);
        }

        // Test API endpoint
        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'slug',
                        'description',
                        'price',
                        'product_type',
                        'category',
                        'seller',
                    ]
                ],
                'links',
                'meta',
            ]);
    }

    public function test_can_show_single_product(): void
    {
        // Create test data
        $user = User::factory()->create(['role' => 'seller']);
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Test description',
            'order' => 1,
        ]);
        
        $product = Product::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Test Product',
            'slug' => 'test-product',
            'description' => 'Test description',
            'short_description' => 'Short description',
            'product_type' => 'audio',
            'price' => 10.00,
            'license_type' => 'Standard',
            'thumbnail_path' => 'test.jpg',
            'file_path' => 'test.zip',
            'file_size' => 1024,
            'is_approved' => true,
            'is_active' => true,
        ]);

        // Test API endpoint
        $response = $this->getJson("/api/v1/products/{$product->slug}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'slug',
                    'description',
                    'price',
                    'product_type',
                    'category',
                    'seller',
                ]
            ])
            ->assertJson([
                'data' => [
                    'id' => $product->id,
                    'slug' => $product->slug,
                ]
            ]);
    }

    public function test_can_filter_products_by_category(): void
    {
        $user = User::factory()->create(['role' => 'seller']);
        $category1 = Category::create([
            'name' => 'Audio',
            'slug' => 'audio',
            'description' => 'Audio category',
            'order' => 1,
        ]);
        $category2 = Category::create([
            'name' => 'Video',
            'slug' => 'video',
            'description' => 'Video category',
            'order' => 2,
        ]);
        
        Product::create([
            'user_id' => $user->id,
            'category_id' => $category1->id,
            'title' => 'Audio Product',
            'slug' => 'audio-product',
            'description' => 'Test description',
            'short_description' => 'Short description',
            'product_type' => 'audio',
            'price' => 10.00,
            'license_type' => 'Standard',
            'thumbnail_path' => 'test.jpg',
            'file_path' => 'test.zip',
            'file_size' => 1024,
            'is_approved' => true,
            'is_active' => true,
        ]);
        
        Product::create([
            'user_id' => $user->id,
            'category_id' => $category2->id,
            'title' => 'Video Product',
            'slug' => 'video-product',
            'description' => 'Test description',
            'short_description' => 'Short description',
            'product_type' => 'video',
            'price' => 20.00,
            'license_type' => 'Standard',
            'thumbnail_path' => 'test.jpg',
            'file_path' => 'test.zip',
            'file_size' => 2048,
            'is_approved' => true,
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/v1/products?category=audio');

        $response->assertStatus(200);
        $this->assertEquals(1, count($response->json('data')));
    }

    public function test_can_search_products(): void
    {
        $user = User::factory()->create(['role' => 'seller']);
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Test description',
            'order' => 1,
        ]);
        
        Product::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Amazing Audio Track',
            'slug' => 'amazing-audio-track',
            'description' => 'Test description',
            'short_description' => 'Short description',
            'product_type' => 'audio',
            'price' => 10.00,
            'license_type' => 'Standard',
            'thumbnail_path' => 'test.jpg',
            'file_path' => 'test.zip',
            'file_size' => 1024,
            'is_approved' => true,
            'is_active' => true,
        ]);
        
        Product::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Cool Video Template',
            'slug' => 'cool-video-template',
            'description' => 'Test description',
            'short_description' => 'Short description',
            'product_type' => 'video',
            'price' => 20.00,
            'license_type' => 'Standard',
            'thumbnail_path' => 'test.jpg',
            'file_path' => 'test.zip',
            'file_size' => 2048,
            'is_approved' => true,
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/v1/products?search=Audio');

        $response->assertStatus(200);
        $this->assertEquals(1, count($response->json('data')));
    }
}
