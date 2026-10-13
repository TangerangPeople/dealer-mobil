<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceBooking;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ServiceMechanicAssignmentController extends Controller
{
    public function index(): View
    {
        $mechanics = User::query()
            ->where('role', 'mechanic')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        $bookings = ServiceBooking::query()
            ->with(['vehicle', 'servicePackage', 'mechanic'])
            ->orderByDesc('preferred_date')
            ->orderByDesc('id')
            ->paginate(15);

        return view('admin.service-bookings.assignments', compact(
            'mechanics',
            'bookings'
        ));
    }

    public function update(Request $request, ServiceBooking $serviceBooking): RedirectResponse
    {
        $data = $request->validate([
            'mechanic_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')
                    ->where(fn ($query) => $query->where('role', 'mechanic')),
            ],
        ]);

        if (in_array($serviceBooking->status, ['completed', 'cancelled'], true)) {
            return back()->withErrors([
                'mechanic_id' => 'Booking yang selesai atau dibatalkan tidak dapat diubah penugasannya.',
            ]);
        }

        $serviceBooking->mechanic_id = $data['mechanic_id'] ?? null;
        $serviceBooking->save();

        return back()->with('status', 'Penugasan mekanik berhasil diperbarui.');
    }
}
