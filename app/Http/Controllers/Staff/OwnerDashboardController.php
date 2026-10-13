<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\PurchaseApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class OwnerDashboardController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(
            $request->user('web')?->role === 'owner',
            403
        );

        $monthStart = now()->startOfMonth();
        $nextMonthStart = (clone $monthStart)->addMonth();
        $previousMonthStart = (clone $monthStart)->subMonth();

        $completedSales = PurchaseApplication::query()
            ->where('status', 'completed');

        $thisMonthSales = (clone $completedSales)
            ->where('updated_at', '>=', $monthStart)
            ->where('updated_at', '<', $nextMonthStart);

        $previousMonthSales = (clone $completedSales)
            ->where('updated_at', '>=', $previousMonthStart)
            ->where('updated_at', '<', $monthStart);

        $totalApplications = PurchaseApplication::query()->count();

        $completedSalesCount = (clone $completedSales)->count();

        $completionRate = $totalApplications > 0
            ? round(($completedSalesCount / $totalApplications) * 100, 1)
            : 0;

        $applicationStatusSummary = PurchaseApplication::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->orderBy('status')
            ->get();

        /*
         * Statistik penjualan enam bulan terakhir.
         * Bulan tanpa transaksi tetap ditampilkan dengan nilai 0.
         */
        $salesChartLabels = [];
        $salesChartData = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->startOfMonth()->subMonths($i);
            $nextMonth = (clone $month)->addMonth();

            $salesChartLabels[] = $month->translatedFormat('M Y');

            $salesChartData[] = (clone $completedSales)
                ->where('updated_at', '>=', $month)
                ->where('updated_at', '<', $nextMonth)
                ->count();
        }

        $statusChartLabels = $applicationStatusSummary
            ->map(fn ($item) => (string) $item->status)
            ->values()
            ->all();

        $statusChartData = $applicationStatusSummary
            ->map(fn ($item) => (int) $item->total)
            ->values()
            ->all();

        return view('owner.dashboard', [
            'totalApplications' => $totalApplications,
            'completedSalesCount' => $completedSalesCount,
            'completedSalesValue' => (clone $completedSales)->sum('vehicle_price'),
            'completionRate' => $completionRate,

            'thisMonthSalesCount' => (clone $thisMonthSales)->count(),
            'thisMonthSalesValue' => (clone $thisMonthSales)->sum('vehicle_price'),

            'previousMonthSalesCount' => (clone $previousMonthSales)->count(),
            'previousMonthSalesValue' => (clone $previousMonthSales)->sum('vehicle_price'),

            'applicationStatusSummary' => $applicationStatusSummary,

            'salesChartLabels' => $salesChartLabels,
            'salesChartData' => $salesChartData,
            'statusChartLabels' => $statusChartLabels,
            'statusChartData' => $statusChartData,
        ]);
    }
}

