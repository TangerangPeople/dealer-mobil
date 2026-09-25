<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleImageRequest;
use App\Models\Vehicle;
use App\Models\VehicleImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VehicleImageController extends Controller
{
    public function store(VehicleImageRequest $request, Vehicle $vehicle): RedirectResponse
    {
        $path = $request->file('image')->store('vehicles', 'public');

        try {
            $image = DB::transaction(function () use ($request, $vehicle, $path): VehicleImage {
                $image = $vehicle->vehicleImages()->create([
                    'image_path' => $path,
                    'alt_text' => $request->validated('alt_text'),
                    'sort_order' => $request->validated('sort_order'),
                    'is_primary' => false,
                ]);

                if ($request->boolean('is_primary')) {
                    $this->setPrimaryImage($vehicle, $image);
                }

                return $image;
            });
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($path);

            throw $exception;
        }

        return to_route('admin.vehicles.edit', $vehicle)->with('success', 'Foto kendaraan berhasil ditambahkan.');
    }

    public function update(VehicleImageRequest $request, Vehicle $vehicle, VehicleImage $vehicleImage): RedirectResponse
    {
        $this->ensureImageBelongsToVehicle($vehicle, $vehicleImage);

        DB::transaction(function () use ($request, $vehicle, $vehicleImage): void {
            $vehicleImage->update([
                'alt_text' => $request->validated('alt_text'),
                'sort_order' => $request->validated('sort_order'),
                'is_primary' => false,
            ]);

            if ($request->boolean('is_primary')) {
                $this->setPrimaryImage($vehicle, $vehicleImage);
            }
        });

        return to_route('admin.vehicles.edit', $vehicle)->with('success', 'Pengaturan foto berhasil diperbarui.');
    }

    public function destroy(Vehicle $vehicle, VehicleImage $vehicleImage): RedirectResponse
    {
        $this->ensureImageBelongsToVehicle($vehicle, $vehicleImage);

        if ($vehicle->status === 'available' && $vehicleImage->is_primary) {
            return to_route('admin.vehicles.edit', $vehicle)->with(
                'error',
                'Foto utama tidak dapat dihapus selama kendaraan berstatus available.'
            );
        }

        $path = $vehicleImage->image_path;

        DB::transaction(fn () => $vehicleImage->delete());
        Storage::disk('public')->delete($path);

        return to_route('admin.vehicles.edit', $vehicle)->with('success', 'Foto kendaraan berhasil dihapus.');
    }

    private function setPrimaryImage(Vehicle $vehicle, VehicleImage $image): void
    {
        $vehicle->vehicleImages()->where('is_primary', true)->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);
    }

    private function ensureImageBelongsToVehicle(Vehicle $vehicle, VehicleImage $image): void
    {
        abort_unless($image->vehicle_id === $vehicle->id, 404);
    }
}
