<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Sale;
use App\Models\ServiceBooking;
use App\Models\ServicePackage;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalVehicles = Schema::hasTable('vehicles')
            ? Vehicle::query()->whereNull('deleted_at')->count()
            : 0;

        $availableVehicles = Schema::hasTable('vehicles')
            ? Vehicle::query()->whereNull('deleted_at')->where('status', 'available')->count()
            : 0;

        $activePromos = Schema::hasTable('promotions')
            ? Promotion::query()
                ->whereNull('deleted_at')
                ->where('is_active', true)
                ->whereDate('start_date', '<=', today())
                ->whereDate('end_date', '>=', today())
                ->count()
            : 0;

        $activeSales = Schema::hasTable('sales')
            ? Sale::query()->whereNull('deleted_at')->where('is_active', true)->count()
            : 0;

        $activeServicePackages = Schema::hasTable('service_packages')
            ? ServicePackage::query()->whereNull('deleted_at')->where('is_active', true)->count()
            : 0;

        $pendingBookings = Schema::hasTable('service_bookings')
            ? ServiceBooking::query()->whereNull('deleted_at')->where('status', 'pending')->count()
            : 0;

        return view('admin.dashboard', compact(
            'totalVehicles',
            'availableVehicles',
            'activePromos',
            'activeSales',
            'activeServicePackages',
            'pendingBookings'
        ));
    }
}
