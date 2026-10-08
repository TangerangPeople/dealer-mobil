@extends('public.layouts.app')

@section('title', 'Pengajuan Pembelian | '.($settings->dealer_name ?? 'Dealer Mobil'))

@section('content')
    <section class="section-shell py-16 sm:py-20">
        <p class="section-label text-[#5f5f5f]">Akun pelanggan</p>
        <h1 class="mt-3 text-4xl font-light tracking-[-0.04em] text-[#1e1e1e]">Pengajuan pembelian</h1>

        <div class="mt-8 divide-y divide-black/10 border-y border-black/10 bg-white">
            @forelse ($applications as $application)
                <article class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
                    <div>
                        <p class="text-xs uppercase tracking-[0.16em] text-[#666]">{{ $application->application_number }}</p>
                        <h2 class="mt-2 text-xl font-light">{{ $application->vehicle_brand }} {{ $application->vehicle_name }}</h2>
                        <p class="mt-2 text-sm text-[#4b4b4b]">Status: {{ ucfirst($application->status) }}</p>
                    </div>
                    <a href="{{ route('purchase.show', $application) }}" class="text-sm underline underline-offset-4">Lihat detail pengajuan</a>
                </article>
            @empty
                <p class="p-6 text-sm text-[#4b4b4b]">Belum ada pengajuan pembelian.</p>
            @endforelse
        </div>
        <div class="mt-6">{{ $applications->links() }}</div>
    </section>
@endsection
