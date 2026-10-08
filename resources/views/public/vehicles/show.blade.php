@extends('public.layouts.app')

@section('title', $vehicleModel->brand.' '.$vehicleModel->name)

@section('content')
    <section class="section-shell py-16 sm:py-20">
        <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
            <div>
                <p class="section-label text-[#5f5f5f]">{{ $vehicleModel->category->name ?? 'Kendaraan' }}</p>
                <h1 class="mt-3 text-4xl font-light tracking-[-0.04em] text-[#1e1e1e] sm:text-5xl">{{ $vehicleModel->brand }} {{ $vehicleModel->name }}</h1>

                <div class="mt-6 overflow-hidden border border-black/10 bg-[#f3f3f3]">
                    @php($image = $vehicleModel->primaryImage?->image_path)
                    @if ($image && Storage::disk('public')->exists($image))
                        <img src="{{ Storage::disk('public')->url($image) }}" alt="{{ $vehicleModel->name }}" class="h-[420px] w-full object-cover sm:h-[540px]" />
                    @else
                        <div class="flex h-[420px] items-center justify-center text-sm uppercase tracking-[0.2em] text-neutral-400 sm:h-[540px]">Foto mobil belum tersedia</div>
                    @endif
                </div>

                <div class="mt-8 grid gap-4 sm:grid-cols-3">
                    @foreach ($vehicleModel->images->take(3) as $image)
                        @if ($image->image_path && Storage::disk('public')->exists($image->image_path))
                            <div class="overflow-hidden border border-black/10 bg-[#f5f5f5]">
                                <img src="{{ Storage::disk('public')->url($image->image_path) }}" alt="{{ $image->alt_text ?? $vehicleModel->name }}" class="h-36 w-full object-cover" />
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <aside class="soft-panel p-6 sm:p-8">
                <p class="section-label text-[#5f5f5f]">Harga</p>
                <p class="mt-3 text-4xl font-light text-[#1e1e1e]">Rp {{ number_format($vehicleModel->price, 0, ',', '.') }}</p>

                <div class="mt-6 space-y-3 text-sm text-[#4b4b4b]">
                    <div class="flex justify-between border-b border-black/10 pb-2"><span>Brand</span><span>{{ $vehicleModel->brand }}</span></div>
                    <div class="flex justify-between border-b border-black/10 pb-2"><span>Tipe</span><span>{{ $vehicleModel->type }}</span></div>
                    <div class="flex justify-between border-b border-black/10 pb-2"><span>Tahun</span><span>{{ $vehicleModel->year }}</span></div>
                    <div class="flex justify-between border-b border-black/10 pb-2"><span>Transmisi</span><span>{{ $vehicleModel->transmission }}</span></div>
                    <div class="flex justify-between border-b border-black/10 pb-2"><span>Mesin</span><span>{{ $vehicleModel->engine_capacity }} cc</span></div>
                    <div class="flex justify-between border-b border-black/10 pb-2"><span>KM</span><span>{{ number_format($vehicleModel->mileage, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between border-b border-black/10 pb-2"><span>Warna</span><span>{{ $vehicleModel->color }}</span></div>
                    <div class="flex justify-between"><span>Status</span><span>{{ ucfirst($vehicleModel->status) }}</span></div>
                </div>

                <div class="mt-8 flex flex-col gap-3">
                    <a href="{{ route('purchase.create', $vehicleModel) }}" class="inline-flex items-center justify-center border border-[#262626] bg-[#262626] px-5 py-3 text-sm font-medium text-white transition hover:bg-[#101010]">
                        Ajukan Pembelian
                    </a>
                    <a href="{{ route('sales.chat', ['vehicle' => $vehicleModel->slug]) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center border border-[#262626] px-5 py-3 text-sm font-medium text-[#262626] transition hover:bg-[#262626] hover:text-white">
                        Chat Sales
                    </a>
                    <a href="{{ route('credit-simulation', ['vehicle' => $vehicleModel->slug]) }}" class="inline-flex items-center justify-center border border-[#262626] px-5 py-3 text-sm font-medium text-[#262626] transition hover:bg-[#262626] hover:text-white">
                        Simulasi Kredit
                    </a>
                </div>
            </aside>
        </div>

        <div class="mt-16 border-t border-black/10 pt-12">
            <p class="section-label text-[#5f5f5f]">Deskripsi</p>
            <p class="mt-5 max-w-3xl text-base leading-8 text-[#4b4b4b]">
                {{ $vehicleModel->description ?: 'Informasi kendaraan akan segera tersedia.' }}
            </p>
        </div>

        @if ($relatedPromotion)
            <div class="mt-16">
                <p class="section-label text-[#5f5f5f]">Promo terkait</p>
                <article class="mt-6 grid gap-6 border border-black/10 bg-white md:grid-cols-[0.9fr_1.1fr]">
                    <div class="aspect-[4/3] overflow-hidden bg-neutral-100">
                        @if ($relatedPromotion->image_path && Storage::disk('public')->exists($relatedPromotion->image_path))
                            <img src="{{ Storage::disk('public')->url($relatedPromotion->image_path) }}" alt="{{ $relatedPromotion->title }}" class="h-full w-full object-cover" />
                        @endif
                    </div>
                    <div class="flex flex-col justify-center p-6 sm:p-8">
                        <p class="text-[10px] uppercase tracking-[0.2em] text-[#777]">Promo</p>
                        <h2 class="mt-3 text-3xl font-light text-[#1e1e1e]">{{ $relatedPromotion->title }}</h2>
                        <p class="mt-4 text-sm leading-7 text-[#5b5b5b]">{{ $relatedPromotion->summary }}</p>
                        <a href="{{ route('promotions.show', ['promotion' => $relatedPromotion->slug]) }}" class="mt-6 inline-flex w-fit border border-[#262626] bg-[#262626] px-5 py-3 text-sm font-medium text-white transition hover:bg-[#101010]">
                            Lihat promo
                        </a>
                    </div>
                </article>
            </div>
        @endif

        @if ($relatedVehicles->isNotEmpty())
            <div class="mt-16">
                <p class="section-label text-[#5f5f5f]">Rekomendasi</p>
                <h2 class="mt-3 text-3xl font-light text-[#1e1e1e]">Mobil lain yang mungkin Anda suka</h2>
                <div class="mt-8 grid gap-6 md:grid-cols-3">
                    @foreach ($relatedVehicles as $related)
                        <article class="editorial-card overflow-hidden">
                            <div class="aspect-[4/3] overflow-hidden bg-neutral-100">
                                @php($relatedImage = $related->primaryImage?->image_path)
                                @if ($relatedImage && Storage::disk('public')->exists($relatedImage))
                                    <img src="{{ Storage::disk('public')->url($relatedImage) }}" alt="{{ $related->name }}" class="h-full w-full object-cover" />
                                @endif
                            </div>
                            <div class="p-5">
                                <p class="text-sm text-[#666]">{{ $related->brand }}</p>
                                <h3 class="mt-2 text-2xl font-light text-[#1e1e1e]">{{ $related->name }}</h3>
                                <p class="mt-3 text-lg font-medium text-[#1e1e1e]">Rp {{ number_format($related->price, 0, ',', '.') }}</p>
                                <a href="{{ route('vehicles.show', ['vehicle' => $related->slug]) }}" class="mt-4 inline-flex text-sm font-medium text-[#262626] underline underline-offset-4">Detail</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @endif
    </section>
@endsection
