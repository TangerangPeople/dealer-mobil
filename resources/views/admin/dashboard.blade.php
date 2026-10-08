@extends('layouts.admin')

@section('title', 'Dashboard Administrator')

@section('content')
    <div class="max-w-6xl">
        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#262626]">Ringkasan</p>
        <h1 class="mt-3 text-3xl font-light leading-tight sm:text-4xl">Dashboard Administrator</h1>
        <p class="mt-5 max-w-2xl text-base leading-7 text-[#262626]">Kelola informasi dealer, katalog mobil, promo, sales, galeri, paket servis, dan booking secara terintegrasi dengan halaman public.</p>

        <section class="mt-10 grid gap-4 sm:grid-cols-2 xl:grid-cols-3" aria-label="Ringkasan data website">
            <article class="border border-[#bbbbbb] bg-white p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-[#262626]">Total kendaraan</p>
                <p class="mt-4 text-3xl font-light text-[#262626]">{{ $totalVehicles }}</p>
            </article>

            <article class="border border-[#bbbbbb] bg-white p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-[#262626]">Kendaraan tersedia</p>
                <p class="mt-4 text-3xl font-light text-[#262626]">{{ $availableVehicles }}</p>
            </article>

            <article class="border border-[#bbbbbb] bg-white p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-[#262626]">Promo aktif</p>
                <p class="mt-4 text-3xl font-light text-[#262626]">{{ $activePromos }}</p>
            </article>

            <article class="border border-[#bbbbbb] bg-white p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-[#262626]">Sales aktif</p>
                <p class="mt-4 text-3xl font-light text-[#262626]">{{ $activeSales }}</p>
            </article>

            <article class="border border-[#bbbbbb] bg-white p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-[#262626]">Paket servis aktif</p>
                <p class="mt-4 text-3xl font-light text-[#262626]">{{ $activeServicePackages }}</p>
            </article>

            <article class="border border-[#bbbbbb] bg-white p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-[#262626]">Booking pending</p>
                <p class="mt-4 text-3xl font-light text-[#262626]">{{ $pendingBookings }}</p>
            </article>
        </section>

        <section class="mt-10 border border-[#bbbbbb] bg-white p-6 sm:p-8" aria-labelledby="admin-access-heading">
            <h2 id="admin-access-heading" class="text-xl font-semibold">Akses administrator terverifikasi</h2>
            <p class="mt-3 text-base leading-7 text-[#262626]">Anda berhasil masuk dengan akun yang memiliki izin administrator. Semua modul utama untuk pengelolaan konten website sudah tersedia pada navigasi admin.</p>

            <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                <a href="{{ route('admin.categories.index') }}" class="border border-[#262626] bg-[#262626] px-4 py-3 text-center text-sm font-semibold text-white transition hover:bg-white hover:text-[#262626]">Kategori</a>
                <a href="{{ route('admin.vehicles.index') }}" class="border border-[#262626] bg-[#262626] px-4 py-3 text-center text-sm font-semibold text-white transition hover:bg-white hover:text-[#262626]">Kendaraan</a>
                <a href="{{ route('admin.promotions.index') }}" class="border border-[#262626] bg-[#262626] px-4 py-3 text-center text-sm font-semibold text-white transition hover:bg-white hover:text-[#262626]">Promo</a>
                <a href="{{ route('admin.sales.index') }}" class="border border-[#262626] bg-[#262626] px-4 py-3 text-center text-sm font-semibold text-white transition hover:bg-white hover:text-[#262626]">Sales</a>
                <a href="{{ route('admin.galleries.index') }}" class="border border-[#262626] bg-[#262626] px-4 py-3 text-center text-sm font-semibold text-white transition hover:bg-white hover:text-[#262626]">Galeri</a>
                <a href="{{ route('admin.dealer-settings.index') }}" class="border border-[#262626] bg-[#262626] px-4 py-3 text-center text-sm font-semibold text-white transition hover:bg-white hover:text-[#262626]">Dealer</a>
            </div>
        </section>
    </div>
@endsection
