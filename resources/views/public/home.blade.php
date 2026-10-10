
@extends('public.layouts.app')

@section('title', ($settings?->dealer_name ?: config('app.name')) . ' | Beranda')

@section('content')
    @php
        $featuredItems = $featuredVehicles->take(3);
        $featuredCount = $featuredItems->count();

        $promotionItems = $latestPromotions->take(3);
        $promotionCount = $promotionItems->count();

        $galleryItems = $gallery->take(4);
        $galleryCount = $galleryItems->count();

        $dealerName = $settings?->dealer_name ?: config('app.name');
    @endphp

    {{-- =========================================================
         HERO
    ========================================================== --}}
    <section aria-labelledby="home-hero-title">
        {{-- Foto utama --}}
        @if ($settings?->hero_image_url)
            <div class="w-full overflow-hidden bg-[#f1f1f1]">
                <img
                    src="{{ $settings->hero_image_url }}"
                    alt=""
                    class="h-[38vh] min-h-[260px] w-full object-cover sm:h-[50vh] sm:min-h-[360px] lg:h-[65vh] lg:min-h-[460px]"
                    fetchpriority="high"
                >
            </div>
        @else
            <div class="flex h-[38vh] min-h-[260px] items-center justify-center border-y border-[#bbbbbb] bg-[#f1f1f1] px-5 text-center text-sm text-[#5f5f5f] sm:h-[50vh] sm:min-h-[360px] lg:h-[65vh] lg:min-h-[460px]">
                Foto utama dealer belum tersedia
            </div>
        @endif

        {{-- Judul dan CTA --}}
        <div class="section-shell py-12 text-center sm:py-16 lg:py-20">
            <p class="section-label">
                {{ $dealerName }}
            </p>

            <h1
                id="home-hero-title"
                class="mx-auto mt-5 max-w-5xl text-3xl font-light leading-tight tracking-[-0.04em] text-[#262626] sm:text-5xl lg:text-[60px] lg:leading-[1.3]"
            >
                {{ $settings?->hero_title ?: 'Temukan mobil yang tepat untuk Anda' }}
            </h1>

            <p class="mx-auto mt-5 max-w-2xl text-base leading-7 text-[#4b4b4b] sm:text-lg sm:leading-8">
                {{ $settings?->hero_description ?: 'Jelajahi pilihan kendaraan, informasi promo, dan layanan dealer dalam satu tempat.' }}
            </p>

            <div class="mt-8 flex flex-col items-stretch justify-center gap-3 sm:flex-row sm:items-center">
                <a
                    href="{{ route('vehicles.index') }}"
                    class="button-primary sm:w-auto"
                >
                    Lihat katalog
                </a>

                <a
                    href="{{ route('booking-service') }}"
                    class="button-secondary sm:w-auto"
                >
                    Booking service
                </a>
            </div>
        </div>
    </section>

    {{-- =========================================================
         KENDARAAN UNGGULAN
    ========================================================== --}}
    <section
        class="border-y border-black/10 bg-white py-14 sm:py-16 lg:py-20"
        aria-labelledby="featured-title"
    >
        <div class="section-shell">
            <header class="mb-8 flex flex-col gap-4 sm:mb-10 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="section-label">Katalog</p>

                    <h2
                        id="featured-title"
                        class="mt-3 text-3xl font-light tracking-[-0.04em] text-[#262626] sm:text-4xl"
                    >
                        Kendaraan unggulan
                    </h2>
                </div>

                <a
                    href="{{ route('vehicles.index') }}"
                    class="text-link hidden sm:inline-flex"
                >
                    Lihat semua kendaraan
                </a>
            </header>

            {{-- Grid otomatis menyesuaikan jumlah kendaraan --}}
            <div
                @class([
                    'flex flex-wrap items-stretch justify-center gap-5 sm:gap-6 xl:gap-7',
                    'mx-auto max-w-2xl' => $featuredCount === 1,
                    'mx-auto max-w-5xl' => $featuredCount === 2,
                    'w-full' => $featuredCount === 0 || $featuredCount >= 3,
                ])
            >
                @forelse ($featuredItems as $vehicle)
                    <article
                        @class([
                            'editorial-card flex min-w-0 flex-col',
                            'w-full max-w-2xl' => $featuredCount === 1,
                            'w-full sm:w-[calc(50%_-_12px)]' => $featuredCount === 2,
                            'w-full sm:w-[calc(50%_-_12px)] xl:w-[calc(33.333%_-_19px)]' => $featuredCount >= 3,
                        ])
                    >
                        {{-- Foto kendaraan --}}
                        <a
                            href="{{ route('vehicles.show', ['vehicle' => $vehicle->slug]) }}"
                            class="block aspect-[4/3] shrink-0 overflow-hidden bg-[#f1f1f1]"
                            aria-label="Lihat {{ $vehicle->brand }} {{ $vehicle->name }}"
                        >
                            @php($image = $vehicle->primaryImage?->image_path)

                            @if ($image && Storage::disk('public')->exists($image))
                                <img
                                    src="{{ Storage::disk('public')->url($image) }}"
                                    alt="{{ $vehicle->brand }} {{ $vehicle->name }}"
                                    class="h-full w-full object-cover"
                                    loading="lazy"
                                    decoding="async"
                                >
                            @else
                                <div class="flex h-full w-full items-center justify-center px-4 text-center text-sm text-[#777777]">
                                    Foto kendaraan belum tersedia
                                </div>
                            @endif
                        </a>

                        {{-- Detail kendaraan --}}
                        <div class="flex flex-1 flex-col p-5 sm:p-6">
                            <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-[#777777]">
                                {{ $vehicle->category?->name ?? 'Mobil' }}
                            </p>

                            <h3 class="mt-3 min-h-[3.5rem] break-words text-xl font-light leading-snug tracking-[-0.025em] text-[#262626] sm:text-2xl [overflow-wrap:anywhere]">
                                {{ $vehicle->brand }} {{ $vehicle->name }}
                            </h3>

                            <p class="mt-3 text-sm leading-6 text-[#5d5d5d]">
                                {{ $vehicle->year }} · {{ $vehicle->transmission ?: '-' }}
                            </p>

                            <div class="mt-auto flex flex-wrap items-center justify-between gap-3 border-t border-black/10 pt-5">
                                <p class="text-base font-semibold leading-snug text-[#262626] sm:text-lg">
                                    Rp {{ number_format((float) $vehicle->price, 0, ',', '.') }}
                                </p>

                                <a
                                    href="{{ route('vehicles.show', ['vehicle' => $vehicle->slug]) }}"
                                    class="text-link shrink-0"
                                >
                                    Lihat detail
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="w-full border-y border-[#bbbbbb] py-10 text-center sm:py-14">
                        <h3 class="text-xl font-light text-[#262626]">
                            Kendaraan unggulan belum tersedia
                        </h3>

                        <p class="mx-auto mt-3 max-w-md text-sm leading-6 text-[#5d5d5d]">
                            Kendaraan akan ditampilkan di sini setelah tersedia dan dipilih sebagai kendaraan unggulan.
                        </p>

                        <a
                            href="{{ route('vehicles.index') }}"
                            class="mt-5 inline-flex min-h-11 items-center justify-center border border-[#262626] px-5 py-3 text-sm font-medium text-[#262626] transition-colors hover:bg-[#262626] hover:text-white"
                        >
                            Lihat katalog mobil
                        </a>
                    </div>
                @endforelse
            </div>

            <a
                href="{{ route('vehicles.index') }}"
                class="text-link mt-8 sm:hidden"
            >
                Lihat semua kendaraan
            </a>
        </div>
    </section>

    {{-- =========================================================
         PROFIL DEALER
    ========================================================== --}}
    <section
        class="section-shell py-14 sm:py-16 lg:py-20"
        aria-labelledby="about-title"
    >
        <div class="grid gap-6 lg:grid-cols-[minmax(0,0.8fr)_minmax(0,1.2fr)] lg:gap-16">
            <div>
                <p class="section-label">Tentang dealer</p>

                <h2
                    id="about-title"
                    class="mt-3 break-words text-3xl font-light leading-tight tracking-[-0.04em] text-[#262626] sm:text-4xl lg:text-5xl [overflow-wrap:anywhere]"
                >
                    {{ $dealerName }}
                </h2>
            </div>

            <div class="max-w-2xl">
                <p class="text-base leading-8 text-[#4b4b4b] sm:text-lg">
                    {{ $settings?->home_intro ?: $settings?->tagline ?: 'Informasi kendaraan dan layanan dealer untuk membantu Anda memilih dengan lebih yakin.' }}
                </p>

                <a
                    href="{{ route('about') }}"
                    class="text-link mt-6"
                >
                    Tentang dealer
                </a>
            </div>
        </div>
    </section>

    {{-- =========================================================
         PROMO TERBARU
    ========================================================== --}}
    <section
        class="bg-[#f1f1f1] py-14 sm:py-16 lg:py-20"
        aria-labelledby="promo-title"
    >
        <div class="section-shell">
            <header class="mb-8 flex flex-col gap-4 sm:mb-10 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="section-label">Penawaran</p>

                    <h2
                        id="promo-title"
                        class="mt-3 text-3xl font-light tracking-[-0.04em] text-[#262626] sm:text-4xl"
                    >
                        Promo terbaru
                    </h2>
                </div>

                <a
                    href="{{ route('promotions.index') }}"
                    class="text-link hidden sm:inline-flex"
                >
                    Lihat semua promo
                </a>
            </header>

            <div
                @class([
                    'flex flex-wrap items-stretch justify-center gap-5 sm:gap-6 xl:gap-7',
                    'mx-auto max-w-2xl' => $promotionCount === 1,
                    'mx-auto max-w-5xl' => $promotionCount === 2,
                    'w-full' => $promotionCount === 0 || $promotionCount >= 3,
                ])
            >
                @forelse ($promotionItems as $promo)
                    <article
                        @class([
                            'editorial-card flex min-w-0 flex-col bg-white',
                            'w-full max-w-2xl' => $promotionCount === 1,
                            'w-full sm:w-[calc(50%_-_12px)]' => $promotionCount === 2,
                            'w-full sm:w-[calc(50%_-_12px)] xl:w-[calc(33.333%_-_19px)]' => $promotionCount >= 3,
                        ])
                    >
                        {{-- Foto promo --}}
                        <div class="aspect-[4/3] shrink-0 overflow-hidden bg-[#f1f1f1]">
                            @if (
                                $promo->image_path
                                && Storage::disk('public')->exists($promo->image_path)
                            )
                                <img
                                    src="{{ Storage::disk('public')->url($promo->image_path) }}"
                                    alt="{{ $promo->title }}"
                                    class="h-full w-full object-cover"
                                    loading="lazy"
                                    decoding="async"
                                >
                            @else
                                <div class="flex h-full w-full items-center justify-center px-4 text-center text-sm text-[#777777]">
                                    Foto promo belum tersedia
                                </div>
                            @endif
                        </div>

                        {{-- Informasi promo --}}
                        <div class="flex flex-1 flex-col p-5 sm:p-6">
                            <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-[#777777]">
                                {{ $promo->vehicle?->brand ?? 'Promo dealer' }}
                            </p>

                            <h3 class="mt-3 min-h-[3.5rem] break-words text-xl font-light leading-snug tracking-[-0.025em] text-[#262626] sm:text-2xl [overflow-wrap:anywhere]">
                                {{ $promo->title }}
                            </h3>

                            <p class="mt-3 break-words text-sm leading-6 text-[#5d5d5d] [overflow-wrap:anywhere]">
                                {{ $promo->summary }}
                            </p>

                            <div class="mt-auto border-t border-black/10 pt-5">
                                <a
                                    href="{{ route('promotions.show', ['promotion' => $promo->slug]) }}"
                                    class="text-link"
                                >
                                    Lihat promo
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="w-full border-y border-[#bbbbbb] bg-white py-10 text-center sm:py-14">
                        <h3 class="text-xl font-light text-[#262626]">
                            Belum ada promo terbaru
                        </h3>

                        <p class="mx-auto mt-3 max-w-md text-sm leading-6 text-[#5d5d5d]">
                            Informasi penawaran akan ditampilkan di sini ketika tersedia.
                        </p>

                        <a
                            href="{{ route('promotions.index') }}"
                            class="mt-5 inline-flex min-h-11 items-center justify-center border border-[#262626] px-5 py-3 text-sm font-medium text-[#262626] transition-colors hover:bg-[#262626] hover:text-white"
                        >
                            Lihat halaman promo
                        </a>
                    </div>
                @endforelse
            </div>

            <a
                href="{{ route('promotions.index') }}"
                class="text-link mt-8 sm:hidden"
            >
                Lihat semua promo
            </a>
        </div>
    </section>

    {{-- =========================================================
         LAYANAN
    ========================================================== --}}
    <section
        class="section-shell py-14 sm:py-16 lg:py-20"
        aria-labelledby="services-title"
    >
        <div class="grid gap-8 lg:grid-cols-[minmax(0,0.8fr)_minmax(0,1.2fr)] lg:gap-16">
            <div>
                <p class="section-label">Layanan</p>

                <h2
                    id="services-title"
                    class="mt-3 text-3xl font-light leading-tight tracking-[-0.04em] text-[#262626] sm:text-4xl lg:text-5xl"
                >
                    Perawatan dan layanan kendaraan
                </h2>
            </div>

            <div class="min-w-0">
                <div class="grid grid-cols-1 border-y border-black/10 sm:grid-cols-3 sm:divide-x sm:divide-black/10">
                    <article class="flex flex-col border-b border-black/10 py-6 sm:border-b-0 sm:px-5 sm:first:pl-0 lg:px-6">
                        <h3 class="text-lg font-medium text-[#262626]">
                            Servis berkala
                        </h3>

                        <p class="mt-3 flex-1 text-sm leading-6 text-[#5d5d5d]">
                            Perawatan rutin untuk membantu menjaga kondisi kendaraan tetap optimal.
                        </p>
                    </article>

                    <article class="flex flex-col border-b border-black/10 py-6 sm:border-b-0 sm:px-5 lg:px-6">
                        <h3 class="text-lg font-medium text-[#262626]">
                            Perbaikan
                        </h3>

                        <p class="mt-3 flex-1 text-sm leading-6 text-[#5d5d5d]">
                            Penanganan keluhan dan kebutuhan perbaikan kendaraan.
                        </p>
                    </article>

                    <article class="flex flex-col py-6 sm:px-5 sm:last:pr-0 lg:px-6">
                        <h3 class="text-lg font-medium text-[#262626]">
                            Inspeksi
                        </h3>

                        <p class="mt-3 flex-1 text-sm leading-6 text-[#5d5d5d]">
                            Pemeriksaan kondisi kendaraan sebelum atau sesudah penggunaan.
                        </p>
                    </article>
                </div>

                <a
                    href="{{ route('services.index') }}"
                    class="text-link mt-6"
                >
                    Lihat paket servis
                </a>

                <div class="mt-6">
                    <a
                        href="{{ route('booking-service') }}"
                        class="button-primary sm:w-auto"
                    >
                        Booking service
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- =========================================================
         GALERI
    ========================================================== --}}
    <section
        class="bg-[#f1f1f1] py-14 sm:py-16 lg:py-20"
        aria-labelledby="gallery-title"
    >
        <div class="section-shell">
            <header class="mb-8 flex flex-col gap-4 sm:mb-10 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="section-label">Galeri</p>

                    <h2
                        id="gallery-title"
                        class="mt-3 text-3xl font-light tracking-[-0.04em] text-[#262626] sm:text-4xl"
                    >
                        Showroom dan kendaraan
                    </h2>
                </div>

                <a
                    href="{{ route('gallery.index') }}"
                    class="text-link hidden sm:inline-flex"
                >
                    Lihat galeri
                </a>
            </header>

            <div
                @class([
                    'flex flex-wrap items-stretch justify-center gap-4 sm:gap-5',
                    'mx-auto max-w-2xl' => $galleryCount === 1,
                    'mx-auto max-w-5xl' => $galleryCount === 2,
                    'w-full' => $galleryCount === 0 || $galleryCount >= 3,
                ])
            >
                @forelse ($galleryItems as $item)
                    <figure
                        @class([
                            'min-w-0 overflow-hidden bg-white',
                            'w-full max-w-2xl' => $galleryCount === 1,
                            'w-full sm:w-[calc(50%_-_10px)]' => $galleryCount === 2,
                            'w-full sm:w-[calc(50%_-_10px)] lg:w-[calc(25%_-_15px)]' => $galleryCount >= 3,
                        ])
                    >
                        <div class="aspect-[4/3] overflow-hidden bg-white">
                            @if (
                                $item->image_path
                                && Storage::disk('public')->exists($item->image_path)
                            )
                                <img
                                    src="{{ Storage::disk('public')->url($item->image_path) }}"
                                    alt="{{ $item->alt_text ?: $item->title }}"
                                    class="h-full w-full object-cover"
                                    loading="lazy"
                                    decoding="async"
                                >
                            @else
                                <div class="flex h-full w-full items-center justify-center px-3 text-center text-sm text-[#777777]">
                                    Foto galeri belum tersedia
                                </div>
                            @endif
                        </div>

                        <figcaption class="border-t border-black/10 px-4 py-4">
                            <p class="break-words text-sm font-medium leading-6 text-[#262626] [overflow-wrap:anywhere]">
                                {{ $item->title }}
                            </p>
                        </figcaption>
                    </figure>
                @empty
                    <div class="w-full border-y border-[#bbbbbb] bg-white py-10 text-center sm:py-14">
                        <h3 class="text-xl font-light text-[#262626]">
                            Galeri sedang disiapkan
                        </h3>

                        <p class="mx-auto mt-3 max-w-md text-sm leading-6 text-[#5d5d5d]">
                            Foto kendaraan dan dokumentasi dealer akan ditampilkan setelah ditambahkan melalui dashboard administrator.
                        </p>

                        <a
                            href="{{ route('gallery.index') }}"
                            class="mt-5 inline-flex min-h-11 items-center justify-center border border-[#262626] px-5 py-3 text-sm font-medium text-[#262626] transition-colors hover:bg-[#262626] hover:text-white"
                        >
                            Buka galeri
                        </a>
                    </div>
                @endforelse
            </div>

            <a
                href="{{ route('gallery.index') }}"
                class="text-link mt-8 sm:hidden"
            >
                Lihat galeri
            </a>
        </div>
    </section>

    {{-- =========================================================
         KONTAK DAN SALES
    ========================================================== --}}
    <section
        class="section-shell py-14 sm:py-16 lg:py-20"
        aria-labelledby="contact-title"
    >
        <div
            @class([
                'grid gap-8 lg:gap-16',
                'lg:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)]' => $sales->isNotEmpty(),
                'mx-auto max-w-3xl' => $sales->isEmpty(),
            ])
        >
            <div class="min-w-0">
                <p class="section-label">Hubungi kami</p>

                <h2
                    id="contact-title"
                    class="mt-3 text-3xl font-light leading-tight tracking-[-0.04em] text-[#262626] sm:text-4xl lg:text-5xl"
                >
                    Siap membantu kebutuhan kendaraan Anda
                </h2>

                <p class="mt-5 break-words text-base leading-7 text-[#4b4b4b] [overflow-wrap:anywhere]">
                    {{ $settings?->address ?: 'Alamat dealer belum diatur.' }}
                </p>

                <div class="mt-6 space-y-3 text-sm leading-6 text-[#4b4b4b]">
                    <p>
                        @if ($settings?->phone)
                            <a
                                href="tel:{{ preg_replace('/[^0-9+]/', '', $settings->phone) }}"
                                class="footer-link underline underline-offset-4"
                            >
                                {{ $settings->phone }}
                            </a>
                        @else
                            Telepon belum diatur
                        @endif
                    </p>

                    <p class="break-words [overflow-wrap:anywhere]">
                        @if ($settings?->email)
                            <a
                                href="mailto:{{ $settings->email }}"
                                class="footer-link underline underline-offset-4"
                            >
                                {{ $settings->email }}
                            </a>
                        @else
                            Email belum diatur
                        @endif
                    </p>

                    <p>
                        WhatsApp: {{ $settings?->whatsapp ?: 'Belum diatur' }}
                    </p>
                </div>

                <a
                    href="{{ route('contact') }}"
                    class="button-secondary mt-8 sm:w-auto"
                >
                    Hubungi dealer
                </a>
            </div>

            @if ($sales->isNotEmpty())
                @php($primarySale = $sales->first())

                <div class="min-w-0 border-t border-black/10 pt-8 lg:border-l lg:border-t-0 lg:pl-10 lg:pt-0">
                    <p class="section-label">Sales</p>

                    <h3 class="mt-3 break-words text-2xl font-light text-[#262626] [overflow-wrap:anywhere]">
                        {{ $primarySale->name }}
                    </h3>

                    <p class="mt-2 text-sm text-[#666666]">
                        {{ $primarySale->position ?: 'Tim penjualan' }}
                    </p>

                    <p class="mt-5 text-sm leading-7 text-[#4b4b4b]">
                        Hubungi tim sales untuk memperoleh informasi kendaraan dan konsultasi pembelian.
                    </p>

                    <a
                        href="{{ route('sales.chat', ['sale' => $primarySale->getKey()]) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="button-primary mt-6 sm:w-auto"
                    >
                        Chat sales
                    </a>
                </div>
            @endif
        </div>
    </section>
@endsection
