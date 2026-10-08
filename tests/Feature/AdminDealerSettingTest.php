<?php

namespace Tests\Feature;

use App\Models\DealerSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminDealerSettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_blocked_from_dealer_settings(): void
    {
        $this->get(route('admin.dealer-settings.index'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_non_admin_receives_forbidden_response_for_dealer_settings(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'user']))
            ->get(route('admin.dealer-settings.index'))
            ->assertForbidden();
    }

    public function test_admin_can_open_dealer_settings_form_and_singleton_exists(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.dealer-settings.index'))
            ->assertOk();

        $setting = DealerSetting::query()->firstOrFail();

        $this->assertSame(1, $setting->id);
        $this->assertDatabaseHas('dealer_settings', ['id' => 1]);
    }

    public function test_admin_can_update_basic_identity_fields(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        DealerSetting::query()->updateOrCreate(['id' => 1], [
            'dealer_name' => 'Dealer Development',
            'address' => 'Jl. Raya Bandung',
            'phone' => '620000000000',
            'email' => 'dealer@example.test',
            'whatsapp' => '620000000000',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.dealer-settings.update'), [
                'dealer_name' => 'Auto Prime Center',
                'tagline' => 'Mobil terbaik untuk setiap kebutuhan.',
                'address' => 'Jl. Sudirman No. 12',
                'phone' => '6281234567890',
                'email' => 'hello@autoprime.test',
                'whatsapp' => '081234567890',
                'opening_hours' => [
                    ['day' => 'Senin', 'open' => '08:00', 'close' => '17:00', 'closed' => false],
                    ['day' => 'Minggu', 'open' => null, 'close' => null, 'closed' => true],
                ],
                'services' => ['Penjualan Baru', 'Test Drive'],
                'social_links' => [
                    'instagram' => 'https://instagram.com/autoprime',
                    'facebook' => 'https://facebook.com/autoprime',
                    'youtube' => 'https://youtube.com/@autoprime',
                ],
            ])
            ->assertRedirect(route('admin.dealer-settings.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('dealer_settings', [
            'id' => 1,
            'dealer_name' => 'Auto Prime Center',
            'tagline' => 'Mobil terbaik untuk setiap kebutuhan.',
            'address' => 'Jl. Sudirman No. 12',
            'phone' => '6281234567890',
            'email' => 'hello@autoprime.test',
            'whatsapp' => '6281234567890',
        ]);
    }

    public function test_email_phone_and_whatsapp_validation_rules_apply(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->from(route('admin.dealer-settings.index'))
            ->put(route('admin.dealer-settings.update'), [
                'dealer_name' => 'Bad Dealer',
                'address' => 'Jl. Test',
                'phone' => 'abc',
                'email' => 'invalid-email',
                'whatsapp' => '123',
                'map_url' => 'not-a-url',
                'latitude' => 91,
                'longitude' => -181,
            ])
            ->assertRedirect(route('admin.dealer-settings.index'))
            ->assertSessionHasErrors(['phone', 'email', 'whatsapp', 'map_url', 'latitude', 'longitude']);
    }

    public function test_opening_hours_services_and_social_links_are_stored_as_json_arrays(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        DealerSetting::query()->updateOrCreate(['id' => 1], [
            'dealer_name' => 'Dealer Development',
            'address' => 'Jl. Raya Bandung',
            'phone' => '620000000000',
            'email' => 'dealer@example.test',
            'whatsapp' => '620000000000',
        ]);

        $openingHours = [
            ['day' => 'Senin', 'open' => '08:00', 'close' => '17:00', 'closed' => false],
            ['day' => 'Sabtu', 'open' => '09:00', 'close' => '15:00', 'closed' => false],
        ];
        $services = ['Penjualan Baru', 'Trade In', 'Service'];
        $socialLinks = ['instagram' => 'https://instagram.com/dealer', 'facebook' => 'https://facebook.com/dealer'];

        $this->actingAs($admin)
            ->put(route('admin.dealer-settings.update'), [
                'dealer_name' => 'Dealer Development',
                'address' => 'Jl. Raya Bandung',
                'phone' => '620000000000',
                'email' => 'dealer@example.test',
                'whatsapp' => '081234567890',
                'opening_hours' => $openingHours,
                'services' => $services,
                'social_links' => $socialLinks,
            ])
            ->assertRedirect(route('admin.dealer-settings.index'))
            ->assertSessionHas('success');

        $setting = DealerSetting::query()->firstOrFail();

        $this->assertSame($openingHours, $setting->opening_hours);
        $this->assertSame($services, $setting->services);
        $this->assertSame($socialLinks, $setting->social_links);
    }

    public function test_logo_and_hero_image_can_be_uploaded_and_replaced(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'admin']);
        DealerSetting::query()->updateOrCreate(['id' => 1], [
            'dealer_name' => 'Dealer Development',
            'address' => 'Jl. Raya Bandung',
            'phone' => '620000000000',
            'email' => 'dealer@example.test',
            'whatsapp' => '620000000000',
            'logo_path' => 'dealer/logo/old-logo.png',
            'hero_image_path' => 'dealer/hero/old-hero.png',
        ]);

        Storage::disk('public')->put('dealer/logo/old-logo.png', 'old-logo');
        Storage::disk('public')->put('dealer/hero/old-hero.png', 'old-hero');

        $this->actingAs($admin)
            ->put(route('admin.dealer-settings.update'), [
                'dealer_name' => 'Dealer Development',
                'address' => 'Jl. Raya Bandung',
                'phone' => '620000000000',
                'email' => 'dealer@example.test',
                'whatsapp' => '081234567890',
                'logo' => UploadedFile::fake()->image('logo.png', 1200, 800),
                'hero_image' => UploadedFile::fake()->image('hero.png', 1400, 900),
            ])
            ->assertRedirect(route('admin.dealer-settings.index'))
            ->assertSessionHas('success');

        $setting = DealerSetting::query()->firstOrFail();
        $this->assertNotNull($setting->logo_path);
        $this->assertNotNull($setting->hero_image_path);
        $this->assertStringStartsWith('dealer/logo/', $setting->logo_path);
        $this->assertStringStartsWith('dealer/hero/', $setting->hero_image_path);
        Storage::disk('public')->assertExists($setting->logo_path);
        Storage::disk('public')->assertExists($setting->hero_image_path);
        Storage::disk('public')->assertMissing('dealer/logo/old-logo.png');
        Storage::disk('public')->assertMissing('dealer/hero/old-hero.png');
    }

    public function test_logo_and_hero_image_can_be_deleted(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'admin']);
        DealerSetting::query()->updateOrCreate(['id' => 1], [
            'dealer_name' => 'Dealer Development',
            'address' => 'Jl. Raya Bandung',
            'phone' => '620000000000',
            'email' => 'dealer@example.test',
            'whatsapp' => '620000000000',
            'logo_path' => 'dealer/logo/current-logo.png',
            'hero_image_path' => 'dealer/hero/current-hero.png',
        ]);

        Storage::disk('public')->put('dealer/logo/current-logo.png', 'logo');
        Storage::disk('public')->put('dealer/hero/current-hero.png', 'hero');

        $this->actingAs($admin)
            ->put(route('admin.dealer-settings.update'), [
                'dealer_name' => 'Dealer Development',
                'address' => 'Jl. Raya Bandung',
                'phone' => '620000000000',
                'email' => 'dealer@example.test',
                'whatsapp' => '081234567890',
                'remove_logo' => true,
                'remove_hero_image' => true,
            ])
            ->assertRedirect(route('admin.dealer-settings.index'))
            ->assertSessionHas('success');

        $setting = DealerSetting::query()->firstOrFail();
        $this->assertNull($setting->logo_path);
        $this->assertNull($setting->hero_image_path);
        Storage::disk('public')->assertMissing('dealer/logo/current-logo.png');
        Storage::disk('public')->assertMissing('dealer/hero/current-hero.png');
    }

    public function test_stale_image_paths_do_not_render_preview_urls(): void
    {
        Storage::fake('public');

        $setting = DealerSetting::query()->create([
            'id' => 1,
            'dealer_name' => 'Dealer Development',
            'address' => 'Jl. Raya Bandung',
            'phone' => '620000000000',
            'email' => 'dealer@example.test',
            'whatsapp' => '620000000000',
            'logo_path' => 'dealer/logo/missing-logo.png',
            'hero_image_path' => 'dealer/hero/missing-hero.png',
        ]);

        $this->assertNull($setting->logo_url);
        $this->assertNull($setting->hero_image_url);

        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.dealer-settings.index'))
            ->assertOk()
            ->assertDontSee('alt="Hero dealer"', false);
    }

    public function test_admin_preview_uses_public_storage_url_for_existing_hero_image(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('dealer/hero/valid-hero.png', 'image bytes');

        DealerSetting::query()->create([
            'id' => 1,
            'dealer_name' => 'Dealer Development',
            'address' => 'Jl. Raya Bandung',
            'phone' => '620000000000',
            'email' => 'dealer@example.test',
            'whatsapp' => '620000000000',
            'hero_image_path' => 'dealer/hero/valid-hero.png',
        ]);

        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.dealer-settings.index'))
            ->assertOk()
            ->assertSee('src="/storage/dealer/hero/valid-hero.png"', false)
            ->assertSee('alt="Hero dealer"', false);
    }

    public function test_dealer_settings_routes_exist_for_edit_and_update_but_not_delete(): void
    {
        $this->assertTrue(Route::has('admin.dealer-settings.index'));
        $this->assertTrue(Route::has('admin.dealer-settings.update'));
        $this->assertFalse(Route::has('admin.dealer-settings.destroy'));

        $this->assertDatabaseMissing('dealer_settings', ['id' => 2]);
    }
}
