<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\ServiceBooking;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MechanicDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $mechanicId = $request->user()->id;

        $assignedBookings = ServiceBooking::query()
            ->where('mechanic_id', $mechanicId);

        $upcomingBookings = ServiceBooking::query()
            ->with(['vehicle', 'servicePackage'])
            ->where('mechanic_id', $mechanicId)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->orderBy('preferred_date')
            ->orderBy('preferred_time')
            ->limit(5)
            ->get();

        return view('mechanic.dashboard', [
            'totalAssigned' => (clone $assignedBookings)->count(),
            'pendingBookings' => (clone $assignedBookings)->where('status', 'pending')->count(),
            'activeBookings' => (clone $assignedBookings)
                ->whereIn('status', ['approved', 'rescheduled'])
                ->count(),
            'completedBookings' => (clone $assignedBookings)
                ->where('status', 'completed')
                ->count(),
            'upcomingBookings' => $upcomingBookings,
        ]);
    }
}
