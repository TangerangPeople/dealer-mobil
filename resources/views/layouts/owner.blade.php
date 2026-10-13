<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard Owner') | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f5f5f5] text-[#262626] antialiased">

<div class="min-h-screen lg:grid lg:grid-cols-[250px_minmax(0,1fr)]">
    <aside class="flex flex-col bg-[#262626] text-white">
        <div class="border-b border-white/15 px-6 py-6">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#c5c5c5]">
                Dealer Mobil
            </p>
            <h1 class="mt-2 text-xl font-medium">{{ config('app.name') }}</h1>
            <p class="mt-4 text-xs uppercase tracking-[0.16em] text-[#c5c5c5]">
                Portal Owner
            </p>
        </div>

        <nav class="flex-1 space-y-1 p-4" aria-label="Menu Owner">
            <a href="{{ route('owner.dashboard') }}"
               class="block border-l-2 border-white bg-white/10 px-4 py-3 text-sm font-semibold">
                Ringkasan Bisnis
            </a>

            <a href="{{ route('admin.sales-report.index') }}"
               class="block border-l-2 border-transparent px-4 py-3 text-sm text-[#dedede] hover:bg-white/10">
                Laporan Penjualan
            </a>
        </nav>

        <div class="border-t border-white/15 px-4 py-5">
            <p class="text-sm font-medium">{{ auth('web')->user()?->name }}</p>
            <p class="mt-1 text-xs text-[#c5c5c5]">Owner</p>

            <form method="POST" action="{{ route('admin.logout') }}" class="mt-4">
                @csrf
                <button type="submit"
                        class="min-h-10 w-full border border-white/40 px-4 py-2 text-xs font-semibold uppercase tracking-wider hover:bg-white hover:text-[#262626]">
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <div class="min-w-0">
        <header class="flex min-h-16 items-center justify-between border-b border-black/10 bg-white px-5 sm:px-8">
            <p class="text-sm text-[#666666]">
                Owner / @yield('title', 'Dashboard')
            </p>
            <p class="hidden text-xs text-[#777777] sm:block">
                {{ now()->format('d/m/Y') }}
            </p>
        </header>

        <main class="px-4 py-6 sm:px-8 sm:py-8">
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
