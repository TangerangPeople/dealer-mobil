@extends('public.layouts.app')

@section('title', 'Katalog Mobil')

@section('content')
    <section class="section-shell py-16 sm:py-20">
        <p class="section-label text-[#5f5f5f]">Katalog</p>
        <h1 class="mt-4 text-4xl font-light tracking-[-0.04em] text-[#1e1e1e] sm:text-5xl">Katalog Mobil</h1>

        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('vehicles.index') }}" class="border border-[#262626] bg-[#262626] px-4 py-2 text-sm font-medium text-white">Semua</a>
            @foreach ($categories as $category)
                <a href="{{ route('vehicles.index', ['category' => $category->slug]) }}" class="border border-black/10 bg-white px-4 py-2 text-sm font-medium text-[#404040] transition hover:border-[#262626] hover:text-[#262626]">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($vehicles as $vehicle)
                <article class="editorial-card overflow-hidden">
                    <div class="aspect-[4/3] overflow-hidden bg-neutral-100">
                        @php($image = $vehicle->primaryImage?->image_path)
                        @if ($image && Storage::disk('public')->exists($image))
                            <img src="{{ Storage::disk('public')->url($image) }}" alt="{{ $vehicle->name }}" class="h-full w-full object-cover" />
                        @else
                            <div class="flex h-full items-center justify-center text-sm text-neutral-400">Foto kendaraan</div>
                        @endif
                    </div>
                    <div class="space-y-4 p-5">
                        <div>
                            <p class="text-[10px] uppercase tracking-[0.18em] text-[#777]">{{ $vehicle->category->name ?? 'Mobil' }}</p>
                            <h2 class="mt-2 text-2xl font-light text-[#1e1e1e]">{{ $vehicle->brand }} {{ $vehicle->name }}</h2>
                        </div>
                        <div class="grid grid-cols-2 gap-3 text-sm text-[#5f5f5f]">
                            <span>Tahun: {{ $vehicle->year }}</span>
                            <span>Transmisi: {{ $vehicle->transmission }}</span>
                            <span>Warna: {{ $vehicle->color }}</span>
                            <span>KM: {{ number_format($vehicle->mileage, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between border-t border-black/10 pt-4">
                            <span class="text-xl font-medium text-[#1e1e1e]">Rp {{ number_format($vehicle->price, 0, ',', '.') }}</span>
                            <a href="{{ route('vehicles.show', ['vehicle' => $vehicle->slug]) }}" class="text-sm font-medium text-[#262626] underline underline-offset-4">Detail</a>
                        </div>
                    </div>
                </article>
            @empty
                <p class="text-sm text-neutral-500">Belum ada kendaraan yang tersedia.</p>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $vehicles->links() }}
        </div>
    </section>
@endsection
