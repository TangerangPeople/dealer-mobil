<?php

namespace Database\Factories;

use App\Models\Gallery;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Gallery> */
class GalleryFactory extends Factory
{
    protected $model = Gallery::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'gallery_type' => fake()->randomElement(['vehicle', 'showroom', 'event', 'other']),
            'image_path' => 'galleries/'.fake()->uuid().'.jpg',
            'alt_text' => fake()->sentence(4),
            'caption' => fake()->optional()->sentence(),
            'is_published' => true,
            'sort_order' => 0,
        ];
    }
}
