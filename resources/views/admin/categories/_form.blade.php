@php
    $isEditing = $category->exists;
    $isActive = old('is_active', $isEditing ? (int) $category->is_active : 1);
@endphp

<div class="grid gap-6">
    <div>
        <label for="name" class="block text-sm font-semibold text-[#262626]">Nama kategori</label>
        <input
            id="name"
            name="name"
            type="text"
            value="{{ old('name', $category->name) }}"
            maxlength="100"
            required
            autofocus
            aria-describedby="name-help @error('name') name-error @enderror"
            @class(['mt-2 block w-full border bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('name'), 'border-[#bbbbbb]' => ! $errors->has('name')])
        >
        <p id="name-help" class="mt-2 text-sm text-[#262626]">Contoh: SUV, MPV, atau Sedan.</p>
        @error('name')
            <p id="name-error" class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="slug" class="block text-sm font-semibold text-[#262626]">Slug URL</label>
        <input
            id="slug"
            name="slug"
            type="text"
            value="{{ old('slug', $category->slug) }}"
            maxlength="120"
            aria-describedby="slug-help @error('slug') slug-error @enderror"
            @class(['mt-2 block w-full border bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('slug'), 'border-[#bbbbbb]' => ! $errors->has('slug')])
        >
        <p id="slug-help" class="mt-2 text-sm text-[#262626]">Kosongkan untuk membuat slug dari nama. Nilai akan dinormalisasi menjadi URL-friendly.</p>
        @error('slug')
            <p id="slug-error" class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-semibold text-[#262626]">Deskripsi <span class="font-normal">(opsional)</span></label>
        <textarea
            id="description"
            name="description"
            rows="5"
            aria-describedby="description-help @error('description') description-error @enderror"
            @class(['mt-2 block w-full border bg-white px-4 py-3 text-base leading-7 outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('description'), 'border-[#bbbbbb]' => ! $errors->has('description')])
        >{{ old('description', $category->description) }}</textarea>
        <p id="description-help" class="mt-2 text-sm text-[#262626]">Penjelasan singkat untuk membantu pengelolaan kategori.</p>
        @error('description')
            <p id="description-error" class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p>
        @enderror
    </div>

    <div class="border border-[#bbbbbb] bg-[#f1f1f1] p-4">
        <div class="flex items-start gap-3">
            <input id="is_active" name="is_active" type="checkbox" value="1" @checked($isActive) class="mt-1 h-4 w-4 border-[#262626] text-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
            <div>
                <label for="is_active" class="font-semibold text-[#262626]">Kategori aktif</label>
                <p class="mt-1 text-sm leading-6 text-[#262626]">Kategori aktif tersedia untuk digunakan pada pengelolaan kendaraan.</p>
            </div>
        </div>
        @error('is_active')
            <p class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="mt-8 flex flex-col-reverse gap-3 border-t border-[#bbbbbb] pt-6 sm:flex-row sm:items-center">
    <a href="{{ route('admin.categories.index') }}" class="border border-[#262626] bg-white px-5 py-3 text-center text-sm font-semibold text-[#262626] transition hover:bg-[#262626] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
        Batal
    </a>
    <button type="submit" class="border border-[#262626] bg-[#262626] px-5 py-3 text-sm font-semibold text-white transition hover:bg-white hover:text-[#262626] focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
        {{ $isEditing ? 'Simpan perubahan' : 'Simpan kategori' }}
    </button>
</div>
