@extends('layouts.admin')

@section('title', 'Dashboard Administrator')

@section('content')
<div class="mx-auto max-w-7xl space-y-8">

    <header class="border-b border-black/10 pb-6">
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#737373]">
            Panel operasional dealer
        </p>

        <h1 class="mt-2 text-3xl font-light tracking-tight sm:text-4xl">
            Dashboard Administrator
        </h1>

        <p class="mt-3 text-sm leading-6 text-[#666666]">
            Selamat datang, {{ auth('web')->user()?->name }}.
            Kelola data dan aktivitas operasional dealer.
        </p>
    </header>

    <section>
        <h2 class="mb-4 text-lg font-medium">Inventaris Kendaraan</h2>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="border border-black/10 bg-white p-5">
                <p class="text-sm text-[#666666]">Total Kendaraan</p>
                <p class="mt-3 text-3xl font-light">{{ number_format($totalVehicles, 0, ',', '.') }}</p>
                <a href="{{ route('admin.vehicles.index') }}"
                   class="mt-4 inline-block text-sm underline underline-offset-4">
                    Kelola kendaraan
                </a>
            </article>

            <article class="border border-black/10 bg-white p-5">
                <p class="text-sm text-[#666666]">Tersedia</p>
                <p class="mt-3 text-3xl font-light">{{ number_format($availableVehicles, 0, ',', '.') }}</p>
            </article>

            <article class="border border-black/10 bg-white p-5">
                <p class="text-sm text-[#666666]">Terjual</p>
                <p class="mt-3 text-3xl font-light">{{ number_format($soldVehicles, 0, ',', '.') }}</p>
            </article>

            <article class="border border-black/10 bg-white p-5">
                <p class="text-sm text-[#666666]">Dipesan</p>
                <p class="mt-3 text-3xl font-light">{{ number_format($reservedVehicles, 0, ',', '.') }}</p>
            </article>
        </div>
    </section>

    <section>
        <h2 class="mb-4 text-lg font-medium">Pekerjaan yang Perlu Ditindaklanjuti</h2>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            <article class="border border-black/10 bg-white p-5">
                <p class="text-sm text-[#666666]">Pengajuan Pembelian Menunggu</p>
                <p class="mt-3 text-3xl font-light">
                    {{ number_format($pendingPurchaseApplications, 0, ',', '.') }}
                </p>
                <a href="{{ route('admin.purchase-applications.index') }}"
                   class="mt-4 inline-block text-sm underline underline-offset-4">
                    Tinjau pengajuan
                </a>
            </article>

            <article class="border border-black/10 bg-white p-5">
                <p class="text-sm text-[#666666]">Booking Servis Menunggu</p>
                <p class="mt-3 text-3xl font-light">
                    {{ number_format($pendingBookings, 0, ',', '.') }}
                </p>
                <a href="{{ route('admin.service-bookings.index') }}"
                   class="mt-4 inline-block text-sm underline underline-offset-4">
                    Kelola booking
                </a>
            </article>

            <article class="border border-black/10 bg-white p-5">
                <p class="text-sm text-[#666666]">Sparepart Stok Rendah</p>
                <p class="mt-3 text-3xl font-light {{ $lowStockSpareParts > 0 ? 'text-red-700' : 'text-[#262626]' }}">
                    {{ number_format($lowStockSpareParts, 0, ',', '.') }}
                </p>
                <a href="{{ route('admin.spare-parts.index') }}"
                   class="mt-4 inline-block text-sm underline underline-offset-4">
                    Kelola inventaris
                </a>
            </article>
        </div>
    </section>

    <section>
        <h2 class="mb-4 text-lg font-medium">Pengelolaan Website</h2>

        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
            <a href="{{ route('admin.categories.index') }}"
               class="border border-black/10 bg-white p-5 hover:border-[#262626]">
                <h3 class="font-medium">Kategori Kendaraan</h3>
                <p class="mt-2 text-sm text-[#666666]">
                    {{ number_format($totalCategories, 0, ',', '.') }} kategori.
                </p>
            </a>

            <a href="{{ route('admin.promotions.index') }}"
               class="border border-black/10 bg-white p-5 hover:border-[#262626]">
                <h3 class="font-medium">Promosi</h3>
                <p class="mt-2 text-sm text-[#666666]">
                    {{ number_format($activePromos, 0, ',', '.') }} promo aktif.
                </p>
            </a>

            <a href="{{ route('admin.sales.index') }}"
               class="border border-black/10 bg-white p-5 hover:border-[#262626]">
                <h3 class="font-medium">Profil Sales</h3>
                <p class="mt-2 text-sm text-[#666666]">
                    {{ number_format($activeSales, 0, ',', '.') }} profil aktif.
                </p>
            </a>

            <a href="{{ route('admin.service-packages.index') }}"
               class="border border-black/10 bg-white p-5 hover:border-[#262626]">
                <h3 class="font-medium">Paket Servis</h3>
                <p class="mt-2 text-sm text-[#666666]">
                    {{ number_format($activeServicePackages, 0, ',', '.') }} paket aktif.
                </p>
            </a>

            <a href="{{ route('admin.galleries.index') }}"
               class="border border-black/10 bg-white p-5 hover:border-[#262626]">
                <h3 class="font-medium">Galeri Website</h3>
                <p class="mt-2 text-sm text-[#666666]">
                    {{ number_format($totalGalleries, 0, ',', '.') }} item galeri.
                </p>
            </a>

            <a href="{{ route('admin.spare-parts.index') }}"
               class="border border-black/10 bg-white p-5 hover:border-[#262626]">
                <h3 class="font-medium">Persediaan Sparepart</h3>
                <p class="mt-2 text-sm text-[#666666]">
                    {{ number_format($activeSpareParts, 0, ',', '.') }} jenis aktif.
                </p>
            </a>

            <a href="{{ route('admin.service-bookings.assignments') }}"
               class="border border-black/10 bg-white p-5 hover:border-[#262626]">
                <h3 class="font-medium">Penugasan Mekanik</h3>
                <p class="mt-2 text-sm text-[#666666]">
                    Tetapkan mekanik untuk booking servis.
                </p>
            </a>

            <a href="{{ route('admin.dealer-settings.index') }}"
               class="border border-black/10 bg-white p-5 hover:border-[#262626]">
                <h3 class="font-medium">Pengaturan Dealer</h3>
                <p class="mt-2 text-sm text-[#666666]">
                    Perbarui informasi dealer.
                </p>
            </a>
        </div>
    </section>

</div>
@endsection
