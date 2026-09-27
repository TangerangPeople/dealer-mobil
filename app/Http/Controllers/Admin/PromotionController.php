<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PromotionRequest;
use App\Models\Promotion;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PromotionController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $vehicleId = $request->query('vehicle_id');
        $status = $request->query('status');

        $promotions = Promotion::query()
            ->with('vehicle')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('summary', 'like', "%{$search}%")
                        ->orWhereHas('vehicle', function ($query) use ($search): void {
                            $query->where('name', 'like', "%{$search}%")
                                ->orWhere('brand', 'like', "%{$search}%");
                        });
                });
            })
            ->when(filled($vehicleId), fn ($query) => $query->where('vehicle_id', $request->integer('vehicle_id')))
            ->when(in_array($status, ['active', 'inactive'], true), function ($query) use ($status): void {
                $query->where('is_active', $status === 'active');
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $vehicles = Vehicle::query()->orderBy('name')->get();

        return view('admin.promotions.index', compact('promotions', 'vehicles', 'search', 'vehicleId', 'status'));
    }

    public function create(): View
    {
        $vehicles = Vehicle::query()->orderBy('name')->get();

        return view('admin.promotions.create', ['promotion' => new Promotion, 'vehicles' => $vehicles]);
    }

    public function store(PromotionRequest $request): RedirectResponse
    {
        $promotion = Promotion::query()->create($request->validated());

        $this->handleImageUpload($request, $promotion);

        return redirect()->route('admin.promotions.index')->with('success', 'Promo berhasil ditambahkan.');
    }

    public function edit(Promotion $promotion): View
    {
        $vehicles = Vehicle::query()->orderBy('name')->get();

        return view('admin.promotions.edit', compact('promotion', 'vehicles'));
    }

    public function update(PromotionRequest $request, Promotion $promotion): RedirectResponse
    {
        $promotion->update($request->validated());

        $this->handleImageUpload($request, $promotion);

        return redirect()->route('admin.promotions.index')->with('success', 'Promo berhasil diperbarui.');
    }

    public function destroy(Promotion $promotion): RedirectResponse
    {
        if ($promotion->image_path && Storage::disk('public')->exists($promotion->image_path)) {
            Storage::disk('public')->delete($promotion->image_path);
        }

        $promotion->delete();

        return redirect()->route('admin.promotions.index')->with('success', 'Promo berhasil dihapus.');
    }

    protected function handleImageUpload(Request $request, Promotion $promotion): void
    {
        if (! $request->hasFile('image')) {
            return;
        }

        $file = $request->file('image');
        $safeName = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs('promotions', $safeName, 'public');

        if ($promotion->image_path && Storage::disk('public')->exists($promotion->image_path)) {
            Storage::disk('public')->delete($promotion->image_path);
        }

        $promotion->update([
            'image_path' => $path,
            'image_alt_text' => trim((string) $request->input('image_alt_text', $promotion->image_alt_text ?: $promotion->title)),
        ]);
    }
}
