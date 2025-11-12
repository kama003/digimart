<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Download;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_purchases(): void
    {
        $user = User::factory()->create();
        $seller = User::factory()->create(['role' => 'seller']);
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $seller->id,
            'category_id' => $category->id,
            'is_approved' => true,
            'is_active' => true,
        ]);

        $transaction = Transaction::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
        ]);

        TransactionItem::factory()->create([
            'transaction_id' => $transaction->id,
            'product_id' => $product->id,
            'seller_id' => $seller->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/user/purchases');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'slug',
                        'product_type',
                        'price',
                        'category',
                        'seller',
                        'purchased_at',
                        'transaction_id',
                    ],
                ],
                'meta' => [
                    'current_page',
                    'last_page',
                    'per_page',
                    'total',
                ],
            ]);
    }

    public function test_unauthenticated_user_cannot_access_purchases(): void
    {
        $response = $this->getJson('/api/v1/user/purchases');

        $response->assertStatus(401);
    }

    public function test_user_can_generate_download_link(): void
    {
        $user = User::factory()->create();
        $seller = User::factory()->create(['role' => 'seller']);
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $seller->id,
            'category_id' => $category->id,
            'is_approved' => true,
            'is_active' => true,
        ]);

        $transaction = Transaction::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
        ]);

        $download = Download::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'transaction_id' => $transaction->id,
            'expires_at' => now()->subHour(), // Expired
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson("/api/v1/user/downloads/{$download->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'download_url',
                    'expires_at',
                    'product',
                ],
                'message',
            ]);
    }

    public function test_user_cannot_generate_download_link_for_other_users_purchase(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $seller = User::factory()->create(['role' => 'seller']);
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $seller->id,
            'category_id' => $category->id,
            'is_approved' => true,
            'is_active' => true,
        ]);

        $transaction = Transaction::factory()->create([
            'user_id' => $otherUser->id,
            'status' => 'completed',
        ]);

        $download = Download::factory()->create([
            'user_id' => $otherUser->id,
            'product_id' => $product->id,
            'transaction_id' => $transaction->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson("/api/v1/user/downloads/{$download->id}");

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthorized access to this download.',
            ]);
    }
}
