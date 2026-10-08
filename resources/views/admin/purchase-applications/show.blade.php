@extends('layouts.admin')

@section('title', 'Detail Pengajuan')

@section('content')
    <div class="max-w-4xl">
        <p class="text-xs font-semibold uppercase tracking-[0.16em]">Pengajuan {{ $application->application_number }}</p>
        <h1 class="mt-3 text-3xl font-light leading-tight sm:text-4xl">{{ $application->vehicle_brand }} {{ $application->vehicle_name }}</h1>

        @if (session('success'))
            <div class="mt-6 border-l-4 border-[#166534] bg-white p-4 text-sm" role="status">{{ session('success') }}</div>
        @endif

        <dl class="mt-8 grid gap-5 border border-[#bbbbbb] bg-white p-6 sm:grid-cols-2">
            <div><dt class="text-xs uppercase tracking-[0.14em] text-[#666]">Pelanggan</dt><dd class="mt-2">{{ $application->customer_name }}</dd></div>
            <div><dt class="text-xs uppercase tracking-[0.14em] text-[#666]">Email</dt><dd class="mt-2">{{ $application->customer_email }}</dd></div>
            <div><dt class="text-xs uppercase tracking-[0.14em] text-[#666]">Telepon</dt><dd class="mt-2">{{ $application->customer_phone }}</dd></div>
            <div><dt class="text-xs uppercase tracking-[0.14em] text-[#666]">Status sekarang</dt><dd class="mt-2">{{ ucfirst($application->status) }}</dd></div>
            <div><dt class="text-xs uppercase tracking-[0.14em] text-[#666]">Metode</dt><dd class="mt-2">{{ ucfirst($application->purchase_method) }}</dd></div>
            <div><dt class="text-xs uppercase tracking-[0.14em] text-[#666]">Harga saat pengajuan</dt><dd class="mt-2">Rp {{ number_format((float) $application->vehicle_price, 0, ',', '.') }}</dd></div>
            <div class="sm:col-span-2"><dt class="text-xs uppercase tracking-[0.14em] text-[#666]">Alamat</dt><dd class="mt-2">{{ $application->customer_address }}</dd></div>
            @if ($application->customer_note)
                <div class="sm:col-span-2"><dt class="text-xs uppercase tracking-[0.14em] text-[#666]">Catatan pelanggan</dt><dd class="mt-2">{{ $application->customer_note }}</dd></div>
            @endif
        </dl>

        <form method="POST" action="{{ route('admin.purchase-applications.status', $application) }}" class="mt-8 flex flex-col gap-4 border border-[#bbbbbb] bg-white p-5 sm:flex-row sm:items-end">
            @csrf
            @method('PUT')
            <div class="flex-1">
                <label for="status" class="block text-sm font-semibold">Perbarui status</label>
                <select id="status" name="status" required class="mt-2 block w-full border border-[#bbbbbb] bg-white px-4 py-3 text-sm">
                    @foreach (\App\Models\PurchaseApplication::STATUSES as $status)
                        <option value="{{ $status }}" @selected($application->status === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="border border-[#262626] bg-[#262626] px-5 py-3 text-sm font-semibold text-white">Simpan status</button>
        </form>

        @if ($application->documents->isNotEmpty())
            <div class="mt-8">
                <h2 class="text-xl font-light">Dokumen pelanggan</h2>
                <ul class="mt-4 divide-y divide-[#bbbbbb] border-y border-[#bbbbbb] bg-white">
                    @foreach ($application->documents as $document)
                        <li class="flex items-center justify-between gap-4 p-4">
                            <span class="text-sm">{{ $document->document_label }}</span>
                            <a href="{{ route('admin.purchase-applications.documents.download', [$application, $document]) }}" class="text-sm underline underline-offset-4">Unduh dokumen</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endsection
