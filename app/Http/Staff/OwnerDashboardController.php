<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\PurchaseApplication;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OwnerDashboardController extends Controller
{
    public function index(Request $request): View
    {
        // Hanya Owner yang boleh mengakses dashboard ini.
        abort_unless(
            $request->user('web')?->role === 'owner',
            403
        );

        $monthStart = now()->startOfMonth();
        $nextMonthStart = (clone $monthStart)->addMonth();
        $previousMonthStart = (clone $monthStart)->subMonth();

        // Data pengajuan yang sudah selesai.
        $completedSales = PurchaseApplication::query()
            ->where('status', 'completed');

        // Penjualan bulan ini.
        $thisMonthSales = (clone $completedSales)
            ->where('updated_at', '>=', $monthStart)
            ->where('updated_at', '<', $nextMonthStart);

        // Penjualan bulan sebelumnya.
        $previousMonthSales = (clone $completedSales)
            ->where('updated_at', '>=', $previousMonthStart)
            ->where('updated_at', '<', $monthStart);

        // Statistik utama.
        $totalApplications = PurchaseApplication::query()->count();

        $completedSalesCount = (clone $completedSales)->count();

        $completionRate = $totalApplications > 0
            ? round(
                ($completedSalesCount / $totalApplications) * 100,
                1
            )
            : 0;

        // Ringkasan seluruh status pengajuan.
        $applicationStatusSummary = PurchaseApplication::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->orderBy('status')
            ->get();

        /*
         * Data diagram batang:
         * jumlah transaksi completed dalam enam bulan terakhir.
         */
        $salesChartLabels = [];
        $salesChartData = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = (clone $monthStart)->subMonths($i);
            $nextMonth = (clone $month)->addMonth();

            $salesChartLabels[] = $month->translatedFormat('M Y');

            $salesChartData[] = (clone $completedSales)
                ->where('updated_at', '>=', $month)
                ->where('updated_at', '<', $nextMonth)
                ->count();
        }

        /*
         * Data diagram donat:
         * jumlah pengajuan untuk setiap status.
         */
        $statusChartLabels = $applicationStatusSummary
            ->map(
                fn ($item) => ucfirst(
                    str_replace('_', ' ', (string) $item->status)
                )
            )
            ->values()
            ->all();

        $statusChartData = $applicationStatusSummary
            ->map(fn ($item) => (int) $item->total)
            ->values()
            ->all();

        // Kirim seluruh data ke halaman dashboard Owner.
        return view('owner.dashboard', [
            // Ringkasan bisnis.
            'totalApplications' => $totalApplications,
            'completedSalesCount' => $completedSalesCount,
            'completedSalesValue' => (clone $completedSales)
                ->sum('vehicle_price'),
            'completionRate' => $completionRate,

            // Perbandingan bulanan.
            'thisMonthSalesCount' => (clone $thisMonthSales)->count(),
            'thisMonthSalesValue' => (clone $thisMonthSales)
                ->sum('vehicle_price'),
            'previousMonthSalesCount' => (clone $previousMonthSales)->count(),
            'previousMonthSalesValue' => (clone $previousMonthSales)
                ->sum('vehicle_price'),

            // Tabel status pengajuan.
            'applicationStatusSummary' => $applicationStatusSummary,

            // Data diagram batang.
            'salesChartLabels' => $salesChartLabels,
            'salesChartData' => $salesChartData,

            // Data diagram donat.
            'statusChartLabels' => $statusChartLabels,
            'statusChartData' => $statusChartData,
        ]);
    }
}
