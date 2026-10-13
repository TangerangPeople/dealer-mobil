
@extends('layouts.owner')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="mx-auto max-w-7xl space-y-8">

    {{-- Header --}}
    <header class="border-b border-black/10 pb-6">
        <a href="{{ route('owner.dashboard') }}"
           class="inline-flex items-center gap-2 text-sm text-[#666666] hover:text-black">
            <span aria-hidden="true">&larr;</span>
            Kembali ke Dashboard Owner
        </a>

        <p class="mt-6 text-xs font-semibold uppercase tracking-[0.18em] text-[#737373]">
            Laporan bisnis
        </p>

        <h1 class="mt-2 text-3xl font-light tracking-tight sm:text-4xl">
            Laporan Penjualan
        </h1>

        <p class="mt-3 max-w-2xl text-sm leading-6 text-[#666666]">
            Pantau transaksi pembelian kendaraan yang telah selesai,
            nilai penjualan, dan riwayat transaksi berdasarkan periode.
        </p>
    </header>

    {{-- Ringkasan --}}
    <section>
        <h2 class="mb-4 text-lg font-medium">Ringkasan Penjualan</h2>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">

            <article class="border border-black/10 bg-white p-5">
                <p class="text-sm text-[#666666]">
                    Total Transaksi Selesai
                </p>

                <p class="mt-3 text-3xl font-light tabular-nums">
                    {{ number_format($totalSales, 0, ',', '.') }}
                </p>

                <p class="mt-2 text-xs text-[#777777]">
                    Seluruh periode
                </p>
            </article>

            <article class="border border-black/10 bg-white p-5">
                <p class="text-sm text-[#666666]">
                    Total Nilai Penjualan
                </p>

                <p class="mt-3 break-words text-2xl font-light">
                    Rp {{ number_format((float) $totalSalesValue, 0, ',', '.') }}
                </p>

                <p class="mt-2 text-xs text-[#777777]">
                    Berdasarkan harga kendaraan
                </p>
            </article>

            <article class="border border-black/10 bg-white p-5">
                <p class="text-sm text-[#666666]">
                    Transaksi Bulan Ini
                </p>

                <p class="mt-3 text-3xl font-light tabular-nums">
                    {{ number_format($salesThisMonth, 0, ',', '.') }}
                </p>

                <p class="mt-2 text-xs text-[#777777]">
                    Pengajuan berstatus selesai
                </p>
            </article>

            <article class="border border-black/10 bg-white p-5">
                <p class="text-sm text-[#666666]">
                    Nilai Penjualan Bulan Ini
                </p>

                <p class="mt-3 break-words text-2xl font-light">
                    Rp {{ number_format((float) $salesValueThisMonth, 0, ',', '.') }}
                </p>

                <p class="mt-2 text-xs text-[#777777]">
                    Bulan berjalan
                </p>
            </article>

        </div>
    </section>

    {{-- Filter laporan --}}
    <section class="border border-black/10 bg-white p-5 sm:p-6">
        <div class="mb-5">
            <h2 class="text-lg font-medium">Filter Laporan</h2>
            <p class="mt-1 text-sm text-[#666666]">
                Cari transaksi berdasarkan nomor pengajuan, pelanggan,
                atau kendaraan.
            </p>
        </div>

        <form action="{{ route('admin.sales-report.index') }}"
              method="GET"
              class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">

            <div>
                <label for="q"
                       class="mb-2 block text-sm font-medium text-[#444444]">
                    Kata kunci
                </label>

                <input type="text"
                       id="q"
                       name="q"
                       value="{{ $filters['q'] ?? '' }}"
                       maxlength="100"
                       placeholder="Nomor, pelanggan, kendaraan"
                       class="min-h-11 w-full border border-[#cccccc] bg-white px-3 py-2 text-sm focus:border-[#555555] focus:outline-none">
            </div>

            <div>
                <label for="from"
                       class="mb-2 block text-sm font-medium text-[#444444]">
                    Dari tanggal
                </label>

                <input type="date"
                       id="from"
                       name="from"
                       value="{{ $filters['from'] ?? '' }}"
                       class="min-h-11 w-full border border-[#cccccc] bg-white px-3 py-2 text-sm focus:border-[#555555] focus:outline-none">
            </div>

            <div>
                <label for="to"
                       class="mb-2 block text-sm font-medium text-[#444444]">
                    Sampai tanggal
                </label>

                <input type="date"
                       id="to"
                       name="to"
                       value="{{ $filters['to'] ?? '' }}"
                       min="{{ $filters['from'] ?? '' }}"
                       class="min-h-11 w-full border border-[#cccccc] bg-white px-3 py-2 text-sm focus:border-[#555555] focus:outline-none">
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                        class="min-h-11 flex-1 bg-[#262626] px-4 py-2 text-sm font-semibold text-white hover:bg-[#444444]">
                    Terapkan
                </button>

                <a href="{{ route('admin.sales-report.index') }}"
                   class="inline-flex min-h-11 items-center justify-center border border-[#cccccc] px-4 py-2 text-sm hover:bg-[#f5f5f5]">
                    Reset
                </a>
            </div>
        </form>
    </section>

    {{-- Tabel transaksi --}}
    <section class="border border-black/10 bg-white">

        <div class="flex flex-col gap-2 border-b border-black/10 p-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-medium">
                    Riwayat Transaksi
                </h2>

                <p class="mt-1 text-sm text-[#666666]">
                    Daftar pengajuan pembelian dengan status completed.
                </p>
            </div>

            <p class="text-sm text-[#666666]">
                {{ number_format($applications->total(), 0, ',', '.') }}
                transaksi ditemukan
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-left text-sm">
                <thead class="bg-[#f5f5f5] text-[#666666]">
                    <tr>
                        <th class="px-5 py-4 font-medium">No.</th>
                        <th class="px-5 py-4 font-medium">Nomor Pengajuan</th>
                        <th class="px-5 py-4 font-medium">Pelanggan</th>
                        <th class="px-5 py-4 font-medium">Kendaraan</th>
                        <th class="px-5 py-4 text-right font-medium">Nilai Penjualan</th>
                        <th class="px-5 py-4 font-medium">Tanggal Perubahan</th>
                        <th class="px-5 py-4 font-medium">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-black/10">
                    @forelse ($applications as $application)
                        <tr class="hover:bg-[#fafafa]">
                            <td class="whitespace-nowrap px-5 py-4 text-[#666666]">
                                {{ $applications->firstItem() + $loop->index }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 font-medium">
                                {{ $application->application_number }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $application->customer_name }}
                            </td>

                            <td class="px-5 py-4">
                                <p class="font-medium">
                                    {{ $application->vehicle_name }}
                                </p>

                                <p class="mt-1 text-xs text-[#777777]">
                                    {{ $application->vehicle_brand }}
                                </p>
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-right tabular-nums">
                                Rp {{ number_format((float) $application->vehicle_price, 0, ',', '.') }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-[#666666]">
                                {{ $application->updated_at?->format('d/m/Y H:i') ?? '-' }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                <span class="inline-flex border border-[#bdbdbd] px-2 py-1 text-xs font-medium">
                                    Selesai
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7"
                                class="px-5 py-12 text-center text-[#777777]">
                                <p class="font-medium text-[#444444]">
                                    Tidak ada transaksi ditemukan.
                                </p>

                                <p class="mt-2 text-sm">
                                    Coba ubah kata kunci atau rentang tanggal filter.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($applications->hasPages())
            <div class="border-t border-black/10 px-5 py-4">
                {{ $applications->links() }}
            </div>
        @endif

    </section>

    {{-- Catatan laporan --}}
    <section class="border border-black/10 p-5">
        <h2 class="text-sm font-semibold">Catatan Laporan</h2>

        <p class="mt-2 text-sm leading-6 text-[#666666]">
            Laporan ini hanya menghitung pengajuan dengan status
            <span class="font-medium text-[#333333]">completed</span>.
            Nilai penjualan menggunakan harga kendaraan pada pengajuan,
            bukan laba bersih atau konfirmasi pembayaran.
        </p>

        <p class="mt-2 text-sm leading-6 text-[#666666]">
            Filter tanggal dan kolom tanggal perubahan sementara menggunakan
            <code>updated_at</code>. Apabila data pengajuan diperbarui kembali,
            tanggal yang ditampilkan juga dapat berubah.
        </p>
    </section>

</div>
@endsection
