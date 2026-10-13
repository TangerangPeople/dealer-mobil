<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceBooking;
use App\Models\ServiceBookingSparePart;
use App\Models\SparePart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ServiceBookingSparePartController extends Controller
{
    public function store(Request $request, ServiceBooking $serviceBooking): RedirectResponse
    {
        $data = $request->validate([
            'spare_part_id' => ['required', 'integer', 'exists:spare_parts,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:100000'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($request, $serviceBooking, $data): void {
            $booking = ServiceBooking::query()
                ->whereKey($serviceBooking->id)
                ->lockForUpdate()
                ->firstOrFail();

            $this->authorizeBooking($request, $booking);
            $this->ensureBookingEditable($booking);

            $part = SparePart::query()
                ->whereKey($data['spare_part_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $usage = ServiceBookingSparePart::query()
                ->where('service_booking_id', $booking->id)
                ->where('spare_part_id', $part->id)
                ->lockForUpdate()
                ->first();

            $oldQuantity = (int) ($usage?->quantity ?? 0);
            $newQuantity = (int) $data['quantity'];
            $difference = $newQuantity - $oldQuantity;

            if ($difference > 0) {
                if (! $part->is_active) {
                    throw ValidationException::withMessages([
                        'spare_part_id' => 'Sparepart tersebut sudah tidak aktif.',
                    ]);
                }

                if ($part->stock < $difference) {
                    throw ValidationException::withMessages([
                        'quantity' => "Stok {$part->name} tidak mencukupi. Stok tersedia: {$part->stock}.",
                    ]);
                }
            }

            // Selisih positif mengurangi stok; selisih negatif mengembalikan stok.
            $part->stock -= $difference;
            $part->save();

            if ($usage) {
                $usage->quantity = $newQuantity;
                $usage->notes = $data['notes'] ?? null;
                $usage->used_by = $request->user()->id;
                $usage->save();
            } else {
                ServiceBookingSparePart::create([
                    'service_booking_id' => $booking->id,
                    'spare_part_id' => $part->id,
                    'quantity' => $newQuantity,
                    'unit_price' => $part->selling_price,
                    'notes' => $data['notes'] ?? null,
                    'used_by' => $request->user()->id,
                ]);
            }
        });

        return back()->with('status', 'Pemakaian sparepart berhasil disimpan dan stok disesuaikan.');
    }

    public function destroy(
        Request $request,
        ServiceBooking $serviceBooking,
        ServiceBookingSparePart $usage
    ): RedirectResponse {
        DB::transaction(function () use ($request, $serviceBooking, $usage): void {
            $booking = ServiceBooking::query()
                ->whereKey($serviceBooking->id)
                ->lockForUpdate()
                ->firstOrFail();

            $this->authorizeBooking($request, $booking);
            $this->ensureBookingEditable($booking);

            $lockedUsage = ServiceBookingSparePart::query()
                ->whereKey($usage->id)
                ->where('service_booking_id', $booking->id)
                ->lockForUpdate()
                ->firstOrFail();

            $part = SparePart::query()
                ->whereKey($lockedUsage->spare_part_id)
                ->lockForUpdate()
                ->firstOrFail();

            $part->stock += $lockedUsage->quantity;
            $part->save();

            $lockedUsage->delete();
        });

        return back()->with('status', 'Pemakaian dihapus dan stok dikembalikan.');
    }

    private function authorizeBooking(Request $request, ServiceBooking $booking): void
    {
        $user = $request->user();

        abort_unless(
            $user !== null
                && in_array($user->role, ['admin', 'mechanic'], true),
            403
        );

        if ($user->role === 'mechanic') {
            abort_unless((int) $booking->mechanic_id === (int) $user->id, 403);
        }
    }

    private function ensureBookingEditable(ServiceBooking $booking): void
    {
        if (in_array($booking->status, ['completed', 'cancelled'], true)) {
            throw ValidationException::withMessages([
                'service_booking' => 'Pemakaian sparepart tidak bisa diubah untuk booking yang selesai atau dibatalkan.',
            ]);
        }
    }
}
