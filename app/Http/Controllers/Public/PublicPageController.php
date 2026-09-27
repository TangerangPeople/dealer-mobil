<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\DealerSetting;
use App\Models\Gallery;
use App\Models\Promotion;
use App\Models\Sale;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class PublicPageController extends Controller
{
    protected function dealerSettings(): DealerSetting
    {
        if (! Schema::hasTable('dealer_settings')) {
            return new DealerSetting([
                'dealer_name' => 'Dealer Mobil',
                'tagline' => 'Mobil terbaik untuk kebutuhan Anda.',
                'hero_title' => 'Temukan mobil impian Anda',
                'hero_description' => 'Kendaraan berkualitas dengan layanan terbaik.',
                'history' => 'Dealer kami hadir untuk memberikan solusi mobil yang tepat bagi kebutuhan Anda.',
                'about_content' => 'Kami berkomitmen pada kepuasan pelanggan, transparansi, dan pelayanan terbaik.',
                'address' => 'Alamat belum diatur.',
                'phone' => '-',
                'email' => '-',
                'whatsapp' => '-',
                'services' => ['Sales', 'Kredit', 'Servis'],
                'opening_hours' => [
                    ['day' => 'Senin - Sabtu', 'open' => '08:00', 'close' => '17:00', 'closed' => false],
                ],
            ]);
        }

        return DealerSetting::query()->first()
            ?? new DealerSetting([
                'dealer_name' => 'Dealer Mobil',
                'tagline' => 'Mobil terbaik untuk kebutuhan Anda.',
                'hero_title' => 'Temukan mobil impian Anda',
                'hero_description' => 'Kendaraan berkualitas dengan layanan terbaik.',
                'history' => 'Dealer kami hadir untuk memberikan solusi mobil yang tepat bagi kebutuhan Anda.',
                'about_content' => 'Kami berkomitmen pada kepuasan pelanggan, transparansi, dan pelayanan terbaik.',
                'address' => 'Alamat belum diatur.',
                'phone' => '-',
                'email' => '-',
                'whatsapp' => '-',
                'services' => ['Sales', 'Kredit', 'Servis'],
                'opening_hours' => [
                    ['day' => 'Senin - Sabtu', 'open' => '08:00', 'close' => '17:00', 'closed' => false],
                ],
            ]);
    }

    public function home(): View
    {
        $settings = $this->dealerSettings();

        $featuredVehicles = Schema::hasTable('vehicles')
            ? Vehicle::query()
                ->with(['category', 'primaryImage'])
                ->whereNull('deleted_at')
                ->where('status', 'available')
                ->where('is_featured', true)
                ->orderByDesc('id')
                ->limit(4)
                ->get()
            : collect();

        $latestPromotions = Schema::hasTable('promotions')
            ? Promotion::query()
                ->with('vehicle')
                ->whereNull('deleted_at')
                ->where('is_active', true)
                ->whereDate('start_date', '<=', today())
                ->whereDate('end_date', '>=', today())
                ->orderByDesc('id')
                ->limit(3)
                ->get()
            : collect();

        $gallery = Schema::hasTable('galleries')
            ? Gallery::query()
                ->whereNull('deleted_at')
                ->where('is_published', true)
                ->orderByDesc('id')
                ->limit(6)
                ->get()
            : collect();

        $sales = Schema::hasTable('sales')
            ? Sale::query()
                ->whereNull('deleted_at')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->limit(4)
                ->get()
            : collect();

        return view('public.home', compact('settings', 'featuredVehicles', 'latestPromotions', 'gallery', 'sales'));
    }

    public function about(): View
    {
        $settings = $this->dealerSettings();
        $sales = Schema::hasTable('sales')
            ? Sale::query()
                ->whereNull('deleted_at')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
            : collect();

        return view('public.about', compact('settings', 'sales'));
    }

    public function vehiclesIndex(Request $request): View
    {
        $query = Vehicle::query()
            ->with(['category', 'primaryImage'])
            ->whereNull('deleted_at')
            ->where('status', 'available');

        if ($request->filled('category')) {
            $query->whereHas('category', function ($categoryQuery) use ($request): void {
                $categoryQuery->where('slug', $request->string('category')->toString());
            });
        }

        $vehicles = $query->orderByDesc('id')->paginate(9)->withQueryString();
        $categories =
            Category::query()->whereNull('deleted_at')->where('is_active', true)->orderBy('name')->get();

        return view('public.vehicles.index', compact('vehicles', 'categories'));
    }

    public function vehicleShow(string $vehicle): View
    {
        $vehicleModel = Vehicle::query()
            ->with(['category', 'images', 'primaryImage'])
            ->whereNull('deleted_at')
            ->where('slug', $vehicle)
            ->firstOrFail();

        $relatedVehicles = Vehicle::query()
            ->with(['category', 'primaryImage'])
            ->whereNull('deleted_at')
            ->where('status', 'available')
            ->where('id', '!=', $vehicleModel->id)
            ->orderByDesc('id')
            ->limit(3)
            ->get();

        return view('public.vehicles.show', compact('vehicleModel', 'relatedVehicles'));
    }

    public function promotionsIndex(): View
    {
        $promotions = Promotion::query()
            ->with('vehicle')
            ->whereNull('deleted_at')
            ->where('is_active', true)
            ->whereDate('start_date', '<=', today())
            ->whereDate('end_date', '>=', today())
            ->orderByDesc('id')
            ->paginate(6);

        return view('public.promotions.index', compact('promotions'));
    }

    public function promotionShow(string $promotion): View
    {
        $promotionModel = Promotion::query()
            ->with('vehicle')
            ->whereNull('deleted_at')
            ->where('is_active', true)
            ->where('slug', $promotion)
            ->firstOrFail();

        return view('public.promotions.show', compact('promotionModel'));
    }

    public function galleryIndex(): View
    {
        $gallery = Gallery::query()
            ->whereNull('deleted_at')
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        return view('public.gallery.index', compact('gallery'));
    }

    public function contact(): View
    {
        $settings = $this->dealerSettings();
        $sales = Schema::hasTable('sales')
            ? Sale::query()
                ->whereNull('deleted_at')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
            : collect();

        return view('public.contact', compact('settings', 'sales'));
    }

    public function creditSimulation(): View
    {
        $settings = $this->dealerSettings();

        return view('public.credit-simulation', compact('settings'));
    }
}
