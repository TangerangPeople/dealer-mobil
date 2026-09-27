@php
    $isEditing = $promotion->exists;
    $isActive = old('is_active', $isEditing ? (int) $promotion->is_active : 1);
@endphp

<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label for="vehicle_id" class="block text-sm font-semibold text-[#262626]">Kendaraan <span class="font-normal">(opsional)</span></label>
        <select id="vehicle_id" name="vehicle_id" aria-describedby="@error('vehicle_id') vehicle_id-error @enderror" @class(['mt-2 block w-full border bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('vehicle_id'), 'border-[#bbbbbb]' => ! $errors->has('vehicle_id')])>
            <option value="">Promo umum</option>
            @foreach ($vehicles as $vehicle)
                <option value="{{ $vehicle->id }}" @selected((string) old('vehicle_id', $promotion->vehicle_id) === (string) $vehicle->id)>{{ $vehicle->brand }} {{ $vehicle->name }}</option>
            @endforeach
        </select>
        @error('vehicle_id') <p id="vehicle_id-error" class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="title" class="block text-sm font-semibold text-[#262626]">Judul promo</label>
        <input id="title" name="title" type="text" maxlength="255" required value="{{ old('title', $promotion->title) }}" aria-describedby="@error('title') title-error @enderror" @class(['mt-2 block w-full border bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('title'), 'border-[#bbbbbb]' => ! $errors->has('title')])>
        @error('title') <p id="title-error" class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label for="slug" class="block text-sm font-semibold text-[#262626]">Slug URL</label>
        <input id="slug" name="slug" type="text" maxlength="255" value="{{ old('slug', $promotion->slug) }}" aria-describedby="slug-help @error('slug') slug-error @enderror" @class(['mt-2 block w-full border bg-white px-4 py-3 text-base font-mono outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('slug'), 'border-[#bbbbbb]' => ! $errors->has('slug')])>
        <p id="slug-help" class="mt-2 text-sm text-[#262626]">Kosongkan untuk membuat slug otomatis dari judul promo.</p>
        @error('slug') <p id="slug-error" class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label for="summary" class="block text-sm font-semibold text-[#262626]">Ringkasan</label>
        <textarea id="summary" name="summary" rows="3" maxlength="500" required aria-describedby="@error('summary') summary-error @enderror" @class(['mt-2 block w-full border bg-white px-4 py-3 text-base leading-7 outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('summary'), 'border-[#bbbbbb]' => ! $errors->has('summary')])>{{ old('summary', $promotion->summary) }}</textarea>
        @error('summary') <p id="summary-error" class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label for="description" class="block text-sm font-semibold text-[#262626]">Deskripsi</label>
        <textarea id="description" name="description" rows="6" required aria-describedby="@error('description') description-error @enderror" @class(['mt-2 block w-full border bg-white px-4 py-3 text-base leading-7 outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('description'), 'border-[#bbbbbb]' => ! $errors->has('description')])>{{ old('description', $promotion->description) }}</textarea>
        @error('description') <p id="description-error" class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="start_date" class="block text-sm font-semibold text-[#262626]">Tanggal mulai</label>
        <input id="start_date" name="start_date" type="date" required value="{{ old('start_date', $promotion->start_date?->format('Y-m-d')) }}" aria-describedby="@error('start_date') start_date-error @enderror" @class(['mt-2 block w-full border bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('start_date'), 'border-[#bbbbbb]' => ! $errors->has('start_date')])>
        @error('start_date') <p id="start_date-error" class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="end_date" class="block text-sm font-semibold text-[#262626]">Tanggal berakhir</label>
        <input id="end_date" name="end_date" type="date" required value="{{ old('end_date', $promotion->end_date?->format('Y-m-d')) }}" aria-describedby="@error('end_date') end_date-error @enderror" @class(['mt-2 block w-full border bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('end_date'), 'border-[#bbbbbb]' => ! $errors->has('end_date')])>
        @error('end_date') <p id="end_date-error" class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label for="image" class="block text-sm font-semibold text-[#262626]">Gambar promo <span class="font-normal">(opsional)</span></label>
        <input id="image" name="image" type="file" accept="image/*" aria-describedby="image-help @error('image') image-error @enderror" @class(['mt-2 block w-full border bg-white px-3 py-2 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('image'), 'border-[#bbbbbb]' => ! $errors->has('image')])>
        <p id="image-help" class="mt-2 text-sm text-[#262626]">Format yang didukung: JPG, PNG, WEBP. Maksimal 5 MB.</p>
        @error('image') <p id="image-error" class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label for="image_alt_text" class="block text-sm font-semibold text-[#262626]">Alt text gambar <span class="font-normal">(opsional)</span></label>
        <input id="image_alt_text" name="image_alt_text" type="text" maxlength="255" value="{{ old('image_alt_text', $promotion->image_alt_text) }}" @class(['mt-2 block w-full border bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('image_alt_text'), 'border-[#bbbbbb]' => ! $errors->has('image_alt_text')])>
        @error('image_alt_text') <p class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2 border border-[#bbbbbb] bg-[#f1f1f1] p-4">
        <div class="flex items-start gap-3">
            <input id="is_active" name="is_active" type="checkbox" value="1" @checked($isActive) class="mt-1 h-4 w-4 border-[#262626] text-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
            <div>
                <label for="is_active" class="font-semibold text-[#262626]">Promo aktif</label>
                <p class="mt-1 text-sm leading-6 text-[#262626]">Promo aktif tampil di halaman promosi dan katalog.</p>
            </div>
        </div>
        @error('is_active') <p class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-8 flex flex-col-reverse gap-3 border-t border-[#bbbbbb] pt-6 sm:flex-row sm:items-center">
    <a href="{{ route('admin.promotions.index') }}" class="border border-[#262626] bg-white px-5 py-3 text-center text-sm font-semibold text-[#262626] transition hover:bg-[#262626] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
        Batal
    </a>
    <button type="submit" class="border border-[#262626] bg-[#262626] px-5 py-3 text-sm font-semibold text-white transition hover:bg-white hover:text-[#262626] focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
        {{ $isEditing ? 'Simpan perubahan' : 'Simpan promo' }}
    </button>
</div>
