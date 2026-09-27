@php
    $isEditing = $gallery->exists;
    $isPublished = old('is_published', $isEditing ? (int) $gallery->is_published : 1);
@endphp

<div class="grid gap-6">
    <div>
        <label for="title" class="block text-sm font-semibold text-[#262626]">Judul galeri</label>
        <input id="title" name="title" type="text" maxlength="255" required value="{{ old('title', $gallery->title) }}" aria-describedby="@error('title') title-error @enderror" @class(['mt-2 block w-full border bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('title'), 'border-[#bbbbbb]' => ! $errors->has('title')])>
        @error('title') <p id="title-error" class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="gallery_type" class="block text-sm font-semibold text-[#262626]">Tipe galeri</label>
            <select id="gallery_type" name="gallery_type" required aria-describedby="@error('gallery_type') gallery_type-error @enderror" @class(['mt-2 block w-full border bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('gallery_type'), 'border-[#bbbbbb]' => ! $errors->has('gallery_type')])>
                <option value="">Pilih tipe</option>
                @foreach (['vehicle' => 'Vehicle', 'showroom' => 'Showroom', 'event' => 'Event', 'other' => 'Lainnya'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('gallery_type', $gallery->gallery_type) === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('gallery_type') <p id="gallery_type-error" class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="sort_order" class="block text-sm font-semibold text-[#262626]">Urutan tampil</label>
            <input id="sort_order" name="sort_order" type="number" min="0" step="1" required value="{{ old('sort_order', $gallery->sort_order ?? 0) }}" aria-describedby="@error('sort_order') sort_order-error @enderror" @class(['mt-2 block w-full border bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('sort_order'), 'border-[#bbbbbb]' => ! $errors->has('sort_order')])>
            @error('sort_order') <p id="sort_order-error" class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label for="image" class="block text-sm font-semibold text-[#262626]">Gambar <span class="font-normal">({{ $isEditing ? 'opsional untuk mengganti' : 'wajib' }})</span></label>
        <input id="image" name="image" type="file" accept="image/*" aria-describedby="image-help @error('image') image-error @enderror" @class(['mt-2 block w-full border bg-white px-3 py-2 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('image'), 'border-[#bbbbbb]' => ! $errors->has('image')])>
        <p id="image-help" class="mt-2 text-sm text-[#262626]">Format yang didukung: JPG, PNG, WEBP. Maksimal 5 MB. Dimensi minimum 600x400 px.</p>
        @error('image') <p id="image-error" class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="alt_text" class="block text-sm font-semibold text-[#262626]">Alt text</label>
        <input id="alt_text" name="alt_text" type="text" maxlength="255" required value="{{ old('alt_text', $gallery->alt_text) }}" aria-describedby="@error('alt_text') alt_text-error @enderror" @class(['mt-2 block w-full border bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('alt_text'), 'border-[#bbbbbb]' => ! $errors->has('alt_text')])>
        @error('alt_text') <p id="alt_text-error" class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="caption" class="block text-sm font-semibold text-[#262626]">Keterangan <span class="font-normal">(opsional)</span></label>
        <textarea id="caption" name="caption" rows="4" maxlength="1000" aria-describedby="@error('caption') caption-error @enderror" @class(['mt-2 block w-full border bg-white px-4 py-3 text-base leading-7 outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('caption'), 'border-[#bbbbbb]' => ! $errors->has('caption')])>{{ old('caption', $gallery->caption) }}</textarea>
        @error('caption') <p id="caption-error" class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
    </div>

    <div class="border border-[#bbbbbb] bg-[#f1f1f1] p-4">
        <div class="flex items-start gap-3">
            <input id="is_published" name="is_published" type="checkbox" value="1" @checked($isPublished) class="mt-1 h-4 w-4 border-[#262626] text-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
            <div>
                <label for="is_published" class="font-semibold text-[#262626]">Tampilkan di galeri</label>
                <p class="mt-1 text-sm leading-6 text-[#262626]">Galeri yang dipublikasikan akan muncul di halaman galeri publik.</p>
            </div>
        </div>
        @error('is_published') <p class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-8 flex flex-col-reverse gap-3 border-t border-[#bbbbbb] pt-6 sm:flex-row sm:items-center">
    <a href="{{ route('admin.galleries.index') }}" class="border border-[#262626] bg-white px-5 py-3 text-center text-sm font-semibold text-[#262626] transition hover:bg-[#262626] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
        Batal
    </a>
    <button type="submit" class="border border-[#262626] bg-[#262626] px-5 py-3 text-sm font-semibold text-white transition hover:bg-white hover:text-[#262626] focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
        {{ $isEditing ? 'Simpan perubahan' : 'Simpan galeri' }}
    </button>
</div>
