<?php

namespace Database\Factories;

use App\Models\Promotion;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Promotion> */
class PromotionFactory extends Factory
{
    protected $model = Promotion::class;

    public function definition(): array
    {
        $title = fake()->sentence(3);

        return [
            'vehicle_id' => Vehicle::factory(),
            'title' => $title,
            'slug' => Str::slug($title.' '.fake()->unique()->numerify('###')),
            'summary' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'image_path' => fake()->optional()->passthrough('promotions/'.fake()->uuid().'.jpg'),
            'image_alt_text' => fake()->optional()->sentence(4),
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
            'is_active' => true,
        ];
    }

    public function general(): static
    {
        return $this->state(fn (): array => [
            'vehicle_id' => null,
        ]);
    }
}
