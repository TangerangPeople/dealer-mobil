@extends('public.layouts.app')

@section('title', 'Kontak')

@section('content')
    <section class="section-shell py-16 sm:py-20">
        <p class="section-label text-[#5f5f5f]">Kontak</p>
        <h1 class="mt-4 text-4xl font-light tracking-[-0.04em] text-[#1e1e1e] sm:text-5xl">Hubungi kami</h1>

        <div class="mt-10 grid gap-8 lg:grid-cols-[1.05fr_0.95fr]">
            <div class="soft-panel p-8 sm:p-10">
                <p class="section-label text-[#5f5f5f]">Dealer</p>
                <h2 class="mt-3 text-3xl font-light text-[#1e1e1e]">{{ $settings->dealer_name ?? 'Dealer Mobil' }}</h2>
                <div class="mt-6 space-y-4 text-base text-[#4b4b4b]">
                    <p>Alamat: {{ $settings->address ?? '-' }}</p>
                    <p>Telepon: @if ($settings->phone ?? false)<a href="tel:{{ preg_replace('/[^0-9+]/', '', $settings->phone) }}" class="underline underline-offset-4">{{ $settings->phone }}</a>@else-@endif</p>
                    <p>Email: @if ($settings->email ?? false)<a href="mailto:{{ $settings->email }}" class="underline underline-offset-4">{{ $settings->email }}</a>@else-@endif</p>
                    <p>WhatsApp: {{ $settings->whatsapp ?? '-' }}</p>
                </div>
                <div class="mt-8 flex flex-wrap gap-3">
                    @if ($settings->map_url ?? false)
                        <a href="{{ $settings->map_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex border border-[#262626] px-5 py-3 text-sm font-medium text-[#262626]">Lihat Lokasi</a>
                    @endif
                    @if ($dealerWhatsappUrl)
                        <a href="{{ $dealerWhatsappUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex border border-[#262626] px-5 py-3 text-sm font-medium text-[#262626]">Chat WhatsApp Dealer</a>
                    @endif
                    <a href="{{ route('booking-service') }}" class="inline-flex border border-[#262626] bg-[#262626] px-5 py-3 text-sm font-medium text-white transition hover:bg-[#101010]">
                    Booking Service
                    </a>
                </div>
                @if (is_array($settings->opening_hours ?? null) && count($settings->opening_hours) > 0)
                    <div class="mt-8 border-t border-black/10 pt-6">
                        <h3 class="text-lg font-light">Jam operasional</h3>
                        <ul class="mt-3 space-y-2 text-sm text-[#4b4b4b]">
                            @foreach ($settings->opening_hours as $hours)
                                <li class="flex justify-between gap-4"><span>{{ $hours['day'] ?? '-' }}</span><span>{{ ($hours['closed'] ?? false) ? 'Tutup' : (($hours['open'] ?? '-') .'–'. ($hours['close'] ?? '-')) }}</span></li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <div id="sales" class="editorial-card p-8 sm:p-10">
                <p class="section-label text-[#5f5f5f]">Sales</p>
                <div class="mt-5 space-y-5">
                    @forelse ($sales as $sale)
                        <div class="border-b border-black/10 pb-4 last:border-b-0 last:pb-0">
                            <p class="text-xl font-light text-[#1e1e1e]">{{ $sale->name }}</p>
                            <p class="mt-1 text-sm text-[#666]">{{ $sale->position ?? 'Sales Representative' }}</p>
                            <p class="mt-2 text-sm text-[#4b4b4b]">WhatsApp: {{ $sale->whatsapp ?? '-' }}</p>
                            @if ($sale->phone)
                                <p class="mt-1 text-sm text-[#4b4b4b]"><a href="tel:{{ preg_replace('/[^0-9+]/', '', $sale->phone) }}" class="underline underline-offset-4">Telepon Sales</a></p>
                            @endif
                            <a href="{{ route('sales.chat', ['sale' => $sale->getKey()]) }}" class="mt-4 inline-flex border border-[#262626] bg-[#262626] px-5 py-3 text-sm font-medium text-white" target="_blank" rel="noopener noreferrer">Chat Sales</a>
                        </div>
                    @empty
                        <p class="text-sm text-neutral-500">Tim sales belum tersedia.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <a href="{{ route('contact') }}#sales" class="mt-8 inline-flex border border-[#262626] px-5 py-3 text-sm font-medium text-[#262626]">Konsultasi dengan Sales</a>
    </section>
@endsection
