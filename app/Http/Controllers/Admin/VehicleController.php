<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleRequest;
use App\Models\Category;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class VehicleController extends Controller
{
    public function index(Request $request): View
    {
        $vehicles = Vehicle::query()
            ->with(['category', 'primaryImage'])
            ->when(
                $request->filled('search'),
                function ($query) use ($request): void {
                    $search = trim((string) $request->query('search', ''));

                    $query->where(function ($query) use ($search): void {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('brand', 'like', "%{$search}%")
                            ->orWhere('type', 'like', "%{$search}%");
                    });
                }
            )
            ->when(
                $request->filled('category_id'),
                fn ($query) => $query->where('category_id', $request->integer('category_id'))
            )
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where('status', $request->string('status')->toString())
            )
            ->when(
                $request->has('featured') && $request->featured !== '',
                fn ($query) => $query->where('is_featured', $request->boolean('featured'))
            )
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $categories = Category::query()
            ->whereNull('deleted_at')
            ->orderBy('name')
            ->get();

        return view('admin.vehicles.index', compact('vehicles', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->whereNull('deleted_at')
            ->orderBy('name')
            ->get();

        return view('admin.vehicles.create', compact('categories'));
    }

    public function store(VehicleRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($data['status'] === 'inactive') {
            $data['is_featured'] = false;
        }

        $vehicle = Vehicle::create($data);

        $this->storeImagesFromRequest($request, $vehicle);

        return redirect()->route('admin.vehicles.index')->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function edit(Vehicle $vehicle): View
    {
        $vehicle->load(['vehicleImages' => fn ($query) => $query->orderBy('sort_order')->orderBy('id')]);

        $categories = Category::query()
            ->whereNull('deleted_at')
            ->where(function ($query) use ($vehicle): void {
                $query->where('is_active', true)->orWhere('id', $vehicle->category_id);
            })
            ->orderBy('name')
            ->get();

        return view('admin.vehicles.edit', compact('vehicle', 'categories'));
    }

    public function update(VehicleRequest $request, Vehicle $vehicle): RedirectResponse
    {
        $data = $request->validated();

        if ($data['status'] === 'inactive') {
            $data['is_featured'] = false;
        }

        $vehicle->update($data);

        $this->storeImagesFromRequest($request, $vehicle);

        return redirect()->route('admin.vehicles.index')->with('success', 'Kendaraan berhasil diperbarui.');
    }

    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        $vehicle->delete();

        return redirect()->route('admin.vehicles.index')->with('success', 'Kendaraan berhasil dihapus.');
    }

    protected function storeImagesFromRequest(Request $request, Vehicle $vehicle): void
    {
        $files = $request->file('images', []);

        if (empty($files)) {
            return;
        }

        foreach ($files as $index => $file) {
            if (! $file) {
                continue;
            }

            $altText = trim((string) ($request->input("image_alt_texts.{$index}", '')));
            $sortOrder = (int) ($request->input("image_sort_orders.{$index}", $index));
            $safeName = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('vehicles', $safeName, 'public');

            $image = $vehicle->vehicleImages()->create([
                'image_path' => $path,
                'alt_text' => $altText !== '' ? $altText : 'Foto kendaraan',
                'sort_order' => $sortOrder,
                'is_primary' => false,
            ]);

            if ((string) $request->input('primary_image_index') === (string) $index) {
                $vehicle->vehicleImages()->where('is_primary', true)->update(['is_primary' => false]);
                $image->update(['is_primary' => true]);
            }
        }
    }
}
