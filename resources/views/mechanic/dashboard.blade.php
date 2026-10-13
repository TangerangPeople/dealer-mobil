@extends('layouts.mechanic')

@section('title', 'Dashboard Mekanik')

@section('content')
<div class="mx-auto max-w-7xl space-y-8">
    <header class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#777777]">
                Area bengkel
            </p>
            <h1 class="mt-2 text-3xl font-light tracking-tight sm:text-4xl">
                Dashboard Mekanik
            </h1>
            <p class="mt-3 text-sm leading-6 text-[#666666]">
                Pantau booking dan pekerjaan servis yang ditugaskan kepada Anda.
            </p>
        </div>

        <a href="{{ route('admin.mechanic.bookings.index') }}"
           class="inline-flex min-h-11 items-center justify-center bg-[#262626] px-5 py-3 text-xs font-semibold uppercase tracking-wider text-white hover:bg-[#444444]">
            Booking Servis Saya
        </a>
    </header>

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <article class="border border-black/10 bg-white p-5">
            <p class="text-sm text-[#666666]">Total Booking Ditugaskan</p>
            <p class="mt-3 text-3xl font-light">{{ number_format($totalAssigned, 0, ',', '.') }}</p>
        </article>

        <article class="border border-black/10 bg-white p-5">
            <p class="text-sm text-[#666666]">Menunggu</p>
            <p class="mt-3 text-3xl font-light">{{ number_format($pendingBookings, 0, ',', '.') }}</p>
        </article>

        <article class="border border-black/10 bg-white p-5">
            <p class="text-sm text-[#666666]">Dalam Penanganan</p>
            <p class="mt-3 text-3xl font-light">{{ number_format($activeBookings, 0, ',', '.') }}</p>
        </article>

        <article class="border border-black/10 bg-white p-5">
            <p class="text-sm text-[#666666]">Selesai</p>
            <p class="mt-3 text-3xl font-light">{{ number_format($completedBookings, 0, ',', '.') }}</p>
        </article>
    </section>

    <section class="overflow-hidden border border-black/10 bg-white">
        <div class="border-b border-black/10 px-5 py-5">
            <h2 class="text-lg font-medium">Booking dan Pekerjaan Berikutnya</h2>
            <p class="mt-1 text-sm text-[#666666]">
                Halaman ini hanya menampilkan booking yang ditugaskan kepada akun Anda.
            </p>
        </div>

        <div class="divide-y divide-black/10">
            @forelse ($upcomingBookings as $booking)
                <article class="flex flex-col gap-4 px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wider text-[#777777]">
                            Booking #{{ $booking->id }}
                        </p>

                        <h3 class="mt-2 font-medium">
                            {{ $booking->customer_name }}
                        </h3>

                        <p class="mt-1 text-sm text-[#666666]">
                            {{ $booking->vehicle?->name ?? 'Kendaraan pelanggan' }}
                            · {{ $booking->vehicle_plate ?: '-' }}
                        </p>

                        <p class="mt-1 text-sm text-[#666666]">
                            {{ $booking->servicePackage?->name ?? $booking->service_type ?? '-' }}
                        </p>

                        <p class="mt-2 text-xs text-[#777777]">
                            {{ $booking->preferred_date?->format('d/m/Y') ?? '-' }}
                            · {{ $booking->preferred_time ?: '-' }}
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="border border-black/15 px-3 py-2 text-xs">
                            {{ ucfirst($booking->status) }}
                        </span>

                        <a href="{{ route('admin.mechanic.bookings.index') }}"
                           class="text-sm font-medium underline underline-offset-4">
                            Buka pekerjaan
                        </a>
                    </div>
                </article>
            @empty
                <div class="px-5 py-12 text-center">
                    <h3 class="font-medium">Belum ada booking yang ditugaskan.</h3>
                    <p class="mt-2 text-sm text-[#777777]">
                        Booking akan muncul setelah Admin memberikan penugasan.
                    </p>
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection
