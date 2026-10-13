
@extends('layouts.admin')

@section('title', 'Detail Pengajuan')

@section('content')
<div class="mx-auto max-w-5xl space-y-8">

    {{-- Header --}}
    <header>
        <a href="{{ route('admin.purchase-applications.index') }}"
           class="text-sm text-[#666666] underline underline-offset-4 hover:text-black">
            &larr; Kembali ke daftar pengajuan
        </a>

        <p class="mt-6 text-xs font-semibold uppercase tracking-[0.16em] text-[#666666]">
            Pengajuan {{ $application->application_number }}
        </p>

        <h1 class="mt-3 text-3xl font-light leading-tight sm:text-4xl">
            {{ $application->vehicle_brand }} {{ $application->vehicle_name }}
        </h1>
    </header>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="border-l-4 border-[#166534] bg-white p-4 text-sm"
             role="status">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="border-l-4 border-[#b91c1c] bg-white p-5 text-sm"
             role="alert">
            <p class="font-semibold text-[#991b1b]">
                Ada data yang belum valid.
            </p>

            <ul class="mt-2 list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Informasi pengajuan --}}
    <section>
        <h2 class="mb-4 text-lg font-medium">Informasi Pengajuan</h2>

        <dl class="grid gap-5 border border-[#bbbbbb] bg-white p-6 sm:grid-cols-2">

            <div>
                <dt class="text-xs uppercase tracking-[0.14em] text-[#666666]">
                    Pelanggan
                </dt>
                <dd class="mt-2">{{ $application->customer_name }}</dd>
            </div>

            <div>
                <dt class="text-xs uppercase tracking-[0.14em] text-[#666666]">
                    Email
                </dt>
                <dd class="mt-2 break-words">
                    {{ $application->customer_email }}
                </dd>
            </div>

            <div>
                <dt class="text-xs uppercase tracking-[0.14em] text-[#666666]">
                    Telepon
                </dt>
                <dd class="mt-2">{{ $application->customer_phone }}</dd>
            </div>

            <div>
                <dt class="text-xs uppercase tracking-[0.14em] text-[#666666]">
                    WhatsApp
                </dt>
                <dd class="mt-2">{{ $application->customer_whatsapp }}</dd>
            </div>

            <div>
                <dt class="text-xs uppercase tracking-[0.14em] text-[#666666]">
                    Status sekarang
                </dt>
                <dd class="mt-2">
                    {{ ucfirst($application->status) }}
                </dd>
            </div>

            <div>
                <dt class="text-xs uppercase tracking-[0.14em] text-[#666666]">
                    Metode pembelian
                </dt>
                <dd class="mt-2">
                    {{ ucfirst($application->purchase_method) }}
                </dd>
            </div>

            <div>
                <dt class="text-xs uppercase tracking-[0.14em] text-[#666666]">
                    Harga saat pengajuan
                </dt>
                <dd class="mt-2">
                    Rp {{ number_format((float) $application->vehicle_price, 0, ',', '.') }}
                </dd>
            </div>

            <div>
                <dt class="text-xs uppercase tracking-[0.14em] text-[#666666]">
                    Tahun kendaraan
                </dt>
                <dd class="mt-2">
                    {{ $application->vehicle_year }}
                </dd>
            </div>

            <div class="sm:col-span-2">
                <dt class="text-xs uppercase tracking-[0.14em] text-[#666666]">
                    Alamat pelanggan
                </dt>
                <dd class="mt-2">
                    {{ $application->customer_address }}
                </dd>
            </div>

            @if ($application->customer_note)
                <div class="sm:col-span-2">
                    <dt class="text-xs uppercase tracking-[0.14em] text-[#666666]">
                        Catatan pelanggan
                    </dt>
                    <dd class="mt-2">
                        {{ $application->customer_note }}
                    </dd>
                </div>
            @endif

        </dl>
    </section>

    {{-- Penugasan Sales --}}
    <section class="border border-[#bbbbbb] bg-white p-5 sm:p-6">
        <h2 class="text-lg font-medium">Sales Penanggung Jawab</h2>

        <p class="mt-2 text-sm leading-6 text-[#666666]">
            Tetapkan Sales yang akan menghubungi dan menindaklanjuti calon
            pelanggan ini. Pengajuan akan ditampilkan pada Dashboard Sales
            yang dipilih.
        </p>

        <div class="mt-5 border-l-2 border-[#bdbdbd] pl-4">
            <p class="text-xs uppercase tracking-[0.12em] text-[#666666]">
                Penugasan saat ini
            </p>

            <p class="mt-2 font-medium text-[#262626]">
                {{ $application->sales?->name ?? 'Belum ditugaskan' }}
            </p>

            @if ($application->sales)
                <p class="mt-1 text-sm text-[#666666]">
                    {{ $application->sales->email }}
                </p>
            @endif
        </div>

        <form
            method="POST"
            action="{{ route('admin.purchase-applications.sales-assignment', $application) }}"
            class="mt-6 space-y-4"
        >
            @csrf
            @method('PUT')

            <div>
                <label for="sales_id" class="block text-sm font-medium">
                    Pilih Sales
                </label>

                <select
                    id="sales_id"
                    name="sales_id"
                    class="mt-2 block min-h-12 w-full border border-[#bbbbbb] bg-white px-4 py-3 text-sm focus:border-[#262626] focus:outline-none"
                >
                    <option value="">-- Belum ditugaskan --</option>

                    @foreach ($salesUsers as $salesUser)
                        <option
                            value="{{ $salesUser->id }}"
                            @selected(
                                (string) old('sales_id', $application->sales_id)
                                === (string) $salesUser->id
                            )
                        >
                            {{ $salesUser->name }} — {{ $salesUser->email }}
                        </option>
                    @endforeach
                </select>

                @error('sales_id')
                    <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                @enderror

                @if ($salesUsers->isEmpty())
                    <p class="mt-2 text-sm text-[#777777]">
                        Belum ada akun dengan role Sales. Buat atau periksa
                        akun Sales terlebih dahulu.
                    </p>
                @endif
            </div>

            <button
                type="submit"
                class="inline-flex min-h-12 items-center justify-center border border-[#262626] bg-[#262626] px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-[#444444] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#262626]"
            >
                Simpan Penugasan
            </button>
        </form>
    </section>

    {{-- Perubahan status --}}
    <section class="border border-[#bbbbbb] bg-white p-5 sm:p-6">
        <h2 class="text-lg font-medium">Perbarui Status Pengajuan</h2>

        <p class="mt-2 text-sm text-[#666666]">
            Perubahan status dilakukan secara terpisah dari penugasan Sales.
        </p>

        <form
            method="POST"
            action="{{ route('admin.purchase-applications.status', $application) }}"
            class="mt-5 flex flex-col gap-4 sm:flex-row sm:items-end"
        >
            @csrf
            @method('PUT')

            <div class="flex-1">
                <label for="status" class="block text-sm font-medium">
                    Status pengajuan
                </label>

                <select
                    id="status"
                    name="status"
                    required
                    class="mt-2 block min-h-12 w-full border border-[#bbbbbb] bg-white px-4 py-3 text-sm focus:border-[#262626] focus:outline-none"
                >
                    @foreach (\App\Models\PurchaseApplication::STATUSES as $status)
                        <option
                            value="{{ $status }}"
                            @selected($application->status === $status)
                        >
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button
                type="submit"
                class="inline-flex min-h-12 items-center justify-center border border-[#262626] bg-[#262626] px-6 py-3 text-sm font-semibold text-white hover:bg-[#444444]"
            >
                Simpan Status
            </button>
        </form>
    </section>

    {{-- Dokumen pelanggan --}}
    <section>
        <h2 class="text-lg font-medium">Dokumen Pelanggan</h2>

        @if ($application->documents->isNotEmpty())
            <ul class="mt-4 divide-y divide-[#bbbbbb] border-y border-[#bbbbbb] bg-white">
                @foreach ($application->documents as $document)
                    <li class="flex flex-col gap-3 p-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-medium">
                                {{ $document->document_label }}
                            </p>
                            <p class="mt-1 text-xs text-[#666666]">
                                {{ $document->original_name }}
                            </p>
                        </div>

                        <a
                            href="{{ route('admin.purchase-applications.documents.download', [$application, $document]) }}"
                            class="inline-flex min-h-10 items-center justify-center border border-[#262626] px-4 py-2 text-sm hover:bg-[#f5f5f5]"
                        >
                            Unduh dokumen
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="mt-3 border border-[#bbbbbb] bg-white p-5 text-sm text-[#666666]">
                Tidak ada dokumen pendukung pada pengajuan ini.
            </p>
        @endif
    </section>

</div>
@endsection
