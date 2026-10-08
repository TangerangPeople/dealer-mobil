@extends('public.layouts.app')

@section('title', $promotionModel->title)

@section('content')
    <section class="section-shell py-16 sm:py-20">
        <div class="overflow-hidden border border-black/10 bg-[#f5f5f5]">
            @if ($promotionModel->image_path && Storage::disk('public')->exists($promotionModel->image_path))
                <img src="{{ Storage::disk('public')->url($promotionModel->image_path) }}" alt="{{ $promotionModel->title }}" class="h-[320px] w-full object-cover sm:h-[420px]" />
            @endif
        </div>

        <div class="mt-10 grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
            <article>
                <p class="section-label text-[#5f5f5f]">Promo</p>
                <h1 class="mt-4 text-4xl font-light tracking-[-0.04em] text-[#1e1e1e] sm:text-5xl">{{ $promotionModel->title }}</h1>
                <p class="mt-5 text-sm uppercase tracking-[0.2em] text-[#666]">
                    {{ $promotionModel->start_date?->format('d M Y') ?? '-' }} — {{ $promotionModel->end_date?->format('d M Y') ?? '-' }}
                </p>
                <p class="mt-6 text-base leading-8 text-[#4b4b4b]">{{ $promotionModel->description }}</p>
            </article>

            <aside class="soft-panel p-6 sm:p-8">
                <p class="section-label text-[#5f5f5f]">Detail</p>
                <div class="mt-5 space-y-3 text-sm text-[#4b4b4b]">
                    <p>Mulai: {{ $promotionModel->start_date?->format('d M Y') ?? '-' }}</p>
                    <p>Berakhir: {{ $promotionModel->end_date?->format('d M Y') ?? '-' }}</p>
                    @if ($promotionModel->vehicle)
                        <p>Mobil terkait: {{ $promotionModel->vehicle->brand }} {{ $promotionModel->vehicle->name }}</p>
                    @endif
                </div>
                <a href="{{ route('sales.chat', $promotionModel->vehicle ? ['vehicle' => $promotionModel->vehicle->slug] : []) }}" target="_blank" rel="noopener noreferrer" class="mt-8 inline-flex border border-[#262626] bg-[#262626] px-5 py-3 text-sm font-medium text-white transition hover:bg-[#101010]">
                    Chat Sales
                </a>
                <a href="{{ route('contact') }}" class="mt-3 inline-flex border border-[#262626] px-5 py-3 text-sm font-medium text-[#262626]">Hubungi Dealer</a>
            </aside>
        </div>

        @if ($promotionModel->vehicle)
            <div class="mt-16">
                <p class="section-label text-[#5f5f5f]">Kendaraan terkait</p>
                <div class="mt-6 grid gap-6 border border-black/10 bg-white md:grid-cols-[0.9fr_1.1fr]">
                    <div class="aspect-[4/3] overflow-hidden bg-neutral-100">
                        @php($vehicleImage = $promotionModel->vehicle->primaryImage?->image_path)
                        @if ($vehicleImage && Storage::disk('public')->exists($vehicleImage))
                            <img src="{{ Storage::disk('public')->url($vehicleImage) }}" alt="{{ $promotionModel->vehicle->name }}" class="h-full w-full object-cover" />
                        @endif
                    </div>
                    <div class="flex flex-col justify-center p-6 sm:p-8">
                        <p class="text-[10px] uppercase tracking-[0.2em] text-[#777]">{{ $promotionModel->vehicle->brand }}</p>
                        <h2 class="mt-3 text-3xl font-light text-[#1e1e1e]">{{ $promotionModel->vehicle->name }}</h2>
                        <p class="mt-4 text-lg font-medium text-[#1e1e1e]">Rp {{ number_format($promotionModel->vehicle->price, 0, ',', '.') }}</p>
                        <a href="{{ route('vehicles.show', ['vehicle' => $promotionModel->vehicle->slug]) }}" class="mt-6 inline-flex w-fit text-sm font-medium text-[#262626] underline underline-offset-4">Lihat Kendaraan</a>
                    </div>
                </div>
            </div>
        @endif
    </section>
@endsection
