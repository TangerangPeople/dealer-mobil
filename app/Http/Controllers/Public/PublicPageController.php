<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\DealerSetting;
use App\Models\Gallery;
use App\Models\Promotion;
use App\Models\Sale;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
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
        $settings = $this->dealerSettings();
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

        return view('public.vehicles.index', compact('settings', 'vehicles', 'categories'));
    }

    public function vehicleShow(string $vehicle): View
    {
        $settings = $this->dealerSettings();
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
        $relatedPromotion = Promotion::query()
            ->whereNull('deleted_at')
            ->where('is_active', true)
            ->where('vehicle_id', $vehicleModel->id)
            ->orderByDesc('id')
            ->first();

        return view('public.vehicles.show', compact('settings', 'vehicleModel', 'relatedVehicles', 'relatedPromotion'));
    }

    public function promotionsIndex(): View
    {
        $settings = $this->dealerSettings();
        $promotions = Promotion::query()
            ->with('vehicle')
            ->whereNull('deleted_at')
            ->where('is_active', true)
            ->whereDate('start_date', '<=', today())
            ->whereDate('end_date', '>=', today())
            ->orderByDesc('id')
            ->paginate(6);

        return view('public.promotions.index', compact('settings', 'promotions'));
    }

    public function promotionShow(string $promotion): View
    {
        $settings = $this->dealerSettings();
        $promotionModel = Promotion::query()
            ->with('vehicle')
            ->whereNull('deleted_at')
            ->where('is_active', true)
            ->where('slug', $promotion)
            ->firstOrFail();

        return view('public.promotions.show', compact('settings', 'promotionModel'));
    }

    public function galleryIndex(): View
    {
        $settings = $this->dealerSettings();
        $gallery = Gallery::query()
            ->whereNull('deleted_at')
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        return view('public.gallery.index', compact('settings', 'gallery'));
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

        $dealerNumber = $this->normalizeWhatsapp($settings->whatsapp);
        $dealerWhatsappUrl = $dealerNumber !== ''
            ? $this->whatsappUrl($dealerNumber, 'Halo, saya ingin menghubungi dealer.')
            : null;

        return view('public.contact', compact('settings', 'sales', 'dealerWhatsappUrl'));
    }

    public function creditSimulation(Request $request): View
    {
        $settings = $this->dealerSettings();
        $vehicles = Vehicle::query()
            ->whereNull('deleted_at')
            ->where('status', 'available')
            ->orderBy('brand')
            ->orderBy('name')
            ->get(['id', 'brand', 'name', 'slug', 'price']);
        $selectedVehicle = null;

        if ($request->filled('vehicle')) {
            $selectedVehicle = $vehicles->firstWhere('slug', $request->query('vehicle'));

            abort_unless($selectedVehicle, 404);
        }

        return view('public.credit-simulation', compact('settings', 'vehicles', 'selectedVehicle'));
    }

    public function chatSales(Request $request, ?int $sale = null): View|RedirectResponse
    {
        $settings = $this->dealerSettings();
        $sales = Sale::query()
            ->whereNull('deleted_at')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $vehicleSlug = $request->query('vehicle');
        $vehicle = null;

        if ($vehicleSlug !== null) {
            abort_unless(is_string($vehicleSlug), 404);
            $vehicle = Vehicle::query()
                ->whereNull('deleted_at')
                ->where('status', 'available')
                ->where('slug', $vehicleSlug)
                ->firstOrFail();
        }

        $message = $vehicle
            ? sprintf('Halo, saya ingin berkonsultasi mengenai %s %s.', $vehicle->brand, $vehicle->name)
            : 'Halo, saya ingin berkonsultasi mengenai kendaraan.';

        if ($request->boolean('credit')) {
            $validated = validator($request->query(), [
                'price' => ['required', 'integer', 'min:1', 'max:999999999999'],
                'down_payment' => ['required', 'integer', 'min:1'],
                'tenor' => ['required', 'integer', 'in:12,24,36,48,60'],
                'annual_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            ])->validate();
            abort_if($validated['down_payment'] >= $validated['price'], 422);

            $principal = $validated['price'] - $validated['down_payment'];
            $totalInterest = $principal * (float) $validated['annual_rate'] / 100 * ((int) $validated['tenor'] / 12);
            $monthlyInstallment = ($principal + $totalInterest) / (int) $validated['tenor'];
            $message .= sprintf(
                "\n\nRingkasan simulasi estimasi:\nHarga: Rp %s\nDP: Rp %s\nTenor: %s bulan\nEstimasi cicilan: Rp %s per bulan",
                number_format((int) $validated['price'], 0, ',', '.'),
                number_format((int) $validated['down_payment'], 0, ',', '.'),
                $validated['tenor'],
                number_format((int) round($monthlyInstallment), 0, ',', '.'),
            );
        }

        if ($sale !== null) {
            $selectedSale = $sales->firstWhere('id', $sale);
            abort_unless($selectedSale, 404);

            $number = $this->normalizeWhatsapp($selectedSale->whatsapp) ?: $this->normalizeWhatsapp($settings->whatsapp);
            if ($number !== '') {
                return redirect()->away($this->whatsappUrl($number, $message));
            }

            return view('public.sales', [
                'settings' => $settings,
                'sales' => collect(),
                'message' => $message,
                'unavailableSale' => $selectedSale,
                'dealerWhatsappUrl' => null,
            ]);
        }

        $salesWithWhatsapp = $sales->filter(fn (Sale $item): bool => $this->normalizeWhatsapp($item->whatsapp) !== '');
        if ($salesWithWhatsapp->count() === 1) {
            $singleSale = $salesWithWhatsapp->first();

            return redirect()->away($this->whatsappUrl($this->normalizeWhatsapp($singleSale->whatsapp), $message));
        }

        if ($salesWithWhatsapp->isEmpty()) {
            $dealerNumber = $this->normalizeWhatsapp($settings->whatsapp);
            if ($dealerNumber !== '') {
                return redirect()->away($this->whatsappUrl($dealerNumber, $message));
            }
        }

        $sales = $salesWithWhatsapp->map(fn (Sale $item): array => [
            'sale' => $item,
            'url' => route('sales.chat', ['sale' => $item->getKey()] + $request->query()),
        ]);

        $dealerNumber = $this->normalizeWhatsapp($settings->whatsapp);

        return view('public.sales', [
            'settings' => $settings,
            'sales' => $sales,
            'message' => $message,
            'unavailableSale' => null,
            'dealerWhatsappUrl' => $dealerNumber !== '' ? $this->whatsappUrl($dealerNumber, $message) : null,
        ]);
    }

    private function normalizeWhatsapp(?string $number): string
    {
        $digits = preg_replace('/\D+/', '', (string) $number) ?? '';

        if (str_starts_with($digits, '0')) {
            return '62'.substr($digits, 1);
        }

        return str_starts_with($digits, '8') ? '62'.$digits : $digits;
    }

    private function whatsappUrl(string $number, string $message): string
    {
        return 'https://wa.me/'.$number.'?text='.rawurlencode($message);
    }
}
