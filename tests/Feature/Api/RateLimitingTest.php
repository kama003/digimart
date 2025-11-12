<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Download;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RateLimitingTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_api_rate_limit_is_enforced(): void
    {
        // Create test data
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

        // Make 61 requests (limit is 60 per minute)
        for ($i = 0; $i < 61; $i++) {
            $response = $this->getJson('/api/v1/products');
            
            if ($i < 60) {
                $response->assertStatus(200);
            } else {
                // 61st request should be rate limited
                $response->assertStatus(429)
                    ->assertJson([
                        'success' => false,
                        'error' => 'rate_limit_exceeded',
                    ]);
                
                // Check for Retry-After header
                $this->assertNotNull($response->headers->get('Retry-After'));
            }
        }
    }

    public function test_authenticated_api_rate_limit_is_enforced(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Make 121 requests (limit is 120 per minute)
        for ($i = 0; $i < 121; $i++) {
            $response = $this->getJson('/api/v1/user/purchases');
            
            if ($i < 120) {
                $response->assertStatus(200);
            } else {
                // 121st request should be rate limited
                $response->assertStatus(429)
                    ->assertJson([
                        'success' => false,
                        'error' => 'rate_limit_exceeded',
                    ]);
                
                // Check for Retry-After header
                $this->assertNotNull($response->headers->get('Retry-After'));
            }
        }
    }

    public function test_download_api_rate_limit_is_enforced(): void
    {
        $customer = User::factory()->create();
        $seller = User::factory()->create(['role' => 'seller']);
        
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Test description',
            'order' => 1,
        ]);
        
        $product = Product::create([
            'user_id' => $seller->id,
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

        $transaction = Transaction::create([
            'user_id' => $customer->id,
            'payment_gateway' => 'stripe',
            'payment_id' => 'test_payment_123',
            'amount' => 10.00,
            'commission' => 1.00,
            'seller_amount' => 9.00,
            'status' => 'completed',
        ]);

        $download = Download::create([
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'transaction_id' => $transaction->id,
            'download_url' => 'https://example.com/download',
            'expires_at' => now()->addHours(24),
        ]);

        Sanctum::actingAs($customer);

        // Make 11 requests (limit is 10 per hour)
        for ($i = 0; $i < 11; $i++) {
            $response = $this->postJson("/api/v1/user/downloads/{$download->id}");
            
            if ($i < 10) {
                // First 10 requests should succeed (or fail for other reasons, but not rate limit)
                $this->assertNotEquals(429, $response->status());
            } else {
                // 11th request should be rate limited
                $response->assertStatus(429)
                    ->assertJson([
                        'success' => false,
                        'error' => 'download_limit_exceeded',
                    ]);
                
                // Check for Retry-After header
                $this->assertNotNull($response->headers->get('Retry-After'));
            }
        }
    }

    public function test_rate_limit_response_includes_retry_after_header(): void
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

        // Exceed rate limit
        for ($i = 0; $i < 61; $i++) {
            $response = $this->getJson('/api/v1/products');
        }

        // Verify the rate limit response has proper headers
        $response->assertStatus(429);
        $this->assertNotNull($response->headers->get('Retry-After'));
        $this->assertNotNull($response->headers->get('X-RateLimit-Limit'));
        $this->assertNotNull($response->headers->get('X-RateLimit-Remaining'));
    }
}
