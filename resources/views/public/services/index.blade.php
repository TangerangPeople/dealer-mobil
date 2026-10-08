@extends('public.layouts.app')

@section('title', $settings->dealer_name.' | Layanan')

@section('content')
    <section class="section-shell py-16 sm:py-20">
        <div class="max-w-3xl">
            <p class="section-label text-[#5f5f5f]">Layanan</p>
            <h1 class="mt-4 text-4xl font-light tracking-[-0.04em] text-[#1e1e1e] sm:text-5xl">Paket servis yang kami tawarkan</h1>
            <p class="mt-5 text-base leading-7 text-[#4b4b4b]">Pilihan paket perawatan dan servis untuk menjaga performa mobil Anda tetap prima.</p>
        </div>

        <div class="mt-12 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($servicePackages as $package)
                <article class="editorial-card flex h-full flex-col p-6">
                    <p class="text-[10px] uppercase tracking-[0.2em] text-[#777]">{{ $package->service_type }}</p>
                    <h2 class="mt-4 text-2xl font-light text-[#1e1e1e]">{{ $package->name }}</h2>
                    <p class="mt-4 text-sm leading-6 text-[#5d5d5d]">{{ $package->description }}</p>

                    <div class="mt-6 space-y-2 border-t border-black/10 pt-5 text-sm text-[#4b4b4b]">
                        <p>Estimasi harga: Rp {{ number_format($package->estimated_price, 0, ',', '.') }}</p>
                        <p>Durasi: {{ $package->estimated_duration }}</p>
                    </div>

                    <a href="{{ route('booking-service') }}" class="mt-8 inline-flex border border-[#262626] bg-[#262626] px-5 py-3 text-sm font-medium text-white transition hover:bg-[#101010]">
                        Booking sekarang
                    </a>
                </article>
            @empty
                <p class="text-sm text-neutral-500">Belum ada paket servis aktif saat ini.</p>
            @endforelse
        </div>
        <div class="mt-10 flex flex-wrap gap-3">
            <a href="{{ route('sales.chat') }}" target="_blank" rel="noopener noreferrer" class="inline-flex border border-[#262626] px-5 py-3 text-sm font-medium text-[#262626]">Chat Sales</a>
            <a href="{{ route('contact') }}" class="inline-flex border border-[#262626] px-5 py-3 text-sm font-medium text-[#262626]">Kontak</a>
        </div>
    </section>
@endsection
