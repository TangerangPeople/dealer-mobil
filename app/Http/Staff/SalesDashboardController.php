<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalesDashboardController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(
            $request->user('web')?->role === 'sales',
            403
        );

        return view('sales.dashboard', [
            'availableVehicles' => Vehicle::where(
                'status',
                'available'
            )->count(),

            'activePromotions' => Promotion::where(
                'is_active',
                true
            )->count(),
        ]);
    }
}
