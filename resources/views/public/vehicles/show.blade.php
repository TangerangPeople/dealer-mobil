
@extends('public.layouts.app')

@php
    // PublicPageController mengirim kendaraan dengan nama vehicleModel.
    $vehicle = $vehicleModel;

    $vehicleImages = $vehicle->vehicleImages ?? collect();

    $primaryImage = $vehicle->primaryImage
        ?? $vehicleImages->firstWhere('is_primary', true)
        ?? $vehicleImages->first();

    $primaryImagePath = $primaryImage?->image_path;

    $otherImages = $vehicleImages
        ->reject(fn ($image) => $image->id === $primaryImage?->id)
        ->take(4)
        ->values();

    $statusLabel = match ($vehicle->status) {
        'available' => 'Tersedia',
        'reserved' => 'Dalam pemesanan',
        'sold' => 'Terjual',
        'inactive' => 'Tidak tersedia',
        default => 'Informasi kendaraan',
    };
@endphp

@section('title', $vehicle->brand.' '.$vehicle->name.' | Detail Kendaraan')

@section('content')
    {{-- Breadcrumb --}}
    <div class="border-b border-black/10 bg-white">
        <nav aria-label="Breadcrumb"
            class="section-shell flex flex-wrap items-center gap-2 py-4 text-xs text-[#777] sm:text-sm">

            <a href="{{ route('home') }}" class="hover:text-carbon">
                Beranda
            </a>

            <span aria-hidden="true">/</span>

            <a href="{{ route('vehicles.index') }}" class="hover:text-carbon">
                Katalog mobil
            </a>

            <span aria-hidden="true">/</span>

            <span class="max-w-[180px] truncate text-carbon sm:max-w-xs">
                {{ $vehicle->brand }} {{ $vehicle->name }}
            </span>
        </nav>
    </div>

    {{-- Vehicle photography and purchase information --}}
    <section class="bg-white py-8 sm:py-12 lg:py-16">
        <div class="section-shell">
            <div class="grid gap-8 lg:grid-cols-[minmax(0,1.3fr)_minmax(320px,0.7fr)] lg:gap-12 xl:gap-16">

                {{-- Main image --}}
                <div class="min-w-0">
                    <div class="relative overflow-hidden bg-fog">
                        @if ($primaryImagePath && Storage::disk('public')->exists($primaryImagePath))
                            <img
                                src="{{ Storage::disk('public')->url($primaryImagePath) }}"
                                alt="{{ $primaryImage->alt_text ?: $vehicle->brand.' '.$vehicle->name }}"
                                fetchpriority="high"
                                class="aspect-[4/3] w-full object-cover sm:aspect-[16/10] lg:aspect-[6/5]"
                            >
                        @else
                            <div class="flex aspect-[4/3] flex-col items-center justify-center px-6 text-center sm:aspect-[16/10] lg:aspect-[6/5]">
                                <span class="text-4xl text-[#999]" aria-hidden="true">＋</span>
                                <p class="mt-3 text-sm text-[#777]">
                                    Foto kendaraan belum tersedia
                                </p>
                            </div>
                        @endif

                        <span class="absolute left-4 top-4 bg-white px-3 py-2 text-[10px] font-semibold uppercase tracking-[0.14em] text-carbon sm:left-6 sm:top-6">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    {{-- Supporting images --}}
                    @if ($otherImages->isNotEmpty())
                        <div class="mt-3 grid grid-cols-3 gap-3 sm:grid-cols-4">
                            @foreach ($otherImages as $image)
                                <figure class="overflow-hidden bg-fog">
                                    @if ($image->image_path && Storage::disk('public')->exists($image->image_path))
                                        <img
                                            src="{{ Storage::disk('public')->url($image->image_path) }}"
                                            alt="{{ $image->alt_text ?: $vehicle->brand.' '.$vehicle->name }}"
                                            loading="lazy"
                                            class="aspect-[4/3] w-full object-cover transition-transform duration-500 hover:scale-[1.04] motion-reduce:transition-none"
                                        >
                                    @else
                                        <div class="flex aspect-[4/3] items-center justify-center text-xs text-[#777]">
                                            Foto tidak tersedia
                                        </div>
                                    @endif
                                </figure>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Main information --}}
                <div class="flex min-w-0 flex-col lg:pt-2">
                    <p class="section-label">
                        {{ $vehicle->category->name ?? 'Kendaraan pilihan' }}
                        <span class="px-1">/</span>
                        {{ $vehicle->year }}
                    </p>

                    <h1 class="mt-4 text-4xl font-light leading-[1.04] tracking-[-0.055em] text-carbon sm:text-5xl xl:text-6xl">
                        {{ $vehicle->brand }}
                        <span class="mt-1 block">{{ $vehicle->name }}</span>
                    </h1>

                    <div class="mt-7 border-y border-black/15 py-6">
                        <p class="text-xs font-medium uppercase tracking-[0.14em] text-[#777]">
                            Harga kendaraan
                        </p>

                        <p class="mt-2 text-2xl font-medium tracking-[-0.035em] text-carbon sm:text-3xl">
                            Rp {{ number_format($vehicle->price, 0, ',', '.') }}
                        </p>

                        <p class="mt-2 text-xs leading-5 text-[#777]">
                            Konfirmasikan harga dan ketersediaan sebelum melakukan transaksi.
                        </p>
                    </div>

                    {{-- Specifications --}}
                    <div class="grid grid-cols-2 gap-x-5 gap-y-6 border-b border-black/15 py-6">
                        <div>
                            <p class="text-xs uppercase tracking-[0.12em] text-[#777]">Tahun</p>
                            <p class="mt-2 text-base font-medium text-carbon">
                                {{ $vehicle->year }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs uppercase tracking-[0.12em] text-[#777]">Transmisi</p>
                            <p class="mt-2 text-base font-medium text-carbon">
                                {{ $vehicle->transmission ?: 'Hubungi dealer' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs uppercase tracking-[0.12em] text-[#777]">Tipe</p>
                            <p class="mt-2 text-base font-medium text-carbon">
                                {{ $vehicle->type ?: 'Belum ditentukan' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs uppercase tracking-[0.12em] text-[#777]">Status</p>
                            <p class="mt-2 text-base font-medium text-carbon">
                                {{ $statusLabel }}
                            </p>
                        </div>
                    </div>

                    {{-- Purchase flow: keep the existing route --}}
                    <div class="mt-7 flex flex-col gap-3">
                        @if ($vehicle->status === 'available')
                            <a
                                href="{{ route('purchase.create', ['vehicle' => $vehicle->slug]) }}"
                                class="button-primary w-full justify-center"
                            >
                                Ajukan Pembelian
                            </a>

                            <a
                                href="{{ route('credit-simulation') }}"
                                class="button-secondary w-full justify-center"
                            >
                                Simulasi Kredit
                            </a>
                        @elseif ($vehicle->status === 'reserved')
                            <p class="border border-black/15 bg-fog px-4 py-4 text-sm leading-6 text-[#555]">
                                Kendaraan sedang dalam pemesanan. Hubungi dealer untuk memastikan ketersediaannya.
                            </p>

                            <a href="{{ route('contact') }}" class="button-secondary w-full justify-center">
                                Hubungi Dealer
                            </a>
                        @elseif ($vehicle->status === 'sold')
                            <p class="border border-black/15 bg-fog px-4 py-4 text-sm leading-6 text-[#555]">
                                Kendaraan telah terjual. Jelajahi katalog untuk menemukan pilihan lainnya.
                            </p>

                            <a href="{{ route('vehicles.index') }}" class="button-primary w-full justify-center">
                                Lihat Mobil Lain
                            </a>
                        @else
                            <p class="border border-black/15 bg-fog px-4 py-4 text-sm leading-6 text-[#555]">
                                Kendaraan sedang tidak tersedia untuk pengajuan pembelian.
                            </p>

                            <a href="{{ route('vehicles.index') }}" class="button-secondary w-full justify-center">
                                Kembali ke Katalog
                            </a>
                        @endif
                    </div>

                    <p class="mt-5 text-xs leading-6 text-[#777]">
                        Informasi kendaraan dapat dikonfirmasi kepada dealer sebelum transaksi.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Description --}}
    <section class="border-y border-black/10 bg-fog py-14 sm:py-20 lg:py-24">
        <div class="section-shell grid gap-8 lg:grid-cols-[minmax(0,0.7fr)_minmax(0,1.3fr)] lg:gap-20">
            <div>
                <p class="section-label">Mengenal kendaraan</p>

                <h2 class="mt-3 max-w-[12ch] text-3xl font-light leading-tight tracking-[-0.05em] text-carbon sm:text-4xl">
                    Detail yang membantu Anda memilih.
                </h2>
            </div>

            <div>
                @if ($vehicle->description)
                    <div class="whitespace-pre-line text-base leading-8 text-[#4f4f4f]">
                        {{ $vehicle->description }}
                    </div>
                @else
                    <p class="text-base leading-8 text-[#666]">
                        Deskripsi kendaraan belum tersedia. Hubungi dealer untuk memperoleh informasi lebih lanjut.
                    </p>
                @endif

                <div class="mt-8 border-t border-black/15 pt-6">
                    <a href="{{ route('contact') }}" class="text-link">
                        Butuh informasi lebih lanjut?
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Related vehicles --}}
    @if (isset($relatedVehicles) && $relatedVehicles->isNotEmpty())
        <section class="section-shell py-16 sm:py-20 lg:py-24">
            <div class="mb-9 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="section-label">Pilihan lainnya</p>
                    <h2 class="mt-3 text-3xl font-light tracking-[-0.05em] text-carbon sm:text-4xl">
                        Kendaraan yang mungkin Anda cari.
                    </h2>
                </div>

                <a href="{{ route('vehicles.index') }}" class="text-link self-start">
                    Seluruh katalog
                </a>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($relatedVehicles as $relatedVehicle)
                    <article class="group min-w-0">
                        <a
                            href="{{ route('vehicles.show', ['vehicle' => $relatedVehicle->slug]) }}"
                            class="block overflow-hidden bg-fog"
                        >
                            @php($relatedImage = $relatedVehicle->primaryImage?->image_path)

                            @if ($relatedImage && Storage::disk('public')->exists($relatedImage))
                                <img
                                    src="{{ Storage::disk('public')->url($relatedImage) }}"
                                    alt="{{ $relatedVehicle->brand }} {{ $relatedVehicle->name }}"
                                    loading="lazy"
                                    class="aspect-[4/3] w-full object-cover transition-transform duration-700 group-hover:scale-[1.025] motion-reduce:transition-none"
                                >
                            @else
                                <div class="flex aspect-[4/3] items-center justify-center text-sm text-[#777]">
                                    Foto kendaraan belum tersedia
                                </div>
                            @endif
                        </a>

                        <div class="border-b border-black/15 py-5">
                            <p class="section-label">
                                {{ $relatedVehicle->year }}
                            </p>

                            <h3 class="mt-2 text-xl font-medium text-carbon">
                                {{ $relatedVehicle->brand }} {{ $relatedVehicle->name }}
                            </h3>

                            <p class="mt-3 text-base font-medium text-carbon">
                                Rp {{ number_format($relatedVehicle->price, 0, ',', '.') }}
                            </p>

                            <a
                                href="{{ route('vehicles.show', ['vehicle' => $relatedVehicle->slug]) }}"
                                class="text-link mt-4"
                            >
                                Lihat detail
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Closing call to action --}}
    <section class="bg-carbon py-14 text-white sm:py-20">
        <div class="section-shell grid gap-7 sm:grid-cols-[minmax(0,1fr)_auto] sm:items-center sm:gap-12">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#c3c3c3]">
                    Langkah berikutnya
                </p>

                <h2 class="mt-3 max-w-[15ch] text-3xl font-light leading-tight tracking-[-0.05em] text-white sm:text-4xl">
                    Temukan kendaraan yang sesuai untuk Anda.
                </h2>

                <p class="mt-4 max-w-xl text-sm leading-7 text-[#d4d4d4]">
                    Jelajahi pilihan kendaraan lainnya dan bandingkan informasi sebelum menentukan pilihan.
                </p>
            </div>

            <a
                href="{{ route('vehicles.index') }}"
                class="inline-flex min-h-[46px] items-center justify-center border border-white/60 px-5 py-3 text-xs font-semibold uppercase tracking-[0.08em] text-white transition-colors hover:bg-white hover:text-carbon"
            >
                Kembali ke Katalog
            </a>
        </div>
    </section>
@endsection
