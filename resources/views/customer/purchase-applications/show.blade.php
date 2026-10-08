@extends('public.layouts.app')

@section('title', 'Detail Pengajuan | '.($settings->dealer_name ?? 'Dealer Mobil'))

@section('content')
    <section class="section-shell max-w-4xl py-16 sm:py-20">
        <p class="section-label text-[#5f5f5f]">Pengajuan {{ $application->application_number }}</p>
        <h1 class="mt-3 text-4xl font-light tracking-[-0.04em] text-[#1e1e1e]">{{ $application->vehicle_brand }} {{ $application->vehicle_name }}</h1>
        <dl class="mt-8 grid gap-5 border border-black/10 bg-white p-6 sm:grid-cols-2 sm:p-8">
            <div><dt class="text-xs uppercase tracking-[0.14em] text-[#666]">Status</dt><dd class="mt-2 text-lg">{{ ucfirst($application->status) }}</dd></div>
            <div><dt class="text-xs uppercase tracking-[0.14em] text-[#666]">Metode</dt><dd class="mt-2 text-lg">{{ ucfirst($application->purchase_method) }}</dd></div>
            <div><dt class="text-xs uppercase tracking-[0.14em] text-[#666]">Tanggal pengajuan</dt><dd class="mt-2 text-sm">{{ $application->created_at->format('d M Y, H:i') }}</dd></div>
            <div><dt class="text-xs uppercase tracking-[0.14em] text-[#666]">Harga kendaraan saat pengajuan</dt><dd class="mt-2 text-sm">Rp {{ number_format((float) $application->vehicle_price, 0, ',', '.') }}</dd></div>
        </dl>

        @if ($application->documents->isNotEmpty())
            <div class="mt-8">
                <h2 class="text-xl font-light">Dokumen pengajuan</h2>
                <ul class="mt-4 divide-y divide-black/10 border-y border-black/10 bg-white">
                    @foreach ($application->documents as $document)
                        <li class="flex items-center justify-between gap-4 p-4">
                            <span class="text-sm">{{ $document->document_label }}</span>
                            <a href="{{ route('purchase.documents.download', [$application, $document]) }}" class="text-sm underline underline-offset-4">Unduh dokumen</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <a href="{{ route('purchase.index') }}" class="mt-8 inline-flex text-sm underline underline-offset-4">Kembali ke daftar pengajuan</a>
    </section>
@endsection
