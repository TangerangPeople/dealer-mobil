<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleRequest;
use App\Models\Category;
use App\Models\Vehicle;
use App\Models\VehicleImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class VehicleController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $categoryId = $request->integer('category');
        $status = $request->query('status');
        $featured = $request->query('featured');

        $vehicles = Vehicle::query()
            ->with(['category', 'primaryImage'])
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%")
                        ->orWhere('type', 'like', "%{$search}%");
                });
            })
            ->when($categoryId > 0, fn ($query) => $query->where('category_id', $categoryId))
            ->when(in_array($status, ['available', 'sold', 'reserved', 'inactive'], true), fn ($query) => $query->where('status', $status))
            ->when(in_array($featured, ['yes', 'no'], true), fn ($query) => $query->where('is_featured', $featured === 'yes'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Category::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']);

        return view('admin.vehicles.index', compact('vehicles', 'categories', 'search', 'categoryId', 'status', 'featured'));
    }

    public function create(): View
    {
        return view('admin.vehicles.create', [
            'vehicle' => new Vehicle,
            'categories' => $this->activeCategories(),
        ]);
    }

    public function store(VehicleRequest $request): RedirectResponse
    {
        $storedPaths = [];

        try {
            $vehicle = DB::transaction(function () use ($request, &$storedPaths): Vehicle {
                $vehicle = Vehicle::query()->create(Arr::except($request->validated(), [
                    'images',
                    'image_alt_texts',
                    'image_sort_orders',
                    'primary_image_index',
                ]));

                $this->storeImages($vehicle, $request, $storedPaths);

                return $vehicle;
            });
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($storedPaths);

            throw $exception;
        }

        return to_route('admin.vehicles.edit', $vehicle)->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function edit(Vehicle $vehicle): View
    {
        $vehicle->load(['vehicleImages' => fn ($query) => $query->orderBy('sort_order'), 'primaryImage']);

        return view('admin.vehicles.edit', [
            'vehicle' => $vehicle,
            'categories' => $this->activeCategories($vehicle->category_id),
        ]);
    }

    public function update(VehicleRequest $request, Vehicle $vehicle): RedirectResponse
    {
        $storedPaths = [];

        try {
            DB::transaction(function () use ($request, $vehicle, &$storedPaths): void {
                $vehicle->update(Arr::except($request->validated(), [
                    'images',
                    'image_alt_texts',
                    'image_sort_orders',
                    'primary_image_index',
                ]));

                $this->storeImages($vehicle, $request, $storedPaths);
            });
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($storedPaths);

            throw $exception;
        }

        return to_route('admin.vehicles.edit', $vehicle)->with('success', 'Kendaraan berhasil diperbarui.');
    }

    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        $vehicle->delete();

        return to_route('admin.vehicles.index')->with('success', 'Kendaraan berhasil dihapus. Foto tetap tersimpan untuk pemulihan data.');
    }

    /** @return \Illuminate\Database\Eloquent\Collection<int, Category> */
    private function activeCategories(?int $selectedCategoryId = null): \Illuminate\Database\Eloquent\Collection
    {
        return Category::query()
            ->where('is_active', true)
            ->when($selectedCategoryId, fn ($query) => $query->orWhereKey($selectedCategoryId))
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    /** @param array<int, string> $storedPaths */
    private function storeImages(Vehicle $vehicle, VehicleRequest $request, array &$storedPaths): void
    {
        $files = $request->file('images', []);
        $altTexts = $request->input('image_alt_texts', []);
        $sortOrders = $request->input('image_sort_orders', []);
        $primaryIndex = $request->input('primary_image_index');

        $storedImages = [];

        foreach ($files as $index => $file) {
            if ($file === null) {
                continue;
            }

            $path = $file->store('vehicles', 'public');
            $storedPaths[] = $path;

            $storedImages[$index] = $vehicle->vehicleImages()->create([
                'image_path' => $path,
                'alt_text' => $altTexts[$index],
                'sort_order' => $sortOrders[$index],
                'is_primary' => false,
            ]);
        }

        if ($primaryIndex !== null && isset($storedImages[(int) $primaryIndex])) {
            $this->setPrimaryImage($vehicle, $storedImages[(int) $primaryIndex]);
        }
    }

    private function setPrimaryImage(Vehicle $vehicle, VehicleImage $image): void
    {
        $vehicle->vehicleImages()->where('is_primary', true)->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);
    }
}
