@extends('layouts.admin')

@section('title', 'Pengajuan Pembelian')

@section('content')
    <div class="max-w-6xl">
        <p class="text-xs font-semibold uppercase tracking-[0.16em]">Pengajuan pelanggan</p>
        <h1 class="mt-3 text-3xl font-light leading-tight sm:text-4xl">Pengajuan pembelian</h1>

        @if (session('success'))
            <div class="mt-6 border-l-4 border-[#166534] bg-white p-4 text-sm" role="status">{{ session('success') }}</div>
        @endif

        <div class="mt-8 divide-y divide-[#bbbbbb] border-y border-[#bbbbbb] bg-white">
            @forelse ($applications as $application)
                <article class="grid gap-3 p-5 sm:grid-cols-[1fr_1fr_auto] sm:items-center">
                    <div>
                        <p class="text-xs uppercase tracking-[0.14em] text-[#666]">{{ $application->application_number }}</p>
                        <h2 class="mt-2 text-lg font-medium">{{ $application->vehicle_brand }} {{ $application->vehicle_name }}</h2>
                    </div>
                    <div class="text-sm">
                        <p>{{ $application->customer_name }}</p>
                        <p class="mt-1 text-[#666]">{{ ucfirst($application->status) }}</p>
                    </div>
                    <a href="{{ route('admin.purchase-applications.show', $application) }}" class="text-sm underline underline-offset-4">Buka pengajuan</a>
                </article>
            @empty
                <p class="p-5 text-sm">Belum ada pengajuan pembelian.</p>
            @endforelse
        </div>
        <div class="mt-6">{{ $applications->links() }}</div>
    </div>
@endsection
