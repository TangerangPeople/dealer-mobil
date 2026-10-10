
@extends('public.layouts.app')

@section('title', 'Katalog Mobil')

@section('content')
    <section class="section-shell py-12 sm:py-16 lg:py-20">
        {{-- Heading --}}
        <div>
            <p class="section-label text-[#5f5f5f]">Katalog</p>

            <h1 class="mt-3 text-3xl font-light tracking-[-0.04em] text-[#1e1e1e] sm:text-4xl lg:text-5xl">
                Katalog Mobil
            </h1>
        </div>

        {{-- Filter kategori --}}
        <div class="mt-7 flex flex-wrap gap-2 sm:gap-3">
            <a
                href="{{ route('vehicles.index') }}"
                @class([
                    'inline-flex min-h-10 items-center justify-center border px-4 py-2 text-sm font-medium transition-colors',
                    'border-[#262626] bg-[#262626] text-white' => ! request()->filled('category'),
                    'border-black/10 bg-white text-[#404040] hover:border-[#262626] hover:text-[#262626]' => request()->filled('category'),
                ])
                @if (! request()->filled('category'))
                    aria-current="page"
                @endif
            >
                Semua
            </a>

            @foreach ($categories as $category)
                <a
                    href="{{ route('vehicles.index', ['category' => $category->slug]) }}"
                    @class([
                        'inline-flex min-h-10 items-center justify-center border px-4 py-2 text-sm font-medium transition-colors',
                        'border-[#262626] bg-[#262626] text-white' => request('category') === $category->slug,
                        'border-black/10 bg-white text-[#404040] hover:border-[#262626] hover:text-[#262626]' => request('category') !== $category->slug,
                    ])
                    @if (request('category') === $category->slug)
                        aria-current="page"
                    @endif
                >
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        {{-- Grid katalog: menyesuaikan jumlah kendaraan --}}
        <div
            @class([
                'mt-8 grid items-stretch gap-5 sm:mt-10 sm:gap-6',
                'mx-auto max-w-md grid-cols-1' => $vehicles->count() === 1,
                'mx-auto max-w-4xl grid-cols-1 sm:grid-cols-2' => $vehicles->count() === 2,
                'grid-cols-1 sm:grid-cols-2 xl:grid-cols-3' => $vehicles->count() !== 1 && $vehicles->count() !== 2,
            ])
        >
            @forelse ($vehicles as $vehicle)
                <article class="editorial-card flex h-full min-w-0 flex-col">
                    {{-- Foto dengan rasio konsisten --}}
                    <a
                        href="{{ route('vehicles.show', ['vehicle' => $vehicle->slug]) }}"
                        class="block aspect-[4/3] shrink-0 overflow-hidden bg-[#f1f1f1]"
                        aria-label="Lihat detail {{ $vehicle->brand }} {{ $vehicle->name }}"
                    >
                        @php($image = $vehicle->primaryImage?->image_path)

                        @if ($image && Storage::disk('public')->exists($image))
                            <img
                                src="{{ Storage::disk('public')->url($image) }}"
                                alt="{{ $vehicle->primaryImage?->alt_text ?: $vehicle->brand . ' ' . $vehicle->name }}"
                                class="h-full w-full object-cover"
                                loading="lazy"
                                decoding="async"
                            >
                        @else
                            <div class="flex h-full w-full items-center justify-center text-sm text-[#777777]">
                                Foto kendaraan belum tersedia
                            </div>
                        @endif
                    </a>

                    {{-- Informasi kendaraan --}}
                    <div class="flex flex-1 flex-col p-4 sm:p-5 lg:p-6">
                        <div>
                            <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-[#777777]">
                                {{ $vehicle->category?->name ?? 'Mobil' }}
                            </p>

                            <h2 class="mt-2 min-h-[3.5rem] text-xl font-light leading-snug tracking-[-0.025em] text-[#1e1e1e] sm:text-2xl">
                                {{ $vehicle->brand }} {{ $vehicle->name }}
                            </h2>
                        </div>

                        {{-- Spesifikasi dibuat dengan grid yang sama --}}
                        <dl class="mt-5 grid grid-cols-2 gap-x-3 gap-y-3 border-b border-black/10 pb-5 text-sm leading-5 text-[#5f5f5f] sm:gap-x-4">
                            <div class="min-w-0">
                                <dt class="sr-only">Tahun</dt>
                                <dd class="break-words">Tahun: {{ $vehicle->year }}</dd>
                            </div>

                            <div class="min-w-0">
                                <dt class="sr-only">Transmisi</dt>
                                <dd class="break-words">Transmisi: {{ $vehicle->transmission ?: '-' }}</dd>
                            </div>

                            <div class="min-w-0">
                                <dt class="sr-only">Warna</dt>
                                <dd class="break-words">Warna: {{ $vehicle->color ?: '-' }}</dd>
                            </div>

                            <div class="min-w-0">
                                <dt class="sr-only">Kilometer</dt>
                                <dd class="break-words">KM: {{ number_format((float) $vehicle->mileage, 0, ',', '.') }}</dd>
                            </div>
                        </dl>

                        {{-- Harga dan detail selalu berada di bagian bawah --}}
                        <div class="mt-auto flex flex-wrap items-center justify-between gap-3 pt-5">
                            <p class="text-lg font-semibold leading-snug text-[#1e1e1e] sm:text-xl">
                                Rp {{ number_format((float) $vehicle->price, 0, ',', '.') }}
                            </p>

                            <a
                                href="{{ route('vehicles.show', ['vehicle' => $vehicle->slug]) }}"
                                class="shrink-0 text-sm font-medium text-[#262626] underline decoration-1 underline-offset-4 transition-opacity hover:opacity-60 focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2"
                            >
                                Detail
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full border-y border-[#bbbbbb] py-12 text-center sm:py-16">
                    <h2 class="text-xl font-light text-[#262626]">
                        Belum ada kendaraan
                    </h2>

                    <p class="mx-auto mt-3 max-w-md text-sm leading-6 text-[#5f5f5f]">
                        Kendaraan yang sesuai belum tersedia. Silakan pilih kategori lain untuk melihat koleksi mobil.
                    </p>

                    @if (request()->filled('category'))
                        <a
                            href="{{ route('vehicles.index') }}"
                            class="mt-5 inline-flex items-center border border-[#262626] px-4 py-3 text-sm font-medium text-[#262626] transition-colors hover:bg-[#262626] hover:text-white"
                        >
                            Lihat semua kendaraan
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($vehicles->hasPages())
            <div class="mt-10">
                {{ $vehicles->links() }}
            </div>
        @endif
    </section>
@endsection
