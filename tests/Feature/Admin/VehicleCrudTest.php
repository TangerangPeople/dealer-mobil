<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->user = User::factory()->create(['role' => 'user']);
    }

    public function test_guests_cannot_access_vehicles_index()
    {
        $response = $this->get(route('admin.vehicles.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_non_admin_users_cannot_access_vehicles_index()
    {
        $response = $this->actingAs($this->user)->get(route('admin.vehicles.index'));
        $response->assertStatus(403);
    }

    public function test_admins_can_access_vehicles_index()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.vehicles.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_vehicle()
    {
        $category = Category::factory()->create();

        $vehicleData = [
            'category_id' => $category->id,
            'name' => 'Toyota Avanza 2024',
            'brand' => 'Toyota',
            'type' => 'Avanza',
            'year' => 2024,
            'price' => 250000000,
            'status' => 'available',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.vehicles.store'), $vehicleData);

        $response->assertRedirect(route('admin.vehicles.index'));
        $this->assertDatabaseHas('vehicles', [
            'name' => 'Toyota Avanza 2024',
            'price' => 250000000,
        ]);
    }
}
