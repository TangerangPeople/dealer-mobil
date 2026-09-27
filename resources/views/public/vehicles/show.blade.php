@extends('public.layouts.app')

@section('title', $vehicleModel->brand.' '.$vehicleModel->name)

@section('content')
    <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
            <div>
                <p class="text-xs uppercase tracking-[0.22em] text-neutral-500">{{ $vehicleModel->category->name ?? 'Kendaraan' }}</p>
                <h1 class="mt-3 text-4xl font-light text-neutral-900 sm:text-5xl">{{ $vehicleModel->brand }} {{ $vehicleModel->name }}</h1>
                <div class="mt-6 overflow-hidden border border-neutral-200 bg-neutral-50">
                    @php($image = $vehicleModel->primaryImage?->image_path)
                    @if ($image && Storage::disk('public')->exists($image))
                        <img src="{{ Storage::disk('public')->url($image) }}" alt="{{ $vehicleModel->name }}" class="h-[420px] w-full object-cover sm:h-[520px]" />
                    @else
                        <div class="flex h-[420px] items-center justify-center text-neutral-400 sm:h-[520px]">Foto mobil belum tersedia</div>
                    @endif
                </div>

                <div class="mt-8 grid gap-4 sm:grid-cols-3">
                    @foreach ($vehicleModel->images->take(3) as $image)
                        @if ($image->image_path && Storage::disk('public')->exists($image->image_path))
                            <div class="overflow-hidden border border-neutral-200 bg-neutral-100">
                                <img src="{{ Storage::disk('public')->url($image->image_path) }}" alt="{{ $image->alt_text ?? $vehicleModel->name }}" class="h-36 w-full object-cover" />
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <aside class="border border-neutral-200 bg-neutral-50 p-6">
                <p class="text-xs uppercase tracking-[0.2em] text-neutral-500">Harga</p>
                <p class="mt-3 text-4xl font-light text-neutral-900">Rp {{ number_format($vehicleModel->price, 0, ',', '.') }}</p>
                <div class="mt-6 space-y-3 text-sm text-neutral-700">
                    <div class="flex justify-between border-b border-neutral-200 pb-2"><span>Brand</span><span>{{ $vehicleModel->brand }}</span></div>
                    <div class="flex justify-between border-b border-neutral-200 pb-2"><span>Tipe</span><span>{{ $vehicleModel->type }}</span></div>
                    <div class="flex justify-between border-b border-neutral-200 pb-2"><span>Tahun</span><span>{{ $vehicleModel->year }}</span></div>
                    <div class="flex justify-between border-b border-neutral-200 pb-2"><span>Transmisi</span><span>{{ $vehicleModel->transmission }}</span></div>
                    <div class="flex justify-between border-b border-neutral-200 pb-2"><span>Mesin</span><span>{{ $vehicleModel->engine_capacity }} cc</span></div>
                    <div class="flex justify-between border-b border-neutral-200 pb-2"><span>KM</span><span>{{ number_format($vehicleModel->mileage, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between border-b border-neutral-200 pb-2"><span>Warna</span><span>{{ $vehicleModel->color }}</span></div>
                    <div class="flex justify-between"><span>Status</span><span>{{ ucfirst($vehicleModel->status) }}</span></div>
                </div>
                <a href="{{ route('contact') }}" class="mt-8 inline-flex w-full items-center justify-center border border-neutral-900 bg-neutral-900 px-5 py-3 text-sm font-medium text-white hover:bg-neutral-700">
                    Konsultasi via WhatsApp
                </a>
            </aside>
        </div>

        <div class="mt-16 border-t border-neutral-200 pt-12">
            <p class="text-xs uppercase tracking-[0.22em] text-neutral-500">Deskripsi</p>
            <p class="mt-5 max-w-3xl text-base leading-7 text-neutral-600">
                {{ $vehicleModel->description ?: 'Informasi kendaraan akan segera tersedia.' }}
            </p>
        </div>

        @if ($relatedVehicles->isNotEmpty())
            <div class="mt-16">
                <p class="text-xs uppercase tracking-[0.22em] text-neutral-500">Rekomendasi</p>
                <h2 class="mt-3 text-3xl font-light text-neutral-900">Mobil lain yang mungkin Anda suka</h2>
                <div class="mt-8 grid gap-6 md:grid-cols-3">
                    @foreach ($relatedVehicles as $related)
                        <article class="border border-neutral-200 bg-white">
                            <div class="aspect-[4/3] overflow-hidden bg-neutral-100">
                                @php($relatedImage = $related->primaryImage?->image_path)
                                @if ($relatedImage && Storage::disk('public')->exists($relatedImage))
                                    <img src="{{ Storage::disk('public')->url($relatedImage) }}" alt="{{ $related->name }}" class="h-full w-full object-cover" />
                                @endif
                            </div>
                            <div class="p-5">
                                <p class="text-sm text-neutral-500">{{ $related->brand }}</p>
                                <h3 class="mt-2 text-2xl font-light text-neutral-900">{{ $related->name }}</h3>
                                <p class="mt-3 text-lg font-medium text-neutral-900">Rp {{ number_format($related->price, 0, ',', '.') }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @endif
    </section>
@endsection
