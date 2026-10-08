<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServicePackageRequest;
use App\Models\ServicePackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServicePackageController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $status = $request->query('status');

        $servicePackages = ServicePackage::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('service_type', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when(in_array($status, ['active', 'inactive'], true), function ($query) use ($status): void {
                $query->where('is_active', $status === 'active');
            })
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.service-packages.index', compact('servicePackages', 'search', 'status'));
    }

    public function create(): View
    {
        return view('admin.service-packages.create', ['servicePackage' => new ServicePackage]);
    }

    public function store(ServicePackageRequest $request): RedirectResponse
    {
        ServicePackage::query()->create($request->validated());

        return redirect()->route('admin.service-packages.index')->with('success', 'Paket servis berhasil ditambahkan.');
    }

    public function edit(ServicePackage $servicePackage): View
    {
        return view('admin.service-packages.edit', compact('servicePackage'));
    }

    public function update(ServicePackageRequest $request, ServicePackage $servicePackage): RedirectResponse
    {
        $servicePackage->update($request->validated());

        return redirect()->route('admin.service-packages.index')->with('success', 'Paket servis berhasil diperbarui.');
    }

    public function destroy(ServicePackage $servicePackage): RedirectResponse
    {
        $servicePackage->delete();

        return redirect()->route('admin.service-packages.index')->with('success', 'Paket servis berhasil dihapus.');
    }
}
