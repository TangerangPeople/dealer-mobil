<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceBooking;
use App\Models\SparePart;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MechanicServiceController extends Controller
{
    public function index(Request $request): View
    {
        $mechanic = $request->user();

        abort_unless($mechanic?->role === 'mechanic', 403);

        $bookings = ServiceBooking::query()
            ->with([
                'vehicle',
                'servicePackage',
                'sparePartUsages.sparePart',
            ])
            ->where('mechanic_id', $mechanic->id)
            ->orderByDesc('preferred_date')
            ->orderByDesc('id')
            ->paginate(10);

        $availableSpareParts = SparePart::query()
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get();

        return view('admin.mechanic.bookings', compact(
            'bookings',
            'availableSpareParts'
        ));
    }
}
