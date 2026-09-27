<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\DealerSetting;
use App\Models\Gallery;
use App\Models\Promotion;
use App\Models\Sale;
use App\Models\Vehicle;
use App\Models\VehicleImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicWebsiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        DealerSetting::query()->updateOrCreate(['id' => 1], [
            'dealer_name' => 'Auto Prime Center',
            'tagline' => 'Mobil terbaik untuk setiap kebutuhan.',
            'home_intro' => 'Dealer mobil pilihan Anda.',
            'hero_title' => 'Temukan mobil impian Anda',
            'hero_description' => 'Kendaraan berkualitas dengan layanan terbaik.',
            'hero_image_path' => 'hero/hero.jpg',
            'history' => 'Berdiri sejak 2015.',
            'about_content' => 'Kami hadir untuk memberikan solusi mobil terbaik.',
            'address' => 'Jl. Sudirman No. 12, Bandung',
            'phone' => '6281234567890',
            'email' => 'hello@autoprime.test',
            'whatsapp' => '6281234567890',
            'opening_hours' => [
                ['day' => 'Senin', 'open' => '08:00', 'close' => '17:00', 'closed' => false],
                ['day' => 'Minggu', 'open' => null, 'close' => null, 'closed' => true],
            ],
            'services' => ['Sales', 'Kredit', 'Servis'],
            'map_url' => 'https://maps.google.com/?q=Bandung',
            'latitude' => -6.9147,
            'longitude' => 107.6098,
            'social_links' => ['instagram' => 'https://instagram.com/autoprime'],
        ]);

        $category = Category::factory()->create(['name' => 'SUV', 'slug' => 'suv']);

        $vehicle = Vehicle::factory()->create([
            'category_id' => $category->id,
            'brand' => 'Toyota',
            'name' => 'Fortuner',
            'type' => 'SUV',
            'slug' => 'fortuner',
            'year' => 2024,
            'price' => 650000000,
            'transmission' => 'automatic',
            'status' => 'available',
            'is_featured' => true,
        ]);

        VehicleImage::factory()->create([
            'vehicle_id' => $vehicle->id,
            'image_path' => 'vehicles/fortuner.jpg',
            'alt_text' => 'Toyota Fortuner',
            'is_primary' => true,
        ]);

        Promotion::factory()->create([
            'title' => 'Promo Akhir Tahun',
            'slug' => 'promo-akhir-tahun',
            'summary' => 'Diskon spesial akhir tahun',
            'description' => 'Nikmati harga terbaik dan cicilan ringan.',
            'image_path' => 'promotions/promo.jpg',
            'image_alt_text' => 'Promo akhir tahun',
            'vehicle_id' => $vehicle->id,
            'is_active' => true,
            'start_date' => now()->subDay(),
            'end_date' => now()->addWeek(),
        ]);

        Gallery::factory()->create([
            'title' => 'Showroom Auto Prime',
            'gallery_type' => 'showroom',
            'image_path' => 'galleries/showroom.jpg',
            'alt_text' => 'Showroom Auto Prime',
            'caption' => 'Interior showroom kami.',
            'is_published' => true,
        ]);

        Sale::factory()->create([
            'name' => 'Rina Sales',
            'phone' => '6282222222222',
            'whatsapp' => '6282222222222',
            'is_active' => true,
        ]);
    }

    public function test_homepage_is_accessible(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Auto Prime Center')
            ->assertSee('Fortuner');
    }

    public function test_public_pages_still_work_when_dealer_settings_are_missing(): void
    {
        DealerSetting::query()->delete();

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Dealer Mobil');

        $this->get(route('about'))
            ->assertOk()
            ->assertSee('Tentang Dealer');

        $this->get(route('contact'))
            ->assertOk()
            ->assertSee('Hubungi kami');
    }

    public function test_about_page_is_accessible(): void
    {
        $this->get(route('about'))
            ->assertOk()
            ->assertSee('Tentang Dealer');
    }

    public function test_vehicles_index_is_accessible(): void
    {
        $this->get(route('vehicles.index'))
            ->assertOk()
            ->assertSee('Katalog Mobil');
    }

    public function test_vehicle_detail_page_is_accessible(): void
    {
        $this->get(route('vehicles.show', ['vehicle' => 'fortuner']))
            ->assertOk()
            ->assertSee('Toyota Fortuner');
    }

    public function test_missing_vehicle_returns_not_found(): void
    {
        $this->get(route('vehicles.show', ['vehicle' => 'tidak-ada']))
            ->assertNotFound();
    }

    public function test_promotions_page_is_accessible(): void
    {
        $this->get(route('promotions.index'))
            ->assertOk()
            ->assertSee('Promo');
    }

    public function test_promotion_detail_is_accessible(): void
    {
        $this->get(route('promotions.show', ['promotion' => 'promo-akhir-tahun']))
            ->assertOk()
            ->assertSee('Promo Akhir Tahun');
    }

    public function test_missing_promotion_returns_not_found(): void
    {
        $this->get(route('promotions.show', ['promotion' => 'promo-tidak-ada']))
            ->assertNotFound();
    }

    public function test_gallery_page_is_accessible(): void
    {
        $this->get(route('gallery.index'))
            ->assertOk()
            ->assertSee('Galeri');
    }

    public function test_contact_page_is_accessible(): void
    {
        $this->get(route('contact'))
            ->assertOk()
            ->assertSee('Kontak');
    }

    public function test_credit_simulation_page_is_accessible(): void
    {
        $this->get(route('credit-simulation'))
            ->assertOk()
            ->assertSee('Simulasi Kredit');
    }

    public function test_inactive_or_soft_deleted_records_do_not_show_on_public_pages(): void
    {
        $inactivePromotion = Promotion::factory()->create([
            'title' => 'Promo Tidak Aktif',
            'slug' => 'promo-tidak-aktif',
            'is_active' => false,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);

        $inactivePromotion->delete();

        $this->get(route('promotions.index'))
            ->assertOk()
            ->assertDontSee('Promo Tidak Aktif');

        $this->assertSoftDeleted($inactivePromotion);
    }
}
