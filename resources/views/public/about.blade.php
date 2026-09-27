@extends('public.layouts.app')

@section('title', 'Tentang Dealer')

@section('content')
    <section class="mx-auto max-w-5xl px-4 py-16 sm:px-6 lg:px-8">
        <p class="text-xs uppercase tracking-[0.24em] text-neutral-500">Profil Dealer</p>
        <h1 class="mt-4 text-4xl font-light text-neutral-900 sm:text-5xl">Tentang Dealer</h1>
        <div class="mt-10 grid gap-10 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="space-y-6 text-base leading-7 text-neutral-600">
                <p>{{ $settings->history ?? 'Dealer kami hadir untuk memberikan solusi mobil yang tepat bagi kebutuhan Anda.' }}</p>
                <p>{{ $settings->about_content ?? 'Kami berkomitmen pada kepuasan pelanggan, transparansi, dan pelayanan terbaik' }}</p>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="border border-neutral-200 bg-neutral-50 p-5">
                        <p class="text-xs uppercase tracking-[0.2em] text-neutral-500">Alamat</p>
                        <p class="mt-3 text-neutral-900">{{ $settings->address ?? '-' }}</p>
                    </div>
                    <div class="border border-neutral-200 bg-neutral-50 p-5">
                        <p class="text-xs uppercase tracking-[0.2em] text-neutral-500">Kontak</p>
                        <p class="mt-3 text-neutral-900">{{ $settings->phone ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <aside class="border border-neutral-200 bg-neutral-50 p-6">
                <p class="text-xs uppercase tracking-[0.22em] text-neutral-500">Layanan</p>
                <ul class="mt-5 space-y-3 text-sm text-neutral-700">
                    @foreach ($settings->services ?? ['Sales', 'Kredit', 'Servis'] as $service)
                        <li class="border-b border-neutral-200 pb-3 last:border-b-0 last:pb-0">{{ $service }}</li>
                    @endforeach
                </ul>
                <div class="mt-8 border-t border-neutral-200 pt-5">
                    <p class="text-xs uppercase tracking-[0.22em] text-neutral-500">Jam operasional</p>
                    @foreach ($settings->opening_hours ?? [['day' => 'Senin - Sabtu', 'open' => '08:00', 'close' => '17:00', 'closed' => false]] as $hours)
                        <div class="mt-3 flex items-center justify-between text-sm text-neutral-700">
                            <span>{{ $hours['day'] ?? 'Hari' }}</span>
                            <span>{{ $hours['closed'] ?? false ? 'Tutup' : (($hours['open'] ?? '08:00').' - '.($hours['close'] ?? '17:00')) }}</span>
                        </div>
                    @endforeach
                </div>
            </aside>
        </div>
    </section>
@endsection
