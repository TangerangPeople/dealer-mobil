<?php

namespace Tests\Feature;

use App\Models\DealerSetting;
use App\Models\ServiceBooking;
use App\Models\ServicePackage;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceBookingFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        DealerSetting::query()->updateOrCreate(['id' => 1], [
            'dealer_name' => 'Auto Prime Center',
            'phone' => '6281234567890',
            'whatsapp' => '6281234567890',
            'address' => 'Jl. Sudirman No. 12, Bandung',
            'email' => 'hello@autoprime.test',
        ]);
    }

    public function test_public_booking_page_is_accessible(): void
    {
        $this->get(route('booking-service'))
            ->assertOk()
            ->assertSee('Booking Service');
    }

    public function test_public_booking_page_prefills_selected_available_vehicle(): void
    {
        $vehicle = Vehicle::factory()->create([
            'brand' => 'Toyota',
            'name' => 'Fortuner',
            'status' => 'available',
        ]);
        $unavailableVehicle = Vehicle::factory()->create([
            'brand' => 'Honda',
            'name' => 'Civic',
            'status' => 'sold',
        ]);
        $package = ServicePackage::factory()->create([
            'name' => 'Servis Berkala',
            'is_active' => true,
        ]);

        $this->get(route('booking-service', ['vehicle_id' => $vehicle->id, 'service_package_id' => $package->id]))
            ->assertOk()
            ->assertSee('Toyota Fortuner')
            ->assertSee('value="'.$vehicle->id.'" selected', false)
            ->assertDontSee('Honda Civic')
            ->assertSee('Servis Berkala');
    }

    public function test_public_can_submit_valid_booking(): void
    {
        $vehicle = Vehicle::factory()->create([
            'brand' => 'Toyota',
            'name' => 'Fortuner',
            'status' => 'available',
        ]);

        $package = ServicePackage::factory()->create([
            'name' => 'Servis Berkala',
            'service_type' => 'maintenance',
            'is_active' => true,
        ]);

        $this->post(route('booking-service.store'), [
            'customer_name' => 'Adit Pratama',
            'phone' => '081234567890',
            'whatsapp' => '081234567890',
            'vehicle_id' => $vehicle->id,
            'vehicle_plate' => 'B 1234 XYZ',
            'service_package_id' => $package->id,
            'service_type' => 'maintenance',
            'preferred_date' => now()->addDay()->toDateString(),
            'preferred_time' => '09:00',
            'complaint' => 'Mesin bergetar saat idle.',
            'notes' => 'Mohon cek oli dan filter udara.',
        ])
            ->assertRedirect(route('booking-service'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('service_bookings', [
            'customer_name' => 'Adit Pratama',
            'vehicle_plate' => 'B 1234 XYZ',
            'service_type' => 'maintenance',
            'status' => 'pending',
        ]);
    }

    public function test_public_booking_cannot_set_an_approved_status(): void
    {
        $package = ServicePackage::factory()->create(['is_active' => true]);

        $this->post(route('booking-service.store'), [
            'customer_name' => 'Adit Pratama',
            'phone' => '081234567890',
            'whatsapp' => '081234567890',
            'vehicle_plate' => 'B 1234 XYZ',
            'service_package_id' => $package->id,
            'service_type' => 'maintenance',
            'preferred_date' => now()->addDay()->toDateString(),
            'preferred_time' => '09:00',
            'complaint' => 'Mesin bergetar saat idle.',
            'status' => 'approved',
        ])
            ->assertRedirect(route('booking-service'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('service_bookings', [
            'customer_name' => 'Adit Pratama',
            'status' => 'pending',
        ]);
    }

    public function test_public_booking_rejects_inactive_packages_and_unavailable_vehicles(): void
    {
        $inactivePackage = ServicePackage::factory()->create(['is_active' => false]);
        $unavailableVehicle = Vehicle::factory()->create(['status' => 'sold']);

        $this->from(route('booking-service'))
            ->post(route('booking-service.store'), [
                'customer_name' => 'Adit Pratama',
                'phone' => '081234567890',
                'whatsapp' => '081234567890',
                'vehicle_id' => $unavailableVehicle->id,
                'vehicle_plate' => 'B 1234 XYZ',
                'service_package_id' => $inactivePackage->id,
                'service_type' => 'maintenance',
                'preferred_date' => now()->addDay()->toDateString(),
                'preferred_time' => '09:00',
                'complaint' => 'Mesin bergetar saat idle.',
            ])
            ->assertRedirect(route('booking-service'))
            ->assertSessionHasErrors(['vehicle_id', 'service_package_id']);

        $this->assertDatabaseCount('service_bookings', 0);
    }

    public function test_invalid_public_booking_is_rejected(): void
    {
        $this->from(route('booking-service'))
            ->post(route('booking-service.store'), [
                'customer_name' => '',
                'phone' => '',
                'whatsapp' => '',
                'vehicle_plate' => '',
                'service_type' => 'maintenance',
                'preferred_date' => now()->subDay()->toDateString(),
                'preferred_time' => '',
                'complaint' => '',
            ])
            ->assertRedirect(route('booking-service'))
            ->assertSessionHasErrors(['customer_name', 'phone', 'whatsapp', 'vehicle_plate', 'preferred_date', 'preferred_time', 'complaint']);
    }

    public function test_public_services_page_lists_active_packages(): void
    {
        ServicePackage::factory()->create([
            'name' => 'Paket Servis Ringan',
            'service_type' => 'maintenance',
            'is_active' => true,
        ]);

        ServicePackage::factory()->create([
            'name' => 'Paket Servis Tidak Aktif',
            'service_type' => 'repair',
            'is_active' => false,
        ]);

        $this->get(route('services.index'))
            ->assertOk()
            ->assertSee('Paket Servis Ringan')
            ->assertDontSee('Paket Servis Tidak Aktif');
    }

    public function test_admin_can_manage_service_packages(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.service-packages.index'))
            ->assertOk();

        $this->actingAs($admin)
            ->post(route('admin.service-packages.store'), [
                'name' => 'Paket Service Premium',
                'service_type' => 'maintenance',
                'description' => 'Paket perawatan berkala premium untuk mobil harian.',
                'estimated_price' => 750000,
                'estimated_duration' => '2 jam',
                'is_active' => true,
                'sort_order' => 1,
            ])
            ->assertRedirect(route('admin.service-packages.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('service_packages', [
            'name' => 'Paket Service Premium',
            'service_type' => 'maintenance',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_update_booking_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $booking = ServiceBooking::factory()->create([
            'status' => 'pending',
            'customer_name' => 'Siska',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.service-bookings.update', $booking), [
                'status' => 'approved',
                'admin_notes' => 'Disetujui untuk jadwal pagi.',
            ])
            ->assertRedirect(route('admin.service-bookings.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('service_bookings', [
            'id' => $booking->id,
            'status' => 'approved',
            'admin_notes' => 'Disetujui untuk jadwal pagi.',
        ]);
    }

    public function test_non_admin_is_blocked_from_admin_booking_pages(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('admin.service-bookings.index'))
            ->assertForbidden();
    }
}
