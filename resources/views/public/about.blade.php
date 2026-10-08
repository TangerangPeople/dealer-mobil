@extends('public.layouts.app')

@section('title', 'Tentang Dealer')

@section('content')
    <section class="section-shell py-16 sm:py-20">
        <p class="section-label text-[#5f5f5f]">Profil Dealer</p>
        <h1 class="mt-4 text-4xl font-light tracking-[-0.04em] text-[#1e1e1e] sm:text-5xl">Tentang Dealer</h1>

        <div class="mt-10 grid gap-8 lg:grid-cols-[1.15fr_0.85fr]">
            <div class="space-y-7">
                <div class="soft-panel p-8 sm:p-10">
                    <p class="text-base leading-8 text-[#4b4b4b]">
                        {{ $settings->history ?? 'Dealer kami hadir untuk memberikan solusi mobil yang tepat bagi kebutuhan Anda.' }}
                    </p>
                    <p class="mt-6 text-base leading-8 text-[#4b4b4b]">
                        {{ $settings->about_content ?? 'Kami berkomitmen pada kepuasan pelanggan, transparansi, dan pelayanan terbaik.' }}
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="editorial-card p-6">
                        <p class="text-[10px] uppercase tracking-[0.2em] text-[#777]">Alamat</p>
                        <p class="mt-4 text-lg font-light text-[#1e1e1e]">{{ $settings->address ?? '-' }}</p>
                    </div>
                    <div class="editorial-card p-6">
                        <p class="text-[10px] uppercase tracking-[0.2em] text-[#777]">Kontak</p>
                        <p class="mt-4 text-lg font-light text-[#1e1e1e]">{{ $settings->phone ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <aside class="soft-panel p-6 sm:p-8">
                <p class="section-label text-[#5f5f5f]">Layanan</p>
                <ul class="mt-5 space-y-3 text-sm text-[#4b4b4b]">
                    @foreach ($settings->services ?? ['Sales', 'Kredit', 'Servis'] as $service)
                        <li class="border-b border-black/10 pb-3 last:border-b-0 last:pb-0">{{ $service }}</li>
                    @endforeach
                </ul>

                <div class="mt-8 border-t border-black/10 pt-6">
                    <p class="section-label text-[#5f5f5f]">Jam operasional</p>
                    @foreach ($settings->opening_hours ?? [['day' => 'Senin - Sabtu', 'open' => '08:00', 'close' => '17:00', 'closed' => false]] as $hours)
                        <div class="mt-3 flex items-center justify-between gap-3 text-sm text-[#4b4b4b]">
                            <span>{{ $hours['day'] ?? 'Hari' }}</span>
                            <span>{{ $hours['closed'] ?? false ? 'Tutup' : (($hours['open'] ?? '08:00').' - '.($hours['close'] ?? '17:00')) }}</span>
                        </div>
                    @endforeach
                </div>
            </aside>
        </div>

        @if ($sales->isNotEmpty())
            <div class="mt-16">
                <p class="section-label text-[#5f5f5f]">Tim sales</p>
                <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                    @foreach ($sales as $sale)
                        <div class="editorial-card p-5">
                            <p class="text-xl font-light text-[#1e1e1e]">{{ $sale->name }}</p>
                            <p class="mt-2 text-sm text-[#666]">{{ $sale->position ?? 'Sales Representative' }}</p>
                            <p class="mt-5 text-sm text-[#4b4b4b]">WhatsApp: {{ $sale->whatsapp ?? '-' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </section>
@endsection
