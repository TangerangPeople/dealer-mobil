
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#ffffff">

    <meta
        name="description"
        content="{{ $settings?->tagline ?: 'Koleksi kendaraan, penawaran, dan layanan dealer.' }}"
    >

    <title>@yield('title', $settings?->dealer_name ?: config('app.name'))</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>

<body class="flex min-h-screen flex-col bg-white font-sans text-[#262626] antialiased">

    @php
        $dealerName = $settings?->dealer_name ?: config('app.name');

        $accountRoute = auth('customer')->check()
            ? 'customer.account'
            : 'customer.login';

        $accountLabel = auth('customer')->check()
            ? 'Akun Saya'
            : 'Masuk';

        $navigation = [
            ['label' => 'Tentang', 'route' => 'about', 'active' => 'about'],
            ['label' => 'Mobil', 'route' => 'vehicles.index', 'active' => 'vehicles.*'],
            ['label' => 'Promo', 'route' => 'promotions.index', 'active' => 'promotions.*'],
            ['label' => 'Layanan', 'route' => 'services.index', 'active' => 'services.*'],
            ['label' => 'Galeri', 'route' => 'gallery.index', 'active' => 'gallery.*'],
            ['label' => 'Simulasi Kredit', 'route' => 'credit-simulation', 'active' => 'credit-simulation'],
            ['label' => 'Kontak', 'route' => 'contact', 'active' => 'contact'],
        ];

        $socialLabels = [
            'instagram' => 'Instagram',
            'facebook' => 'Facebook',
            'tiktok' => 'TikTok',
            'youtube' => 'YouTube',
            'x' => 'X',
            'twitter' => 'X',
            'linkedin' => 'LinkedIn',
        ];

        /*
         * Mendukung social_links berupa array maupun JSON string.
         */
        $socialData = $settings?->social_links;

        if (is_string($socialData)) {
            $decodedSocialData = json_decode($socialData, true);
            $socialData = is_array($decodedSocialData) ? $decodedSocialData : [];
        }

        $socialLinks = collect(is_array($socialData) ? $socialData : [])
            ->filter(function ($url): bool {
                if (
                    ! is_string($url)
                    || trim($url) === ''
                    || filter_var($url, FILTER_VALIDATE_URL) === false
                ) {
                    return false;
                }

                return in_array(
                    strtolower((string) parse_url($url, PHP_URL_SCHEME)),
                    ['http', 'https'],
                    true
                );
            });
    @endphp

    {{-- Skip link untuk aksesibilitas --}}
    <a
        href="#main-content"
        class="sr-only left-4 top-4 z-[60] border border-[#262626] bg-white px-4 py-3 text-sm font-semibold focus:not-sr-only focus:absolute"
    >
        Lewati ke konten utama
    </a>

    {{-- HEADER --}}
    <header class="relative z-40 border-b border-black/10 bg-white">
        <div class="section-shell flex min-h-[72px] items-center justify-between gap-3 sm:min-h-[80px] sm:gap-5">

            {{-- Identitas dealer --}}
            <a
                href="{{ route('home') }}"
                title="{{ $dealerName }}"
                class="flex min-w-0 shrink items-center gap-2.5 text-[#262626] sm:gap-3"
            >
                @if ($settings?->logo_url)
                    <img
                        src="{{ $settings->logo_url }}"
                        alt="{{ $dealerName }}"
                        class="h-9 w-12 shrink-0 object-contain sm:h-10 sm:w-14"
                    >
                @endif

                <span class="max-w-[155px] break-words text-[11px] font-semibold uppercase leading-5 tracking-[0.12em] sm:max-w-[190px] sm:text-xs lg:max-w-[210px] lg:text-sm [overflow-wrap:anywhere]">
                    {{ $dealerName }}
                </span>
            </a>

            {{-- Navigasi desktop --}}
            <nav
                class="hidden min-w-0 flex-1 items-center justify-center gap-3 text-[12px] font-normal text-[#404040] xl:flex 2xl:gap-4 2xl:text-[13px]"
                aria-label="Navigasi utama"
            >
                @foreach ($navigation as $item)
                    <a
                        href="{{ route($item['route']) }}"
                        @class([
                            'inline-flex min-h-10 shrink-0 items-center border-b-2 py-2 transition-colors',
                            'border-[#262626] font-semibold text-[#262626]' => request()->routeIs($item['active']),
                            'border-transparent hover:border-[#bbbbbb] hover:text-[#262626]' => ! request()->routeIs($item['active']),
                        ])
                        @if (request()->routeIs($item['active']))
                            aria-current="page"
                        @endif
                    >
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            {{-- Aksi desktop --}}
            <div class="hidden shrink-0 items-center gap-4 xl:flex">
                <a
                    href="{{ route($accountRoute) }}"
                    class="text-xs font-medium text-[#262626] underline-offset-4 hover:underline 2xl:text-sm"
                >
                    {{ $accountLabel }}
                </a>

                <a
                    href="{{ route('booking-service') }}"
                    class="button-primary whitespace-nowrap px-4"
                >
                    Booking Service
                </a>
            </div>

            {{-- Aksi tablet --}}
            <a
                href="{{ route('booking-service') }}"
                class="button-primary hidden shrink-0 whitespace-nowrap px-3 md:inline-flex xl:hidden"
            >
                Booking Service
            </a>

            {{-- Menu tablet dan mobile --}}
            <details class="relative shrink-0 xl:hidden">
                <summary
                    class="flex min-h-11 cursor-pointer list-none items-center justify-center gap-3 border border-[#262626] px-3.5 py-2 text-xs font-semibold uppercase tracking-[0.1em] text-[#262626] transition-colors hover:bg-[#262626] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2 [&::-webkit-details-marker]:hidden"
                    aria-label="Buka menu navigasi"
                >
                    <span>Menu</span>

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.5"
                        aria-hidden="true"
                    >
                        <path d="M4 7h16M4 12h16M4 17h16" />
                    </svg>
                </summary>

                <nav
                    class="absolute right-0 top-full z-50 mt-2 flex max-h-[calc(100dvh-6rem)] w-80 max-w-[calc(100vw-2rem)] flex-col overflow-y-auto border border-[#bbbbbb] bg-white p-3 text-sm text-[#262626]"
                    aria-label="Navigasi mobile"
                >
                    <a
                        href="{{ route('home') }}"
                        @class([
                            'mobile-nav-link border-l-2 border-b border-black/5',
                            'border-l-[#262626] bg-[#f1f1f1] font-semibold' => request()->routeIs('home'),
                            'border-l-transparent' => ! request()->routeIs('home'),
                        ])
                        @if (request()->routeIs('home'))
                            aria-current="page"
                        @endif
                    >
                        Beranda
                    </a>

                    @foreach ($navigation as $item)
                        <a
                            href="{{ route($item['route']) }}"
                            @class([
                                'mobile-nav-link border-l-2 border-b border-black/5',
                                'border-l-[#262626] bg-[#f1f1f1] font-semibold' => request()->routeIs($item['active']),
                                'border-l-transparent' => ! request()->routeIs($item['active']),
                            ])
                            @if (request()->routeIs($item['active']))
                                aria-current="page"
                            @endif
                        >
                            {{ $item['label'] }}
                        </a>
                    @endforeach

                    <div class="mt-3 grid gap-2 border-t border-black/10 pt-4">
                        <a
                            href="{{ route('booking-service') }}"
                            class="button-primary w-full"
                        >
                            Booking Service
                        </a>

                        <a
                            href="{{ route($accountRoute) }}"
                            class="button-secondary w-full"
                        >
                            {{ $accountLabel }}
                        </a>
                    </div>
                </nav>
            </details>
        </div>
    </header>

    {{-- KONTEN UTAMA --}}
    <main id="main-content" class="min-w-0 flex-1">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="border-t border-white/10 bg-[#262626] text-[#efefef]">
        <div class="section-shell py-10 sm:py-12 lg:py-16">

            <div class="grid grid-cols-1 gap-9 sm:grid-cols-2 sm:gap-10 lg:grid-cols-[minmax(0,1.2fr)_minmax(0,0.9fr)_minmax(0,0.8fr)] lg:gap-12">

                {{-- Identitas dealer --}}
                <div class="min-w-0 sm:col-span-2 lg:col-span-1">
                    @if ($settings?->logo_url)
                        <img
                            src="{{ $settings->logo_url }}"
                            alt="{{ $dealerName }}"
                            class="h-10 w-20 bg-white object-contain p-1 sm:h-12 sm:w-24"
                            loading="lazy"
                        >
                    @endif

                    <p class="mt-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-[#bbbbbb]">
                        Dealer Mobil
                    </p>

                    <h2 class="mt-3 max-w-md break-words text-2xl font-light leading-snug text-white sm:text-3xl [overflow-wrap:anywhere]">
                        {{ $dealerName }}
                    </h2>

                    <p class="mt-4 max-w-sm break-words text-sm leading-7 text-[#d3d3d3] [overflow-wrap:anywhere]">
                        {{ $settings?->tagline ?: 'Informasi kendaraan, penawaran, dan layanan dealer dalam satu tempat.' }}
                    </p>
                </div>

                {{-- Kontak --}}
                <div class="min-w-0">
                    <h2 class="text-xs font-semibold uppercase tracking-[0.16em] text-white">
                        Hubungi Kami
                    </h2>

                    <ul class="mt-5 space-y-3 break-words text-sm leading-6 text-[#d3d3d3] [overflow-wrap:anywhere]">
                        <li>
                            {{ $settings?->address ?: 'Alamat dealer belum diatur.' }}
                        </li>

                        <li>
                            @if ($settings?->phone)
                                <a
                                    href="tel:{{ preg_replace('/[^0-9+]/', '', $settings->phone) }}"
                                    class="footer-link underline-offset-4 hover:text-white hover:underline"
                                >
                                    {{ $settings->phone }}
                                </a>
                            @else
                                Telepon belum diatur
                            @endif
                        </li>

                        <li>
                            @if ($settings?->email)
                                <a
                                    href="mailto:{{ $settings->email }}"
                                    class="footer-link underline-offset-4 hover:text-white hover:underline"
                                >
                                    {{ $settings->email }}
                                </a>
                            @else
                                Email belum diatur
                            @endif
                        </li>

                        @if ($settings?->whatsapp)
                            <li>
                                WhatsApp: {{ $settings->whatsapp }}
                            </li>
                        @endif
                    </ul>

                    {{-- SOCIAL LINKS --}}
                    @if ($socialLinks->isNotEmpty())
                        <div class="mt-6 border-t border-white/15 pt-5">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.16em] text-[#bbbbbb]">
                                Social
                            </p>

                            <div class="mt-4 flex flex-wrap gap-x-4 gap-y-3 text-sm text-[#d3d3d3]">
                                @foreach ($socialLinks as $platform => $url)
                                    <a
                                        href="{{ $url }}"
                                        target="_blank" rel="noopener noreferrer"
                                        class="footer-link underline-offset-4 hover:text-white hover:underline"
                                    >
                                        {{ $socialLabels[strtolower((string) $platform)] ?? \Illuminate\Support\Str::headline((string) $platform) }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Navigasi footer --}}
                <div class="min-w-0">
                    <h2 class="text-xs font-semibold uppercase tracking-[0.16em] text-white">
                        Jelajahi
                    </h2>

                    <nav
                        class="mt-5 flex flex-col items-start gap-3 text-sm text-[#d3d3d3]"
                        aria-label="Navigasi footer"
                    >
                        <a
                            href="{{ route('home') }}"
                            class="footer-link underline-offset-4 hover:text-white hover:underline"
                        >
                            Beranda
                        </a>

                        @foreach ($navigation as $item)
                            <a
                                href="{{ route($item['route']) }}"
                                class="footer-link underline-offset-4 hover:text-white hover:underline"
                            >
                                {{ $item['label'] }}
                            </a>
                        @endforeach

                        <a
                            href="{{ route('booking-service') }}"
                            class="footer-link underline-offset-4 hover:text-white hover:underline"
                        >
                            Booking Service
                        </a>

                        <a
                            href="{{ route($accountRoute) }}"
                            class="footer-link underline-offset-4 hover:text-white hover:underline"
                        >
                            {{ $accountLabel }}
                        </a>
                    </nav>
                </div>
            </div>

            {{-- Penutup footer --}}
            <div class="mt-10 flex flex-col gap-3 border-t border-white/15 pt-5 text-xs leading-5 text-[#bbbbbb] sm:mt-12 sm:flex-row sm:items-center sm:justify-between">
                <p>{{ $dealerName }}</p>

                <a
                    href="{{ route('home') }}"
                    class="footer-link underline-offset-4 hover:text-white hover:underline"
                >
                    Kembali ke beranda ↑
                </a>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
