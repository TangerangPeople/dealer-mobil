<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{ $settings->tagline ?? 'Dealer mobil premium' }}">
        <title>@yield('title', $settings->dealer_name ?? config('app.name'))</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-white text-neutral-900 antialiased">
        <header class="border-b border-neutral-200 bg-white/95 backdrop-blur-sm sticky top-0 z-40">
            <div class="mx-auto flex w-full max-w-6xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="text-lg font-semibold tracking-[0.2em] uppercase text-neutral-900">
                    {{ $settings->dealer_name ?? 'Dealer Mobil' }}
                </a>

                <nav class="hidden items-center gap-6 text-sm font-medium text-neutral-700 md:flex">
                    <a href="{{ route('home') }}" class="hover:text-neutral-950">Beranda</a>
                    <a href="{{ route('about') }}" class="hover:text-neutral-950">Tentang</a>
                    <a href="{{ route('vehicles.index') }}" class="hover:text-neutral-950">Mobil</a>
                    <a href="{{ route('promotions.index') }}" class="hover:text-neutral-950">Promo</a>
                    <a href="{{ route('gallery.index') }}" class="hover:text-neutral-950">Galeri</a>
                    <a href="{{ route('contact') }}" class="hover:text-neutral-950">Kontak</a>
                </nav>

                <a href="{{ route('credit-simulation') }}" class="inline-flex items-center border border-neutral-900 px-4 py-2 text-sm font-medium text-neutral-900 transition hover:bg-neutral-900 hover:text-white">
                    Simulasi Kredit
                </a>
            </div>
        </header>

        <main>
            @yield('content')
        </main>

        <footer class="border-t border-neutral-200 bg-neutral-900 text-neutral-200">
            <div class="mx-auto grid max-w-6xl gap-10 px-4 py-12 sm:px-6 lg:grid-cols-3 lg:px-8">
                <div>
                    <p class="text-sm uppercase tracking-[0.24em] text-neutral-400">Dealer</p>
                    <h3 class="mt-3 text-2xl font-light text-white">{{ $settings->dealer_name ?? 'Dealer Mobil' }}</h3>
                    <p class="mt-4 text-sm leading-6 text-neutral-300">{{ $settings->tagline ?? 'Mobil terbaik untuk kebutuhan Anda.' }}</p>
                </div>

                <div>
                    <p class="text-sm uppercase tracking-[0.24em] text-neutral-400">Kontak</p>
                    <ul class="mt-4 space-y-2 text-sm text-neutral-300">
                        <li>{{ $settings->address ?? '-' }}</li>
                        <li>{{ $settings->phone ?? '-' }}</li>
                        <li>{{ $settings->email ?? '-' }}</li>
                    </ul>
                </div>

                <div>
                    <p class="text-sm uppercase tracking-[0.24em] text-neutral-400">Navigasi</p>
                    <div class="mt-4 flex flex-col gap-2 text-sm text-neutral-300">
                        <a href="{{ route('vehicles.index') }}" class="hover:text-white">Katalog Mobil</a>
                        <a href="{{ route('promotions.index') }}" class="hover:text-white">Promo</a>
                        <a href="{{ route('gallery.index') }}" class="hover:text-white">Galeri</a>
                        <a href="{{ route('contact') }}" class="hover:text-white">Kontak</a>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
