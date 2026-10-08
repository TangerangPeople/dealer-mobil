<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\DealerSetting;
use App\Models\PurchaseApplication;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PurchaseApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $applications = $request->user('customer')
            ->purchaseApplications()
            ->with('vehicle')
            ->latest()
            ->paginate(10);

        return view('customer.purchase-applications.index', [
            'applications' => $applications,
            'settings' => DealerSetting::query()->first(),
        ]);
    }

    public function show(Request $request, PurchaseApplication $application): View
    {
        $application = $request->user('customer')
            ->purchaseApplications()
            ->with('vehicle', 'documents')
            ->whereKey($application->getKey())
            ->firstOrFail();

        return view('customer.purchase-applications.show', [
            'application' => $application,
            'settings' => DealerSetting::query()->first(),
        ]);
    }
}
