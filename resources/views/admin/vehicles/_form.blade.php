@php
    $isEditing = $vehicle->exists;
    $isFeatured = old('is_featured', $isEditing ? (int) $vehicle->is_featured : 0);
@endphp

<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label for="category_id" class="block text-sm font-semibold">Kategori</label>
        <select id="category_id" name="category_id" required aria-describedby="@error('category_id') category_id-error @enderror" @class(['mt-2 block w-full border bg-white px-4 py-3 outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('category_id'), 'border-[#bbbbbb]' => ! $errors->has('category_id')])>
            <option value="">Pilih kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((string) old('category_id', $vehicle->category_id) === (string) $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id') <p id="category_id-error" class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="brand" class="block text-sm font-semibold">Merek</label>
        <input id="brand" name="brand" type="text" maxlength="100" required value="{{ old('brand', $vehicle->brand) }}" aria-describedby="@error('brand') brand-error @enderror" @class(['mt-2 block w-full border bg-white px-4 py-3 outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('brand'), 'border-[#bbbbbb]' => ! $errors->has('brand')])>
        @error('brand') <p id="brand-error" class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="name" class="block text-sm font-semibold">Nama kendaraan</label>
        <input id="name" name="name" type="text" maxlength="255" required value="{{ old('name', $vehicle->name) }}" aria-describedby="@error('name') name-error @enderror" @class(['mt-2 block w-full border bg-white px-4 py-3 outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('name'), 'border-[#bbbbbb]' => ! $errors->has('name')])>
        @error('name') <p id="name-error" class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="type" class="block text-sm font-semibold">Tipe</label>
        <input id="type" name="type" type="text" maxlength="100" required value="{{ old('type', $vehicle->type) }}" aria-describedby="@error('type') type-error @enderror" @class(['mt-2 block w-full border bg-white px-4 py-3 outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('type'), 'border-[#bbbbbb]' => ! $errors->has('type')])>
        @error('type') <p id="type-error" class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label for="slug" class="block text-sm font-semibold">Slug URL</label>
        <input id="slug" name="slug" type="text" maxlength="255" value="{{ old('slug', $vehicle->slug) }}" aria-describedby="slug-help @error('slug') slug-error @enderror" @class(['mt-2 block w-full border bg-white px-4 py-3 font-mono text-sm outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('slug'), 'border-[#bbbbbb]' => ! $errors->has('slug')])>
        <p id="slug-help" class="mt-2 text-sm">Kosongkan untuk membuat slug dari nama; nilai manual tetap dinormalisasi menjadi URL-friendly.</p>
        @error('slug') <p id="slug-error" class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="year" class="block text-sm font-semibold">Tahun</label>
        <input id="year" name="year" type="number" min="1900" max="{{ now()->addYear()->year }}" required value="{{ old('year', $vehicle->year) }}" aria-describedby="@error('year') year-error @enderror" @class(['mt-2 block w-full border bg-white px-4 py-3 outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('year'), 'border-[#bbbbbb]' => ! $errors->has('year')])>
        @error('year') <p id="year-error" class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="price" class="block text-sm font-semibold">Harga (Rp)</label>
        <input id="price" name="price" type="number" min="0" step="1" required value="{{ old('price', $vehicle->price) }}" aria-describedby="@error('price') price-error @enderror" @class(['mt-2 block w-full border bg-white px-4 py-3 outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('price'), 'border-[#bbbbbb]' => ! $errors->has('price')])>
        @error('price') <p id="price-error" class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="color" class="block text-sm font-semibold">Warna <span class="font-normal">(opsional)</span></label>
        <input id="color" name="color" type="text" maxlength="50" value="{{ old('color', $vehicle->color) }}" @class(['mt-2 block w-full border bg-white px-4 py-3 outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('color'), 'border-[#bbbbbb]' => ! $errors->has('color')])>
        @error('color') <p class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="transmission" class="block text-sm font-semibold">Transmisi <span class="font-normal">(opsional)</span></label>
        <input id="transmission" name="transmission" type="text" maxlength="30" value="{{ old('transmission', $vehicle->transmission) }}" @class(['mt-2 block w-full border bg-white px-4 py-3 outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('transmission'), 'border-[#bbbbbb]' => ! $errors->has('transmission')])>
        @error('transmission') <p class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="engine_capacity" class="block text-sm font-semibold">Kapasitas mesin (cc) <span class="font-normal">(opsional)</span></label>
        <input id="engine_capacity" name="engine_capacity" type="number" min="1" value="{{ old('engine_capacity', $vehicle->engine_capacity) }}" @class(['mt-2 block w-full border bg-white px-4 py-3 outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('engine_capacity'), 'border-[#bbbbbb]' => ! $errors->has('engine_capacity')])>
        @error('engine_capacity') <p class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="mileage" class="block text-sm font-semibold">Kilometer <span class="font-normal">(opsional)</span></label>
        <input id="mileage" name="mileage" type="number" min="0" value="{{ old('mileage', $vehicle->mileage) }}" @class(['mt-2 block w-full border bg-white px-4 py-3 outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('mileage'), 'border-[#bbbbbb]' => ! $errors->has('mileage')])>
        @error('mileage') <p class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="status" class="block text-sm font-semibold">Status</label>
        <select id="status" name="status" required aria-describedby="status-help @error('status') status-error @enderror" @class(['mt-2 block w-full border bg-white px-4 py-3 outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('status'), 'border-[#bbbbbb]' => ! $errors->has('status')])>
            @foreach (['available' => 'Available — unit tersedia', 'reserved' => 'Reserved — sedang dipesan', 'sold' => 'Sold — sudah terjual', 'inactive' => 'Inactive — tidak ditampilkan'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $vehicle->status ?: 'inactive') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <p id="status-help" class="mt-2 text-sm">Kendaraan available harus memiliki satu foto utama.</p>
        @error('status') <p id="status-error" class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
    </div>

    <div class="border border-[#bbbbbb] bg-[#f1f1f1] p-4 md:self-end">
        <div class="flex items-start gap-3">
            <input id="is_featured" name="is_featured" type="checkbox" value="1" @checked($isFeatured) class="mt-1 h-4 w-4 border-[#262626] text-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
            <div>
                <label for="is_featured" class="font-semibold">Tandai sebagai featured</label>
                <p class="mt-1 text-sm leading-6">Kendaraan inactive tidak dapat menjadi featured.</p>
            </div>
        </div>
        @error('is_featured') <p class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label for="description" class="block text-sm font-semibold">Deskripsi <span class="font-normal">(opsional)</span></label>
        <textarea id="description" name="description" rows="6" @class(['mt-2 block w-full border bg-white px-4 py-3 leading-7 outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('description'), 'border-[#bbbbbb]' => ! $errors->has('description')])>{{ old('description', $vehicle->description) }}</textarea>
        @error('description') <p class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
    </div>
</div>
