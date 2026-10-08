<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{ $settings->tagline ?? 'Dealer mobil' }}">
        <title>@yield('title', $settings->dealer_name ?? config('app.name'))</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-white text-carbon antialiased">
        <header class="relative z-40 bg-white">
            <div class="section-shell flex min-h-[76px] items-center justify-between gap-4">
                <a href="{{ route('home') }}" title="{{ $settings->dealer_name ?? 'Dealer Mobil' }}" class="flex min-w-0 shrink-0 items-center gap-3 text-carbon">
                    @if ($settings?->logo_url)
                        <img src="{{ $settings->logo_url }}" alt="{{ $settings->dealer_name ?: 'Logo dealer' }}" class="h-9 w-16 shrink-0 object-contain sm:h-10 sm:w-20">
                    @endif
                    <span class="max-w-[12rem] truncate text-xs font-semibold uppercase tracking-[0.12em] sm:max-w-56 sm:text-sm">
                        {{ $settings?->dealer_name ?: 'Dealer Mobil' }}
                    </span>
                </a>

                <nav class="hidden items-center gap-4 text-[13px] font-medium text-[#3a3a3a] lg:flex xl:gap-5" aria-label="Navigasi utama">
                    <a href="{{ route('home') }}" class="nav-link">Beranda</a>
                    <a href="{{ route('about') }}" class="nav-link">Tentang</a>
                    <a href="{{ route('vehicles.index') }}" class="nav-link">Mobil</a>
                    <a href="{{ route('promotions.index') }}" class="nav-link">Promo</a>
                    <a href="{{ route('services.index') }}" class="nav-link">Layanan</a>
                    <a href="{{ route('gallery.index') }}" class="nav-link">Galeri</a>
                    <a href="{{ route('contact') }}" class="nav-link">Kontak</a>
                    <a href="{{ route('credit-simulation') }}" class="nav-link">Simulasi Kredit</a>
                </nav>

                <div class="flex shrink-0 items-center gap-2">
                    <a href="{{ route('booking-service') }}" class="button-primary hidden sm:inline-flex">Booking Service</a>
                    <a href="{{ auth('customer')->check() ? route('customer.account') : route('customer.login') }}" class="button-secondary hidden sm:inline-flex">
                        {{ auth('customer')->check() ? 'Akun' : 'Masuk' }}
                    </a>

                    <details class="relative lg:hidden">
                        <summary class="button-secondary cursor-pointer list-none">Menu</summary>
                        <nav class="absolute right-0 top-full mt-3 flex w-72 flex-col border border-concrete bg-white p-2 text-sm text-carbon" aria-label="Navigasi mobile">
                            <a href="{{ route('home') }}" class="mobile-nav-link">Beranda</a>
                            <a href="{{ route('about') }}" class="mobile-nav-link">Tentang</a>
                            <a href="{{ route('vehicles.index') }}" class="mobile-nav-link">Mobil</a>
                            <a href="{{ route('promotions.index') }}" class="mobile-nav-link">Promo</a>
                            <a href="{{ route('services.index') }}" class="mobile-nav-link">Layanan</a>
                            <a href="{{ route('gallery.index') }}" class="mobile-nav-link">Galeri</a>
                            <a href="{{ route('contact') }}" class="mobile-nav-link">Kontak</a>
                            <a href="{{ route('credit-simulation') }}" class="mobile-nav-link">Simulasi Kredit</a>
                            <a href="{{ route('booking-service') }}" class="mobile-nav-link">Booking Service</a>
                            <a href="{{ auth('customer')->check() ? route('customer.account') : route('customer.login') }}" class="mobile-nav-link">
                                {{ auth('customer')->check() ? 'Akun' : 'Masuk' }}
                            </a>
                        </nav>
                    </details>
                </div>
            </div>
        </header>

        <main>
            @yield('content')
        </main>

        @php
            $socialLabels = [
                'instagram' => 'Instagram', 'facebook' => 'Facebook', 'tiktok' => 'TikTok',
                'youtube' => 'YouTube', 'x' => 'X', 'linkedin' => 'LinkedIn',
            ];
            $socialLinks = collect(is_array($settings?->social_links) ? $settings->social_links : [])
                ->filter(function ($url): bool {
                    if (! is_string($url) || trim($url) === '' || filter_var($url, FILTER_VALIDATE_URL) === false) {
                        return false;
                    }
                    return in_array(strtolower((string) parse_url($url, PHP_URL_SCHEME)), ['http', 'https'], true);
                });
        @endphp

        <footer class="border-t border-white/10 bg-carbon text-[#efefef]">
            <div class="section-shell grid gap-10 py-12 md:grid-cols-[1.2fr_0.9fr_0.8fr]">
                <div>
                    <p class="section-label text-[#bbbbbb]">Dealer</p>
                    @if ($settings?->logo_url)
                        <img src="{{ $settings->logo_url }}" alt="{{ $settings->dealer_name ?: 'Logo dealer' }}" class="mt-4 h-10 w-20 bg-white object-contain p-1 sm:h-12 sm:w-24">
                    @endif
                    <h2 class="mt-4 text-2xl font-light text-white">{{ $settings?->dealer_name ?: 'Dealer Mobil' }}</h2>
                    <p class="mt-4 max-w-sm text-sm leading-6 text-[#d3d3d3]">{{ $settings->tagline ?? 'Informasi kendaraan, promo, dan layanan dealer.' }}</p>
                </div>

                <div>
                    <p class="section-label text-[#bbbbbb]">Kontak</p>
                    <ul class="mt-4 space-y-2 text-sm leading-6 text-[#d3d3d3]">
                        <li>{{ $settings?->address ?? '-' }}</li>
                        <li>@if ($settings?->phone)<a href="tel:{{ preg_replace('/[^0-9+]/', '', $settings->phone) }}" class="footer-link">{{ $settings->phone }}</a>@else-@endif</li>
                        <li>@if ($settings?->email)<a href="mailto:{{ $settings->email }}" class="footer-link">{{ $settings->email }}</a>@else-@endif</li>
                        <li>{{ $settings?->whatsapp ?? '-' }}</li>
                    </ul>

                    @if ($socialLinks->isNotEmpty())
                        <div class="mt-7">
                            <p class="section-label text-[#bbbbbb]">Social</p>
                            <div class="mt-4 flex flex-wrap gap-x-5 gap-y-2 text-sm text-[#d3d3d3]">
                                @foreach ($socialLinks as $platform => $url)
                                    <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="footer-link">
                                        {{ $socialLabels[strtolower((string) $platform)] ?? \Illuminate\Support\Str::headline((string) $platform) }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div>
                    <p class="section-label text-[#bbbbbb]">Navigasi</p>
                    <div class="mt-4 flex flex-col gap-2 text-sm text-[#d3d3d3]">
                        <a href="{{ route('home') }}" class="footer-link">Beranda</a>
                        <a href="{{ route('about') }}" class="footer-link">Tentang Dealer</a>
                        <a href="{{ route('vehicles.index') }}" class="footer-link">Katalog Mobil</a>
                        <a href="{{ route('promotions.index') }}" class="footer-link">Promo</a>
                        <a href="{{ route('services.index') }}" class="footer-link">Layanan</a>
                        <a href="{{ route('gallery.index') }}" class="footer-link">Galeri</a>
                        <a href="{{ route('contact') }}" class="footer-link">Kontak</a>
                        <a href="{{ route('credit-simulation') }}" class="footer-link">Simulasi Kredit</a>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
