
@extends('public.layouts.app')

@section('title', 'Promo Kendaraan')

@section('content')
    @php
        $promotionCount = $promotions->count();
    @endphp

    <section class="section-shell py-12 sm:py-16 lg:py-20">
        {{-- Judul halaman --}}
        <header class="mx-auto max-w-3xl text-center">
            <p class="section-label text-[#5f5f5f]">
                Promo
            </p>

            <h1 class="mt-4 text-3xl font-light leading-tight tracking-[-0.04em] text-[#1e1e1e] sm:text-4xl lg:text-5xl">
                Promo kendaraan
            </h1>

            <p class="mx-auto mt-5 max-w-2xl text-sm leading-7 text-[#5f5f5f] sm:text-base">
                Temukan informasi promo dan penawaran kendaraan dari YAOP Dealer Development.
            </p>
        </header>

        {{-- Grid promo --}}
        <div
            @class([
                'mt-8 flex flex-wrap items-stretch justify-center gap-5 sm:mt-10 sm:gap-6 xl:gap-7',
                'mx-auto max-w-2xl' => $promotionCount === 1,
                'mx-auto max-w-5xl' => $promotionCount === 2,
                'w-full' => $promotionCount === 0 || $promotionCount >= 3,
            ])
        >
            @forelse ($promotions as $promo)
                <article
                    @class([
                        'editorial-card flex min-w-0 flex-col',
                        'w-full max-w-2xl' => $promotionCount === 1,
                        'w-full sm:w-[calc(50%_-_12px)]' => $promotionCount === 2,
                        'w-full sm:w-[calc(50%_-_12px)] xl:w-[calc(33.333%_-_19px)]' => $promotionCount >= 3,
                    ])
                >
                    {{-- Foto promo --}}
                    <div class="aspect-[4/3] w-full shrink-0 overflow-hidden bg-[#f1f1f1]">
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
                            <div class="flex h-full w-full flex-col items-center justify-center gap-3 px-4 text-center text-[#777777]">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="32"
                                    height="32"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.25"
                                    aria-hidden="true"
                                >
                                    <rect x="3" y="3" width="18" height="18" />
                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                    <path d="m21 15-5-5L5 21" />
                                </svg>

                                <span class="text-xs">
                                    Foto promo belum tersedia
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Informasi promo --}}
                    <div class="flex flex-1 flex-col p-5 sm:p-6 lg:p-7">
                        <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-[#777777]">
                            {{ $promo->vehicle?->brand ?? 'YAOP Dealer Development' }}
                        </p>

                        <h2 class="mt-3 min-h-[3.5rem] break-words text-xl font-light leading-snug tracking-[-0.025em] text-[#1e1e1e] sm:text-2xl [overflow-wrap:anywhere]">
                            {{ $promo->title }}
                        </h2>

                        <p class="mt-4 break-words text-sm leading-6 text-[#5b5b5b] [overflow-wrap:anywhere]">
                            {{ $promo->summary }}
                        </p>

                        {{-- Link diposisikan di bagian bawah --}}
                        <div class="mt-auto border-t border-[#bbbbbb] pt-5">
                            <a
                                href="{{ route('promotions.show', ['promotion' => $promo->slug]) }}"
                                class="inline-flex min-h-10 items-center gap-2 text-sm font-semibold text-[#262626] underline decoration-1 underline-offset-4 transition-opacity hover:opacity-60 focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2"
                            >
                                Lihat promo
                                <span aria-hidden="true">→</span>
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                {{-- Tampilan ketika tidak ada promo --}}
                <div class="w-full border-y border-[#bbbbbb] py-12 text-center sm:py-16">
                    <h2 class="text-xl font-light text-[#262626] sm:text-2xl">
                        Belum ada promo aktif
                    </h2>

                    <p class="mx-auto mt-3 max-w-md text-sm leading-6 text-[#5f5f5f]">
                        Informasi penawaran kendaraan akan ditampilkan di halaman ini ketika tersedia.
                    </p>

                    <a
                        href="{{ route('vehicles.index') }}"
                        class="mt-6 inline-flex min-h-11 items-center justify-center border border-[#262626] bg-[#262626] px-5 py-3 text-sm font-medium text-white transition-colors hover:bg-white hover:text-[#262626] focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2"
                    >
                        Lihat katalog mobil
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($promotions->hasPages())
            <nav class="mt-10" aria-label="Navigasi halaman promo">
                {{ $promotions->links() }}
            </nav>
        @endif
    </section>
@endsection
