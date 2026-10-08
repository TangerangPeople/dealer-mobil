@extends('public.layouts.app')

@section('title', 'Promo')

@section('content')
    <section class="section-shell py-16 sm:py-20">
        <p class="section-label text-[#5f5f5f]">Promo</p>
        <h1 class="mt-4 text-4xl font-light tracking-[-0.04em] text-[#1e1e1e] sm:text-5xl">Promo kendaraan</h1>

        <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($promotions as $promo)
                <article class="editorial-card overflow-hidden">
                    <div class="aspect-[4/3] overflow-hidden bg-neutral-100">
                        @if ($promo->image_path && Storage::disk('public')->exists($promo->image_path))
                            <img src="{{ Storage::disk('public')->url($promo->image_path) }}" alt="{{ $promo->title }}" class="h-full w-full object-cover" />
                        @else
                            <div class="flex h-full items-center justify-center text-sm uppercase tracking-[0.2em] text-neutral-400">Promo</div>
                        @endif
                    </div>
                    <div class="p-5 sm:p-6">
                        <p class="text-[10px] uppercase tracking-[0.18em] text-[#777]">{{ $promo->vehicle?->brand ?? 'Dealer' }}</p>
                        <h2 class="mt-2 text-2xl font-light text-[#1e1e1e]">{{ $promo->title }}</h2>
                        <p class="mt-3 text-sm leading-6 text-[#5b5b5b]">{{ $promo->summary }}</p>
                        <a href="{{ route('promotions.show', ['promotion' => $promo->slug]) }}" class="mt-5 inline-flex text-sm font-medium text-[#262626] underline underline-offset-4">Lihat promo</a>
                    </div>
                </article>
            @empty
                <div class="soft-panel col-span-full p-8 text-sm text-[#5b5b5b]">Belum ada promo berlangsung saat ini.</div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $promotions->links() }}
        </div>
    </section>
@endsection
