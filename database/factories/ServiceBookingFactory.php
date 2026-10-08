<?php

namespace Database\Factories;

use App\Models\ServiceBooking;
use App\Models\ServicePackage;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<ServiceBooking> */
class ServiceBookingFactory extends Factory
{
    protected $model = ServiceBooking::class;

    public function definition(): array
    {
        return [
            'customer_name' => fake()->name(),
            'phone' => '08'.fake()->numerify('##########'),
            'whatsapp' => '08'.fake()->numerify('##########'),
            'vehicle_id' => Vehicle::factory(),
            'vehicle_plate' => strtoupper(fake()->bothify('B #### ???')),
            'service_package_id' => ServicePackage::factory(),
            'service_type' => fake()->randomElement(['maintenance', 'repair', 'inspection']),
            'preferred_date' => fake()->dateTimeBetween('now', '+10 days')->format('Y-m-d'),
            'preferred_time' => fake()->randomElement(['09:00', '10:30', '13:00', '15:30']),
            'complaint' => fake()->sentence(),
            'notes' => fake()->optional()->sentence(),
            'status' => fake()->randomElement(['pending', 'approved', 'rescheduled', 'completed', 'cancelled']),
            'admin_notes' => fake()->optional()->sentence(),
        ];
    }
}
