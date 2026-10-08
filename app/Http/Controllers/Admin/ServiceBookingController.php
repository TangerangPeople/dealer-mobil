<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceBookingRequest;
use App\Models\ServiceBooking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceBookingController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $status = $request->query('status');

        $serviceBookings = ServiceBooking::query()
            ->with(['vehicle', 'servicePackage'])
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('customer_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('vehicle_plate', 'like', "%{$search}%");
                });
            })
            ->when(in_array($status, ['pending', 'approved', 'rescheduled', 'completed', 'cancelled'], true), function ($query) use ($status): void {
                $query->where('status', $status);
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.service-bookings.index', compact('serviceBookings', 'search', 'status'));
    }

    public function update(ServiceBookingRequest $request, ServiceBooking $serviceBooking): RedirectResponse
    {
        $serviceBooking->update($request->validated());

        return redirect()->route('admin.service-bookings.index')->with('success', 'Status booking berhasil diperbarui.');
    }

    public function destroy(ServiceBooking $serviceBooking): RedirectResponse
    {
        $serviceBooking->delete();

        return redirect()->route('admin.service-bookings.index')->with('success', 'Booking berhasil dihapus.');
    }
}
