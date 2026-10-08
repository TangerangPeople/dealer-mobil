<?php

namespace Database\Factories;

use App\Models\PurchaseApplication;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<PurchaseApplication>
 */
class PurchaseApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'application_number' => 'PA-'.now()->format('Ymd').'-'.Str::upper(Str::random(8)),
            'user_id' => User::factory(),
            'vehicle_id' => Vehicle::factory(),
            'vehicle_brand' => 'Toyota',
            'vehicle_name' => 'Model Kendaraan',
            'vehicle_type' => 'MPV',
            'vehicle_year' => 2024,
            'vehicle_price' => 350000000,
            'customer_name' => fake()->name(),
            'customer_email' => fake()->safeEmail(),
            'customer_phone' => '6281234567890',
            'customer_whatsapp' => '6281234567890',
            'customer_address' => fake()->address(),
            'purchase_method' => 'consultation',
            'financing_preference' => null,
            'customer_note' => null,
            'status' => 'pending',
        ];
    }
}
