@extends('public.layouts.app')

@section('title', $settings->dealer_name.' | Beranda')

@section('content')
    <section aria-labelledby="home-hero-title">
        @if ($settings->hero_image_url)
            <div class="w-full overflow-hidden bg-fog">
                <img src="{{ $settings->hero_image_url }}" alt="" class="h-[46vh] min-h-[360px] w-full object-cover sm:h-[54vh] lg:h-[68vh]" fetchpriority="high">
            </div>
        @else
            <div class="flex h-[46vh] min-h-[360px] items-center justify-center border-y border-concrete bg-fog text-sm text-[#5f5f5f] sm:h-[54vh] lg:h-[68vh]">Foto hero belum tersedia</div>
        @endif

        <div class="section-shell py-12 sm:py-16 lg:py-20">
            <div class="max-w-4xl">
                <h1 id="home-hero-title" class="max-w-4xl text-[36px] font-light leading-[1.12] tracking-[-0.04em] text-carbon sm:text-5xl lg:text-[60px]">
                    {{ $settings->hero_title ?? 'Temukan mobil yang tepat untuk Anda' }}
                </h1>
                <p class="mt-6 max-w-2xl text-base leading-7 text-[#4b4b4b] sm:text-lg">
                    {{ $settings->hero_description ?? 'Lihat kendaraan, promo, dan layanan dealer dalam satu tempat.' }}
                </p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('vehicles.index') }}" class="button-primary">Lihat Katalog</a>
                    <a href="{{ route('booking-service') }}" class="button-secondary">Booking Service</a>
                </div>
            </div>
        </div>
    </section>

    <section class="border-y border-black/10 bg-white py-16 sm:py-20" aria-labelledby="featured-title">
        <div class="section-shell">
            <div class="mb-10 flex items-end justify-between gap-6">
                <div>
                    <p class="section-label">Katalog</p>
                    <h2 id="featured-title" class="mt-2 text-3xl font-light tracking-[-0.04em] text-carbon sm:text-4xl">Kendaraan unggulan</h2>
                </div>
                <a href="{{ route('vehicles.index') }}" class="text-link hidden sm:inline-flex">Lihat semua</a>
            </div>

            <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($featuredVehicles->take(3) as $vehicle)
                    <article class="editorial-card">
                        <div class="aspect-[4/3] overflow-hidden bg-fog">
                            @php($image = $vehicle->primaryImage?->image_path)
                            @if ($image && Storage::disk('public')->exists($image))
                                <img src="{{ Storage::disk('public')->url($image) }}" alt="{{ $vehicle->brand }} {{ $vehicle->name }}" class="h-full w-full object-cover" loading="lazy">
                            @else
                                <div class="flex h-full items-center justify-center text-sm text-[#777]">Foto mobil</div>
                            @endif
                        </div>
                        <div class="p-6">
                            <p class="section-label">{{ $vehicle->category->name ?? 'Mobil' }}</p>
                            <h3 class="mt-3 text-xl font-medium leading-7 text-carbon sm:text-2xl">{{ $vehicle->brand }} {{ $vehicle->name }}</h3>
                            <p class="mt-3 text-sm text-[#5d5d5d]">{{ $vehicle->year }} · {{ $vehicle->transmission }}</p>
                            <div class="mt-6 flex items-end justify-between gap-4 border-t border-black/10 pt-5">
                                <span class="text-base font-medium text-carbon sm:text-lg">Rp {{ number_format($vehicle->price, 0, ',', '.') }}</span>
                                <a href="{{ route('vehicles.show', ['vehicle' => $vehicle->slug]) }}" class="text-link shrink-0">Lihat detail</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="text-sm text-[#666]">Belum ada kendaraan unggulan saat ini.</p>
                @endforelse
            </div>

            <a href="{{ route('vehicles.index') }}" class="text-link mt-8 sm:hidden">Lihat semua kendaraan</a>
        </div>
    </section>

    <section class="section-shell py-16 sm:py-20" aria-labelledby="about-title">
        <div class="grid gap-8 lg:grid-cols-[0.75fr_1.25fr] lg:gap-16">
            <div>
                <p class="section-label">Tentang dealer</p>
                <h2 id="about-title" class="mt-2 text-3xl font-light tracking-[-0.04em] text-carbon sm:text-4xl">{{ $settings->dealer_name }}</h2>
            </div>
            <div class="max-w-2xl">
                <p class="text-base leading-8 text-[#4b4b4b] sm:text-lg">
                    {{ $settings->home_intro ?? $settings->tagline ?? 'Informasi kendaraan dan layanan dealer untuk membantu Anda memilih dengan lebih yakin.' }}
                </p>
                <a href="{{ route('about') }}" class="text-link mt-6">Tentang Dealer</a>
            </div>
        </div>
    </section>

    <section class="bg-fog py-16 sm:py-20" aria-labelledby="promo-title">
        <div class="section-shell">
            <div class="mb-10 flex items-end justify-between gap-6">
                <div>
                    <p class="section-label">Penawaran</p>
                    <h2 id="promo-title" class="mt-2 text-3xl font-light tracking-[-0.04em] text-carbon sm:text-4xl">Promo terbaru</h2>
                </div>
                <a href="{{ route('promotions.index') }}" class="text-link hidden sm:inline-flex">Lihat semua</a>
            </div>

            <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($latestPromotions->take(3) as $promo)
                    <article class="editorial-card bg-white">
                        <div class="aspect-[4/3] overflow-hidden bg-fog">
                            @if ($promo->image_path && Storage::disk('public')->exists($promo->image_path))
                                <img src="{{ Storage::disk('public')->url($promo->image_path) }}" alt="{{ $promo->title }}" class="h-full w-full object-cover" loading="lazy">
                            @else
                                <div class="flex h-full items-center justify-center text-sm text-[#777]">Promosi</div>
                            @endif
                        </div>
                        <div class="p-6">
                            <p class="section-label">Promo</p>
                            <h3 class="mt-3 text-xl font-medium leading-7 text-carbon sm:text-2xl">{{ $promo->title }}</h3>
                            <p class="mt-3 text-sm leading-6 text-[#5d5d5d]">{{ $promo->summary }}</p>
                            <a href="{{ route('promotions.show', ['promotion' => $promo->slug]) }}" class="text-link mt-6">Lihat promo</a>
                        </div>
                    </article>
                @empty
                    <p class="text-sm text-[#666]">Belum ada promo yang tersedia saat ini.</p>
                @endforelse
            </div>

            <a href="{{ route('promotions.index') }}" class="text-link mt-8 sm:hidden">Lihat semua promo</a>
        </div>
    </section>

    <section class="section-shell py-16 sm:py-20" aria-labelledby="services-title">
        <div class="grid gap-10 lg:grid-cols-[0.8fr_1.2fr] lg:gap-16">
            <div>
                <p class="section-label">Layanan</p>
                <h2 id="services-title" class="mt-2 text-3xl font-light tracking-[-0.04em] text-carbon sm:text-4xl">Perawatan dan layanan kendaraan</h2>
            </div>
            <div>
                <div class="grid gap-0 border-y border-black/10 sm:grid-cols-3 sm:divide-x sm:divide-black/10">
                    <div class="border-b border-black/10 py-6 sm:border-b-0 sm:px-6 sm:py-2 sm:first:pl-0">
                        <p class="text-lg font-medium text-carbon">Servis Berkala</p>
                        <p class="mt-2 text-sm leading-6 text-[#666]">Perawatan rutin untuk menjaga kondisi kendaraan tetap optimal.</p>
                    </div>
                    <div class="border-b border-black/10 py-6 sm:border-b-0 sm:px-6 sm:py-2">
                        <p class="text-lg font-medium text-carbon">Perbaikan</p>
                        <p class="mt-2 text-sm leading-6 text-[#666]">Penanganan keluhan dan kebutuhan perbaikan kendaraan.</p>
                    </div>
                    <div class="py-6 sm:px-6 sm:py-2 sm:last:pr-0">
                        <p class="text-lg font-medium text-carbon">Inspeksi</p>
                        <p class="mt-2 text-sm leading-6 text-[#666]">Pemeriksaan kondisi kendaraan sebelum atau sesudah penggunaan.</p>
                    </div>
                </div>
                <a href="{{ route('booking-service') }}" class="button-primary mt-8">Booking Service</a>
            </div>
        </div>
    </section>

    <section class="bg-fog py-16 sm:py-20" aria-labelledby="gallery-title">
        <div class="section-shell">
            <div class="mb-10 flex items-end justify-between gap-6">
                <div>
                    <p class="section-label">Galeri</p>
                    <h2 id="gallery-title" class="mt-2 text-3xl font-light tracking-[-0.04em] text-carbon sm:text-4xl">Showroom dan mobil kami</h2>
                </div>
                <a href="{{ route('gallery.index') }}" class="text-link hidden sm:inline-flex">Lihat galeri</a>
            </div>

            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                @forelse ($gallery->take(4) as $item)
                    <div class="aspect-[4/3] overflow-hidden bg-white">
                        @if ($item->image_path && Storage::disk('public')->exists($item->image_path))
                            <img src="{{ Storage::disk('public')->url($item->image_path) }}" alt="{{ $item->alt_text ?? $item->title }}" class="h-full w-full object-cover" loading="lazy">
                        @else
                            <div class="flex h-full items-center justify-center text-sm text-[#777]">Foto galeri</div>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-[#666]">Galeri belum tersedia.</p>
                @endforelse
            </div>

            <a href="{{ route('gallery.index') }}" class="text-link mt-8 sm:hidden">Lihat galeri</a>
        </div>
    </section>

    <section class="section-shell py-16 sm:py-20" aria-labelledby="contact-title">
        <div class="grid gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:gap-16">
            <div>
                <p class="section-label">Hubungi kami</p>
                <h2 id="contact-title" class="mt-2 text-3xl font-light tracking-[-0.04em] text-carbon sm:text-4xl">Siap membantu kebutuhan kendaraan Anda</h2>
                <p class="mt-5 max-w-2xl text-base leading-7 text-[#4b4b4b]">{{ $settings->address ?? 'Alamat dealer belum diatur.' }}</p>

                <div class="mt-6 space-y-2 text-sm leading-6 text-[#4b4b4b]">
                    <p>
                        @if ($settings?->phone)
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $settings->phone) }}" class="text-link">{{ $settings->phone }}</a>
                        @else
                            Telepon belum diatur
                        @endif
                    </p>
                    <p>
                        @if ($settings?->email)
                            <a href="mailto:{{ $settings->email }}" class="text-link">{{ $settings->email }}</a>
                        @else
                            Email belum diatur
                        @endif
                    </p>
                    <p>WhatsApp: {{ $settings->whatsapp ?? '-' }}</p>
                </div>

                <a href="{{ route('contact') }}" class="button-secondary mt-8">Hubungi Dealer</a>
            </div>

            @if ($sales->isNotEmpty())
                @php($primarySale = $sales->first())
                <div class="border-t border-black/10 pt-8 lg:border-l lg:border-t-0 lg:pl-10 lg:pt-0">
                    <p class="section-label">Sales</p>
                    <h3 class="mt-2 text-2xl font-light text-carbon">{{ $primarySale->name }}</h3>
                    <p class="mt-2 text-sm text-[#666]">{{ $primarySale->position ?? 'Sales Representative' }}</p>
                    <p class="mt-5 text-sm leading-6 text-[#4b4b4b]">Hubungi sales untuk informasi kendaraan dan konsultasi pembelian.</p>
                    <a href="{{ route('sales.chat', ['sale' => $primarySale->getKey()]) }}" target="_blank" rel="noopener noreferrer" class="button-primary mt-6">Chat Sales</a>
                </div>
            @endif
        </div>
    </section>
@endsection
