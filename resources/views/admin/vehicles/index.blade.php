@extends('layouts.admin')

@section('title', 'Kendaraan')

@section('content')
    <div class="max-w-6xl">
        <div class="flex flex-col gap-6 border-b border-[#bbbbbb] pb-8 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#262626]">Kendaraan</p>
                <h1 class="mt-3 text-3xl font-light leading-tight sm:text-4xl">Daftar kendaraan</h1>
            </div>

            <a href="{{ route('admin.vehicles.create') }}" class="border border-[#262626] bg-[#262626] px-5 py-3 text-center text-sm font-semibold text-white transition hover:bg-white hover:text-[#262626] focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
                Tambah kendaraan
            </a>
        </div>

        @if (session('success'))
            <div class="mt-6 border-l-4 border-[#166534] bg-white p-4 text-sm leading-6 text-[#262626]" role="status">
                <span class="font-semibold">Berhasil.</span> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mt-6 border-l-4 border-[#b91c1c] bg-white p-4 text-sm leading-6 text-[#262626]" role="alert">
                <span class="font-semibold">Tidak dapat diproses.</span> {{ session('error') }}
            </div>
        @endif

        <form method="GET" action="{{ route('admin.vehicles.index') }}" class="mt-8 grid gap-4 border border-[#bbbbbb] bg-white p-5 md:grid-cols-[1.4fr_1fr_1fr_1fr_auto] md:items-end">
            <div>
                <label for="search" class="block text-sm font-semibold text-[#262626]">Cari</label>
                <input id="search" name="search" type="search" value="{{ request('search') }}" placeholder="Nama, merek, tipe" class="mt-2 block w-full border border-[#bbbbbb] bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
            </div>

            <div>
                <label for="category_id" class="block text-sm font-semibold text-[#262626]">Kategori</label>
                <select id="category_id" name="category_id" class="mt-2 block w-full border border-[#bbbbbb] bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
                    <option value="">Semua</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-semibold text-[#262626]">Status</label>
                <select id="status" name="status" class="mt-2 block w-full border border-[#bbbbbb] bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
                    <option value="">Semua</option>
                    @foreach (['available' => 'Available', 'reserved' => 'Reserved', 'sold' => 'Sold', 'inactive' => 'Inactive'] as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="featured" class="block text-sm font-semibold text-[#262626]">Featured</label>
                <select id="featured" name="featured" class="mt-2 block w-full border border-[#bbbbbb] bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
                    <option value="">Semua</option>
                    <option value="1" @selected(request('featured') === '1')>Featured</option>
                    <option value="0" @selected(request('featured') === '0')>Non-featured</option>
                </select>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="border border-[#262626] bg-[#262626] px-5 py-3 text-sm font-semibold text-white transition hover:bg-white hover:text-[#262626] focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">Terapkan</button>
                <a href="{{ route('admin.vehicles.index') }}" class="border border-[#262626] bg-white px-5 py-3 text-sm font-semibold text-[#262626] transition hover:bg-[#262626] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">Reset</a>
            </div>
        </form>

        @if ($vehicles->isEmpty())
            <section class="mt-8 border border-[#bbbbbb] bg-white p-8 sm:p-10" aria-labelledby="empty-vehicles-heading">
                <h2 id="empty-vehicles-heading" class="text-xl font-semibold text-[#262626]">Belum ada kendaraan</h2>
                <p class="mt-3 max-w-xl text-base leading-7 text-[#262626]">Tambahkan kendaraan pertama untuk menampilkan unit yang tersedia di administrasi.</p>
                <a href="{{ route('admin.vehicles.create') }}" class="mt-6 inline-block border border-[#262626] bg-[#262626] px-5 py-3 text-sm font-semibold text-white transition hover:bg-white hover:text-[#262626] focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">Tambah kendaraan</a>
            </section>
        @else
            <div class="mt-8 overflow-x-auto border border-[#bbbbbb] bg-white">
                <table class="min-w-full text-left text-sm">
                    <caption class="sr-only">Daftar kendaraan</caption>
                    <thead class="border-b border-[#bbbbbb] bg-[#f1f1f1] text-xs font-semibold uppercase tracking-[0.08em] text-[#262626]">
                        <tr>
                            <th scope="col" class="px-4 py-4">Foto</th>
                            <th scope="col" class="px-4 py-4">Nama</th>
                            <th scope="col" class="px-4 py-4">Merek</th>
                            <th scope="col" class="px-4 py-4">Tipe</th>
                            <th scope="col" class="px-4 py-4">Kategori</th>
                            <th scope="col" class="px-4 py-4">Tahun</th>
                            <th scope="col" class="px-4 py-4">Harga</th>
                            <th scope="col" class="px-4 py-4">Status</th>
                            <th scope="col" class="px-4 py-4">Featured</th>
                            <th scope="col" class="px-4 py-4">Dibuat</th>
                            <th scope="col" class="px-4 py-4"><span class="sr-only">Aksi</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#bbbbbb]">
                        @foreach ($vehicles as $vehicle)
                            <tr>
                                <td class="px-4 py-4">
                                    @if ($vehicle->primaryImage)
                                        <img src="{{ Storage::disk('public')->url($vehicle->primaryImage->image_path) }}" alt="{{ $vehicle->primaryImage->alt_text ?: $vehicle->name }}" class="h-16 w-20 border border-[#bbbbbb] object-cover" loading="lazy">
                                    @else
                                        <div class="flex h-16 w-20 items-center justify-center border border-[#bbbbbb] bg-[#f1f1f1] text-[10px] font-semibold uppercase tracking-[0.12em] text-[#262626]">No photo</div>
                                    @endif
                                </td>
                                <td class="px-4 py-4 font-semibold text-[#262626]">{{ $vehicle->name }}</td>
                                <td class="px-4 py-4">{{ $vehicle->brand }}</td>
                                <td class="px-4 py-4">{{ $vehicle->type }}</td>
                                <td class="px-4 py-4">{{ $vehicle->category?->name ?? '-' }}</td>
                                <td class="px-4 py-4">{{ $vehicle->year }}</td>
                                <td class="px-4 py-4">Rp {{ number_format((float) $vehicle->price, 0, ',', '.') }}</td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center gap-2">
                                        <span class="h-2 w-2 border border-[#262626] {{ $vehicle->status === 'available' ? 'bg-[#262626]' : 'bg-white' }}" aria-hidden="true"></span>
                                        {{ ucfirst($vehicle->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    @if ($vehicle->is_featured)
                                        <span class="font-semibold text-[#262626]">Ya</span>
                                    @else
                                        <span class="text-[#262626]">Tidak</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-4 py-4">{{ $vehicle->created_at->format('d M Y') }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-end gap-4">
                                        <a href="{{ route('admin.vehicles.edit', $vehicle, false) }}" class="font-semibold underline underline-offset-4 focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">Edit<span class="sr-only"> {{ $vehicle->name }}</span></a>
                                        <form method="POST" action="{{ route('admin.vehicles.destroy', $vehicle) }}" onsubmit="return confirm('Hapus kendaraan {{ addslashes($vehicle->name) }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-semibold underline underline-offset-4 focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">Hapus<span class="sr-only"> {{ $vehicle->name }}</span></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $vehicles->links('admin.pagination') }}
        @endif
    </div>
@endsection
