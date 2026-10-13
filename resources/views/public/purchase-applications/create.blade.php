
@extends('public.layouts.app')

@section('title', 'Ajukan Pembelian | '.($settings->dealer_name ?? 'Dealer Mobil'))

@section('content')
<section class="section-shell max-w-4xl py-16 sm:py-20">

    <p class="section-label text-[#5f5f5f]">
        Pengajuan pembelian
    </p>

    <h1 class="mt-3 text-4xl font-light tracking-[-0.04em] text-[#1e1e1e]">
        Ajukan Pembelian
    </h1>

    <p class="mt-4 text-sm leading-7 text-[#4b4b4b]">
        Kirim minat pembelian Anda. Tim dealer akan menghubungi Anda untuk
        konsultasi. Formulir ini bukan transaksi atau pembayaran.
    </p>

    {{-- Informasi kendaraan --}}
    <div
        class="mt-8 border border-black/10 bg-white p-6 sm:p-8"
        data-vehicle-id="{{ $vehicle->getKey() }}"
    >
        <p class="section-label text-[#5f5f5f]">
            Kendaraan yang dipilih
        </p>

        <h2 class="mt-2 text-2xl font-light text-[#1e1e1e]">
            {{ $vehicle->brand }} {{ $vehicle->name }}
        </h2>

        <p class="mt-2 text-sm text-[#4b4b4b]">
            {{ $vehicle->type }} &middot;
            {{ $vehicle->year }} &middot;
            Rp {{ number_format($vehicle->price, 0, ',', '.') }}
        </p>
    </div>

    {{-- Ringkasan kesalahan validasi --}}
    @if ($errors->any())
        <div
            class="mt-6 border-l-4 border-[#b91c1c] bg-white p-5 text-sm"
            role="alert"
            aria-labelledby="validation-error-title"
        >
            <h2 id="validation-error-title" class="font-semibold text-[#991b1b]">
                Pengajuan belum berhasil dikirim
            </h2>

            <p class="mt-2 leading-6 text-[#444444]">
                Perbaiki kesalahan berikut, kemudian kirim formulir kembali.
            </p>

            <ul class="mt-3 list-inside list-disc space-y-1 text-[#991b1b]">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <p class="mt-3 text-xs leading-5 text-[#666666]">
                Jika Anda sebelumnya memilih dokumen, pilih kembali berkasnya
                sebelum mengirim formulir.
            </p>
        </div>
    @endif

    {{-- Formulir pengajuan --}}
    <form
        method="POST"
        action="{{ route('purchase.store', ['vehicle' => $vehicle->slug]) }}"
        enctype="multipart/form-data"
        class="mt-8 space-y-6 border border-black/10 bg-white p-6 sm:p-8"
    >
        @csrf

        {{-- Identitas pelanggan --}}
        <div class="grid gap-5 sm:grid-cols-2">

            <div>
                <label for="customer_name" class="block text-sm font-medium">
                    Nama lengkap <span class="text-red-700">*</span>
                </label>

                <input
                    id="customer_name"
                    name="customer_name"
                    type="text"
                    value="{{ old('customer_name', $customer?->name) }}"
                    maxlength="255"
                    autocomplete="name"
                    required
                    aria-invalid="{{ $errors->has('customer_name') ? 'true' : 'false' }}"
                    class="mt-2 block w-full border border-[#bdbdbd] bg-white px-4 py-3 text-sm focus:border-[#262626] focus:outline-none"
                >

                @error('customer_name')
                    <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="customer_email" class="block text-sm font-medium">
                    Email <span class="text-red-700">*</span>
                </label>

                <input
                    id="customer_email"
                    name="customer_email"
                    type="email"
                    value="{{ old('customer_email', $customer?->email) }}"
                    maxlength="255"
                    autocomplete="email"
                    required
                    aria-invalid="{{ $errors->has('customer_email') ? 'true' : 'false' }}"
                    class="mt-2 block w-full border border-[#bdbdbd] bg-white px-4 py-3 text-sm focus:border-[#262626] focus:outline-none"
                >

                @error('customer_email')
                    <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="customer_phone" class="block text-sm font-medium">
                    Nomor telepon <span class="text-red-700">*</span>
                </label>

                <input
                    id="customer_phone"
                    name="customer_phone"
                    type="tel"
                    value="{{ old('customer_phone', $customer?->phone) }}"
                    maxlength="20"
                    autocomplete="tel"
                    placeholder="Contoh: 081234567890"
                    required
                    aria-invalid="{{ $errors->has('customer_phone') ? 'true' : 'false' }}"
                    class="mt-2 block w-full border border-[#bdbdbd] bg-white px-4 py-3 text-sm focus:border-[#262626] focus:outline-none"
                >

                @error('customer_phone')
                    <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="customer_whatsapp" class="block text-sm font-medium">
                    Nomor WhatsApp <span class="text-red-700">*</span>
                </label>

                <input
                    id="customer_whatsapp"
                    name="customer_whatsapp"
                    type="tel"
                    value="{{ old('customer_whatsapp', $customer?->whatsapp) }}"
                    maxlength="20"
                    autocomplete="tel"
                    placeholder="Nomor WhatsApp aktif"
                    required
                    aria-invalid="{{ $errors->has('customer_whatsapp') ? 'true' : 'false' }}"
                    class="mt-2 block w-full border border-[#bdbdbd] bg-white px-4 py-3 text-sm focus:border-[#262626] focus:outline-none"
                >

                @error('customer_whatsapp')
                    <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                @enderror
            </div>

        </div>

        {{-- Alamat --}}
        <div>
            <label for="customer_address" class="block text-sm font-medium">
                Alamat lengkap <span class="text-red-700">*</span>
            </label>

            <textarea
                id="customer_address"
                name="customer_address"
                rows="3"
                maxlength="2000"
                autocomplete="street-address"
                required
                aria-invalid="{{ $errors->has('customer_address') ? 'true' : 'false' }}"
                class="mt-2 block w-full border border-[#bdbdbd] bg-white px-4 py-3 text-sm focus:border-[#262626] focus:outline-none"
            >{{ old('customer_address') }}</textarea>

            @error('customer_address')
                <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
            @enderror
        </div>

        {{-- Metode pembelian --}}
        <div>
            <label for="purchase_method" class="block text-sm font-medium">
                Metode pembelian yang diminati
                <span class="text-red-700">*</span>
            </label>

            <select
                id="purchase_method"
                name="purchase_method"
                required
                aria-invalid="{{ $errors->has('purchase_method') ? 'true' : 'false' }}"
                class="mt-2 block w-full border border-[#bdbdbd] bg-white px-4 py-3 text-sm focus:border-[#262626] focus:outline-none"
            >
                <option value="">Pilih metode</option>

                <option value="cash" @selected(old('purchase_method') === 'cash')>
                    Tunai
                </option>

                <option value="financing" @selected(old('purchase_method') === 'financing')>
                    Pembiayaan
                </option>

                <option value="consultation" @selected(old('purchase_method') === 'consultation')>
                    Konsultasi terlebih dahulu
                </option>
            </select>

            @error('purchase_method')
                <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
            @enderror
        </div>

        {{-- Preferensi pembiayaan --}}
        <div>
            <label for="financing_preference" class="block text-sm font-medium">
                Preferensi pembiayaan
            </label>

            <p class="mt-1 text-xs leading-5 text-[#666666]">
                Opsional untuk metode tunai atau konsultasi. Wajib diisi jika
                memilih metode pembiayaan.
            </p>

            <textarea
                id="financing_preference"
                name="financing_preference"
                rows="2"
                maxlength="1000"
                placeholder="Contoh: ingin mengetahui simulasi uang muka dan cicilan"
                class="mt-2 block w-full border border-[#bdbdbd] bg-white px-4 py-3 text-sm focus:border-[#262626] focus:outline-none"
            >{{ old('financing_preference') }}</textarea>

            @error('financing_preference')
                <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
            @enderror
        </div>

        {{-- Catatan --}}
        <div>
            <label for="customer_note" class="block text-sm font-medium">
                Catatan (opsional)
            </label>

            <textarea
                id="customer_note"
                name="customer_note"
                rows="3"
                maxlength="2000"
                placeholder="Informasi tambahan untuk tim dealer"
                class="mt-2 block w-full border border-[#bdbdbd] bg-white px-4 py-3 text-sm focus:border-[#262626] focus:outline-none"
            >{{ old('customer_note') }}</textarea>

            @error('customer_note')
                <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
            @enderror
        </div>

        {{-- Dokumen pendukung --}}
        <fieldset class="border-t border-black/10 pt-6">
            <legend class="text-sm font-medium">
                Dokumen pendukung (opsional)
            </legend>

            <p class="mt-2 text-xs leading-5 text-[#666666]">
                Dokumen tidak wajib untuk pengajuan awal. Jika mengunggah berkas,
                jenis dokumen dan namanya harus diisi. Format yang didukung PDF,
                JPG, JPEG, PNG, atau WEBP dengan ukuran maksimal 5 MB.
            </p>

            <div class="mt-4 grid gap-5 sm:grid-cols-2">

                <div>
                    <label for="document_type" class="block text-sm font-medium">
                        Jenis dokumen
                    </label>

                    <input
                        id="document_type"
                        name="documents[0][document_type]"
                        type="text"
                        value="{{ old('documents.0.document_type') }}"
                        maxlength="60"
                        placeholder="Contoh: identitas"
                        class="mt-2 block w-full border border-[#bdbdbd] bg-white px-4 py-3 text-sm focus:border-[#262626] focus:outline-none"
                    >

                    @error('documents.0.document_type')
                        <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="document_label" class="block text-sm font-medium">
                        Nama dokumen
                    </label>

                    <input
                        id="document_label"
                        name="documents[0][document_label]"
                        type="text"
                        value="{{ old('documents.0.document_label') }}"
                        maxlength="120"
                        placeholder="Contoh: Dokumen pendukung"
                        class="mt-2 block w-full border border-[#bdbdbd] bg-white px-4 py-3 text-sm focus:border-[#262626] focus:outline-none"
                    >

                    @error('documents.0.document_label')
                        <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="mt-5">
                <label for="document_file" class="block text-sm font-medium">
                    Pilih berkas
                </label>

                <input
                    id="document_file"
                    name="documents[0][file]"
                    type="file"
                    accept=".pdf,.jpg,.jpeg,.png,.webp"
                    class="mt-2 block w-full border border-[#bdbdbd] bg-white px-4 py-3 text-sm file:mr-4 file:border-0 file:bg-[#f3f3f3] file:px-4 file:py-2"
                >

                @error('documents.0.file')
                    <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                @enderror

                @error('documents')
                    <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                @enderror
            </div>
        </fieldset>

        {{-- Tombol submit --}}
        <div class="border-t border-black/10 pt-6">
            <button
                type="submit"
                class="inline-flex min-h-12 w-full items-center justify-center border border-[#262626] bg-[#262626] px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-[#444444] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#262626] sm:w-auto"
            >
                Kirim Pengajuan
            </button>

            <p class="mt-3 text-xs leading-5 text-[#666666]">
                Tanda bintang (*) menunjukkan kolom wajib diisi.
            </p>
        </div>

    </form>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const method = document.getElementById('purchase_method');
    const preference = document.getElementById('financing_preference');

    function updateFinancingRequirement() {
        const financingSelected = method.value === 'financing';

        preference.required = financingSelected;

        preference.setAttribute(
            'aria-required',
            financingSelected ? 'true' : 'false'
        );
    }

    method.addEventListener('change', updateFinancingRequirement);

    updateFinancingRequirement();
});
</script>
@endsection
