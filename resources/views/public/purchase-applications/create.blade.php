@extends('public.layouts.app')

@section('title', 'Ajukan Pembelian | '.($settings->dealer_name ?? 'Dealer Mobil'))

@section('content')
    <section class="section-shell max-w-4xl py-16 sm:py-20">
        <p class="section-label text-[#5f5f5f]">Pengajuan pembelian</p>
        <h1 class="mt-3 text-4xl font-light tracking-[-0.04em] text-[#1e1e1e]">Ajukan Pembelian</h1>
        <p class="mt-4 text-sm leading-7 text-[#4b4b4b]">Kirim minat pembelian Anda. Tim dealer akan menghubungi untuk konsultasi; formulir ini bukan transaksi atau pembayaran.</p>

        <div class="mt-8 border border-black/10 bg-white p-6 sm:p-8" data-vehicle-id="{{ $vehicle->getKey() }}">
            <p class="section-label text-[#5f5f5f]">Kendaraan yang dipilih</p>
            <h2 class="mt-2 text-2xl font-light text-[#1e1e1e]">{{ $vehicle->brand }} {{ $vehicle->name }}</h2>
            <p class="mt-2 text-sm text-[#4b4b4b]">{{ $vehicle->type }} · {{ $vehicle->year }} · Rp {{ number_format($vehicle->price, 0, ',', '.') }}</p>
        </div>

        @if ($errors->any())
            <div class="mt-6 border-l-4 border-[#b91c1c] bg-white p-4 text-sm" role="alert">
                Periksa kembali data pengajuan dan dokumen yang diunggah.
            </div>
        @endif

        <form method="POST" action="{{ route('purchase.store', ['vehicle' => $vehicle->slug]) }}" enctype="multipart/form-data" class="mt-8 space-y-6 border border-black/10 bg-white p-6 sm:p-8">
            @csrf
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="customer_name" class="block text-sm font-medium">Nama lengkap</label>
                    <input id="customer_name" name="customer_name" type="text" value="{{ old('customer_name', $customer?->name) }}" required class="mt-2 block w-full border border-[#bdbdbd] bg-white px-4 py-3 text-sm focus:border-[#262626] focus:outline-none">
                </div>
                <div>
                    <label for="customer_email" class="block text-sm font-medium">Email</label>
                    <input id="customer_email" name="customer_email" type="email" value="{{ old('customer_email', $customer?->email) }}" required class="mt-2 block w-full border border-[#bdbdbd] bg-white px-4 py-3 text-sm focus:border-[#262626] focus:outline-none">
                </div>
                <div>
                    <label for="customer_phone" class="block text-sm font-medium">Nomor telepon</label>
                    <input id="customer_phone" name="customer_phone" type="tel" value="{{ old('customer_phone', $customer?->phone) }}" required class="mt-2 block w-full border border-[#bdbdbd] bg-white px-4 py-3 text-sm focus:border-[#262626] focus:outline-none">
                </div>
                <div>
                    <label for="customer_whatsapp" class="block text-sm font-medium">Nomor WhatsApp</label>
                    <input id="customer_whatsapp" name="customer_whatsapp" type="tel" value="{{ old('customer_whatsapp', $customer?->whatsapp) }}" required class="mt-2 block w-full border border-[#bdbdbd] bg-white px-4 py-3 text-sm focus:border-[#262626] focus:outline-none">
                </div>
            </div>
            <div>
                <label for="customer_address" class="block text-sm font-medium">Alamat</label>
                <textarea id="customer_address" name="customer_address" rows="3" required class="mt-2 block w-full border border-[#bdbdbd] bg-white px-4 py-3 text-sm focus:border-[#262626] focus:outline-none">{{ old('customer_address') }}</textarea>
            </div>
            <div>
                <label for="purchase_method" class="block text-sm font-medium">Metode pembelian yang diminati</label>
                <select id="purchase_method" name="purchase_method" required class="mt-2 block w-full border border-[#bdbdbd] bg-white px-4 py-3 text-sm focus:border-[#262626] focus:outline-none">
                    <option value="">Pilih metode</option>
                    <option value="cash" @selected(old('purchase_method') === 'cash')>Tunai</option>
                    <option value="financing" @selected(old('purchase_method') === 'financing')>Pembiayaan</option>
                    <option value="consultation" @selected(old('purchase_method') === 'consultation')>Konsultasi terlebih dahulu</option>
                </select>
            </div>
            <div>
                <label for="financing_preference" class="block text-sm font-medium">Preferensi pembiayaan (opsional)</label>
                <textarea id="financing_preference" name="financing_preference" rows="2" class="mt-2 block w-full border border-[#bdbdbd] bg-white px-4 py-3 text-sm focus:border-[#262626] focus:outline-none">{{ old('financing_preference') }}</textarea>
            </div>
            <div>
                <label for="customer_note" class="block text-sm font-medium">Catatan (opsional)</label>
                <textarea id="customer_note" name="customer_note" rows="3" class="mt-2 block w-full border border-[#bdbdbd] bg-white px-4 py-3 text-sm focus:border-[#262626] focus:outline-none">{{ old('customer_note') }}</textarea>
            </div>
            <fieldset class="border-t border-black/10 pt-6">
                <legend class="text-sm font-medium">Dokumen pendukung (opsional)</legend>
                <div class="mt-4 grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="document_type" class="block text-sm font-medium">Jenis dokumen</label>
                        <input id="document_type" name="documents[0][document_type]" type="text" value="{{ old('documents.0.document_type') }}" maxlength="60" class="mt-2 block w-full border border-[#bdbdbd] bg-white px-4 py-3 text-sm focus:border-[#262626] focus:outline-none">
                    </div>
                    <div>
                        <label for="document_label" class="block text-sm font-medium">Nama dokumen</label>
                        <input id="document_label" name="documents[0][document_label]" type="text" value="{{ old('documents.0.document_label') }}" maxlength="120" class="mt-2 block w-full border border-[#bdbdbd] bg-white px-4 py-3 text-sm focus:border-[#262626] focus:outline-none">
                    </div>
                </div>
                <div class="mt-5">
                    <label for="document_file" class="block text-sm font-medium">Pilih berkas (PDF, JPG, PNG, WEBP; maks. 5 MB)</label>
                    <input id="document_file" name="documents[0][file]" type="file" accept=".pdf,.jpg,.jpeg,.png,.webp" class="mt-2 block w-full border border-[#bdbdbd] bg-white px-4 py-3 text-sm file:mr-4 file:border-0 file:bg-[#f3f3f3] file:px-4 file:py-2">
                </div>
            </fieldset>
            <button type="submit" class="border border-[#262626] bg-[#262626] px-5 py-3 text-sm font-medium text-white transition hover:bg-white hover:text-[#262626]">Kirim Pengajuan</button>
        </form>
    </section>
@endsection
