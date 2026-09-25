<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Vehicle> */
class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    public function definition(): array
    {
        $brand = fake()->randomElement(['Toyota', 'Honda', 'Mitsubishi']);
        $name = fake()->bothify($brand.' Model ##');

        return [
            'category_id' => Category::factory(),
            'brand' => $brand,
            'name' => $name,
            'type' => fake()->randomElement(['SUV', 'MPV', 'Sedan']),
            'slug' => Str::slug($name.' '.fake()->unique()->numerify('###')),
            'year' => fake()->numberBetween(2018, (int) now()->addYear()->format('Y')),
            'price' => fake()->numberBetween(150_000_000, 900_000_000),
            'color' => fake()->colorName(),
            'transmission' => fake()->randomElement(['manual', 'automatic']),
            'engine_capacity' => fake()->randomElement([1000, 1500, 2000, 2500]),
            'mileage' => fake()->numberBetween(0, 150_000),
            'status' => 'available',
            'description' => fake()->paragraph(),
            'is_featured' => false,
        ];
    }
}
