@php
    $isEditing = $sale->exists;
    $isActive = old('is_active', $isEditing ? (int) $sale->is_active : 1);
@endphp

<div class="grid gap-6">
    <div>
        <label for="name" class="block text-sm font-semibold text-[#262626]">Nama sales</label>
        <input
            id="name"
            name="name"
            type="text"
            maxlength="255"
            required
            value="{{ old('name', $sale->name) }}"
            aria-describedby="@error('name') name-error @enderror"
            @class(['mt-2 block w-full border bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('name'), 'border-[#bbbbbb]' => ! $errors->has('name')])
        >
        @error('name')
            <p id="name-error" class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="phone" class="block text-sm font-semibold text-[#262626]">Nomor telepon</label>
            <input
                id="phone"
                name="phone"
                type="tel"
                maxlength="20"
                required
                value="{{ old('phone', $sale->phone) }}"
                aria-describedby="@error('phone') phone-error @enderror"
                @class(['mt-2 block w-full border bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('phone'), 'border-[#bbbbbb]' => ! $errors->has('phone')])
            >
            @error('phone')
                <p id="phone-error" class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="whatsapp" class="block text-sm font-semibold text-[#262626]">Nomor WhatsApp</label>
            <input
                id="whatsapp"
                name="whatsapp"
                type="tel"
                maxlength="20"
                required
                value="{{ old('whatsapp', $sale->whatsapp) }}"
                aria-describedby="@error('whatsapp') whatsapp-error @enderror"
                @class(['mt-2 block w-full border bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('whatsapp'), 'border-[#bbbbbb]' => ! $errors->has('whatsapp')])
            >
            @error('whatsapp')
                <p id="whatsapp-error" class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="position" class="block text-sm font-semibold text-[#262626]">Jabatan <span class="font-normal">(opsional)</span></label>
        <input
            id="position"
            name="position"
            type="text"
            maxlength="100"
            value="{{ old('position', $sale->position) }}"
            aria-describedby="@error('position') position-error @enderror"
            @class(['mt-2 block w-full border bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('position'), 'border-[#bbbbbb]' => ! $errors->has('position')])
        >
        @error('position')
            <p id="position-error" class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div class="border border-[#bbbbbb] bg-[#f1f1f1] p-4">
            <div class="flex items-start gap-3">
                <input id="is_active" name="is_active" type="checkbox" value="1" @checked($isActive) class="mt-1 h-4 w-4 border-[#262626] text-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
                <div>
                    <label for="is_active" class="font-semibold text-[#262626]">Sales aktif</label>
                    <p class="mt-1 text-sm leading-6 text-[#262626]">Status aktif ditampilkan sebagai Active, status non-aktif sebagai Inactive.</p>
                </div>
            </div>
            @error('is_active')
                <p class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="sort_order" class="block text-sm font-semibold text-[#262626]">Urutan tampil</label>
            <input
                id="sort_order"
                name="sort_order"
                type="number"
                min="0"
                step="1"
                required
                value="{{ old('sort_order', $sale->sort_order ?? 0) }}"
                aria-describedby="@error('sort_order') sort_order-error @enderror"
                @class(['mt-2 block w-full border bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2', 'border-[#b91c1c]' => $errors->has('sort_order'), 'border-[#bbbbbb]' => ! $errors->has('sort_order')])
            >
            @error('sort_order')
                <p id="sort_order-error" class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>

<div class="mt-8 flex flex-col-reverse gap-3 border-t border-[#bbbbbb] pt-6 sm:flex-row sm:items-center">
    <a href="{{ route('admin.sales.index') }}" class="border border-[#262626] bg-white px-5 py-3 text-center text-sm font-semibold text-[#262626] transition hover:bg-[#262626] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
        Batal
    </a>
    <button type="submit" class="border border-[#262626] bg-[#262626] px-5 py-3 text-sm font-semibold text-white transition hover:bg-white hover:text-[#262626] focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
        {{ $isEditing ? 'Simpan perubahan' : 'Simpan sales' }}
    </button>
</div>
