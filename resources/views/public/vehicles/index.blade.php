@extends('public.layouts.app')

@section('title', 'Katalog Mobil')

@section('content')
    <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8">
        <p class="text-xs uppercase tracking-[0.24em] text-neutral-500">Katalog</p>
        <h1 class="mt-4 text-4xl font-light text-neutral-900 sm:text-5xl">Katalog Mobil</h1>

        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('vehicles.index') }}" class="border border-neutral-900 bg-neutral-900 px-4 py-2 text-sm font-medium text-white">Semua</a>
            @foreach ($categories as $category)
                <a href="{{ route('vehicles.index', ['category' => $category->slug]) }}" class="border border-neutral-300 px-4 py-2 text-sm font-medium text-neutral-700 hover:border-neutral-900">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($vehicles as $vehicle)
                <article class="border border-neutral-200 bg-white">
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
                            <p class="text-xs uppercase tracking-[0.18em] text-neutral-500">{{ $vehicle->category->name ?? 'Mobil' }}</p>
                            <h2 class="mt-2 text-2xl font-light text-neutral-900">{{ $vehicle->brand }} {{ $vehicle->name }}</h2>
                        </div>
                        <div class="grid grid-cols-2 gap-3 text-sm text-neutral-600">
                            <span>Tahun: {{ $vehicle->year }}</span>
                            <span>Transmisi: {{ $vehicle->transmission }}</span>
                            <span>Warna: {{ $vehicle->color }}</span>
                            <span>KM: {{ number_format($vehicle->mileage, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between border-t border-neutral-200 pt-4">
                            <span class="text-xl font-medium text-neutral-900">Rp {{ number_format($vehicle->price, 0, ',', '.') }}</span>
                            <a href="{{ route('vehicles.show', ['vehicle' => $vehicle->slug]) }}" class="text-sm font-medium text-neutral-900 underline underline-offset-4">Detail</a>
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
