<?php

namespace Database\Factories;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Sale> */
class SaleFactory extends Factory
{
    protected $model = Sale::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => '62'.fake()->numerify('8##########'),
            'whatsapp' => '62'.fake()->numerify('8##########'),
            'position' => 'Sales Consultant',
            'is_active' => true,
            'sort_order' => 0,
        ];
    }
}
