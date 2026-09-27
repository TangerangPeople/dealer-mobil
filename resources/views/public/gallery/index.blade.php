@extends('public.layouts.app')

@section('title', 'Galeri')

@section('content')
    <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8">
        <p class="text-xs uppercase tracking-[0.24em] text-neutral-500">Galeri</p>
        <h1 class="mt-4 text-4xl font-light text-neutral-900 sm:text-5xl">Galeri Dealer</h1>

        <div class="mt-10 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($gallery as $item)
                <figure class="border border-neutral-200 bg-white overflow-hidden">
                    @if ($item->image_path && Storage::disk('public')->exists($item->image_path))
                        <img src="{{ Storage::disk('public')->url($item->image_path) }}" alt="{{ $item->alt_text ?? $item->title }}" class="h-72 w-full object-cover" />
                    @endif
                    <figcaption class="p-5 text-sm text-neutral-600">
                        <span class="block text-xs uppercase tracking-[0.2em] text-neutral-500">{{ $item->gallery_type ?? 'Galeri' }}</span>
                        <span class="mt-2 block text-lg font-medium text-neutral-900">{{ $item->title }}</span>
                    </figcaption>
                </figure>
            @empty
                <p class="text-sm text-neutral-500">Galeri belum tersedia.</p>
            @endforelse
        </div>
    </section>
@endsection
