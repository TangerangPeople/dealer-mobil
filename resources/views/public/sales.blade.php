@extends('public.layouts.app')

@section('title', 'Chat Sales | '.($settings->dealer_name ?? 'Dealer Mobil'))

@section('content')
    <section id="sales" class="section-shell py-16 sm:py-20">
        <p class="section-label text-[#5f5f5f]">Konsultasi</p>
        <h1 class="mt-3 text-4xl font-light tracking-[-0.04em] text-[#1e1e1e] sm:text-5xl">Chat Sales</h1>
        <p class="mt-5 max-w-2xl whitespace-pre-line text-base leading-7 text-[#4b4b4b]">{{ $message }}</p>

        @if ($unavailableSale)
            <p class="mt-8 border-l-4 border-[#92400e] bg-[#f1f1f1] p-4 text-sm">WhatsApp sales ini belum tersedia.</p>
        @endif

        @if ($sales->isNotEmpty())
            <div class="mt-10 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($sales as $contact)
                    <article class="border border-black/10 bg-white p-6">
                        <h2 class="text-xl font-light text-[#1e1e1e]">{{ $contact['sale']->name }}</h2>
                        <p class="mt-2 text-sm text-[#5b5b5b]">{{ $contact['sale']->position ?: 'Sales Representative' }}</p>
                        <a href="{{ $contact['url'] }}" target="_blank" rel="noopener noreferrer" class="mt-5 inline-flex min-h-11 items-center border border-[#262626] bg-[#262626] px-5 py-3 text-sm font-medium text-white transition hover:bg-white hover:text-[#262626]">
                            Chat WhatsApp
                        </a>
                    </article>
                @endforeach
            </div>
        @else
            <p class="mt-8 text-sm text-[#5b5b5b]">Belum ada sales aktif dengan nomor WhatsApp. Silakan hubungi dealer.</p>
        @endif

        @if ($dealerWhatsappUrl)
            <a href="{{ $dealerWhatsappUrl }}" target="_blank" rel="noopener noreferrer" class="mt-8 inline-flex min-h-11 items-center border border-[#262626] px-5 py-3 text-sm font-medium text-[#262626] transition hover:bg-[#262626] hover:text-white">
                Chat WhatsApp Dealer
            </a>
        @endif

        <a href="{{ route('contact') }}" class="mt-8 block text-sm underline underline-offset-4">Hubungi Dealer</a>
    </section>
@endsection
