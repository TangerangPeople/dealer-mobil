<?php

namespace Tests\Feature;

use App\Models\Promotion;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminPromotionTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_open_promotions(): void
    {
        $this->get(route('admin.promotions.index'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_non_admin_receives_forbidden_response_for_promotions(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'user']))
            ->get(route('admin.promotions.index'))
            ->assertForbidden();
    }

    public function test_admin_can_open_promotion_pages(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.promotions.index'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('admin.promotions.create'))
            ->assertOk();
    }

    public function test_admin_can_create_and_update_promotion(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'admin']);
        $vehicle = Vehicle::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.promotions.store'), [
                'vehicle_id' => $vehicle->id,
                'title' => 'Promo Avanza 2025',
                'slug' => 'promo-avanza-2025',
                'summary' => 'Diskon khusus akhir tahun.',
                'description' => 'Nikmati promo spesial untuk pembelian Avanza.',
                'start_date' => now()->subDay()->toDateString(),
                'end_date' => now()->addDays(14)->toDateString(),
                'is_active' => true,
                'image' => UploadedFile::fake()->image('promo.jpg', 1200, 800),
                'image_alt_text' => 'Promo mobil Avanza',
            ])
            ->assertRedirect(route('admin.promotions.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('promotions', [
            'vehicle_id' => $vehicle->id,
            'title' => 'Promo Avanza 2025',
            'slug' => 'promo-avanza-2025',
            'is_active' => true,
        ]);

        $promotion = Promotion::query()->first();
        $this->assertNotNull($promotion->image_path);
        Storage::disk('public')->assertExists($promotion->image_path);

        $this->actingAs($admin)
            ->put(route('admin.promotions.update', $promotion), [
                'vehicle_id' => $vehicle->id,
                'title' => 'Promo Avanza 2025 Baru',
                'slug' => 'promo-avanza-2025-baru',
                'summary' => 'Diskon spesial untuk pembelian Avanza.',
                'description' => 'Promo baru untuk unit terpilih.',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addMonth()->toDateString(),
                'is_active' => false,
                'image_alt_text' => 'Promo terbaru Avanza',
            ])
            ->assertRedirect(route('admin.promotions.index'))
            ->assertSessionHas('success');

        $promotion->refresh();

        $this->assertSame('Promo Avanza 2025 Baru', $promotion->title);
        $this->assertFalse($promotion->is_active);
        $this->assertSame('promo-avanza-2025-baru', $promotion->slug);
    }

    public function test_promotion_validation_requires_valid_dates_and_vehicle(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->from(route('admin.promotions.create'))
            ->post(route('admin.promotions.store'), [
                'vehicle_id' => 999,
                'title' => '',
                'slug' => '',
                'summary' => '',
                'description' => '',
                'start_date' => now()->addDay()->toDateString(),
                'end_date' => now()->subDay()->toDateString(),
                'is_active' => 'invalid',
            ])
            ->assertRedirect(route('admin.promotions.create'))
            ->assertSessionHasErrors(['vehicle_id', 'title', 'summary', 'description', 'end_date', 'is_active']);
    }

    public function test_admin_can_delete_promotion(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $promotion = Promotion::factory()->create();

        $this->actingAs($admin)
            ->delete(route('admin.promotions.destroy', $promotion))
            ->assertRedirect(route('admin.promotions.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted('promotions', ['id' => $promotion->id]);
    }
}
