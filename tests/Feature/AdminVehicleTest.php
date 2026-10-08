<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminVehicleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_open_vehicles(): void
    {
        $this->get(route('admin.vehicles.index'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_non_admin_receives_forbidden_response_for_vehicles(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'user']))
            ->get(route('admin.vehicles.index'))
            ->assertForbidden();
    }

    public function test_non_admin_cannot_open_vehicle_edit_page(): void
    {
        $category = Category::factory()->create();
        $vehicle = Vehicle::factory()->for($category)->create();

        $this->actingAs(User::factory()->create(['role' => 'user']))
            ->get(route('admin.vehicles.edit', $vehicle))
            ->assertForbidden();
    }

    public function test_admin_can_open_vehicle_pages(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.vehicles.index'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('admin.vehicles.create'))
            ->assertOk();
    }

    public function test_vehicle_edit_link_uses_same_origin_and_opens_the_matching_vehicle(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create(['name' => 'SUV']);
        $vehicle = Vehicle::factory()->for($category)->create([
            'brand' => 'Toyota',
            'name' => 'Innova Reborn',
            'is_featured' => true,
        ]);
        $image = VehicleImage::factory()->for($vehicle)->create([
            'image_path' => 'vehicles/innova-reborn.jpg',
            'alt_text' => 'Foto Innova Reborn',
            'is_primary' => true,
        ]);
        Storage::disk('public')->put($image->image_path, 'image bytes');
        $editUrl = '/admin/vehicles/'.$vehicle->id.'/edit';
        $updateAction = '/admin/vehicles/'.$vehicle->id;

        $indexResponse = $this->actingAs($admin)
            ->get(route('admin.vehicles.index'));

        $indexResponse->assertOk()
            ->assertSee('href="'.$editUrl.'"', false);

        $this->get($editUrl)
            ->assertOk()
            ->assertSee('Edit kendaraan')
            ->assertSee('value="Innova Reborn"', false)
            ->assertSee('value="Toyota"', false)
            ->assertSee('value="'.$category->id.'" selected', false)
            ->assertSee('src="/storage/'.$image->image_path.'"', false)
            ->assertSee('alt="Foto Innova Reborn"', false)
            ->assertSee('action="'.$updateAction.'"', false)
            ->assertSee('name="_method" value="PUT"', false)
            ->assertSee('name="_token"', false)
            ->assertSee('<button type="submit"', false)
            ->assertSee('Perbarui kendaraan');

        $this->put(route('admin.vehicles.update', $vehicle), [
            'category_id' => $category->id,
            'brand' => 'Toyota',
            'name' => 'Innova Reborn Updated',
            'type' => 'SUV',
            'slug' => 'innova-reborn-updated',
            'year' => 2026,
            'price' => 400000000,
            'status' => 'available',
        ])
            ->assertRedirect(route('admin.vehicles.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('vehicles', [
            'id' => $vehicle->id,
            'name' => 'Innova Reborn Updated',
            'category_id' => $category->id,
            'is_featured' => false,
        ]);
        $this->assertDatabaseHas('vehicle_images', [
            'id' => $image->id,
            'vehicle_id' => $vehicle->id,
            'image_path' => $image->image_path,
        ]);
        Storage::disk('public')->assertExists($image->image_path);
    }

    public function test_invalid_vehicle_update_returns_validation_errors_and_keeps_existing_data(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();
        $vehicle = Vehicle::factory()->for($category)->create([
            'name' => 'Fortuner',
            'status' => 'inactive',
        ]);

        $this->actingAs($admin)
            ->from(route('admin.vehicles.edit', $vehicle))
            ->put(route('admin.vehicles.update', $vehicle), [
                'category_id' => $category->id,
                'brand' => 'Toyota',
                'name' => '',
                'type' => 'SUV',
                'slug' => 'fortuner',
                'year' => 2025,
                'price' => 500000000,
                'status' => 'inactive',
            ])
            ->assertRedirect(route('admin.vehicles.edit', $vehicle))
            ->assertSessionHasErrors('name');

        $this->assertDatabaseHas('vehicles', [
            'id' => $vehicle->id,
            'name' => 'Fortuner',
        ]);
    }

    public function test_admin_can_create_vehicle(): void
    {
        $category = Category::factory()->create(['is_active' => true]);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.vehicles.store'), [
                'category_id' => $category->id,
                'brand' => 'Toyota',
                'name' => 'Avanza 2024',
                'type' => 'MPV',
                'slug' => 'avanza-2024',
                'year' => 2024,
                'price' => 250000000,
                'color' => 'Silver',
                'transmission' => 'Automatic',
                'engine_capacity' => 1500,
                'mileage' => 12000,
                'status' => 'available',
                'description' => 'Kendaraan keluarga baru.',
                'is_featured' => true,
            ])
            ->assertRedirect(route('admin.vehicles.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('vehicles', [
            'brand' => 'Toyota',
            'slug' => 'avanza-2024',
            'status' => 'available',
            'is_featured' => true,
        ]);
    }

    public function test_vehicle_validation_requires_valid_category_and_fields(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->from(route('admin.vehicles.create'))
            ->post(route('admin.vehicles.store'), [
                'category_id' => 999,
                'brand' => '',
                'name' => '',
                'type' => '',
                'year' => 1899,
                'price' => -1,
                'status' => 'unknown',
            ])
            ->assertRedirect(route('admin.vehicles.create'))
            ->assertSessionHasErrors(['category_id', 'brand', 'name', 'type', 'year', 'price', 'status']);

        Category::factory()->create(['is_active' => false]);

        $this->actingAs($admin)
            ->from(route('admin.vehicles.create'))
            ->post(route('admin.vehicles.store'), [
                'category_id' => Category::latest('id')->first()->id,
                'brand' => 'Honda',
                'name' => 'Brio',
                'type' => 'Hatchback',
                'year' => 2024,
                'price' => 200000000,
                'status' => 'available',
            ])
            ->assertSessionHasErrors('category_id');
    }

    public function test_slug_must_be_unique_and_cannot_be_reused_when_soft_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();

        Vehicle::factory()->for($category)->create(['slug' => 'xenia-2024']);

        $this->actingAs($admin)
            ->from(route('admin.vehicles.create'))
            ->post(route('admin.vehicles.store'), [
                'category_id' => $category->id,
                'brand' => 'Daihatsu',
                'name' => 'Xenia',
                'type' => 'MPV',
                'slug' => 'xenia-2024',
                'year' => 2024,
                'price' => 200000000,
                'status' => 'available',
            ])
            ->assertSessionHasErrors('slug');

        $deletedVehicle = Vehicle::factory()->for($category)->create(['slug' => 'soft-deleted-vehicle']);
        $deletedVehicle->delete();

        $this->actingAs($admin)
            ->from(route('admin.vehicles.create'))
            ->post(route('admin.vehicles.store'), [
                'category_id' => $category->id,
                'brand' => 'Mitsubishi',
                'name' => 'Xpander',
                'type' => 'SUV',
                'slug' => 'soft-deleted-vehicle',
                'year' => 2025,
                'price' => 300000000,
                'status' => 'available',
            ])
            ->assertSessionHasErrors('slug');
    }

    public function test_admin_can_update_vehicle_and_soft_delete_vehicle(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();
        $vehicle = Vehicle::factory()->for($category)->create([
            'brand' => 'Toyota',
            'name' => 'Fortuner',
            'status' => 'available',
            'is_featured' => false,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.vehicles.update', $vehicle), [
                'category_id' => $category->id,
                'brand' => 'Toyota',
                'name' => 'Fortuner Baru',
                'type' => 'SUV',
                'slug' => 'fortuner-baru',
                'year' => 2025,
                'price' => 500000000,
                'status' => 'reserved',
                'is_featured' => false,
            ])
            ->assertRedirect(route('admin.vehicles.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('vehicles', ['id' => $vehicle->id, 'name' => 'Fortuner Baru']);

        $this->actingAs($admin)
            ->delete(route('admin.vehicles.destroy', $vehicle))
            ->assertRedirect(route('admin.vehicles.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted('vehicles', ['id' => $vehicle->id]);
    }

    public function test_vehicle_index_filters_and_search_work(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $categoryA = Category::factory()->create(['name' => 'SUV', 'is_active' => true]);
        $categoryB = Category::factory()->create(['name' => 'MPV', 'is_active' => true]);

        Vehicle::factory()->for($categoryA)->create([
            'name' => 'CR-V',
            'brand' => 'Honda',
            'type' => 'SUV',
            'status' => 'available',
            'is_featured' => true,
        ]);

        Vehicle::factory()->for($categoryB)->create([
            'name' => 'Avanza',
            'brand' => 'Toyota',
            'type' => 'MPV',
            'status' => 'reserved',
            'is_featured' => false,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.vehicles.index', ['search' => 'cr-v']))
            ->assertOk()
            ->assertSee('CR-V')
            ->assertDontSee('Avanza');

        $this->actingAs($admin)
            ->get(route('admin.vehicles.index', ['category_id' => $categoryB->id]))
            ->assertOk()
            ->assertSee('Avanza')
            ->assertDontSee('CR-V');

        $this->actingAs($admin)
            ->get(route('admin.vehicles.index', ['status' => 'available']))
            ->assertOk()
            ->assertSee('CR-V')
            ->assertDontSee('Avanza');

        $this->actingAs($admin)
            ->get(route('admin.vehicles.index', ['featured' => '1']))
            ->assertOk()
            ->assertSee('CR-V');
    }

    public function test_admin_can_upload_and_delete_vehicle_image(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();
        $vehicle = Vehicle::factory()->for($category)->create(['status' => 'available']);

        $response = $this->actingAs($admin)
            ->post(route('admin.vehicles.images.store', $vehicle), [
                'image' => UploadedFile::fake()->image('vehicle-front.jpg', 1200, 800),
                'alt_text' => 'Depan kendaraan',
                'sort_order' => 0,
                'is_primary' => true,
            ]);

        $response->assertRedirect(route('admin.vehicles.edit', $vehicle));

        $this->assertDatabaseHas('vehicle_images', [
            'vehicle_id' => $vehicle->id,
            'alt_text' => 'Depan kendaraan',
            'is_primary' => true,
        ]);

        $image = $vehicle->fresh()->primaryImage;
        $this->assertNotNull($image);
        Storage::disk('public')->assertExists($image->image_path);

        $newPrimary = VehicleImage::factory()->for($vehicle)->create([
            'image_path' => 'vehicles/old-primary.jpg',
            'alt_text' => 'Dinonaktifkan',
            'sort_order' => 1,
            'is_primary' => false,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.vehicles.images.update', [$vehicle, $newPrimary]), [
                'alt_text' => 'Primary baru',
                'sort_order' => 1,
                'is_primary' => true,
            ])
            ->assertRedirect(route('admin.vehicles.edit', $vehicle));

        $this->assertDatabaseHas('vehicle_images', ['id' => $newPrimary->id, 'is_primary' => true]);
        $this->assertDatabaseHas('vehicle_images', ['id' => $image->id, 'is_primary' => false]);

        $this->actingAs($admin)
            ->delete(route('admin.vehicles.images.destroy', [$vehicle, $image]))
            ->assertRedirect(route('admin.vehicles.edit', $vehicle));

        $this->assertDatabaseMissing('vehicle_images', ['id' => $image->id]);
        Storage::disk('public')->assertMissing($image->image_path);
    }

    public function test_upload_routes_require_admin_auth(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);
        $category = Category::factory()->create();
        $vehicle = Vehicle::factory()->for($category)->create();

        $this->post(route('admin.vehicles.images.store', $vehicle), [])->assertRedirect(route('admin.login'));
        $this->actingAs($user)
            ->post(route('admin.vehicles.images.store', $vehicle), [])
            ->assertForbidden();

        $this->actingAs($admin)
            ->delete(route('admin.vehicles.images.destroy', [$vehicle, VehicleImage::factory()->for($vehicle)->create()]))
            ->assertRedirect(route('admin.vehicles.edit', $vehicle));
    }

    public function test_vehicle_soft_delete_keeps_images_and_relationships_are_present(): void
    {
        $category = Category::factory()->create();
        $vehicle = Vehicle::factory()->for($category)->create();
        $image = VehicleImage::factory()->for($vehicle)->create([
            'is_primary' => true,
            'sort_order' => 0,
        ]);

        $this->assertEquals($category->id, $vehicle->category->id);
        $this->assertTrue($vehicle->vehicleImages()->exists());
        $this->assertNotNull($vehicle->primaryImage);

        $vehicle->delete();

        $this->assertDatabaseHas('vehicle_images', ['id' => $image->id, 'vehicle_id' => $vehicle->id]);
        $this->assertSoftDeleted('vehicles', ['id' => $vehicle->id]);
    }
}
