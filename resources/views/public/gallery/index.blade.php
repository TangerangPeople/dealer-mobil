@extends('public.layouts.app')

@section('title', 'Galeri')

@section('content')
    <section class="section-shell py-16 sm:py-20">
        <p class="section-label text-[#5f5f5f]">Galeri</p>
        <h1 class="mt-4 text-4xl font-light tracking-[-0.04em] text-[#1e1e1e] sm:text-5xl">Galeri Dealer</h1>

        @php($galleryTypes = collect($gallery)->map->gallery_type->filter()->unique()->values()->all())
        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('gallery.index') }}" class="border border-[#262626] bg-[#262626] px-4 py-2 text-sm font-medium text-white {{ request('type') ? '' : '' }}">Semua</a>
            @foreach ($galleryTypes as $type)
                <a href="{{ route('gallery.index', ['type' => $type]) }}" class="border border-black/10 bg-white px-4 py-2 text-sm font-medium text-[#404040] transition hover:border-[#262626] hover:text-[#262626] {{ request('type') === $type ? 'border-[#262626] text-[#262626]' : '' }}">
                    {{ $type }}
                </a>
            @endforeach
        </div>

        @php($filteredGallery = $gallery->filter(function ($item) { return ! request('type') || ($item->gallery_type ?? '') === request('type'); })->values())

        <div class="mt-10 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($filteredGallery as $item)
                <figure class="editorial-card overflow-hidden">
                    @if ($item->image_path && Storage::disk('public')->exists($item->image_path))
                        <img src="{{ Storage::disk('public')->url($item->image_path) }}" alt="{{ $item->alt_text ?? $item->title }}" class="h-72 w-full object-cover" />
                    @endif
                    <figcaption class="p-5 text-sm text-[#4b4b4b]">
                        <span class="block text-[10px] uppercase tracking-[0.2em] text-[#777]">{{ $item->gallery_type ?? 'Galeri' }}</span>
                        <span class="mt-2 block text-lg font-medium text-[#1e1e1e]">{{ $item->title }}</span>
                    </figcaption>
                </figure>
            @empty
                <div class="soft-panel col-span-full p-8 text-sm text-[#5b5b5b]">Galeri belum tersedia untuk kategori ini.</div>
            @endforelse
        </div>
    </section>
@endsection
