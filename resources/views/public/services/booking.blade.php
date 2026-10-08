@extends('public.layouts.app')

@section('title', $settings->dealer_name.' | Booking Service')

@section('content')
    <section class="section-shell py-16 sm:py-20">
        <div class="mb-8 max-w-2xl">
            <p class="section-label text-[#5f5f5f]">Booking service</p>
            <h1 class="mt-4 text-4xl font-light tracking-[-0.04em] text-[#1e1e1e] sm:text-5xl">Booking Service</h1>
        </div>

        @if (session('success'))
            <div class="mb-8 border-l-4 border-[#166534] bg-[#f4f7f4] p-4 text-sm text-[#1d4d2a]" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-8 border border-[#dca2a2] bg-[#fdf2f2] p-4 text-sm text-[#7f1d1d]">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('booking-service.store') }}" class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
            @csrf

            <div class="soft-panel p-6 sm:p-8">
                <div class="space-y-6">
                    <div>
                        <label for="customer_name" class="block text-sm font-semibold text-[#1e1e1e]">Nama lengkap</label>
                        <input id="customer_name" name="customer_name" type="text" value="{{ old('customer_name') }}" class="mt-2 block w-full border border-black/10 bg-white px-4 py-3 text-base text-[#1e1e1e] focus:border-[#262626] focus:outline-none" required>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-[#1e1e1e]">Nomor telepon</label>
                            <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" class="mt-2 block w-full border border-black/10 bg-white px-4 py-3 text-base text-[#1e1e1e] focus:border-[#262626] focus:outline-none" required>
                        </div>
                        <div>
                            <label for="whatsapp" class="block text-sm font-semibold text-[#1e1e1e]">WhatsApp</label>
                            <input id="whatsapp" name="whatsapp" type="tel" value="{{ old('whatsapp') }}" class="mt-2 block w-full border border-black/10 bg-white px-4 py-3 text-base text-[#1e1e1e] focus:border-[#262626] focus:outline-none" required>
                        </div>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="vehicle_id" class="block text-sm font-semibold text-[#1e1e1e]">Mobil yang akan servis</label>
                            <select id="vehicle_id" name="vehicle_id" class="mt-2 block w-full border border-black/10 bg-white px-4 py-3 text-base text-[#1e1e1e] focus:border-[#262626] focus:outline-none">
                                <option value="">Pilih mobil (opsional)</option>
                                @foreach ($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" @selected(old('vehicle_id', $selectedVehicle?->id) == $vehicle->id)>{{ $vehicle->brand }} {{ $vehicle->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="vehicle_plate" class="block text-sm font-semibold text-[#1e1e1e]">Nomor plat</label>
                            <input id="vehicle_plate" name="vehicle_plate" type="text" value="{{ old('vehicle_plate') }}" class="mt-2 block w-full border border-black/10 bg-white px-4 py-3 text-base text-[#1e1e1e] focus:border-[#262626] focus:outline-none" required>
                        </div>
                    </div>

                    <div>
                        <label for="service_package_id" class="block text-sm font-semibold text-[#1e1e1e]">Paket servis</label>
                        <select id="service_package_id" name="service_package_id" class="mt-2 block w-full border border-black/10 bg-white px-4 py-3 text-base text-[#1e1e1e] focus:border-[#262626] focus:outline-none" required>
                            <option value="">Pilih paket</option>
                            @foreach ($servicePackages as $servicePackage)
                                <option value="{{ $servicePackage->id }}" @selected(old('service_package_id', request('service_package_id')) == $servicePackage->id)>{{ $servicePackage->name }} ({{ $servicePackage->estimated_duration }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="service_type" class="block text-sm font-semibold text-[#1e1e1e]">Jenis layanan</label>
                            <select id="service_type" name="service_type" class="mt-2 block w-full border border-black/10 bg-white px-4 py-3 text-base text-[#1e1e1e] focus:border-[#262626] focus:outline-none" required>
                                <option value="maintenance" @selected(old('service_type') === 'maintenance')>Servis berkala</option>
                                <option value="repair" @selected(old('service_type') === 'repair')>Perbaikan</option>
                                <option value="inspection" @selected(old('service_type') === 'inspection')>Inspeksi</option>
                            </select>
                        </div>
                        <div>
                            <label for="preferred_date" class="block text-sm font-semibold text-[#1e1e1e]">Tanggal yang diinginkan</label>
                            <input id="preferred_date" name="preferred_date" type="date" value="{{ old('preferred_date', now()->addDay()->toDateString()) }}" class="mt-2 block w-full border border-black/10 bg-white px-4 py-3 text-base text-[#1e1e1e] focus:border-[#262626] focus:outline-none" required>
                        </div>
                    </div>

                    <div>
                        <label for="preferred_time" class="block text-sm font-semibold text-[#1e1e1e]">Jam yang diinginkan</label>
                        <input id="preferred_time" name="preferred_time" type="time" value="{{ old('preferred_time') }}" class="mt-2 block w-full border border-black/10 bg-white px-4 py-3 text-base text-[#1e1e1e] focus:border-[#262626] focus:outline-none" required>
                    </div>

                    <div>
                        <label for="complaint" class="block text-sm font-semibold text-[#1e1e1e]">Keluhan / masalah</label>
                        <textarea id="complaint" name="complaint" rows="5" class="mt-2 block w-full border border-black/10 bg-white px-4 py-3 text-base text-[#1e1e1e] focus:border-[#262626] focus:outline-none" required>{{ old('complaint') }}</textarea>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-semibold text-[#1e1e1e]">Catatan tambahan</label>
                        <textarea id="notes" name="notes" rows="4" class="mt-2 block w-full border border-black/10 bg-white px-4 py-3 text-base text-[#1e1e1e] focus:border-[#262626] focus:outline-none">{{ old('notes') }}</textarea>
                    </div>

                    <button type="submit" class="inline-flex items-center justify-center border border-[#262626] bg-[#262626] px-6 py-3 text-sm font-medium text-white transition hover:bg-[#101010]">
                        Kirim Booking
                    </button>
                </div>
            </div>

            <aside class="editorial-card p-6 sm:p-8">
                <p class="section-label text-[#5f5f5f]">Butuh bantuan cepat?</p>
                <h2 class="mt-4 text-3xl font-light text-[#1e1e1e]">Hubungi kami</h2>
                <div class="mt-6 space-y-3 text-sm text-[#4b4b4b]">
                    <p>Telepon: {{ $settings->phone ?? '-' }}</p>
                    <p>WhatsApp: {{ $settings->whatsapp ?? '-' }}</p>
                    <p>Email: {{ $settings->email ?? '-' }}</p>
                    <p>Alamat: {{ $settings->address ?? '-' }}</p>
                </div>
                <a href="{{ route('services.index') }}" class="mt-8 inline-flex text-sm font-medium text-[#262626] underline underline-offset-4">Lihat paket servis</a>
                <a href="{{ route('sales.chat') }}" target="_blank" rel="noopener noreferrer" class="mt-4 inline-flex text-sm font-medium text-[#262626] underline underline-offset-4">Chat Sales</a>
                <a href="{{ route('contact') }}" class="mt-4 inline-flex text-sm font-medium text-[#262626] underline underline-offset-4">Kontak</a>
            </aside>
        </form>
    </section>
@endsection
