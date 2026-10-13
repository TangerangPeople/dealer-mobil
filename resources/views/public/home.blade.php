
@extends('public.layouts.app')

@section('title', ($settings->dealer_name ?? 'Dealer Mobil').' | Beranda')

@section('content')
    @php
        $homeFeaturedVehicles = $featuredVehicles->take(3)->values();
        $homePromotions = $latestPromotions->take(3)->values();
        $homeGallery = $gallery->take(4)->values();
        $primarySale = $sales->first();
    @endphp

    {{-- HERO: CINEMATIC AUTOMOTIVE --}}
    <section aria-labelledby="home-hero-title" class="bg-white">
        <div class="relative isolate h-[48vh] min-h-[340px] max-h-[780px] overflow-hidden bg-fog sm:min-h-[420px] md:h-[64vh] lg:min-h-[520px]">
            @if ($settings->hero_image_url)
                <img
                    src="{{ $settings->hero_image_url }}"
                    alt=""
                    fetchpriority="high"
                    class="absolute inset-0 h-full w-full object-cover object-center"
                >

                <div class="absolute left-4 top-4 max-w-[calc(100%-2rem)] bg-white/95 px-4 py-3 text-carbon sm:left-8 sm:top-8 sm:px-5">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-[#696969]">
                        Automotive · Mobility · Service
                    </p>
                    <p class="mt-1 truncate text-xs font-semibold uppercase tracking-[0.12em] sm:text-sm">
                        {{ $settings->dealer_name }}
                    </p>
                </div>

                <div class="absolute bottom-0 left-0 right-0 h-1 bg-carbon"></div>
            @else
                <div class="absolute inset-0 flex flex-col items-center justify-center border-y border-concrete bg-fog px-6 text-center text-[#5f5f5f]">
                    <span class="section-label">{{ $settings->dealer_name }}</span>
                    <p class="mt-3 text-sm">Foto hero belum tersedia</p>
                </div>
            @endif
        </div>

        <div class="section-shell">
            <div class="grid gap-8 py-10 sm:py-14 lg:grid-cols-[minmax(0,1.12fr)_minmax(300px,0.88fr)] lg:items-end lg:gap-16 lg:py-20">
                <div>
                    <p class="section-label">Temukan perjalanan berikutnya</p>

                    <h1 id="home-hero-title" class="mt-4 max-w-[14ch] text-[clamp(2.7rem,6.4vw,5.6rem)] font-light leading-[0.98] tracking-[-0.065em] text-carbon">
                        {{ $settings->hero_title ?? 'Temukan mobil impian Anda' }}
                    </h1>
                </div>

                <div class="max-w-xl lg:justify-self-end">
                    <p class="max-w-lg text-base leading-8 text-[#505050] sm:text-lg">
                        {{ $settings->hero_description ?? 'Jelajahi pilihan kendaraan, temukan penawaran, dan dapatkan bantuan dari tim dealer kami.' }}
                    </p>

                    <div class="mt-7 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                        <a href="{{ route('vehicles.index') }}" class="button-primary">
                            Lihat Katalog
                        </a>

                        <a href="{{ route('booking-service') }}" class="button-secondary">
                            Booking Service
                        </a>
                    </div>
                </div>
            </div>

            {{-- QUICK ACCESS --}}
            <nav aria-label="Akses cepat" class="grid border-t border-black/15 sm:grid-cols-3 sm:divide-x sm:divide-black/15">
                @foreach ([
                    ['01', 'Temukan mobil', route('vehicles.index')],
                    ['02', 'Penawaran terkini', route('promotions.index')],
                    ['03', 'Simulasi kredit', route('credit-simulation')],
                ] as [$number, $label, $url])
                    <a
                        href="{{ $url }}"
                        class="group flex min-h-20 items-center justify-between gap-4 border-b border-black/15 py-5 transition-colors hover:bg-fog sm:border-b-0 sm:px-5 sm:first:pl-0 sm:last:pr-0"
                    >
                        <span class="flex items-center gap-4">
                            <span class="text-xs text-[#777]">{{ $number }}</span>
                            <span class="text-sm font-medium sm:text-base">{{ $label }}</span>
                        </span>

                        <span aria-hidden="true" class="text-xl transition-transform group-hover:translate-x-1">
                            →
                        </span>
                    </a>
                @endforeach
            </nav>
        </div>
    </section>

    {{-- FEATURED VEHICLES --}}
    <section class="border-y border-black/10 bg-white py-16 sm:py-20 lg:py-24" aria-labelledby="featured-title">
        <div class="section-shell">
            <div class="mb-9 flex flex-col gap-5 sm:mb-12 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="section-label">Pilihan kendaraan</p>

                    <h2 id="featured-title" class="mt-3 max-w-[14ch] text-3xl font-light leading-tight tracking-[-0.05em] text-carbon sm:text-4xl lg:text-5xl">
                        Dirancang untuk kebutuhan Anda.
                    </h2>
                </div>

                <a href="{{ route('vehicles.index') }}" class="text-link self-start sm:self-auto">
                    Jelajahi seluruh katalog
                </a>
            </div>

            @if ($homeFeaturedVehicles->isNotEmpty())
                @php($leadVehicle = $homeFeaturedVehicles->first())

                <div class="grid gap-8 lg:grid-cols-[minmax(0,1.25fr)_minmax(300px,0.75fr)] lg:gap-10">
                    {{-- MAIN FEATURED VEHICLE --}}
                    <article class="group {{ $homeFeaturedVehicles->count() === 1 ? 'lg:col-span-2' : '' }}">
                        <a
                            href="{{ route('vehicles.show', ['vehicle' => $leadVehicle->slug]) }}"
                            class="block overflow-hidden bg-fog"
                        >
                            @php($leadImage = $leadVehicle->primaryImage?->image_path)

                            @if ($leadImage && Storage::disk('public')->exists($leadImage))
                                <img
                                    src="{{ Storage::disk('public')->url($leadImage) }}"
                                    alt="{{ $leadVehicle->brand }} {{ $leadVehicle->name }}"
                                    class="aspect-[16/10] w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.025] motion-reduce:transition-none"
                                    loading="lazy"
                                >
                            @else
                                <div class="flex aspect-[16/10] items-center justify-center text-sm text-[#777]">
                                    Foto kendaraan belum tersedia
                                </div>
                            @endif
                        </a>

                        <div class="grid gap-5 border-b border-black/15 py-6 sm:grid-cols-[minmax(0,1fr)_auto] sm:items-end sm:gap-8">
                            <div>
                                <p class="section-label">
                                    {{ $leadVehicle->category->name ?? 'Kendaraan unggulan' }}
                                    · {{ $leadVehicle->year }}
                                </p>

                                <h3 class="mt-3 text-2xl font-light leading-tight tracking-[-0.04em] text-carbon sm:text-3xl lg:text-4xl">
                                    {{ $leadVehicle->brand }} {{ $leadVehicle->name }}
                                </h3>

                                <p class="mt-3 text-sm text-[#666]">
                                    {{ $leadVehicle->transmission }}
                                </p>
                            </div>

                            <div class="sm:text-right">
                                <p class="text-xs uppercase tracking-[0.12em] text-[#777]">
                                    Harga kendaraan
                                </p>

                                <p class="mt-1 text-lg font-medium text-carbon sm:text-xl">
                                    Rp {{ number_format($leadVehicle->price, 0, ',', '.') }}
                                </p>

                                <a
                                    href="{{ route('vehicles.show', ['vehicle' => $leadVehicle->slug]) }}"
                                    class="text-link mt-3"
                                >
                                    Lihat detail
                                </a>
                            </div>
                        </div>
                    </article>

                    {{-- SUPPORTING VEHICLES --}}
                    @if ($homeFeaturedVehicles->count() > 1)
                        <aside
                            class="grid content-start gap-4 sm:grid-cols-2 lg:grid-cols-1"
                            aria-label="Kendaraan unggulan lainnya"
                        >
                            @foreach ($homeFeaturedVehicles->skip(1) as $vehicle)
                                <article class="group grid grid-cols-[minmax(0,0.88fr)_minmax(0,1.12fr)] gap-4 border-b border-black/15 pb-5 sm:grid-cols-1 sm:gap-0 lg:grid-cols-[minmax(0,0.92fr)_minmax(0,1.08fr)] lg:gap-4">
                                    <a
                                        href="{{ route('vehicles.show', ['vehicle' => $vehicle->slug]) }}"
                                        class="block self-start overflow-hidden bg-fog"
                                    >
                                        @php($vehicleImage = $vehicle->primaryImage?->image_path)

                                        @if ($vehicleImage && Storage::disk('public')->exists($vehicleImage))
                                            <img
                                                src="{{ Storage::disk('public')->url($vehicleImage) }}"
                                                alt="{{ $vehicle->brand }} {{ $vehicle->name }}"
                                                class="aspect-[4/3] w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.025] motion-reduce:transition-none"
                                                loading="lazy"
                                            >
                                        @else
                                            <div class="flex aspect-[4/3] items-center justify-center text-xs text-[#777]">
                                                Foto kendaraan
                                            </div>
                                        @endif
                                    </a>

                                    <div class="flex min-w-0 flex-col justify-between py-1 sm:pb-5 sm:pt-4 lg:py-0">
                                        <div>
                                            <p class="section-label">
                                                {{ $vehicle->category->name ?? 'Kendaraan' }}
                                                · {{ $vehicle->year }}
                                            </p>

                                            <h3 class="mt-2 text-lg font-medium leading-snug tracking-[-0.025em] text-carbon sm:text-xl">
                                                {{ $vehicle->brand }} {{ $vehicle->name }}
                                            </h3>

                                            <p class="mt-2 text-xs text-[#666]">
                                                {{ $vehicle->transmission }}
                                            </p>
                                        </div>

                                        <div class="mt-4">
                                            <p class="text-sm font-medium text-carbon">
                                                Rp {{ number_format($vehicle->price, 0, ',', '.') }}
                                            </p>

                                            <a
                                                href="{{ route('vehicles.show', ['vehicle' => $vehicle->slug]) }}"
                                                class="text-link mt-3 text-sm"
                                            >
                                                Detail kendaraan
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </aside>
                    @endif
                </div>
            @else
                <div class="border-y border-black/15 py-12">
                    <p class="text-base text-[#626262]">
                        Kendaraan unggulan belum tersedia.
                    </p>

                    <p class="mt-2 text-sm text-[#777]">
                        Silakan kunjungi katalog atau hubungi tim dealer untuk informasi kendaraan.
                    </p>

                    <a href="{{ route('vehicles.index') }}" class="text-link mt-5">
                        Buka katalog mobil
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- DEALER STORY --}}
    <section class="bg-fog py-16 sm:py-20 lg:py-28" aria-labelledby="about-title">
        <div class="section-shell grid gap-8 lg:grid-cols-[minmax(0,0.82fr)_minmax(0,1.18fr)] lg:gap-20">
            <div>
                <p class="section-label">Tentang dealer</p>

                <h2 id="about-title" class="mt-3 max-w-[11ch] text-3xl font-light leading-[1.08] tracking-[-0.05em] text-carbon sm:text-4xl lg:text-5xl">
                    Pilihan yang lebih yakin dimulai dari informasi yang tepat.
                </h2>
            </div>

            <div class="flex flex-col items-start justify-center lg:pt-10">
                <p class="max-w-2xl text-base leading-8 text-[#4e4e4e] sm:text-lg sm:leading-9">
                    {{ $settings->home_intro ?? $settings->tagline ?? 'Jelajahi informasi kendaraan dan layanan dealer dalam satu tempat. Tim kami siap membantu Anda menemukan pilihan yang sesuai dengan kebutuhan.' }}
                </p>

                <a href="{{ route('about') }}" class="text-link mt-7">
                    Kenali {{ $settings->dealer_name }}
                </a>
            </div>
        </div>
    </section>

    {{-- PROMOTIONS --}}
    <section class="border-b border-black/10 bg-white py-16 sm:py-20 lg:py-24" aria-labelledby="promo-title">
        <div class="section-shell">
            <div class="mb-9 flex flex-col gap-5 sm:mb-12 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="section-label">Penawaran pilihan</p>

                    <h2 id="promo-title" class="mt-3 text-3xl font-light tracking-[-0.05em] text-carbon sm:text-4xl lg:text-5xl">
                        Temukan penawaran terbaru.
                    </h2>
                </div>

                <a href="{{ route('promotions.index') }}" class="text-link self-start sm:self-auto">
                    Semua promo
                </a>
            </div>

            @if ($homePromotions->isNotEmpty())
                @php($leadPromotion = $homePromotions->first())

                <div class="grid gap-8 lg:grid-cols-[minmax(0,1.08fr)_minmax(300px,0.92fr)] lg:gap-10">
                    <article class="group overflow-hidden border border-black/10 bg-white {{ $homePromotions->count() === 1 ? 'lg:col-span-2 lg:grid lg:grid-cols-2' : '' }}">
                        <a
                            href="{{ route('promotions.show', ['promotion' => $leadPromotion->slug]) }}"
                            class="block overflow-hidden bg-fog {{ $homePromotions->count() === 1 ? 'lg:h-full' : '' }}"
                        >
                            @if ($leadPromotion->image_path && Storage::disk('public')->exists($leadPromotion->image_path))
                                <img
                                    src="{{ Storage::disk('public')->url($leadPromotion->image_path) }}"
                                    alt="{{ $leadPromotion->title }}"
                                    class="aspect-[16/10] w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.025] motion-reduce:transition-none {{ $homePromotions->count() === 1 ? 'lg:aspect-auto lg:h-full lg:min-h-[360px]' : '' }}"
                                    loading="lazy"
                                >
                            @else
                                <div class="flex aspect-[16/10] items-center justify-center text-sm text-[#777]">
                                    Foto promo belum tersedia
                                </div>
                            @endif
                        </a>

                        <div class="flex flex-col items-start p-6 sm:p-8 lg:p-9">
                            <p class="section-label">Promo pilihan</p>

                            <h3 class="mt-3 text-2xl font-light leading-tight tracking-[-0.04em] text-carbon sm:text-3xl">
                                {{ $leadPromotion->title }}
                            </h3>

                            @if ($leadPromotion->summary)
                                <p class="mt-4 max-w-xl text-sm leading-7 text-[#5c5c5c] sm:text-base">
                                    {{ $leadPromotion->summary }}
                                </p>
                            @endif

                            <a
                                href="{{ route('promotions.show', ['promotion' => $leadPromotion->slug]) }}"
                                class="text-link mt-6"
                            >
                                Lihat detail promo
                            </a>
                        </div>
                    </article>

                    @if ($homePromotions->count() > 1)
                        <aside
                            class="divide-y divide-black/15 border-y border-black/15"
                            aria-label="Promo lainnya"
                        >
                            @foreach ($homePromotions->skip(1) as $promo)
                                <article class="group grid grid-cols-[minmax(0,0.85fr)_minmax(0,1.15fr)] gap-4 py-5 first:pt-0 last:pb-0 sm:gap-6">
                                    <a
                                        href="{{ route('promotions.show', ['promotion' => $promo->slug]) }}"
                                        class="block self-start overflow-hidden bg-fog"
                                    >
                                        @if ($promo->image_path && Storage::disk('public')->exists($promo->image_path))
                                            <img
                                                src="{{ Storage::disk('public')->url($promo->image_path) }}"
                                                alt="{{ $promo->title }}"
                                                class="aspect-[4/3] w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.025] motion-reduce:transition-none"
                                                loading="lazy"
                                            >
                                        @else
                                            <div class="flex aspect-[4/3] items-center justify-center text-xs text-[#777]">
                                                Foto promo
                                            </div>
                                        @endif
                                    </a>

                                    <div class="flex min-w-0 flex-col items-start justify-center">
                                        <p class="section-label">Penawaran</p>

                                        <h3 class="mt-2 text-lg font-medium leading-snug tracking-[-0.025em] text-carbon sm:text-xl">
                                            {{ $promo->title }}
                                        </h3>

                                        @if ($promo->summary)
                                            <p class="mt-2 line-clamp-3 text-sm leading-6 text-[#666]">
                                                {{ $promo->summary }}
                                            </p>
                                        @endif

                                        <a
                                            href="{{ route('promotions.show', ['promotion' => $promo->slug]) }}"
                                            class="text-link mt-3 text-sm"
                                        >
                                            Selengkapnya
                                        </a>
                                    </div>
                                </article>
                            @endforeach
                        </aside>
                    @endif
                </div>
            @else
                <div class="border-y border-black/15 py-12">
                    <p class="text-base text-[#626262]">
                        Belum ada promo yang ditampilkan.
                    </p>

                    <a href="{{ route('promotions.index') }}" class="text-link mt-5">
                        Lihat halaman promo
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- SERVICES --}}
    <section class="section-shell py-16 sm:py-20 lg:py-24" aria-labelledby="services-title">
        <div class="grid gap-10 lg:grid-cols-[minmax(0,0.78fr)_minmax(0,1.22fr)] lg:gap-20">
            <div>
                <p class="section-label">Layanan dan bantuan</p>

                <h2 id="services-title" class="mt-3 max-w-[12ch] text-3xl font-light leading-[1.08] tracking-[-0.05em] text-carbon sm:text-4xl lg:text-5xl">
                    Semua langkah, lebih mudah dijalani.
                </h2>

                <p class="mt-5 max-w-md text-sm leading-7 text-[#5c5c5c] sm:text-base">
                    Mulai dari mencari kendaraan sampai menyiapkan pembelian atau perawatan, temukan jalur yang sesuai dengan kebutuhan Anda.
                </p>
            </div>

            <div class="border-t border-black/15">
                @foreach ([
                    ['01', 'Jelajahi kendaraan', 'Bandingkan pilihan dan buka informasi kendaraan secara lebih lengkap.', route('vehicles.index')],
                    ['02', 'Rencanakan pembelian', 'Gunakan simulasi kredit sebagai langkah awal perencanaan.', route('credit-simulation')],
                    ['03', 'Rawat kendaraan', 'Jadwalkan kebutuhan servis melalui halaman booking service.', route('booking-service')],
                ] as [$number, $title, $description, $url])
                    <a
                        href="{{ $url }}"
                        class="group grid grid-cols-[44px_minmax(0,1fr)_24px] items-center gap-4 border-b border-black/15 py-6 sm:py-7"
                    >
                        <span class="text-xs text-[#777]">{{ $number }}</span>

                        <span>
                            <span class="block text-xl font-light tracking-[-0.025em] text-carbon sm:text-2xl">
                                {{ $title }}
                            </span>

                            <span class="mt-2 block text-sm leading-6 text-[#666]">
                                {{ $description }}
                            </span>
                        </span>

                        <span aria-hidden="true" class="text-xl transition-transform group-hover:translate-x-1">
                            →
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- GALLERY: ASYMMETRIC MOSAIC --}}
    <section class="bg-fog py-16 sm:py-20 lg:py-24" aria-labelledby="gallery-title">
        <div class="section-shell">
            <div class="mb-9 flex flex-col gap-5 sm:mb-12 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="section-label">Di balik perjalanan</p>

                    <h2 id="gallery-title" class="mt-3 text-3xl font-light tracking-[-0.05em] text-carbon sm:text-4xl lg:text-5xl">
                        Lihat lebih dekat.
                    </h2>

                    <p class="mt-4 max-w-xl text-sm leading-7 text-[#5b5b5b] sm:text-base">
                        Dokumentasi kendaraan, ruang, dan aktivitas yang ditampilkan oleh dealer.
                    </p>
                </div>

                <a href="{{ route('gallery.index') }}" class="text-link self-start sm:self-auto">
                    Jelajahi galeri
                </a>
            </div>

            @if ($homeGallery->isNotEmpty())
                <div class="grid grid-cols-2 gap-3 sm:gap-4 {{ $homeGallery->count() >= 4 ? 'md:grid-cols-4 lg:grid-cols-12 lg:grid-rows-[190px_190px]' : 'md:grid-cols-3' }}">
                    @foreach ($homeGallery as $item)
                        <figure class="group relative h-full overflow-hidden bg-white
                            @if ($homeGallery->count() >= 4)
                                @if ($loop->first)
                                    col-span-2 aspect-[16/10] md:col-span-2 lg:col-span-7 lg:row-span-2 lg:aspect-auto
                                @elseif ($loop->index === 1)
                                    col-span-1 aspect-[4/3] md:col-span-2 lg:col-span-5 lg:aspect-auto
                                @elseif ($loop->index === 2)
                                    col-span-1 aspect-[4/3] lg:col-span-2 lg:aspect-auto
                                @else
                                    col-span-1 aspect-[4/3] lg:col-span-3 lg:aspect-auto
                                @endif
                            @else
                                aspect-[4/3]
                            @endif
                        ">
                            @if ($item->image_path && Storage::disk('public')->exists($item->image_path))
                                <img
                                    src="{{ Storage::disk('public')->url($item->image_path) }}"
                                    alt="{{ $item->alt_text ?? $item->title }}"
                                    class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.035] motion-reduce:transition-none"
                                    loading="lazy"
                                >
                            @else
                                <div class="flex h-full min-h-36 items-center justify-center text-sm text-[#777]">
                                    Foto galeri belum tersedia
                                </div>
                            @endif

                            @if ($item->title || $item->caption)
                                <figcaption class="absolute inset-x-0 bottom-0 translate-y-full bg-carbon/95 px-4 py-3 text-white transition-transform duration-300 group-hover:translate-y-0 motion-reduce:transition-none">
                                    @if ($item->title)
                                        <p class="text-sm font-medium">{{ $item->title }}</p>
                                    @endif

                                    @if ($item->caption)
                                        <p class="mt-1 text-xs leading-5 text-white/80">{{ $item->caption }}</p>
                                    @endif
                                </figcaption>
                            @endif
                        </figure>
                    @endforeach
                </div>
            @else
                <div class="border-y border-black/15 py-12">
                    <p class="text-base text-[#626262]">
                        Galeri belum tersedia.
                    </p>

                    <p class="mt-2 text-sm text-[#777]">
                        Foto yang sudah ditambahkan ke galeri akan tampil di bagian ini.
                    </p>

                    <a href="{{ route('gallery.index') }}" class="text-link mt-5">
                        Buka galeri
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- FINAL CTA --}}
    <section class="bg-carbon py-16 text-white sm:py-20 lg:py-24" aria-labelledby="contact-title">
        <div class="section-shell grid gap-10 lg:grid-cols-[minmax(0,1.15fr)_minmax(280px,0.85fr)] lg:items-end lg:gap-16">
            <div>
                <p class="section-label text-[#c3c3c3]">
                    Langkah berikutnya
                </p>

                <h2 id="contact-title" class="mt-3 max-w-[13ch] text-3xl font-light leading-[1.08] tracking-[-0.05em] text-white sm:text-4xl lg:text-6xl">
                    Mari temukan kendaraan yang tepat untuk Anda.
                </h2>

                <p class="mt-5 max-w-xl text-sm leading-7 text-[#d4d4d4] sm:text-base">
                    Hubungi dealer untuk mendapatkan informasi lebih lanjut mengenai kendaraan, penawaran, dan layanan.
                </p>

                <div class="mt-7 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                    <a href="{{ route('contact') }}" class="button-secondary">
                        Hubungi Dealer
                    </a>

                    <a
                        href="{{ route('credit-simulation') }}"
                        class="inline-flex min-h-[46px] items-center justify-center border border-white/60 px-5 py-3 text-xs font-semibold uppercase tracking-[0.06em] text-white transition-colors hover:bg-white hover:text-carbon"
                    >
                        Simulasi Kredit
                    </a>
                </div>
            </div>

            <div class="border-t border-white/25 pt-7 lg:border-l lg:border-t-0 lg:pl-10 lg:pt-0">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#c3c3c3]">
                    {{ $settings->dealer_name }}
                </p>

                <p class="mt-4 max-w-md text-sm leading-7 text-[#ededed]">
                    {{ $settings->address ?? 'Alamat dealer belum diatur.' }}
                </p>

                <div class="mt-5 space-y-2 text-sm text-[#d4d4d4]">
                    @if ($settings?->phone)
                        <p>
                            <a
                                href="tel:{{ preg_replace('/[^0-9+]/', '', $settings->phone) }}"
                                class="underline decoration-white/40 underline-offset-4 hover:text-white"
                            >
                                {{ $settings->phone }}
                            </a>
                        </p>
                    @endif

                    @if ($settings?->email)
                        <p>
                            <a
                                href="mailto:{{ $settings->email }}"
                                class="underline decoration-white/40 underline-offset-4 hover:text-white"
                            >
                                {{ $settings->email }}
                            </a>
                        </p>
                    @endif

                    @if ($settings?->whatsapp)
                        <p>WhatsApp: {{ $settings->whatsapp }}</p>
                    @endif
                </div>

                @if ($primarySale)
                    <div class="mt-7 border-t border-white/20 pt-6">
                        <p class="text-xs uppercase tracking-[0.16em] text-[#c3c3c3]">
                            Hubungi sales
                        </p>

                        <p class="mt-2 text-lg font-medium text-white">
                            {{ $primarySale->name }}
                        </p>

                        <p class="mt-1 text-sm text-[#d4d4d4]">
                            {{ $primarySale->position ?? 'Sales Representative' }}
                        </p>

                        <a
                            href="{{ route('sales.chat', ['sale' => $primarySale->getKey()]) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-white underline decoration-white/40 underline-offset-4 hover:decoration-white"
                        >
                            Chat dengan sales
                            <span aria-hidden="true">↗</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
