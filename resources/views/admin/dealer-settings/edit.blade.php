@extends('layouts.admin')

@section('title', 'Dealer Settings')

@section('content')
    <div class="max-w-5xl">
        <div class="border-b border-[#bbbbbb] pb-8">
            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#262626]">Informasi Dealer</p>
            <h1 class="mt-3 text-3xl font-light leading-tight sm:text-4xl">Pengaturan dealer</h1>
        </div>

        @if ($errors->any())
            <div class="mt-6 border-l-4 border-[#b91c1c] bg-white p-4" role="alert">
                <p class="text-sm font-semibold text-[#262626]">Terdapat beberapa masalah pada data yang dikirim.</p>
            </div>
        @endif

        @if (session('success'))
            <div class="mt-6 border-l-4 border-[#166534] bg-white p-4 text-sm leading-6 text-[#262626]" role="status">
                <span class="font-semibold">Berhasil.</span> {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.dealer-settings.update') }}" enctype="multipart/form-data" class="mt-8 space-y-8 border border-[#bbbbbb] bg-white p-6 sm:p-8">
            @csrf
            @method('PUT')

            <section class="space-y-6">
                <div class="border-b border-[#bbbbbb] pb-4">
                    <h2 class="text-xl font-semibold text-[#262626]">1. Identitas Dealer</h2>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="dealer_name" class="block text-sm font-semibold text-[#262626]">Nama dealer</label>
                        <input id="dealer_name" name="dealer_name" type="text" maxlength="255" required value="{{ old('dealer_name', $setting->dealer_name) }}" @class(['mt-2 block w-full border bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('dealer_name'), 'border-[#bbbbbb]' => ! $errors->has('dealer_name')])>
                        @error('dealer_name') <p class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="tagline" class="block text-sm font-semibold text-[#262626]">Tagline</label>
                        <input id="tagline" name="tagline" type="text" maxlength="255" value="{{ old('tagline', $setting->tagline) }}" @class(['mt-2 block w-full border bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('tagline'), 'border-[#bbbbbb]' => ! $errors->has('tagline')])>
                        @error('tagline') <p class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="logo" class="block text-sm font-semibold text-[#262626]">Logo dealer</label>
                    <input id="logo" name="logo" type="file" accept="image/*" @class(['mt-2 block w-full border bg-white px-3 py-2 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('logo'), 'border-[#bbbbbb]' => ! $errors->has('logo')])>
                    @if ($setting->logo_path)
                        <div class="mt-4 flex items-center gap-4 border border-[#bbbbbb] bg-[#f1f1f1] p-4">
                            <img src="{{ Storage::disk('public')->url($setting->logo_path) }}" alt="Logo dealer" class="h-16 w-16 border border-[#bbbbbb] object-contain bg-white">
                            <label class="inline-flex items-center gap-2 text-sm font-semibold text-[#262626]">
                                <input type="checkbox" name="remove_logo" value="1">
                                Hapus logo
                            </label>
                        </div>
                    @endif
                    @error('logo') <p class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
                </div>
            </section>

            <section class="space-y-6">
                <div class="border-b border-[#bbbbbb] pb-4">
                    <h2 class="text-xl font-semibold text-[#262626]">2. Hero Website</h2>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="hero_title" class="block text-sm font-semibold text-[#262626]">Hero title</label>
                        <input id="hero_title" name="hero_title" type="text" maxlength="255" value="{{ old('hero_title', $setting->hero_title) }}" @class(['mt-2 block w-full border bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('hero_title'), 'border-[#bbbbbb]' => ! $errors->has('hero_title')])>
                        @error('hero_title') <p class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="hero_description" class="block text-sm font-semibold text-[#262626]">Hero description</label>
                        <textarea id="hero_description" name="hero_description" rows="3" @class(['mt-2 block w-full border bg-white px-4 py-3 text-base leading-7 outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('hero_description'), 'border-[#bbbbbb]' => ! $errors->has('hero_description')])>{{ old('hero_description', $setting->hero_description) }}</textarea>
                        @error('hero_description') <p class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="hero_image" class="block text-sm font-semibold text-[#262626]">Hero image</label>
                    <input id="hero_image" name="hero_image" type="file" accept="image/*" @class(['mt-2 block w-full border bg-white px-3 py-2 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('hero_image'), 'border-[#bbbbbb]' => ! $errors->has('hero_image')])>
                    @if ($setting->hero_image_path)
                        <div class="mt-4 flex items-center gap-4 border border-[#bbbbbb] bg-[#f1f1f1] p-4">
                            <img src="{{ Storage::disk('public')->url($setting->hero_image_path) }}" alt="Hero dealer" class="h-32 w-56 border border-[#bbbbbb] object-cover bg-white">
                            <label class="inline-flex items-center gap-2 text-sm font-semibold text-[#262626]">
                                <input type="checkbox" name="remove_hero_image" value="1">
                                Hapus hero image
                            </label>
                        </div>
                    @endif
                    @error('hero_image') <p class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
                </div>
            </section>

            <section class="space-y-6">
                <div class="border-b border-[#bbbbbb] pb-4">
                    <h2 class="text-xl font-semibold text-[#262626]">3. Profil</h2>
                </div>

                <div>
                    <label for="home_intro" class="block text-sm font-semibold text-[#262626]">Home intro</label>
                    <textarea id="home_intro" name="home_intro" rows="4" @class(['mt-2 block w-full border bg-white px-4 py-3 text-base leading-7 outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('home_intro'), 'border-[#bbbbbb]' => ! $errors->has('home_intro')])>{{ old('home_intro', $setting->home_intro) }}</textarea>
                    @error('home_intro') <p class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="history" class="block text-sm font-semibold text-[#262626]">History</label>
                    <textarea id="history" name="history" rows="5" @class(['mt-2 block w-full border bg-white px-4 py-3 text-base leading-7 outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('history'), 'border-[#bbbbbb]' => ! $errors->has('history')])>{{ old('history', $setting->history) }}</textarea>
                    @error('history') <p class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="about_content" class="block text-sm font-semibold text-[#262626]">About content</label>
                    <textarea id="about_content" name="about_content" rows="5" @class(['mt-2 block w-full border bg-white px-4 py-3 text-base leading-7 outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('about_content'), 'border-[#bbbbbb]' => ! $errors->has('about_content')])>{{ old('about_content', $setting->about_content) }}</textarea>
                    @error('about_content') <p class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
                </div>
            </section>

            <section class="space-y-6">
                <div class="border-b border-[#bbbbbb] pb-4">
                    <h2 class="text-xl font-semibold text-[#262626]">4. Kontak</h2>
                </div>

                <div>
                    <label for="address" class="block text-sm font-semibold text-[#262626]">Alamat</label>
                    <textarea id="address" name="address" rows="3" required @class(['mt-2 block w-full border bg-white px-4 py-3 text-base leading-7 outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('address'), 'border-[#bbbbbb]' => ! $errors->has('address')])>{{ old('address', $setting->address) }}</textarea>
                    @error('address') <p class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-[#262626]">Phone</label>
                        <input id="phone" name="phone" type="tel" maxlength="30" required value="{{ old('phone', $setting->phone) }}" @class(['mt-2 block w-full border bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('phone'), 'border-[#bbbbbb]' => ! $errors->has('phone')])>
                        @error('phone') <p class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-[#262626]">Email</label>
                        <input id="email" name="email" type="email" maxlength="255" required value="{{ old('email', $setting->email) }}" @class(['mt-2 block w-full border bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('email'), 'border-[#bbbbbb]' => ! $errors->has('email')])>
                        @error('email') <p class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="whatsapp" class="block text-sm font-semibold text-[#262626]">WhatsApp</label>
                    <input id="whatsapp" name="whatsapp" type="tel" maxlength="20" required value="{{ old('whatsapp', $setting->whatsapp) }}" @class(['mt-2 block w-full border bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('whatsapp'), 'border-[#bbbbbb]' => ! $errors->has('whatsapp')])>
                    @error('whatsapp') <p class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
                </div>
            </section>

            <section class="space-y-6">
                <div class="border-b border-[#bbbbbb] pb-4">
                    <h2 class="text-xl font-semibold text-[#262626]">5. Jam Operasional</h2>
                </div>

                @php
                    $openingHours = old('opening_hours', $setting->opening_hours ?? [
                        ['day' => 'Senin', 'open' => '08:00', 'close' => '17:00', 'closed' => false],
                        ['day' => 'Selasa', 'open' => '08:00', 'close' => '17:00', 'closed' => false],
                        ['day' => 'Rabu', 'open' => '08:00', 'close' => '17:00', 'closed' => false],
                        ['day' => 'Kamis', 'open' => '08:00', 'close' => '17:00', 'closed' => false],
                        ['day' => 'Jumat', 'open' => '08:00', 'close' => '17:00', 'closed' => false],
                        ['day' => 'Sabtu', 'open' => '09:00', 'close' => '15:00', 'closed' => false],
                        ['day' => 'Minggu', 'open' => null, 'close' => null, 'closed' => true],
                    ]);
                @endphp

                <div id="opening-hours-wrapper" class="space-y-4">
                    @foreach ($openingHours as $index => $entry)
                        <div class="grid gap-4 border border-[#bbbbbb] bg-[#f1f1f1] p-4 md:grid-cols-[1.2fr_1fr_1fr_1fr_auto] md:items-end">
                            <div>
                                <label class="block text-sm font-semibold text-[#262626]">Hari</label>
                                <input type="text" name="opening_hours[{{ $index }}][day]" value="{{ $entry['day'] ?? '' }}" class="mt-2 block w-full border border-[#bbbbbb] bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-[#262626]">Buka</label>
                                <input type="time" name="opening_hours[{{ $index }}][open]" value="{{ $entry['open'] ?? '' }}" class="mt-2 block w-full border border-[#bbbbbb] bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2" {{ ($entry['closed'] ?? false) ? 'disabled' : '' }}>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-[#262626]">Tutup</label>
                                <input type="time" name="opening_hours[{{ $index }}][close]" value="{{ $entry['close'] ?? '' }}" class="mt-2 block w-full border border-[#bbbbbb] bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2" {{ ($entry['closed'] ?? false) ? 'disabled' : '' }}>
                            </div>
                            <label class="inline-flex items-center gap-2 text-sm font-semibold text-[#262626]">
                                <input type="checkbox" name="opening_hours[{{ $index }}][closed]" value="1" @checked((bool) ($entry['closed'] ?? false))>
                                Tutup
                            </label>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="space-y-6">
                <div class="border-b border-[#bbbbbb] pb-4">
                    <h2 class="text-xl font-semibold text-[#262626]">6. Layanan</h2>
                </div>

                @php
                    $services = old('services', $setting->services ?? ['Penjualan Mobil Baru', 'Penjualan Mobil Bekas', 'Test Drive', 'Layanan Kredit']);
                @endphp
                <div id="service-list" class="space-y-3">
                    @foreach ($services as $index => $service)
                        <div class="flex items-center gap-3">
                            <input type="text" name="services[{{ $index }}]" value="{{ $service }}" class="block w-full border border-[#bbbbbb] bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2" aria-label="Layanan {{ $index + 1 }}">
                            <button type="button" data-remove-service class="border border-[#262626] bg-white px-3 py-3 text-sm font-semibold text-[#262626] transition hover:bg-[#262626] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">Hapus</button>
                        </div>
                    @endforeach
                </div>
                <button type="button" id="add-service" class="border border-[#262626] bg-[#262626] px-5 py-3 text-sm font-semibold text-white transition hover:bg-white hover:text-[#262626] focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">Tambah layanan</button>
            </section>

            <section class="space-y-6">
                <div class="border-b border-[#bbbbbb] pb-4">
                    <h2 class="text-xl font-semibold text-[#262626]">7. Lokasi</h2>
                </div>

                <div>
                    <label for="map_url" class="block text-sm font-semibold text-[#262626]">Map URL</label>
                    <input id="map_url" name="map_url" type="url" maxlength="2048" value="{{ old('map_url', $setting->map_url) }}" @class(['mt-2 block w-full border bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('map_url'), 'border-[#bbbbbb]' => ! $errors->has('map_url')])>
                    @error('map_url') <p class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="latitude" class="block text-sm font-semibold text-[#262626]">Latitude</label>
                        <input id="latitude" name="latitude" type="number" step="any" min="-90" max="90" value="{{ old('latitude', $setting->latitude) }}" @class(['mt-2 block w-full border bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('latitude'), 'border-[#bbbbbb]' => ! $errors->has('latitude')])>
                        @error('latitude') <p class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="longitude" class="block text-sm font-semibold text-[#262626]">Longitude</label>
                        <input id="longitude" name="longitude" type="number" step="any" min="-180" max="180" value="{{ old('longitude', $setting->longitude) }}" @class(['mt-2 block w-full border bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('longitude'), 'border-[#bbbbbb]' => ! $errors->has('longitude')])>
                        @error('longitude') <p class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <section class="space-y-6">
                <div class="border-b border-[#bbbbbb] pb-4">
                    <h2 class="text-xl font-semibold text-[#262626]">8. Social Media</h2>
                </div>

                @php
                    $socialLinks = old('social_links', $setting->social_links ?? [
                        'instagram' => '',
                        'facebook' => '',
                        'youtube' => '',
                    ]);
                @endphp

                <div class="grid gap-6 md:grid-cols-3">
                    @foreach (['instagram' => 'Instagram', 'facebook' => 'Facebook', 'youtube' => 'YouTube'] as $key => $label)
                        <div>
                            <label for="social_links_{{ $key }}" class="block text-sm font-semibold text-[#262626]">{{ $label }}</label>
                            <input id="social_links_{{ $key }}" name="social_links[{{ $key }}]" type="url" value="{{ $socialLinks[$key] ?? '' }}" @class(['mt-2 block w-full border bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('social_links'), 'border-[#bbbbbb]' => ! $errors->has('social_links')])>
                        </div>
                    @endforeach
                </div>
            </section>

            <div class="flex justify-end">
                <button type="submit" class="border border-[#262626] bg-[#262626] px-6 py-3 text-sm font-semibold text-white transition hover:bg-white hover:text-[#262626] focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">Simpan pengaturan</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const serviceList = document.getElementById('service-list');
            const addServiceButton = document.getElementById('add-service');

            if (serviceList && addServiceButton) {
                addServiceButton.addEventListener('click', function () {
                    const currentCount = serviceList.querySelectorAll('input[type="text"]').length;
                    const row = document.createElement('div');
                    row.className = 'flex items-center gap-3';
                    row.innerHTML = '<input type="text" name="services[' + currentCount + ']" value="" class="block w-full border border-[#bbbbbb] bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2" aria-label="Layanan ' + (currentCount + 1) + '"><button type="button" data-remove-service class="border border-[#262626] bg-white px-3 py-3 text-sm font-semibold text-[#262626] transition hover:bg-[#262626] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">Hapus</button>';
                    serviceList.appendChild(row);
                });

                serviceList.addEventListener('click', function (event) {
                    const target = event.target;
                    if (target.matches('[data-remove-service]')) {
                        const row = target.closest('div');
                        if (row && serviceList.children.length > 1) {
                            row.remove();
                        }
                    }
                });
            }
        });
    </script>
@endsection
