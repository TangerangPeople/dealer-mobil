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
use Illuminate\Support\Facades\Storage;
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

    public function test_public_header_and_footer_render_the_configured_dealer_logo(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('dealer/logo/dealer-mark.png', 'image bytes');
        DealerSetting::query()->whereKey(1)->update(['logo_path' => 'dealer/logo/dealer-mark.png']);

        $response = $this->get(route('home'));
        $logoMarkup = 'src="/storage/dealer/logo/dealer-mark.png"';

        $response->assertOk()
            ->assertSee($logoMarkup, false)
            ->assertSee('alt="Auto Prime Center"', false);

        $this->assertSame(2, substr_count($response->getContent(), $logoMarkup));
    }

    public function test_public_header_and_footer_fall_back_to_dealer_name_when_logo_is_missing(): void
    {
        DealerSetting::query()->whereKey(1)->update(['logo_path' => null]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Auto Prime Center')
            ->assertDontSee('alt="Auto Prime Center"', false)
            ->assertDontSee('src="/storage/dealer/logo/', false);
    }

    public function test_social_links_from_dealer_settings_are_rendered_in_the_public_footer(): void
    {
        $socialLinks = [
            'instagram' => 'https://instagram.com/autoprime',
            'facebook' => 'https://facebook.com/autoprime',
            'tiktok' => 'https://tiktok.com/@autoprime',
            'youtube' => 'https://youtube.com/@autoprime',
        ];
        DealerSetting::query()->whereKey(1)->update(['social_links' => $socialLinks]);

        $this->get(route('vehicles.index'))
            ->assertOk()
            ->assertSee('Social')
            ->assertSee('href="https://instagram.com/autoprime"', false)
            ->assertSee('href="https://facebook.com/autoprime"', false)
            ->assertSee('href="https://tiktok.com/@autoprime"', false)
            ->assertSee('href="https://youtube.com/@autoprime"', false)
            ->assertSee('target="_blank" rel="noopener noreferrer"', false)
            ->assertSee('Instagram')
            ->assertSee('Facebook')
            ->assertSee('TikTok')
            ->assertSee('YouTube');
    }

    public function test_empty_social_links_are_omitted_from_the_footer(): void
    {
        DealerSetting::query()->whereKey(1)->update([
            'social_links' => [
                'instagram' => '',
                'facebook' => null,
                'youtube' => ' ',
            ],
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('Social')
            ->assertDontSee('Instagram')
            ->assertDontSee('Facebook')
            ->assertDontSee('YouTube');
    }

    public function test_homepage_uses_the_configured_dealer_hero_image(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('hero/hero.jpg', 'image bytes');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('src="/storage/hero/hero.jpg"', false)
            ->assertSee('alt=""', false)
            ->assertSee('Temukan mobil impian Anda')
            ->assertSee('Kendaraan berkualitas dengan layanan terbaik.')
            ->assertSee('Lihat Katalog')
            ->assertSee('Booking Service')
            ->assertDontSee('Pilihan utama')
            ->assertSee('Rp 650.000.000');
    }

    public function test_homepage_uses_neutral_fallback_when_hero_image_is_missing(): void
    {
        Storage::fake('public');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Foto hero belum tersedia')
            ->assertDontSee('src="/storage/hero/hero.jpg"', false)
            ->assertSee('Temukan mobil impian Anda')
            ->assertSee('Lihat Katalog')
            ->assertSee('Booking Service')
            ->assertDontSee('Pilihan utama');
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
            ->assertSee('Toyota Fortuner')
            ->assertSee('Ajukan Pembelian')
            ->assertSee(route('purchase.create', ['vehicle' => 'fortuner']));
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
            ->assertSee('Kontak')
            ->assertSee('Chat WhatsApp Dealer')
            ->assertSee('https://wa.me/6281234567890', false)
            ->assertDontSee('https://wa.me/6282222222222', false);
    }

    public function test_credit_simulation_page_is_accessible(): void
    {
        $this->get(route('credit-simulation'))
            ->assertOk()
            ->assertSee('Simulasi Kredit')
            ->assertSee('Toyota Fortuner')
            ->assertSee('Ajukan Pembelian');
    }

    public function test_credit_simulation_preserves_selected_vehicle_for_purchase_and_sales_links(): void
    {
        $response = $this->get(route('credit-simulation', ['vehicle' => 'fortuner']))
            ->assertOk()
            ->assertSee('Toyota Fortuner')
            ->assertSee(route('purchase.create', ['vehicle' => 'fortuner']));

        $this->assertStringContainsString(route('sales.chat', ['vehicle' => 'fortuner']), $response->getContent());
    }

    public function test_sales_chat_redirects_to_selected_sales_whatsapp_with_vehicle_context(): void
    {
        $sale = Sale::query()->firstOrFail();
        $message = rawurlencode('Halo, saya ingin berkonsultasi mengenai Toyota Fortuner.');

        $this->get(route('sales.chat', ['sale' => $sale->id, 'vehicle' => 'fortuner']))
            ->assertRedirect('https://wa.me/6282222222222?text='.$message);
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
