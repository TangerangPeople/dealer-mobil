
@extends('public.layouts.app')

@section('title', 'Galeri Dealer')

@section('content')
    @php
        $galleryTypes = collect($gallery)
            ->pluck('gallery_type')
            ->filter(fn ($type) => is_string($type) && trim($type) !== '')
            ->unique()
            ->values();

        $selectedType = request('type');

        $filteredGallery = collect($gallery)
            ->filter(function ($item) use ($selectedType) {
                return ! $selectedType
                    || ($item->gallery_type ?? '') === $selectedType;
            })
            ->values();

        $galleryCount = $filteredGallery->count();
    @endphp

    <section class="section-shell py-12 sm:py-16 lg:py-20">
        {{-- Judul --}}
        <header class="mx-auto max-w-3xl text-center">
            <p class="section-label text-[#5f5f5f]">
                Galeri
            </p>

            <h1 class="mt-4 text-3xl font-light leading-tight tracking-[-0.04em] text-[#1e1e1e] sm:text-4xl lg:text-5xl">
                Galeri Dealer
            </h1>

            <p class="mx-auto mt-5 max-w-2xl text-sm leading-7 text-[#5f5f5f] sm:text-base">
                Dokumentasi kendaraan, showroom, dan aktivitas YAOP Dealer Development.
            </p>
        </header>

        {{-- Filter galeri --}}
        <nav
            class="mt-8 flex flex-wrap items-center justify-center gap-2 sm:gap-3"
            aria-label="Filter kategori galeri"
        >
            <a
                href="{{ route('gallery.index') }}"
                @class([
                    'inline-flex min-h-10 items-center justify-center border px-4 py-2 text-sm font-medium transition-colors',
                    'border-[#262626] bg-[#262626] text-white' => ! $selectedType,
                    'border-black/10 bg-white text-[#404040] hover:border-[#262626] hover:text-[#262626]' => (bool) $selectedType,
                ])
                @if (! $selectedType)
                    aria-current="page"
                @endif
            >
                Semua
            </a>

            @foreach ($galleryTypes as $type)
                <a
                    href="{{ route('gallery.index', ['type' => $type]) }}"
                    @class([
                        'inline-flex min-h-10 items-center justify-center border px-4 py-2 text-sm font-medium transition-colors',
                        'border-[#262626] bg-[#262626] text-white' => $selectedType === $type,
                        'border-black/10 bg-white text-[#404040] hover:border-[#262626] hover:text-[#262626]' => $selectedType !== $type,
                    ])
                    @if ($selectedType === $type)
                        aria-current="page"
                    @endif
                >
                    {{ $type }}
                </a>
            @endforeach
        </nav>

        {{-- Grid foto --}}
        <div
            @class([
                'mt-8 flex flex-wrap items-stretch justify-center gap-5 sm:mt-10 sm:gap-6 lg:gap-7',
                'mx-auto max-w-2xl' => $galleryCount === 1,
                'mx-auto max-w-5xl' => $galleryCount === 2,
                'w-full' => $galleryCount === 0 || $galleryCount >= 3,
            ])
        >
            @forelse ($filteredGallery as $item)
                <figure
                    @class([
                        'editorial-card flex min-w-0 flex-col',
                        'w-full max-w-2xl' => $galleryCount === 1,
                        'w-full sm:w-[calc(50%_-_12px)]' => $galleryCount === 2,
                        'w-full sm:w-[calc(50%_-_12px)] xl:w-[calc(33.333%_-_19px)]' => $galleryCount >= 3,
                    ])
                >
                    {{-- Foto dengan rasio yang konsisten --}}
                    <div class="aspect-[4/3] w-full shrink-0 overflow-hidden bg-[#f1f1f1]">
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
                                    <rect x="3" y="3" width="18" height="18" rx="0" />
                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                    <path d="m21 15-5-5L5 21" />
                                </svg>

                                <span class="text-xs">
                                    Foto belum tersedia
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Keterangan foto --}}
                    <figcaption class="flex flex-1 flex-col p-4 sm:p-5 lg:p-6">
                        <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-[#777777]">
                            {{ $item->gallery_type ?: 'Galeri' }}
                        </p>

                        <h2 class="mt-2 min-h-[3rem] break-words text-lg font-light leading-snug tracking-[-0.02em] text-[#1e1e1e] sm:text-xl [overflow-wrap:anywhere]">
                            {{ $item->title }}
                        </h2>
                    </figcaption>
                </figure>
            @empty
                {{-- Empty state --}}
                <div class="w-full border-y border-[#bbbbbb] py-12 text-center sm:py-16">
                    <h2 class="text-xl font-light text-[#262626] sm:text-2xl">
                        Galeri sedang disiapkan
                    </h2>

                    <p class="mx-auto mt-3 max-w-md text-sm leading-6 text-[#5f5f5f]">
                        @if ($selectedType)
                            Belum ada foto untuk kategori {{ $selectedType }}. Lihat kategori lain atau kembali ke seluruh galeri.
                        @else
                            Foto kendaraan dan dokumentasi dealer akan ditampilkan di halaman ini setelah ditambahkan melalui dashboard administrator.
                        @endif
                    </p>

                    @if ($selectedType)
                        <a
                            href="{{ route('gallery.index') }}"
                            class="mt-6 inline-flex min-h-11 items-center justify-center border border-[#262626] px-5 py-3 text-sm font-medium text-[#262626] transition-colors hover:bg-[#262626] hover:text-white"
                        >
                            Lihat semua galeri
                        </a>
                    @else
                        <a
                            href="{{ route('contact') }}"
                            class="mt-6 inline-flex min-h-11 items-center justify-center border border-[#262626] bg-[#262626] px-5 py-3 text-sm font-medium text-white transition-colors hover:bg-white hover:text-[#262626]"
                        >
                            Hubungi Dealer
                        </a>
                    @endif
                </div>
            @endforelse
        </div>
    </section>
@endsection
