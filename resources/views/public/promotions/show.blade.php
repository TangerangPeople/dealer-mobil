@extends('public.layouts.app')

@section('title', $promotionModel->title)

@section('content')
    <section class="mx-auto max-w-5xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="overflow-hidden border border-neutral-200 bg-white">
            @if ($promotionModel->image_path && Storage::disk('public')->exists($promotionModel->image_path))
                <img src="{{ Storage::disk('public')->url($promotionModel->image_path) }}" alt="{{ $promotionModel->title }}" class="h-[320px] w-full object-cover sm:h-[420px]" />
            @endif
        </div>

        <div class="mt-10 grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
            <article>
                <p class="text-xs uppercase tracking-[0.22em] text-neutral-500">Promo</p>
                <h1 class="mt-4 text-4xl font-light text-neutral-900 sm:text-5xl">{{ $promotionModel->title }}</h1>
                <p class="mt-6 text-base leading-7 text-neutral-600">{{ $promotionModel->description }}</p>
            </article>

            <aside class="border border-neutral-200 bg-neutral-50 p-6">
                <p class="text-xs uppercase tracking-[0.22em] text-neutral-500">Detail</p>
                <div class="mt-5 space-y-3 text-sm text-neutral-700">
                    <p>Mulai: {{ $promotionModel->start_date?->format('d M Y') ?? '-' }}</p>
                    <p>Berakhir: {{ $promotionModel->end_date?->format('d M Y') ?? '-' }}</p>
                    @if ($promotionModel->vehicle)
                        <p>Mobil terkait: {{ $promotionModel->vehicle->brand }} {{ $promotionModel->vehicle->name }}</p>
                    @endif
                </div>
                <a href="{{ route('contact') }}" class="mt-8 inline-flex border border-neutral-900 bg-neutral-900 px-5 py-3 text-sm font-medium text-white hover:bg-neutral-700">
                    Hubungi Sales
                </a>
            </aside>
        </div>
    </section>
@endsection
