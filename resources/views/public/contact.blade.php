@extends('public.layouts.app')

@section('title', 'Kontak')

@section('content')
    <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8">
        <p class="text-xs uppercase tracking-[0.24em] text-neutral-500">Kontak</p>
        <h1 class="mt-4 text-4xl font-light text-neutral-900 sm:text-5xl">Hubungi kami</h1>

        <div class="mt-10 grid gap-8 lg:grid-cols-[1fr_1fr]">
            <div class="border border-neutral-200 bg-neutral-50 p-8">
                <p class="text-xs uppercase tracking-[0.2em] text-neutral-500">Dealer</p>
                <h2 class="mt-3 text-3xl font-light text-neutral-900">{{ $settings->dealer_name }}</h2>
                <div class="mt-6 space-y-4 text-base text-neutral-700">
                    <p>Alamat: {{ $settings->address ?? '-' }}</p>
                    <p>Telepon: {{ $settings->phone ?? '-' }}</p>
                    <p>Email: {{ $settings->email ?? '-' }}</p>
                    <p>WhatsApp: {{ $settings->whatsapp ?? '-' }}</p>
                </div>
            </div>

            <div class="border border-neutral-200 bg-white p-8">
                <p class="text-xs uppercase tracking-[0.2em] text-neutral-500">Sales</p>
                <div class="mt-5 space-y-4">
                    @forelse ($sales as $sale)
                        <div class="border-b border-neutral-200 pb-3 last:border-b-0 last:pb-0">
                            <p class="text-lg font-medium text-neutral-900">{{ $sale->name }}</p>
                            <p class="mt-1 text-sm text-neutral-500">{{ $sale->position ?? 'Sales Representative' }}</p>
                            <p class="mt-2 text-sm text-neutral-700">WhatsApp: {{ $sale->whatsapp ?? '-' }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-neutral-500">Tim sales belum tersedia.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
@endsection
