@extends('public.layouts.app')

@section('title', 'Akun | '.($settings->dealer_name ?? 'Dealer Mobil'))

@section('content')
    <section class="section-shell py-16 sm:py-20">
        <p class="section-label text-[#5f5f5f]">Akun pelanggan</p>
        <h1 class="mt-3 text-4xl font-light tracking-[-0.04em] text-[#1e1e1e]">Halo, {{ $customer->name }}</h1>
        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('purchase.index') }}" class="border border-[#262626] bg-[#262626] px-5 py-3 text-sm font-medium text-white">Lihat pengajuan pembelian</a>
            <form method="POST" action="{{ route('customer.logout') }}">
                @csrf
                <button type="submit" class="border border-[#262626] px-5 py-3 text-sm font-medium text-[#262626]">Keluar</button>
            </form>
        </div>
    </section>
@endsection
