<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePurchaseApplicationStatusRequest;
use App\Models\PurchaseApplication;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PurchaseApplicationController extends Controller
{
    /**
     * Menampilkan daftar pengajuan pembelian.
     */
    public function index(Request $request): View
    {
        $applications = PurchaseApplication::query()
            ->with(['user', 'vehicle', 'sales'])
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where(
                    'status',
                    $request->query('status')
                )
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.purchase-applications.index',
            compact('applications')
        );
    }

    /**
     * Menampilkan detail pengajuan dan daftar akun Sales.
     */
    public function show(PurchaseApplication $application): View
    {
        $application->load([
            'user',
            'vehicle',
            'documents',
            'sales',
        ]);

        // Hanya akun dengan role sales yang ditampilkan.
        $salesUsers = User::query()
            ->where('role', 'sales')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        // salesUsers wajib dikirim ke halaman show.blade.php.
        return view(
            'admin.purchase-applications.show',
            [
                'application' => $application,
                'salesUsers' => $salesUsers,
            ]
        );
    }

    /**
     * Memperbarui status pengajuan.
     */
    public function updateStatus(
        UpdatePurchaseApplicationStatusRequest $request,
        PurchaseApplication $application
    ): RedirectResponse {
        $application->update([
            'status' => $request->validated('status'),
        ]);

        return to_route(
            'admin.purchase-applications.show',
            $application
        )->with(
            'success',
            'Status pengajuan berhasil diperbarui.'
        );
    }

    /**
     * Menetapkan atau menghapus Sales penanggung jawab.
     */
    public function assignSales(
        Request $request,
        PurchaseApplication $application
    ): RedirectResponse {
        abort_unless(
            $request->user('web')?->role === 'admin',
            403
        );

        $validated = $request->validate([
            'sales_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where('role', 'sales'),
            ],
        ]);

        $application->sales_id = $validated['sales_id'] ?? null;
        $application->save();

        $message = $application->sales_id !== null
            ? 'Sales penanggung jawab berhasil ditetapkan.'
            : 'Penugasan Sales berhasil dihapus.';

        return to_route(
            'admin.purchase-applications.show',
            $application
        )->with('success', $message);
    }
}
