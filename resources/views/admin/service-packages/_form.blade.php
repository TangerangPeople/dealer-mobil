<div class="grid gap-6 md:grid-cols-2">
    <div class="md:col-span-2">
        <label for="name" class="block text-sm font-semibold text-[#262626]">Nama paket</label>
        <input id="name" name="name" type="text" value="{{ old('name', $servicePackage->name ?? '') }}" class="mt-2 block w-full border border-[#bbbbbb] bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2" required>
        @error('name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="service_type" class="block text-sm font-semibold text-[#262626]">Jenis layanan</label>
        <select id="service_type" name="service_type" class="mt-2 block w-full border border-[#bbbbbb] bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2" required>
            <option value="maintenance" @selected(old('service_type', $servicePackage->service_type ?? '') === 'maintenance')>Servis berkala</option>
            <option value="repair" @selected(old('service_type', $servicePackage->service_type ?? '') === 'repair')>Perbaikan</option>
            <option value="inspection" @selected(old('service_type', $servicePackage->service_type ?? '') === 'inspection')>Inspeksi</option>
        </select>
        @error('service_type')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="estimated_price" class="block text-sm font-semibold text-[#262626]">Estimasi harga</label>
        <input id="estimated_price" name="estimated_price" type="number" min="0" value="{{ old('estimated_price', $servicePackage->estimated_price ?? 0) }}" class="mt-2 block w-full border border-[#bbbbbb] bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2" required>
        @error('estimated_price')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="estimated_duration" class="block text-sm font-semibold text-[#262626]">Durasi estimasi</label>
        <input id="estimated_duration" name="estimated_duration" type="text" value="{{ old('estimated_duration', $servicePackage->estimated_duration ?? '') }}" class="mt-2 block w-full border border-[#bbbbbb] bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2" required>
        @error('estimated_duration')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="sort_order" class="block text-sm font-semibold text-[#262626]">Urutan tampil</label>
        <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $servicePackage->sort_order ?? 0) }}" class="mt-2 block w-full border border-[#bbbbbb] bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2" required>
        @error('sort_order')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="is_active" class="block text-sm font-semibold text-[#262626]">Status</label>
        <select id="is_active" name="is_active" class="mt-2 block w-full border border-[#bbbbbb] bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
            <option value="1" @selected(old('is_active', $servicePackage->is_active ?? true) == true)>Aktif</option>
            <option value="0" @selected(old('is_active', $servicePackage->is_active ?? true) == false)>Tidak aktif</option>
        </select>
    </div>

    <div class="md:col-span-2">
        <label for="description" class="block text-sm font-semibold text-[#262626]">Deskripsi</label>
        <textarea id="description" name="description" rows="5" class="mt-2 block w-full border border-[#bbbbbb] bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">{{ old('description', $servicePackage->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="mt-8 flex flex-wrap gap-4">
    <button type="submit" class="border border-[#262626] bg-[#262626] px-5 py-3 text-sm font-semibold text-white transition hover:bg-white hover:text-[#262626] focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
        Simpan
    </button>
    <a href="{{ route('admin.service-packages.index') }}" class="border border-[#262626] bg-white px-5 py-3 text-sm font-semibold text-[#262626] transition hover:bg-[#262626] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
        Batal
    </a>
</div>
