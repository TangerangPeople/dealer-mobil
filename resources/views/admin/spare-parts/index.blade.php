
@extends('layouts.admin')

@section('title', 'Inventaris Sparepart')

@section('content')
    <div class="mx-auto max-w-7xl space-y-6">

        <header class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#737373]">
                    Persediaan bengkel
                </p>

                <h1 class="mt-2 text-3xl font-light tracking-tight text-[#262626]">
                    Inventaris Sparepart
                </h1>

                <p class="mt-2 text-sm text-[#666666]">
                    Pantau ketersediaan barang dan stok minimum.
                </p>
            </div>

            @if (auth()->user()->role === 'admin')
                <a
                    href="{{ route('admin.spare-parts.create') }}"
                    class="inline-flex min-h-11 items-center justify-center bg-[#262626] px-5 py-3 text-xs font-semibold uppercase tracking-wider text-white hover:bg-[#444444]"
                >
                    Tambah Sparepart
                </a>
            @endif
        </header>

        @if (session('status'))
            <div class="border border-black/15 bg-[#f5f5f5] px-4 py-3 text-sm text-[#262626]" role="status">
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

        <div class="grid gap-4 sm:grid-cols-2">
            <article class="border border-black/10 bg-white p-5">
                <p class="text-sm text-[#666666]">Sparepart Aktif</p>
                <p class="mt-3 text-3xl font-light text-[#262626]">
                    {{ number_format($totalActive, 0, ',', '.') }}
                </p>
            </article>

            <article class="border border-black/10 bg-white p-5">
                <p class="text-sm text-[#666666]">Stok Menipis atau Habis</p>
                <p class="mt-3 text-3xl font-light {{ $lowStockCount > 0 ? 'text-red-700' : 'text-[#262626]' }}">
                    {{ number_format($lowStockCount, 0, ',', '.') }}
                </p>
                <p class="mt-2 text-xs text-[#777777]">
                    Termasuk barang dengan stok sama dengan nol.
                </p>
            </article>
        </div>

        <form method="GET" action="{{ route('admin.spare-parts.index') }}"
            class="grid gap-3 border border-black/10 bg-white p-4 sm:grid-cols-[minmax(0,1fr)_200px_auto_auto]">

            <input
                type="search"
                name="q"
                value="{{ $filters['q'] ?? '' }}"
                placeholder="Cari kode atau nama sparepart"
                aria-label="Cari sparepart"
                class="min-h-11 border border-black/15 px-3 py-2 text-sm focus:border-[#262626] focus:outline-none"
            >

            <select
                name="stock"
                aria-label="Filter stok"
                class="min-h-11 border border-black/15 bg-white px-3 py-2 text-sm focus:border-[#262626] focus:outline-none"
            >
                <option value="all" @selected(($filters['stock'] ?? 'all') === 'all')>
                    Semua stok
                </option>
                <option value="low" @selected(($filters['stock'] ?? '') === 'low')>
                    Stok menipis / habis
                </option>
            </select>

            <button
                type="submit"
                class="min-h-11 bg-[#262626] px-5 py-3 text-xs font-semibold uppercase tracking-wider text-white hover:bg-[#444444]"
            >
                Cari
            </button>

            <a
                href="{{ route('admin.spare-parts.index') }}"
                class="inline-flex min-h-11 items-center justify-center border border-black/15 px-5 py-3 text-xs font-semibold uppercase tracking-wider text-[#262626] hover:bg-[#f5f5f5]"
            >
                Reset
            </a>
        </form>

        <section class="overflow-hidden border border-black/10 bg-white">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[850px] text-left text-sm">
                    <thead class="bg-[#f5f5f5] text-xs uppercase tracking-wider text-[#666666]">
                        <tr>
                            <th class="px-4 py-4 font-semibold">Kode</th>
                            <th class="px-4 py-4 font-semibold">Nama</th>
                            <th class="px-4 py-4 font-semibold">Harga Jual</th>
                            <th class="px-4 py-4 text-right font-semibold">Stok</th>
                            <th class="px-4 py-4 text-right font-semibold">Minimum</th>
                            <th class="px-4 py-4 font-semibold">Status</th>
                            @if (auth()->user()->role === 'admin')
                                <th class="px-4 py-4 font-semibold">Aksi</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-black/10">
                        @forelse ($spareParts as $part)
                            <tr class="hover:bg-[#fafafa]">
                                <td class="px-4 py-4 font-medium text-[#262626]">
                                    {{ $part->code }}
                                </td>

                                <td class="px-4 py-4">
                                    <p class="font-medium text-[#262626]">{{ $part->name }}</p>
                                    <p class="mt-1 text-xs text-[#777777]">{{ $part->unit }}</p>
                                </td>

                                <td class="whitespace-nowrap px-4 py-4">
                                    Rp {{ number_format((float) $part->selling_price, 0, ',', '.') }}
                                </td>

                                <td class="px-4 py-4 text-right font-semibold {{ $part->isLowStock() ? 'text-red-700' : 'text-[#262626]' }}">
                                    {{ number_format($part->stock, 0, ',', '.') }}
                                </td>

                                <td class="px-4 py-4 text-right text-[#666666]">
                                    {{ number_format($part->min_stock, 0, ',', '.') }}
                                </td>

                                <td class="px-4 py-4">
                                    @if (! $part->is_active)
                                        <span class="text-[#777777]">Nonaktif</span>
                                    @elseif ($part->isLowStock())
                                        <span class="font-medium text-red-700">Stok rendah</span>
                                    @else
                                        <span class="text-[#404040]">Aktif</span>
                                    @endif
                                </td>

                                @if (auth()->user()->role === 'admin')
                                    <td class="whitespace-nowrap px-4 py-4">
                                        <div class="flex items-center gap-3">
                                            <a
                                                href="{{ route('admin.spare-parts.edit', $part) }}"
                                                class="font-medium text-[#262626] underline underline-offset-4"
                                            >
                                                Edit
                                            </a>

                                            @if ($part->is_active)
                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.spare-parts.destroy', $part) }}"
                                                    onsubmit="return confirm('Nonaktifkan sparepart ini?')"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="font-medium text-red-700 underline underline-offset-4">
                                                        Nonaktifkan
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="{{ auth()->user()->role === 'admin' ? 7 : 6 }}"
                                    class="px-4 py-12 text-center text-[#777777]"
                                >
                                    Belum ada data sparepart.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-black/10 px-4 py-4">
                {{ $spareParts->links() }}
            </div>
        </section>
    </div>
@endsection
