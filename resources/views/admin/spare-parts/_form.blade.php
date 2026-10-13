
@php
    $editing = isset($sparePart) && $sparePart->exists;
@endphp

@if ($errors->any())
    <div class="mb-5 border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-800">
        <ul class="list-inside list-disc space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label for="code" class="mb-2 block text-sm font-medium">Kode Sparepart *</label>
        <input
            id="code"
            name="code"
            value="{{ old('code', $sparePart->code) }}"
            required
            maxlength="50"
            class="min-h-11 w-full border border-black/15 px-3 py-2 text-sm"
        >
    </div>

    <div>
        <label for="name" class="mb-2 block text-sm font-medium">Nama Sparepart *</label>
        <input
            id="name"
            name="name"
            value="{{ old('name', $sparePart->name) }}"
            required
            maxlength="150"
            class="min-h-11 w-full border border-black/15 px-3 py-2 text-sm"
        >
    </div>

    <div>
        <label for="unit" class="mb-2 block text-sm font-medium">Satuan *</label>
        <input
            id="unit"
            name="unit"
            value="{{ old('unit', $sparePart->unit ?: 'pcs') }}"
            required
            maxlength="30"
            class="min-h-11 w-full border border-black/15 px-3 py-2 text-sm"
        >
    </div>

    <div>
        <label for="purchase_price" class="mb-2 block text-sm font-medium">Harga Beli (Rp) *</label>
        <input
            id="purchase_price"
            name="purchase_price"
            type="number"
            min="0"
            step="0.01"
            value="{{ old('purchase_price', $sparePart->purchase_price ?? 0) }}"
            required
            class="min-h-11 w-full border border-black/15 px-3 py-2 text-sm"
        >
    </div>

    <div>
        <label for="selling_price" class="mb-2 block text-sm font-medium">Harga Jual (Rp) *</label>
        <input
            id="selling_price"
            name="selling_price"
            type="number"
            min="0"
            step="0.01"
            value="{{ old('selling_price', $sparePart->selling_price ?? 0) }}"
            required
            class="min-h-11 w-full border border-black/15 px-3 py-2 text-sm"
        >
    </div>

    <div>
        <label for="stock" class="mb-2 block text-sm font-medium">Stok Saat Ini *</label>
        <input
            id="stock"
            name="stock"
            type="number"
            min="0"
            step="1"
            value="{{ old('stock', $sparePart->stock ?? 0) }}"
            required
            class="min-h-11 w-full border border-black/15 px-3 py-2 text-sm"
        >

        @if ($editing)
            <p class="mt-2 text-xs leading-5 text-[#777777]">
                Koreksi stok manual tidak menjadi transaksi pembelian stok tersendiri.
            </p>
        @endif
    </div>

    <div>
        <label for="min_stock" class="mb-2 block text-sm font-medium">Batas Stok Minimum *</label>
        <input
            id="min_stock"
            name="min_stock"
            type="number"
            min="0"
            step="1"
            value="{{ old('min_stock', $sparePart->min_stock ?? 0) }}"
            required
            class="min-h-11 w-full border border-black/15 px-3 py-2 text-sm"
        >
    </div>

    <div class="sm:col-span-2">
        <label for="description" class="mb-2 block text-sm font-medium">Deskripsi</label>
        <textarea
            id="description"
            name="description"
            rows="4"
            class="w-full border border-black/15 px-3 py-2 text-sm"
        >{{ old('description', $sparePart->description) }}</textarea>
    </div>

    <div class="sm:col-span-2">
        <label class="inline-flex items-center gap-3 text-sm">
            <input
                type="checkbox"
                name="is_active"
                value="1"
                @checked(old('is_active', $sparePart->is_active ?? true))
                class="size-4"
            >
            <span>Sparepart aktif dan dapat dipilih untuk servis</span>
        </label>
    </div>
</div>

<div class="mt-7 flex flex-wrap gap-3 border-t border-black/10 pt-5">
    <button
        type="submit"
        class="min-h-11 bg-[#262626] px-5 py-3 text-xs font-semibold uppercase tracking-wider text-white hover:bg-[#444444]"
    >
        Simpan Sparepart
    </button>

    <a
        href="{{ route('admin.spare-parts.index') }}"
        class="inline-flex min-h-11 items-center justify-center border border-black/15 px-5 py-3 text-xs font-semibold uppercase tracking-wider"
    >
        Batal
    </a>
</div>
