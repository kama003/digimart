<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_categories(): void
    {
        // Create test data
        for ($i = 1; $i <= 5; $i++) {
            Category::create([
                'name' => "Category {$i}",
                'slug' => "category-{$i}",
                'description' => "Description {$i}",
                'order' => $i,
            ]);
        }

        // Test API endpoint
        $response = $this->getJson('/api/v1/categories');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'slug',
                        'description',
                        'icon',
                        'order',
                    ]
                ]
            ]);

        $this->assertEquals(5, count($response->json('data')));
    }
}
