<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceBookingRequest;
use App\Models\DealerSetting;
use App\Models\ServiceBooking;
use App\Models\ServicePackage;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $settings = $this->dealerSettings();
        $servicePackages = ServicePackage::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        return view('public.services.index', compact('settings', 'servicePackages'));
    }

    public function booking(Request $request): View
    {
        $settings = $this->dealerSettings();
        $servicePackages = ServicePackage::query()
            ->whereNull('deleted_at')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();
        $vehicles = Vehicle::query()
            ->whereNull('deleted_at')
            ->where('status', 'available')
            ->orderBy('name')
            ->get(['id', 'brand', 'name']);
        $selectedVehicle = $request->filled('vehicle_id')
            ? $vehicles->firstWhere('id', (int) $request->query('vehicle_id'))
            : null;

        return view('public.services.booking', compact('settings', 'servicePackages', 'vehicles', 'selectedVehicle'));
    }

    public function store(ServiceBookingRequest $request): RedirectResponse
    {
        ServiceBooking::query()->create($request->validated());

        return redirect()->route('booking-service')->with('success', 'Booking servis berhasil dikirim. Tim kami akan menghubungi Anda segera.');
    }

    private function dealerSettings(): DealerSetting
    {
        $fallback = fn (): DealerSetting => new DealerSetting([
            'dealer_name' => 'Dealer Mobil',
            'phone' => '-',
            'email' => '-',
            'whatsapp' => '-',
            'address' => 'Alamat belum diatur.',
        ]);

        if (! Schema::hasTable('dealer_settings')) {
            return $fallback();
        }

        return DealerSetting::query()->first() ?? $fallback();
    }
}
