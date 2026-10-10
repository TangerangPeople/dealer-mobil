
@extends('public.layouts.app')

@section('title', ($settings?->dealer_name ?: config('app.name')) . ' | Layanan')

@section('content')
    @php
        $packageCount = $servicePackages->count();
    @endphp

    <section class="section-shell py-12 sm:py-16 lg:py-20">
        {{-- Heading halaman --}}
        <div class="mx-auto max-w-3xl text-center">
            <p class="section-label text-[#5f5f5f]">Layanan</p>

            <h1 class="mt-4 text-3xl font-light leading-tight tracking-[-0.04em] text-[#1e1e1e] sm:text-4xl lg:text-5xl">
                Paket servis yang kami tawarkan
            </h1>

            <p class="mx-auto mt-5 max-w-2xl text-sm leading-7 text-[#4b4b4b] sm:text-base">
                Pilihan paket perawatan dan servis untuk menjaga performa mobil Anda tetap prima.
            </p>
        </div>

        {{-- Paket servis --}}
        <div class="mt-9 flex flex-wrap items-stretch justify-center gap-5 sm:mt-12 sm:gap-6">
            @forelse ($servicePackages as $package)
                <article
                    @class([
                        'editorial-card flex min-w-0 flex-col p-5 sm:p-6 lg:p-7',
                        'w-full max-w-md' => $packageCount === 1,
                        'w-full sm:w-[calc(50%_-_12px)]' => $packageCount === 2,
                        'w-full sm:w-[calc(50%_-_12px)] xl:w-[calc(33.333%_-_16px)]' => $packageCount >= 3,
                    ])
                >
                    {{-- Jenis layanan --}}
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#777777]">
                            {{ $package->service_type }}
                        </p>

                        <h2 class="mt-4 min-h-[3.5rem] text-xl font-light leading-snug tracking-[-0.025em] text-[#1e1e1e] sm:text-2xl">
                            {{ $package->name }}
                        </h2>
                    </div>

                    {{-- Deskripsi --}}
                    <p class="mt-4 text-sm leading-6 text-[#5d5d5d]">
                        {{ $package->description }}
                    </p>

                    {{-- Informasi harga dan durasi --}}
                    <dl class="mt-6 space-y-4 border-t border-[#bbbbbb] pt-5 text-sm">
                        <div class="flex items-start justify-between gap-4">
                            <dt class="shrink-0 text-[#777777]">
                                Estimasi harga
                            </dt>

                            <dd class="text-right font-semibold text-[#262626]">
                                Rp {{ number_format((float) $package->estimated_price, 0, ',', '.') }}
                            </dd>
                        </div>

                        <div class="flex items-start justify-between gap-4">
                            <dt class="shrink-0 text-[#777777]">
                                Durasi
                            </dt>

                            <dd class="text-right font-medium text-[#262626]">
                                {{ $package->estimated_duration }}
                            </dd>
                        </div>
                    </dl>

                    {{-- CTA selalu berada di bagian bawah kartu --}}
                    <div class="mt-auto pt-7">
                        <a
                            href="{{ route('booking-service') }}"
                            class="flex min-h-12 w-full items-center justify-center border border-[#262626] bg-[#262626] px-5 py-3 text-center text-sm font-semibold text-white transition-colors hover:bg-white hover:text-[#262626] focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2"
                        >
                            Booking sekarang
                        </a>
                    </div>
                </article>
            @empty
                <div class="w-full border-y border-[#bbbbbb] py-12 text-center sm:py-16">
                    <h2 class="text-xl font-light text-[#262626]">
                        Paket servis belum tersedia
                    </h2>

                    <p class="mx-auto mt-3 max-w-md text-sm leading-6 text-[#5d5d5d]">
                        Belum ada paket servis aktif saat ini. Silakan hubungi tim sales untuk informasi lebih lanjut.
                    </p>

                    <a
                        href="{{ route('sales.chat') }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="mt-6 inline-flex min-h-11 items-center justify-center border border-[#262626] px-5 py-3 text-sm font-medium text-[#262626] transition-colors hover:bg-[#262626] hover:text-white"
                    >
                        Chat Sales
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Aksi tambahan --}}
        <div class="mt-10 flex flex-wrap items-center justify-center gap-3 border-t border-black/10 pt-8 sm:mt-12">
            <a
                href="{{ route('sales.chat') }}"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex min-h-11 items-center justify-center border border-[#262626] px-5 py-3 text-sm font-medium text-[#262626] transition-colors hover:bg-[#262626] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2"
            >
                Chat Sales
            </a>

            <a
                href="{{ route('contact') }}"
                class="inline-flex min-h-11 items-center justify-center border border-[#262626] bg-[#262626] px-5 py-3 text-sm font-medium text-white transition-colors hover:bg-white hover:text-[#262626] focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2"
            >
                Kontak
            </a>
        </div>
    </section>
@endsection
