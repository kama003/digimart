<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Audio',
                'slug' => 'audio',
                'description' => 'High-quality audio files, music tracks, sound effects, and audio templates',
                'icon' => '🎵',
                'order' => 10,
            ],
            [
                'name' => 'Video',
                'slug' => 'video',
                'description' => 'Professional video footage, motion graphics, and video templates',
                'icon' => '🎬',
                'order' => 20,
            ],
            [
                'name' => '3D Models',
                'slug' => '3d-models',
                'description' => '3D models, textures, and assets for games and visualization',
                'icon' => '🎮',
                'order' => 30,
            ],
            [
                'name' => 'Templates',
                'slug' => 'templates',
                'description' => 'Website templates, design templates, and document templates',
                'icon' => '📐',
                'order' => 40,
            ],
            [
                'name' => 'Graphics',
                'slug' => 'graphics',
                'description' => 'Vector graphics, illustrations, icons, and design elements',
                'icon' => '🎨',
                'order' => 50,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        $this->command->info('Categories seeded successfully!');
    }
}
