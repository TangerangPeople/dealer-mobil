<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['name' => 'SUV', 'slug' => 'suv'],
            ['name' => 'MPV', 'slug' => 'mpv'],
            ['name' => 'Sedan', 'slug' => 'sedan'],
        ] as $category) {
            Category::query()->updateOrCreate(
                ['slug' => $category['slug']],
                [...$category, 'is_active' => true]
            );
        }
    }
}
