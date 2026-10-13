@extends('layouts.owner')

@section('title', 'Dashboard Owner')

@section('content')
<div class="mx-auto max-w-7xl space-y-8">

    <header class="border-b border-black/10 pb-6">
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#737373]">
            Ringkasan eksekutif
        </p>

        <h1 class="mt-2 text-3xl font-light tracking-tight sm:text-4xl">
            Dashboard Owner
        </h1>

        <p class="mt-3 max-w-2xl text-sm leading-6 text-[#666666]">
            Pantau hasil penjualan, perkembangan pengajuan pelanggan,
            dan performa bisnis dealer.
        </p>
    </header>

    <section>
        <h2 class="mb-4 text-lg font-medium">Ikhtisar Bisnis</h2>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="border border-black/10 bg-white p-5">
                <p class="text-sm text-[#666666]">Total Pengajuan</p>
                <p class="mt-3 text-3xl font-light">
                    {{ number_format($totalApplications, 0, ',', '.') }}
                </p>
            </article>

            <article class="border border-black/10 bg-white p-5">
                <p class="text-sm text-[#666666]">Pengajuan Selesai</p>
                <p class="mt-3 text-3xl font-light">
                    {{ number_format($completedSalesCount, 0, ',', '.') }}
                </p>
            </article>

            <article class="border border-black/10 bg-white p-5">
                <p class="text-sm text-[#666666]">Nilai Penjualan Tercatat</p>
                <p class="mt-3 break-words text-2xl font-light">
                    Rp {{ number_format((float) $completedSalesValue, 0, ',', '.') }}
                </p>
            </article>

            <article class="border border-black/10 bg-white p-5">
                <p class="text-sm text-[#666666]">Persentase Pengajuan Selesai</p>
                <p class="mt-3 text-3xl font-light">
                    {{ number_format($completionRate, 1, ',', '.') }}%
                </p>
            </article>
        </div>
    </section>

    <section>
        <h2 class="mb-4 text-lg font-medium">Perbandingan Bulanan</h2>

        <div class="grid gap-4 lg:grid-cols-2">
            <article class="border border-black/10 bg-white p-6">
                <p class="text-xs font-semibold uppercase tracking-wider text-[#777777]">
                    Bulan Ini
                </p>

                <p class="mt-3 text-2xl font-light">
                    {{ number_format($thisMonthSalesCount, 0, ',', '.') }}
                    <span class="text-sm text-[#777777]">pengajuan selesai</span>
                </p>

                <p class="mt-4 text-sm text-[#666666]">Nilai tercatat</p>
                <p class="mt-1 break-words text-2xl font-light">
                    Rp {{ number_format((float) $thisMonthSalesValue, 0, ',', '.') }}
                </p>
            </article>

            <article class="border border-black/10 bg-white p-6">
                <p class="text-xs font-semibold uppercase tracking-wider text-[#777777]">
                    Bulan Sebelumnya
                </p>

                <p class="mt-3 text-2xl font-light">
                    {{ number_format($previousMonthSalesCount, 0, ',', '.') }}
                    <span class="text-sm text-[#777777]">pengajuan selesai</span>
                </p>

                <p class="mt-4 text-sm text-[#666666]">Nilai tercatat</p>
                <p class="mt-1 break-words text-2xl font-light">
                    Rp {{ number_format((float) $previousMonthSalesValue, 0, ',', '.') }}
                </p>
            </article>
        </div>
    </section>

    <section class="border border-black/10 bg-white">
        <div class="border-b border-black/10 p-5">
            <h2 class="text-lg font-medium">Perkembangan Pengajuan Pembelian</h2>
            <p class="mt-1 text-sm text-[#666666]">
                Jumlah pengajuan berdasarkan status yang tercatat.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-[#f5f5f5] text-[#666666]">
                    <tr>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 text-right font-medium">Jumlah</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-black/10">
                    @forelse ($applicationStatusSummary as $status)
                        <tr>
                            <td class="px-5 py-4">
                                {{ ucfirst(str_replace('_', ' ', $status->status)) }}
                            </td>
                            <td class="px-5 py-4 text-right tabular-nums">
                                {{ number_format($status->total, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-5 py-8 text-center text-[#777777]">
                                Belum ada pengajuan pembelian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="border border-black/10 bg-white p-5 sm:p-6">
        <h2 class="text-lg font-medium">Laporan Bisnis</h2>

        <p class="mt-2 text-sm leading-6 text-[#666666]">
            Lihat laporan terperinci untuk meninjau penjualan yang tercatat.
            Dashboard Owner berfungsi untuk pemantauan bisnis, bukan mengubah data operasional.
        </p>

        <a href="{{ route('admin.sales-report.index') }}"
           class="mt-5 inline-flex min-h-11 items-center justify-center bg-[#262626] px-5 py-3 text-xs font-semibold uppercase tracking-wider text-white hover:bg-[#444444]">
            Buka Laporan Penjualan
        </a>

        <p class="mt-4 text-xs leading-5 text-[#777777]">
            Nilai penjualan berdasarkan harga kendaraan pada pengajuan completed,
            bukan laba bersih atau bukti pembayaran. Perhitungan bulanan memakai
            updated_at karena tanggal penyelesaian transaksi tersendiri belum digunakan.
        </p>
    </section>

<section class="mt-8 grid grid-cols-1 gap-6 xl:grid-cols-2">

    {{-- Diagram penjualan enam bulan terakhir --}}
    <div class="border border-[#d8d8d8] bg-white p-5">
        <div class="mb-5">
            <h2 class="text-lg font-semibold text-[#292929]">
                Statistik Penjualan
            </h2>
            <p class="mt-1 text-sm text-[#737373]">
                Jumlah transaksi selesai selama enam bulan terakhir.
            </p>
        </div>

        <div class="relative h-72 w-full">
            <canvas id="ownerSalesChart"></canvas>
        </div>
    </div>

    {{-- Diagram status pengajuan --}}
    <div class="border border-[#d8d8d8] bg-white p-5">
        <div class="mb-5">
            <h2 class="text-lg font-semibold text-[#292929]">
                Status Pengajuan Pembelian
            </h2>
            <p class="mt-1 text-sm text-[#737373]">
                Distribusi seluruh pengajuan pembelian kendaraan.
            </p>
        </div>

        <div class="relative mx-auto h-72 w-full max-w-sm">
            <canvas id="ownerApplicationChart"></canvas>
        </div>

        <div class="mt-4 space-y-2">
            @forelse ($applicationStatusSummary as $item)
                <div class="flex items-center justify-between border-b border-[#eeeeee] py-2 text-sm">
                    <span class="text-[#555555]">
                        {{ \Illuminate\Support\Str::headline($item->status) }}
                    </span>
                    <span class="font-semibold text-[#292929]">
                        {{ number_format($item->total, 0, ',', '.') }}
                    </span>
                </div>
            @empty
                <p class="text-sm text-[#737373]">
                    Belum ada data pengajuan pembelian.
                </p>
            @endforelse
        </div>
    </div>

</section>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const salesCanvas = document.getElementById('ownerSalesChart');
    const applicationCanvas = document.getElementById('ownerApplicationChart');

    if (salesCanvas && typeof Chart !== 'undefined') {
        new Chart(salesCanvas, {
            type: 'bar',
            data: {
                labels: @json($salesChartLabels),
                datasets: [{
                    label: 'Transaksi selesai',
                    data: @json($salesChartData),
                    backgroundColor: '#737373',
                    borderColor: '#404040',
                    borderWidth: 1,
                    borderRadius: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return context.parsed.y + ' transaksi';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }

    if (applicationCanvas && typeof Chart !== 'undefined') {
        new Chart(applicationCanvas, {
            type: 'doughnut',
            data: {
                labels: @json($statusChartLabels),
                datasets: [{
                    data: @json($statusChartData),
                    backgroundColor: [
                        '#737373',
                        '#a3a3a3',
                        '#525252',
                        '#d4d4d4',
                        '#262626',
                        '#999999',
                        '#bdbdbd'
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
});
</script>


</div>
@endsection
