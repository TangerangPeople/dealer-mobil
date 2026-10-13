@extends('layouts.sales')

@section('title', 'Dashboard Sales')

@section('content')
<div class="mx-auto max-w-7xl space-y-8">
    <header>
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#777777]">
            Area penjualan
        </p>
        <h1 class="mt-2 text-3xl font-light tracking-tight sm:text-4xl">
            Dashboard Sales
        </h1>
        <p class="mt-3 text-sm leading-6 text-[#666666]">
            Informasi kendaraan dan promo untuk membantu pelayanan calon pelanggan.
        </p>
    </header>

    <section class="grid gap-4 sm:grid-cols-2">
        <article class="border border-black/10 bg-white p-5 sm:p-6">
            <p class="text-sm text-[#666666]">Kendaraan Tersedia</p>
            <p class="mt-3 text-3xl font-light">{{ number_format($availableVehicles, 0, ',', '.') }}</p>
            <a href="{{ route('vehicles.index') }}"
               class="mt-4 inline-block text-sm font-medium underline underline-offset-4">
                Buka katalog kendaraan
            </a>
        </article>

        <article class="border border-black/10 bg-white p-5 sm:p-6">
            <p class="text-sm text-[#666666]">Promo Aktif</p>
            <p class="mt-3 text-3xl font-light">{{ number_format($activePromotions, 0, ',', '.') }}</p>
            <a href="{{ route('promotions.index') }}"
               class="mt-4 inline-block text-sm font-medium underline underline-offset-4">
                Lihat promo
            </a>
        </article>
    </section>

    <section class="border border-black/10 bg-white p-5 sm:p-6">
        <h2 class="text-lg font-medium">Peralatan Kerja Sales</h2>
        <p class="mt-2 text-sm leading-7 text-[#666666]">
            Gunakan katalog kendaraan dan informasi promo saat melayani calon pelanggan.
            Daftar prospek pribadi belum ditampilkan karena sistem penugasan
            pengajuan pembelian kepada akun Sales belum tersedia.
        </p>

        <div class="mt-4 flex flex-wrap gap-3">
            <a href="{{ route('vehicles.index') }}"
               class="inline-flex min-h-11 items-center justify-center bg-[#262626] px-5 py-3 text-xs font-semibold uppercase tracking-wider text-white hover:bg-[#444444]">
                Katalog Kendaraan
            </a>

            <a href="{{ route('promotions.index') }}"
               class="inline-flex min-h-11 items-center justify-center border border-[#262626] px-5 py-3 text-xs font-semibold uppercase tracking-wider hover:bg-[#f1f1f1]">
                Promo Dealer
            </a>

            <a href="{{ route('contact') }}"
               class="inline-flex min-h-11 items-center justify-center border border-[#262626] px-5 py-3 text-xs font-semibold uppercase tracking-wider hover:bg-[#f1f1f1]">
                Kontak Dealer
            </a>
        </div>
    </section>
</div>
@endsection
