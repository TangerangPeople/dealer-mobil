<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseApplication;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalesReportController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(
            in_array($request->user('web')?->role, ['admin', 'owner'], true),
            403
        );

        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $search = trim((string) ($filters['q'] ?? ''));
        $from = $filters['from'] ?? null;
        $to = $filters['to'] ?? null;

        $completedQuery = fn (): Builder => PurchaseApplication::query()
            ->where('status', 'completed');

        $filteredQuery = $completedQuery();

        if ($search !== '') {
            $filteredQuery->where(function (Builder $query) use ($search): void {
                $query
                    ->where('application_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('vehicle_name', 'like', "%{$search}%")
                    ->orWhere('vehicle_brand', 'like', "%{$search}%");
            });
        }

        if ($from !== null) {
            $filteredQuery->whereDate('updated_at', '>=', $from);
        }

        if ($to !== null) {
            $filteredQuery->whereDate('updated_at', '<=', $to);
        }

        $applications = $filteredQuery
            ->orderByDesc('updated_at')
            ->paginate(15)
            ->withQueryString();

        $totalSales = $completedQuery()->count();

        $totalSalesValue = $completedQuery()->sum('vehicle_price');

        $monthStart = now()->startOfMonth()->toDateString();

        $salesThisMonth = $completedQuery()
            ->whereDate('updated_at', '>=', $monthStart)
            ->count();

        $salesValueThisMonth = $completedQuery()
            ->whereDate('updated_at', '>=', $monthStart)
            ->sum('vehicle_price');

        return view('admin.sales-report.index', [
            'applications' => $applications,
            'totalSales' => $totalSales,
            'totalSalesValue' => $totalSalesValue,
            'salesThisMonth' => $salesThisMonth,
            'salesValueThisMonth' => $salesValueThisMonth,
            'filters' => $filters,
        ]);
    }
}
