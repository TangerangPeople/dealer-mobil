@extends('layouts.admin')

@section('title', 'Galeri')

@section('content')
    <div class="max-w-6xl">
        <div class="flex flex-col gap-6 border-b border-[#bbbbbb] pb-8 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#262626]">Galeri</p>
                <h1 class="mt-3 text-3xl font-light leading-tight sm:text-4xl">Daftar galeri</h1>
            </div>

            <a href="{{ route('admin.galleries.create') }}" class="border border-[#262626] bg-[#262626] px-5 py-3 text-center text-sm font-semibold text-white transition hover:bg-white hover:text-[#262626] focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
                Tambah galeri
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

        <form method="GET" action="{{ route('admin.galleries.index') }}" class="mt-8 grid gap-4 border border-[#bbbbbb] bg-white p-5 md:grid-cols-[1.4fr_1fr_1fr_auto] md:items-end">
            <div>
                <label for="search" class="block text-sm font-semibold text-[#262626]">Cari</label>
                <input id="search" name="search" type="search" value="{{ $search }}" placeholder="Judul, alt text, keterangan" class="mt-2 block w-full border border-[#bbbbbb] bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
            </div>

            <div>
                <label for="gallery_type" class="block text-sm font-semibold text-[#262626]">Tipe</label>
                <select id="gallery_type" name="gallery_type" class="mt-2 block w-full border border-[#bbbbbb] bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
                    <option value="">Semua</option>
                    @foreach (['vehicle' => 'Vehicle', 'showroom' => 'Showroom', 'event' => 'Event', 'other' => 'Lainnya'] as $value => $label)
                        <option value="{{ $value }}" @selected((string) $galleryType === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-semibold text-[#262626]">Status</label>
                <select id="status" name="status" class="mt-2 block w-full border border-[#bbbbbb] bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
                    <option value="">Semua</option>
                    <option value="published" @selected($status === 'published')>Published</option>
                    <option value="unpublished" @selected($status === 'unpublished')>Unpublished</option>
                </select>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="border border-[#262626] bg-[#262626] px-5 py-3 text-sm font-semibold text-white transition hover:bg-white hover:text-[#262626] focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">Terapkan</button>
                <a href="{{ route('admin.galleries.index') }}" class="border border-[#262626] bg-white px-5 py-3 text-sm font-semibold text-[#262626] transition hover:bg-[#262626] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">Reset</a>
            </div>
        </form>

        @if ($galleries->isEmpty())
            <section class="mt-8 border border-[#bbbbbb] bg-white p-8 sm:p-10" aria-labelledby="empty-galleries-heading">
                <h2 id="empty-galleries-heading" class="text-xl font-semibold text-[#262626]">Belum ada galeri</h2>
                <p class="mt-3 max-w-xl text-base leading-7 text-[#262626]">Tambahkan foto pertama untuk melengkapi galeri properti, showroom, dan kegiatan dealer.</p>
                <a href="{{ route('admin.galleries.create') }}" class="mt-6 inline-block border border-[#262626] bg-[#262626] px-5 py-3 text-sm font-semibold text-white transition hover:bg-white hover:text-[#262626] focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">Tambah galeri</a>
            </section>
        @else
            <div class="mt-8 overflow-x-auto border border-[#bbbbbb] bg-white">
                <table class="min-w-full text-left text-sm">
                    <caption class="sr-only">Daftar galeri</caption>
                    <thead class="border-b border-[#bbbbbb] bg-[#f1f1f1] text-xs font-semibold uppercase tracking-[0.08em] text-[#262626]">
                        <tr>
                            <th scope="col" class="px-4 py-4">Gambar</th>
                            <th scope="col" class="px-4 py-4">Judul</th>
                            <th scope="col" class="px-4 py-4">Tipe</th>
                            <th scope="col" class="px-4 py-4">Status</th>
                            <th scope="col" class="px-4 py-4">Urutan</th>
                            <th scope="col" class="px-4 py-4">Dibuat</th>
                            <th scope="col" class="px-4 py-4"><span class="sr-only">Aksi</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#bbbbbb]">
                        @foreach ($galleries as $gallery)
                            <tr>
                                <td class="px-4 py-4">
                                    @if ($gallery->image_path)
                                        <img src="{{ Storage::disk('public')->url($gallery->image_path) }}" alt="{{ $gallery->alt_text ?: $gallery->title }}" class="h-16 w-20 border border-[#bbbbbb] object-cover" loading="lazy">
                                    @else
                                        <div class="flex h-16 w-20 items-center justify-center border border-[#bbbbbb] bg-[#f1f1f1] text-[10px] font-semibold uppercase tracking-[0.12em] text-[#262626]">No photo</div>
                                    @endif
                                </td>
                                <td class="px-4 py-4 font-semibold text-[#262626]">{{ $gallery->title }}</td>
                                <td class="px-4 py-4">{{ ucfirst($gallery->gallery_type) }}</td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center gap-2">
                                        <span class="h-2 w-2 border border-[#262626] {{ $gallery->is_published ? 'bg-[#262626]' : 'bg-white' }}" aria-hidden="true"></span>
                                        {{ $gallery->is_published ? 'Published' : 'Unpublished' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">{{ $gallery->sort_order }}</td>
                                <td class="whitespace-nowrap px-4 py-4">{{ $gallery->created_at->format('d M Y') }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-end gap-4">
                                        <a href="{{ route('admin.galleries.edit', $gallery) }}" class="font-semibold underline underline-offset-4 focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">Edit<span class="sr-only"> {{ $gallery->title }}</span></a>
                                        <form method="POST" action="{{ route('admin.galleries.destroy', $gallery) }}" onsubmit="return confirm('Hapus galeri {{ addslashes($gallery->title) }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-semibold underline underline-offset-4 focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">Hapus<span class="sr-only"> {{ $gallery->title }}</span></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $galleries->links('admin.pagination') }}
        @endif
    </div>
@endsection
