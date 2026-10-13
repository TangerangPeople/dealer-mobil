<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#ffffff">
    <title>@yield('title', 'Dashboard') | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-[#f5f5f5] text-[#262626] antialiased">
    @php
        $user = auth()->user();
        $roleLabels = [
            'owner' => 'Owner',
            'sales' => 'Sales',
            'mechanic' => 'Mekanik',
        ];
        $roleLabel = $roleLabels[$user?->role ?? ''] ?? 'Staf';
    @endphp

    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:bg-white focus:p-3">
        Lewati ke konten utama
    </a>

    <div class="min-h-screen lg:grid lg:grid-cols-[250px_minmax(0,1fr)]">
        <aside class="flex flex-col bg-[#262626] text-white">
            <div class="border-b border-white/15 px-6 py-6">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#c5c5c5]">Dealer Mobil</p>
                <h1 class="mt-2 text-xl font-medium">{{ config('app.name') }}</h1>
                <p class="mt-4 text-xs uppercase tracking-[0.16em] text-[#c5c5c5]">Panel {{ $roleLabel }}</p>
            </div>

            <nav class="flex-1 space-y-1 p-4" aria-label="Menu staf">
                @if ($user?->role === 'owner')
                    <a href="{{ route('owner.dashboard') }}" class="block border-l-2 border-white bg-white/10 px-4 py-3 text-sm font-semibold">Dashboard Owner</a>
                    <a href="{{ route('admin.sales-report.index') }}" class="block border-l-2 border-transparent px-4 py-3 text-sm text-[#dedede] hover:bg-white/10 hover:text-white">Laporan Penjualan</a>
                    <a href="{{ route('admin.spare-parts.index') }}" class="block border-l-2 border-transparent px-4 py-3 text-sm text-[#dedede] hover:bg-white/10 hover:text-white">Inventaris Sparepart</a>
                @endif

                @if ($user?->role === 'sales')
                    <a href="{{ route('sales.dashboard') }}" class="block border-l-2 border-white bg-white/10 px-4 py-3 text-sm font-semibold">Dashboard Sales</a>
                    <a href="{{ route('vehicles.index') }}" class="block border-l-2 border-transparent px-4 py-3 text-sm text-[#dedede] hover:bg-white/10 hover:text-white">Katalog Kendaraan</a>
                    <a href="{{ route('promotions.index') }}" class="block border-l-2 border-transparent px-4 py-3 text-sm text-[#dedede] hover:bg-white/10 hover:text-white">Promo</a>
                @endif

                @if ($user?->role === 'mechanic')
                    <a href="{{ route('mechanic.dashboard') }}" class="block border-l-2 border-white bg-white/10 px-4 py-3 text-sm font-semibold">Dashboard Mekanik</a>
                    <a href="{{ route('admin.mechanic.bookings.index') }}" class="block border-l-2 border-transparent px-4 py-3 text-sm text-[#dedede] hover:bg-white/10 hover:text-white">Booking Servis Saya</a>
                @endif
            </nav>

            <div class="border-t border-white/15 px-4 py-5">
                <p class="text-sm font-medium">{{ $user?->name }}</p>
                <p class="mt-1 text-xs text-[#c5c5c5]">{{ $roleLabel }}</p>
                <form method="POST" action="{{ route('admin.logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="min-h-10 w-full border border-white/40 px-4 py-2 text-xs font-semibold uppercase tracking-wider hover:bg-white hover:text-[#262626]">Keluar</button>
                </form>
            </div>
        </aside>

        <div class="min-w-0">
            <header class="flex min-h-16 items-center justify-between border-b border-black/10 bg-white px-5 sm:px-8">
                <p class="text-sm text-[#666666]">{{ $roleLabel }} / @yield('title', 'Dashboard')</p>
                <p class="hidden text-xs text-[#777777] sm:block">{{ now()->format('d/m/Y') }}</p>
            </header>
            <main id="main-content" class="px-4 py-6 sm:px-8 sm:py-8">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
