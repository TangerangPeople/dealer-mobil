<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaleRequest;
use App\Models\Sale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $status = $request->query('status');

        $sales = Sale::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('whatsapp', 'like', "%{$search}%");
                });
            })
            ->when(in_array($status, ['active', 'inactive'], true), function ($query) use ($status): void {
                $query->where('is_active', $status === 'active');
            })
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.sales.index', compact('sales', 'search', 'status'));
    }

    public function create(): View
    {
        return view('admin.sales.create', ['sale' => new Sale]);
    }

    public function store(SaleRequest $request): RedirectResponse
    {
        Sale::query()->create($request->validated());

        return redirect()->route('admin.sales.index')->with('success', 'Sales berhasil ditambahkan.');
    }

    public function edit(Sale $sale): View
    {
        return view('admin.sales.edit', compact('sale'));
    }

    public function update(SaleRequest $request, Sale $sale): RedirectResponse
    {
        $sale->update($request->validated());

        return redirect()->route('admin.sales.index')->with('success', 'Sales berhasil diperbarui.');
    }

    public function destroy(Sale $sale): RedirectResponse
    {
        $sale->delete();

        return redirect()->route('admin.sales.index')->with('success', 'Sales berhasil dihapus.');
    }
}
