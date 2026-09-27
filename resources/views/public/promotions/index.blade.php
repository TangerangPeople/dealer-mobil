@extends('public.layouts.app')

@section('title', 'Promo')

@section('content')
    <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8">
        <p class="text-xs uppercase tracking-[0.24em] text-neutral-500">Promo</p>
        <h1 class="mt-4 text-4xl font-light text-neutral-900 sm:text-5xl">Promo kendaraan</h1>

        <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($promotions as $promo)
                <article class="border border-neutral-200 bg-white">
                    <div class="aspect-[4/3] overflow-hidden bg-neutral-100">
                        @if ($promo->image_path && Storage::disk('public')->exists($promo->image_path))
                            <img src="{{ Storage::disk('public')->url($promo->image_path) }}" alt="{{ $promo->title }}" class="h-full w-full object-cover" />
                        @else
                            <div class="flex h-full items-center justify-center text-sm text-neutral-400">Promo</div>
                        @endif
                    </div>
                    <div class="p-5">
                        <p class="text-xs uppercase tracking-[0.18em] text-neutral-500">{{ $promo->vehicle?->brand ?? 'Dealer' }}</p>
                        <h2 class="mt-2 text-2xl font-light text-neutral-900">{{ $promo->title }}</h2>
                        <p class="mt-3 text-sm leading-6 text-neutral-600">{{ $promo->summary }}</p>
                        <a href="{{ route('promotions.show', ['promotion' => $promo->slug]) }}" class="mt-5 inline-flex text-sm font-medium text-neutral-900 underline underline-offset-4">Lihat promo</a>
                    </div>
                </article>
            @empty
                <p class="text-sm text-neutral-500">Belum ada promo berlangsung saat ini.</p>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $promotions->links() }}
        </div>
    </section>
@endsection
