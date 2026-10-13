<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SparePart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SparePartController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(
            in_array($request->user()?->role, ['admin', 'owner'], true),
            403
        );

        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'stock' => ['nullable', Rule::in(['all', 'low'])],
        ]);

        $query = SparePart::query()
            ->when(trim((string) ($filters['q'] ?? '')) !== '', function ($query) use ($filters): void {
                $search = trim($filters['q']);

                $query->where(function ($subQuery) use ($search): void {
                    $subQuery
                        ->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                });
            })
            ->when(($filters['stock'] ?? 'all') === 'low', function ($query): void {
                $query->whereColumn('stock', '<=', 'min_stock');
            })
            ->orderBy('name');

        $spareParts = $query->paginate(15)->withQueryString();

        $totalActive = SparePart::where('is_active', true)->count();

        $lowStockCount = SparePart::where('is_active', true)
            ->whereColumn('stock', '<=', 'min_stock')
            ->count();

        return view('admin.spare-parts.index', compact(
            'spareParts',
            'totalActive',
            'lowStockCount',
            'filters'
        ));
    }

    public function create(): View
    {
        return view('admin.spare-parts.create', [
            'sparePart' => new SparePart(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules());

        $data['is_active'] = $request->boolean('is_active');

        SparePart::create($data);

        return redirect()
            ->route('admin.spare-parts.index')
            ->with('status', 'Sparepart berhasil ditambahkan.');
    }

    public function edit(SparePart $sparePart): View
    {
        return view('admin.spare-parts.edit', compact('sparePart'));
    }

    public function update(Request $request, SparePart $sparePart): RedirectResponse
    {
        $data = $request->validate($this->rules($sparePart));

        $data['is_active'] = $request->boolean('is_active');

        $sparePart->update($data);

        return redirect()
            ->route('admin.spare-parts.index')
            ->with('status', 'Data sparepart berhasil diperbarui.');
    }

    public function destroy(SparePart $sparePart): RedirectResponse
    {
        // Nonaktifkan agar riwayat pemakaian tidak kehilangan referensi.
        $sparePart->update(['is_active' => false]);

        return redirect()
            ->route('admin.spare-parts.index')
            ->with('status', 'Sparepart berhasil dinonaktifkan.');
    }

    private function rules(?SparePart $sparePart = null): array
    {
        $codeRule = Rule::unique('spare_parts', 'code');

        if ($sparePart?->exists) {
            $codeRule->ignore($sparePart->id);
        }

        return [
            'code' => ['required', 'string', 'max:50', $codeRule],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:5000'],
            'unit' => ['required', 'string', 'max:30'],
            'purchase_price' => ['required', 'numeric', 'min:0', 'max:999999999999.99'],
            'selling_price' => ['required', 'numeric', 'min:0', 'max:999999999999.99'],
            'stock' => ['required', 'integer', 'min:0', 'max:4294967295'],
            'min_stock' => ['required', 'integer', 'min:0', 'max:4294967295'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
