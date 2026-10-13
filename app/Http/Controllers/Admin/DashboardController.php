<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\Promotion;
use App\Models\PurchaseApplication;
use App\Models\Sale;
use App\Models\ServiceBooking;
use App\Models\ServicePackage;
use App\Models\SparePart;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(
            $request->user('web')?->role === 'admin',
            403
        );

        return view('admin.dashboard', [
            'totalVehicles' => Vehicle::count(),
            'availableVehicles' => Vehicle::where(
                'status',
                'available'
            )->count(),
            'soldVehicles' => Vehicle::where(
                'status',
                'sold'
            )->count(),
            'reservedVehicles' => Vehicle::where(
                'status',
                'reserved'
            )->count(),

            'pendingPurchaseApplications' => PurchaseApplication::where(
                'status',
                'pending'
            )->count(),

            'pendingBookings' => ServiceBooking::where(
                'status',
                'pending'
            )->count(),

            'totalCategories' => Category::count(),
            'totalGalleries' => Gallery::count(),

            'activePromos' => Promotion::where(
                'is_active',
                true
            )->count(),

            'activeSales' => Sale::where(
                'is_active',
                true
            )->count(),

            'activeServicePackages' => ServicePackage::where(
                'is_active',
                true
            )->count(),

            'activeSpareParts' => SparePart::where(
                'is_active',
                true
            )->count(),

            'lowStockSpareParts' => SparePart::query()
                ->where('is_active', true)
                ->whereColumn('stock', '<=', 'min_stock')
                ->count(),
        ]);
    }
}
