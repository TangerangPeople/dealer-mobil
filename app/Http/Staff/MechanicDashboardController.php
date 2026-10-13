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
        $user = $request->user('web');

        abort_unless(
            $user !== null && $user->role === 'mechanic',
            403
        );

        $assignedBookings = ServiceBooking::query()
            ->where('mechanic_id', $user->id);

        $upcomingBookings = ServiceBooking::query()
            ->with(['vehicle', 'servicePackage'])
            ->where('mechanic_id', $user->id)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->orderBy('preferred_date')
            ->orderBy('preferred_time')
            ->limit(8)
            ->get();

        return view('mechanic.dashboard', [
            'totalAssigned' => (clone $assignedBookings)->count(),

            'pendingBookings' => (clone $assignedBookings)
                ->where('status', 'pending')
                ->count(),

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
