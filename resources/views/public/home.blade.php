@extends('public.layouts.app')

@section('title', $settings->dealer_name.' | Beranda')

@section('content')
    <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid items-end gap-8 lg:grid-cols-[1.2fr_0.8fr]">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-neutral-500">Dealer mobil premium</p>
                <h1 class="mt-4 max-w-2xl text-4xl font-light tracking-tight text-neutral-900 sm:text-5xl lg:text-6xl">
                    {{ $settings->hero_title ?? 'Temukan mobil impian Anda' }}
                </h1>
                <p class="mt-6 max-w-xl text-base leading-7 text-neutral-600">
                    {{ $settings->hero_description ?? 'Kendaraan berkualitas dengan layanan terbaik.' }}
                </p>

                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('vehicles.index') }}" class="inline-flex items-center border border-neutral-900 bg-neutral-900 px-6 py-3 text-sm font-medium text-white hover:bg-neutral-700">
                        Lihat Katalog
                    </a>
                    <a href="{{ route('contact') }}" class="inline-flex items-center border border-neutral-300 px-6 py-3 text-sm font-medium text-neutral-900 hover:border-neutral-900">
                        Hubungi Sales
                    </a>
                </div>
            </div>

            <div class="rounded-none border border-neutral-200 bg-neutral-50 p-6">
                <p class="text-xs uppercase tracking-[0.22em] text-neutral-500">Mobil unggulan</p>
                <div class="mt-4 space-y-3 text-neutral-900">
                    @if ($featuredVehicles->isNotEmpty())
                        @foreach ($featuredVehicles->take(2) as $vehicle)
                            <div class="flex items-center justify-between border-b border-neutral-200 pb-3 last:border-b-0 last:pb-0">
                                <div>
                                    <p class="text-lg font-medium">{{ $vehicle->brand }} {{ $vehicle->name }}</p>
                                    <p class="text-sm text-neutral-500">{{ $vehicle->year }} · {{ $vehicle->transmission }}</p>
                                </div>
                                <p class="text-sm font-medium">Rp {{ number_format($vehicle->price, 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    @else
                        <p class="text-sm text-neutral-500">Belum ada kendaraan unggulan.</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="border-t border-neutral-200 bg-neutral-50 py-16">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex items-end justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.22em] text-neutral-500">Katalog</p>
                    <h2 class="mt-2 text-3xl font-light text-neutral-900 sm:text-4xl">Kendaraan unggulan</h2>
                </div>
                <a href="{{ route('vehicles.index') }}" class="text-sm font-medium text-neutral-900 underline underline-offset-4">Lihat semua</a>
            </div>

            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                @forelse ($featuredVehicles as $vehicle)
                    <article class="border border-neutral-200 bg-white">
                        <div class="aspect-[4/3] overflow-hidden bg-neutral-100">
                            @php($image = $vehicle->primaryImage?->image_path)
                            @if ($image && Storage::disk('public')->exists($image))
                                <img src="{{ Storage::disk('public')->url($image) }}" alt="{{ $vehicle->name }}" class="h-full w-full object-cover" />
                            @else
                                <div class="flex h-full items-center justify-center text-sm text-neutral-400">Foto mobil</div>
                            @endif
                        </div>
                        <div class="space-y-4 p-5">
                            <div>
                                <p class="text-xs uppercase tracking-[0.2em] text-neutral-500">{{ $vehicle->category->name ?? 'Mobil' }}</p>
                                <h3 class="mt-2 text-2xl font-light text-neutral-900">{{ $vehicle->brand }} {{ $vehicle->name }}</h3>
                            </div>
                            <div class="flex items-center justify-between text-sm text-neutral-600">
                                <span>{{ $vehicle->year }}</span>
                                <span>{{ $vehicle->transmission }}</span>
                            </div>
                            <div class="flex items-center justify-between border-t border-neutral-200 pt-4">
                                <span class="text-lg font-medium text-neutral-900">Rp {{ number_format($vehicle->price, 0, ',', '.') }}</span>
                                <a href="{{ route('vehicles.show', ['vehicle' => $vehicle->slug]) }}" class="text-sm font-medium text-neutral-900 underline underline-offset-4">Lihat Detail</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="text-sm text-neutral-500">Belum ada kendaraan unggulan saat ini.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="mb-8">
            <p class="text-xs uppercase tracking-[0.22em] text-neutral-500">Promo</p>
            <h2 class="mt-2 text-3xl font-light text-neutral-900 sm:text-4xl">Promo terbaru</h2>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            @forelse ($latestPromotions as $promo)
                <article class="border border-neutral-200 bg-white">
                    <div class="aspect-[4/3] overflow-hidden bg-neutral-100">
                        @if ($promo->image_path && Storage::disk('public')->exists($promo->image_path))
                            <img src="{{ Storage::disk('public')->url($promo->image_path) }}" alt="{{ $promo->title }}" class="h-full w-full object-cover" />
                        @else
                            <div class="flex h-full items-center justify-center text-sm text-neutral-400">Promosi</div>
                        @endif
                    </div>
                    <div class="p-5">
                        <p class="text-xs uppercase tracking-[0.18em] text-neutral-500">Promo</p>
                        <h3 class="mt-2 text-2xl font-light text-neutral-900">{{ $promo->title }}</h3>
                        <p class="mt-3 text-sm leading-6 text-neutral-600">{{ $promo->summary }}</p>
                        <a href="{{ route('promotions.show', ['promotion' => $promo->slug]) }}" class="mt-5 inline-flex text-sm font-medium text-neutral-900 underline underline-offset-4">Selengkapnya</a>
                    </div>
                </article>
            @empty
                <p class="text-sm text-neutral-500">Belum ada promo yang tersedia saat ini.</p>
            @endforelse
        </div>
    </section>

    <section class="border-t border-neutral-200 bg-neutral-50 py-16">
        <div class="mx-auto grid max-w-6xl gap-10 px-4 sm:px-6 lg:grid-cols-[1.2fr_0.8fr] lg:px-8">
            <div>
                <p class="text-xs uppercase tracking-[0.22em] text-neutral-500">Galeri</p>
                <h2 class="mt-2 text-3xl font-light text-neutral-900 sm:text-4xl">Eksperimen showroom dan mobil kami</h2>
                <div class="mt-8 grid grid-cols-2 gap-4">
                    @forelse ($gallery->take(4) as $item)
                        <div class="overflow-hidden border border-neutral-200 bg-white">
                            @if ($item->image_path && Storage::disk('public')->exists($item->image_path))
                                <img src="{{ Storage::disk('public')->url($item->image_path) }}" alt="{{ $item->alt_text ?? $item->title }}" class="h-44 w-full object-cover" />
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-neutral-500">Galeri belum tersedia.</p>
                    @endforelse
                </div>
            </div>

            <div class="border border-neutral-200 bg-white p-8">
                <p class="text-xs uppercase tracking-[0.22em] text-neutral-500">Lokasi</p>
                <h3 class="mt-3 text-3xl font-light text-neutral-900">{{ $settings->dealer_name }}</h3>
                <p class="mt-5 text-base leading-7 text-neutral-600">
                    {{ $settings->address ?? 'Alamat belum diatur.' }}
                </p>
                <div class="mt-8 space-y-3 text-sm text-neutral-700">
                    <p>Telepon: {{ $settings->phone ?? '-' }}</p>
                    <p>Email: {{ $settings->email ?? '-' }}</p>
                    <p>Jam operasional: {{ $settings->opening_hours ? collect($settings->opening_hours)->first()['day'] ?? 'Senin - Sabtu' : 'Senin - Sabtu' }}</p>
                </div>
                <a href="{{ route('contact') }}" class="mt-8 inline-flex border border-neutral-900 px-5 py-3 text-sm font-medium text-neutral-900 hover:bg-neutral-900 hover:text-white">
                    Hubungi Dealer
                </a>
            </div>
        </div>
    </section>
    @if ($sales->isNotEmpty())
        <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="mb-8">
                <p class="text-xs uppercase tracking-[0.22em] text-neutral-500">Sales</p>
                <h2 class="mt-2 text-3xl font-light text-neutral-900 sm:text-4xl">Tim sales kami</h2>
            </div>
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                @foreach ($sales as $sale)
                    <div class="border border-neutral-200 bg-white p-5">
                        <p class="text-lg font-medium text-neutral-900">{{ $sale->name }}</p>
                        <p class="mt-2 text-sm text-neutral-500">{{ $sale->position ?? 'Sales Representative' }}</p>
                        <div class="mt-5 space-y-2 text-sm text-neutral-600">
                            <p>WhatsApp: {{ $sale->whatsapp ?? '-' }}</p>
                            <p>Phone: {{ $sale->phone ?? '-' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
@endsection
