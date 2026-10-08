<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePurchaseApplicationStatusRequest;
use App\Models\PurchaseApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PurchaseApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $applications = PurchaseApplication::query()
            ->with(['user', 'vehicle'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->query('status')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.purchase-applications.index', compact('applications'));
    }

    public function show(PurchaseApplication $application): View
    {
        $application->load(['user', 'vehicle', 'documents']);

        return view('admin.purchase-applications.show', compact('application'));
    }

    public function updateStatus(UpdatePurchaseApplicationStatusRequest $request, PurchaseApplication $application): RedirectResponse
    {
        $application->update(['status' => $request->validated('status')]);

        return to_route('admin.purchase-applications.show', $application)->with('success', 'Status pengajuan berhasil diperbarui.');
    }
}
