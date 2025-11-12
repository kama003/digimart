<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class HomepageTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test seller user
        $seller = User::firstOrCreate(
            ['email' => 'seller@example.com'],
            [
                'name' => 'Test Seller',
                'password' => bcrypt('password'),
                'role' => 'seller',
                'email_verified_at' => now(),
            ]
        );

        // Create categories
        $categories = [
            ['name' => 'Audio', 'icon' => '🎵', 'description' => 'Music tracks, sound effects, and audio files', 'order' => 1],
            ['name' => 'Video', 'icon' => '🎬', 'description' => 'Video templates, stock footage, and animations', 'order' => 2],
            ['name' => '3D Models', 'icon' => '🎨', 'description' => '3D assets, models, and textures', 'order' => 3],
            ['name' => 'Templates', 'icon' => '📄', 'description' => 'Web templates, UI kits, and design files', 'order' => 4],
            ['name' => 'Graphics', 'icon' => '🖼️', 'description' => 'Icons, illustrations, and graphic elements', 'order' => 5],
            ['name' => 'Fonts', 'icon' => '✍️', 'description' => 'Typography and font families', 'order' => 6],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($categoryData['name'])],
                $categoryData
            );
        }

        // Create sample products
        $audioCategory = Category::where('slug', 'audio')->first();
        $videoCategory = Category::where('slug', 'video')->first();
        $graphicsCategory = Category::where('slug', 'graphics')->first();

        $products = [
            [
                'user_id' => $seller->id,
                'category_id' => $audioCategory->id,
                'title' => 'Epic Cinematic Music Pack',
                'description' => 'A collection of 10 epic cinematic music tracks perfect for trailers, games, and videos.',
                'short_description' => 'Epic music for your projects',
                'product_type' => 'audio',
                'price' => 29.99,
                'license_type' => 'Commercial',
                'thumbnail_path' => 'products/thumbnails/epic-pack.jpg',
                'file_path' => 'products/audio/epic-pack.zip',
                'file_size' => 150000000,
                'is_approved' => true,
                'is_active' => true,
                'downloads_count' => 145,
            ],
            [
                'user_id' => $seller->id,
                'category_id' => $videoCategory->id,
                'title' => 'Modern Logo Reveal Animation',
                'description' => 'Professional logo reveal animation template with customizable colors and text.',
                'short_description' => 'Stunning logo animation',
                'product_type' => 'video',
                'price' => 19.99,
                'license_type' => 'Commercial',
                'thumbnail_path' => 'products/thumbnails/logo-reveal.jpg',
                'file_path' => 'products/video/logo-reveal.zip',
                'file_size' => 250000000,
                'is_approved' => true,
                'is_active' => true,
                'downloads_count' => 89,
            ],
            [
                'user_id' => $seller->id,
                'category_id' => $graphicsCategory->id,
                'title' => 'Minimalist Icon Set - 500 Icons',
                'description' => 'A comprehensive set of 500 minimalist icons in multiple formats (SVG, PNG, AI).',
                'short_description' => '500 beautiful icons',
                'product_type' => 'graphic',
                'price' => 39.99,
                'license_type' => 'Extended',
                'thumbnail_path' => 'products/thumbnails/icon-set.jpg',
                'file_path' => 'products/graphics/icon-set.zip',
                'file_size' => 50000000,
                'is_approved' => true,
                'is_active' => true,
                'downloads_count' => 234,
            ],
            [
                'user_id' => $seller->id,
                'category_id' => $audioCategory->id,
                'title' => 'Ambient Background Music Collection',
                'description' => 'Relaxing ambient music perfect for meditation, yoga, or background atmosphere.',
                'short_description' => 'Peaceful ambient sounds',
                'product_type' => 'audio',
                'price' => 24.99,
                'license_type' => 'Commercial',
                'thumbnail_path' => 'products/thumbnails/ambient-collection.jpg',
                'file_path' => 'products/audio/ambient-collection.zip',
                'file_size' => 120000000,
                'is_approved' => true,
                'is_active' => true,
                'downloads_count' => 67,
            ],
            [
                'user_id' => $seller->id,
                'category_id' => $videoCategory->id,
                'title' => 'Social Media Video Templates',
                'description' => 'Ready-to-use video templates for Instagram, TikTok, and YouTube.',
                'short_description' => 'Social media ready',
                'product_type' => 'video',
                'price' => 34.99,
                'license_type' => 'Commercial',
                'thumbnail_path' => 'products/thumbnails/social-templates.jpg',
                'file_path' => 'products/video/social-templates.zip',
                'file_size' => 180000000,
                'is_approved' => true,
                'is_active' => true,
                'downloads_count' => 156,
            ],
        ];

        foreach ($products as $productData) {
            Product::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($productData['title'])],
                $productData
            );
        }

        $this->command->info('Homepage test data seeded successfully!');
    }
}
