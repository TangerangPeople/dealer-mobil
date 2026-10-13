
@extends('layouts.admin')

@section('title', 'Booking Servis Saya')

@section('content')
    <div class="mx-auto max-w-7xl space-y-6">
        <header>
            <p class="text-xs font-semibold uppercase tracking-wider text-[#777777]">
                Area mekanik
            </p>
            <h1 class="mt-2 text-3xl font-light text-[#262626]">
                Booking Servis Saya
            </h1>
            <p class="mt-2 text-sm text-[#666666]">
                Booking yang ditugaskan kepada akun mekanik ini.
            </p>
        </header>

        @if (session('status'))
            <div class="border border-black/10 bg-[#f5f5f5] px-4 py-3 text-sm" role="status">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="space-y-5">
            @forelse ($bookings as $booking)
                <article class="border border-black/10 bg-white p-5 sm:p-6">
                    <div class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_minmax(300px,0.9fr)]">

                        <div>
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <p class="text-xs uppercase tracking-wider text-[#777777]">
                                        Booking #{{ $booking->id }}
                                    </p>
                                    <h2 class="mt-2 text-xl font-medium text-[#262626]">
                                        {{ $booking->customer_name }}
                                    </h2>
                                </div>

                                <span class="border border-black/15 px-3 py-1 text-xs">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>

                            <dl class="mt-5 grid gap-4 sm:grid-cols-2">
                                <div>
                                    <dt class="text-xs text-[#777777]">Kendaraan</dt>
                                    <dd class="mt-1 text-sm text-[#262626]">
                                        {{ $booking->vehicle?->name ?? 'Kendaraan pelanggan' }}
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-xs text-[#777777]">Nomor polisi</dt>
                                    <dd class="mt-1 text-sm text-[#262626]">
                                        {{ $booking->vehicle_plate ?: '-' }}
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-xs text-[#777777]">Paket / jenis servis</dt>
                                    <dd class="mt-1 text-sm text-[#262626]">
                                        {{ $booking->servicePackage?->name ?? $booking->service_type ?? '-' }}
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-xs text-[#777777]">Jadwal</dt>
                                    <dd class="mt-1 text-sm text-[#262626]">
                                        {{ $booking->preferred_date?->format('d/m/Y') ?? '-' }}
                                        · {{ $booking->preferred_time ?: '-' }}
                                    </dd>
                                </div>
                            </dl>

                            @if ($booking->complaint)
                                <div class="mt-5 border-t border-black/10 pt-4">
                                    <p class="text-xs uppercase tracking-wider text-[#777777]">
                                        Keluhan pelanggan
                                    </p>
                                    <p class="mt-2 whitespace-pre-line text-sm leading-6 text-[#404040]">
                                        {{ $booking->complaint }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        <div class="border-t border-black/10 pt-5 lg:border-l lg:border-t-0 lg:pl-5 lg:pt-0">
                            <h3 class="font-medium text-[#262626]">Pemakaian Sparepart</h3>

                            <div class="mt-3 space-y-3">
                                @forelse ($booking->sparePartUsages as $usage)
                                    <div class="flex items-start justify-between gap-3 border-b border-black/10 pb-3">
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-[#262626]">
                                                {{ $usage->sparePart?->name ?? 'Sparepart tidak tersedia' }}
                                            </p>
                                            <p class="mt-1 text-xs text-[#777777]">
                                                {{ $usage->quantity }} {{ $usage->sparePart?->unit ?? 'pcs' }}
                                                × Rp {{ number_format((float) $usage->unit_price, 0, ',', '.') }}
                                            </p>

                                            @if ($usage->notes)
                                                <p class="mt-1 text-xs leading-5 text-[#666666]">
                                                    {{ $usage->notes }}
                                                </p>
                                            @endif
                                        </div>

                                        @if (! in_array($booking->status, ['completed', 'cancelled'], true))
                                            <form
                                                method="POST"
                                                action="{{ route('admin.service-bookings.spare-parts.destroy', [$booking, $usage]) }}"
                                                onsubmit="return confirm('Hapus pemakaian ini dan kembalikan stok?')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="text-xs font-medium text-red-700 underline underline-offset-4"
                                                >
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-sm text-[#777777]">
                                        Belum ada sparepart yang dicatat.
                                    </p>
                                @endforelse
                            </div>

                            @if (! in_array($booking->status, ['completed', 'cancelled'], true))
                                <form
                                    method="POST"
                                    action="{{ route('admin.service-bookings.spare-parts.store', $booking) }}"
                                    class="mt-5 space-y-3 border-t border-black/10 pt-4"
                                >
                                    @csrf

                                    <div>
                                        <label
                                            for="spare_part_id_{{ $booking->id }}"
                                            class="mb-2 block text-sm font-medium"
                                        >
                                            Sparepart
                                        </label>

                                        <select
                                            id="spare_part_id_{{ $booking->id }}"
                                            name="spare_part_id"
                                            required
                                            class="min-h-11 w-full border border-black/15 bg-white px-3 py-2 text-sm"
                                        >
                                            <option value="">Pilih sparepart</option>

                                            @foreach ($availableSpareParts as $part)
                                                <option
                                                    value="{{ $part->id }}"
                                                    @selected((string) old('spare_part_id') === (string) $part->id)
                                                >
                                                    {{ $part->code }} — {{ $part->name }}
                                                    (stok {{ $part->stock }} {{ $part->unit }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label
                                            for="quantity_{{ $booking->id }}"
                                            class="mb-2 block text-sm font-medium"
                                        >
                                            Jumlah akhir yang dipakai
                                        </label>

                                        <input
                                            id="quantity_{{ $booking->id }}"
                                            name="quantity"
                                            type="number"
                                            min="1"
                                            max="100000"
                                            required
                                            value="{{ old('quantity', 1) }}"
                                            class="min-h-11 w-full border border-black/15 px-3 py-2 text-sm"
                                        >
                                    </div>

                                    <div>
                                        <label
                                            for="notes_{{ $booking->id }}"
                                            class="mb-2 block text-sm font-medium"
                                        >
                                            Catatan
                                        </label>

                                        <textarea
                                            id="notes_{{ $booking->id }}"
                                            name="notes"
                                            rows="2"
                                            class="w-full border border-black/15 px-3 py-2 text-sm"
                                        >{{ old('notes') }}</textarea>
                                    </div>

                                    <button
                                        type="submit"
                                        class="min-h-11 w-full bg-[#262626] px-4 py-3 text-xs font-semibold uppercase tracking-wider text-white hover:bg-[#444444]"
                                    >
                                        Simpan Pemakaian
                                    </button>

                                    <p class="text-xs leading-5 text-[#777777]">
                                        Jika sparepart yang sama sudah tercatat, jumlah yang dimasukkan
                                        menjadi jumlah akhir, bukan tambahan.
                                    </p>
                                </form>
                            @else
                                <p class="mt-4 text-xs leading-5 text-[#777777]">
                                    Booking telah ditutup. Pemakaian tidak dapat diubah.
                                </p>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="border border-black/10 bg-white px-5 py-12 text-center">
                    <h2 class="font-medium text-[#262626]">
                        Belum ada booking yang ditugaskan.
                    </h2>
                    <p class="mt-2 text-sm text-[#777777]">
                        Minta Admin menetapkan akun ini pada booking servis.
                    </p>
                </div>
            @endforelse
        </div>

        <div>
            {{ $bookings->links() }}
        </div>
    </div>
@endsection
