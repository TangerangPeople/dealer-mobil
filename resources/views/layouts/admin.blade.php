<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Dashboard Administrator') | {{ config('app.name') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#f1f1f1] font-sans text-[#262626] antialiased">
        <a
            href="#main-content"
            class="sr-only left-4 top-4 z-50 border border-[#262626] bg-white px-4 py-2 text-sm font-semibold focus:not-sr-only focus:absolute"
        >
            Lewati ke konten utama
        </a>

        <div class="min-h-screen lg:grid lg:grid-cols-[17rem_1fr]">
            <aside class="bg-[#262626] px-6 py-7 text-white lg:min-h-screen">
                <div class="flex items-center justify-between gap-4 lg:block">
                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold uppercase tracking-[0.16em] focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-[#262626]">
                        {{ config('app.name') }}
                    </a>
                    <p class="text-xs uppercase tracking-[0.14em] text-[#bbbbbb] lg:mt-3">Administrator</p>
                </div>

                <nav class="mt-8" aria-label="Navigasi administrator">
                    <a href="{{ route('admin.dashboard') }}" @class(['block border-l-2 px-3 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-[#262626]', 'border-white bg-white text-[#262626]' => request()->routeIs('admin.dashboard'), 'border-transparent text-white hover:border-[#bbbbbb]' => ! request()->routeIs('admin.dashboard')])>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.categories.index') }}" @class(['block border-l-2 px-3 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-[#262626]', 'border-white bg-white text-[#262626]' => request()->routeIs('admin.categories.*'), 'border-transparent text-white hover:border-[#bbbbbb]' => ! request()->routeIs('admin.categories.*')])>
                        Kategori Kendaraan
                    </a>
                    <a href="{{ route('admin.vehicles.index') }}" @class(['block border-l-2 px-3 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-[#262626]', 'border-white bg-white text-[#262626]' => request()->routeIs('admin.vehicles.*'), 'border-transparent text-white hover:border-[#bbbbbb]' => ! request()->routeIs('admin.vehicles.*')])>
                        Kendaraan
                    </a>
                    <a href="{{ route('admin.promotions.index') }}" @class(['block border-l-2 px-3 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-[#262626]', 'border-white bg-white text-[#262626]' => request()->routeIs('admin.promotions.*'), 'border-transparent text-white hover:border-[#bbbbbb]' => ! request()->routeIs('admin.promotions.*')])>
                        Promo
                    </a>
                    <a href="{{ route('admin.sales.index') }}" @class(['block border-l-2 px-3 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-[#262626]', 'border-white bg-white text-[#262626]' => request()->routeIs('admin.sales.*'), 'border-transparent text-white hover:border-[#bbbbbb]' => ! request()->routeIs('admin.sales.*')])>
                        Sales
                    </a>
                    <a href="{{ route('admin.galleries.index') }}" @class(['block border-l-2 px-3 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-[#262626]', 'border-white bg-white text-[#262626]' => request()->routeIs('admin.galleries.*'), 'border-transparent text-white hover:border-[#bbbbbb]' => ! request()->routeIs('admin.galleries.*')])>
                        Galeri
                    </a>
                    <a href="{{ route('admin.dealer-settings.index') }}" @class(['block border-l-2 px-3 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-[#262626]', 'border-white bg-white text-[#262626]' => request()->routeIs('admin.dealer-settings.*'), 'border-transparent text-white hover:border-[#bbbbbb]' => ! request()->routeIs('admin.dealer-settings.*')])>
                        Pengaturan Dealer
                    </a>
                    <a href="{{ route('admin.service-packages.index') }}" @class(['block border-l-2 px-3 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-[#262626]', 'border-white bg-white text-[#262626]' => request()->routeIs('admin.service-packages.*'), 'border-transparent text-white hover:border-[#bbbbbb]' => ! request()->routeIs('admin.service-packages.*')])>
                        Paket Servis
                    </a>
                    <a href="{{ route('admin.service-bookings.index') }}" @class(['block border-l-2 px-3 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-[#262626]', 'border-white bg-white text-[#262626]' => request()->routeIs('admin.service-bookings.*'), 'border-transparent text-white hover:border-[#bbbbbb]' => ! request()->routeIs('admin.service-bookings.*')])>
                        Booking Service
                    </a>
                    <a href="{{ route('admin.purchase-applications.index') }}" @class(['block border-l-2 px-3 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-[#262626]', 'border-white bg-white text-[#262626]' => request()->routeIs('admin.purchase-applications.*'), 'border-transparent text-white hover:border-[#bbbbbb]' => ! request()->routeIs('admin.purchase-applications.*')])>
                        Pengajuan Pembelian
                    </a>
                </nav>
            </aside>

            <div class="flex min-h-screen flex-col">
                <header class="flex items-center justify-between gap-4 border-b border-[#bbbbbb] bg-white px-6 py-5 sm:px-10">
                    <p class="text-sm text-[#262626]">{{ auth()->user()->name }}</p>

                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="border border-[#262626] bg-white px-4 py-2 text-sm font-semibold text-[#262626] transition hover:bg-[#262626] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
                            Keluar
                        </button>
                    </form>
                </header>

                <main id="main-content" class="flex-1 px-6 py-10 sm:px-10 lg:px-14">
                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
