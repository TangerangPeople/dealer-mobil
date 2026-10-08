<?php

namespace Database\Factories;

use App\Models\ServicePackage;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<ServicePackage> */
class ServicePackageFactory extends Factory
{
    protected $model = ServicePackage::class;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Paket Servis Berkala', 'Paket Tune Up', 'Paket Perawatan Premium']),
            'description' => fake()->sentence(12),
            'service_type' => fake()->randomElement(['maintenance', 'repair', 'inspection']),
            'estimated_price' => fake()->numberBetween(250000, 3000000),
            'estimated_duration' => fake()->randomElement(['1 hari', '2 jam', '3 jam', '1 hari kerja']),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(1, 20),
        ];
    }
}
