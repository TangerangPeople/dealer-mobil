@extends('layouts.admin')

@section('title', 'Paket Servis')

@section('content')
    <div class="max-w-6xl">
        <div class="flex flex-col gap-6 border-b border-[#bbbbbb] pb-8 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#262626]">Layanan</p>
                <h1 class="mt-3 text-3xl font-light leading-tight sm:text-4xl">Paket servis</h1>
            </div>

            <a href="{{ route('admin.service-packages.create') }}" class="border border-[#262626] bg-[#262626] px-5 py-3 text-center text-sm font-semibold text-white transition hover:bg-white hover:text-[#262626] focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
                Tambah paket
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

        <form method="GET" action="{{ route('admin.service-packages.index') }}" class="mt-8 grid gap-4 border border-[#bbbbbb] bg-white p-5 md:grid-cols-[1fr_auto_auto] md:items-end">
            <div>
                <label for="search" class="block text-sm font-semibold text-[#262626]">Cari paket</label>
                <input id="search" name="search" type="search" value="{{ $search }}" placeholder="Nama atau jenis layanan" class="mt-2 block w-full border border-[#bbbbbb] bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
            </div>
            <div>
                <label for="status" class="block text-sm font-semibold text-[#262626]">Status</label>
                <select id="status" name="status" class="mt-2 block w-full border border-[#bbbbbb] bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
                    <option value="">Semua status</option>
                    <option value="active" @selected($status === 'active')>Aktif</option>
                    <option value="inactive" @selected($status === 'inactive')>Tidak aktif</option>
                </select>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="border border-[#262626] bg-[#262626] px-5 py-3 text-sm font-semibold text-white transition hover:bg-white hover:text-[#262626] focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">Terapkan</button>
                <a href="{{ route('admin.service-packages.index') }}" class="border border-[#262626] bg-white px-5 py-3 text-sm font-semibold text-[#262626] transition hover:bg-[#262626] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">Reset</a>
            </div>
        </form>

        @if ($servicePackages->isEmpty())
            <section class="mt-8 border border-[#bbbbbb] bg-white p-8 sm:p-10">
                <h2 class="text-xl font-semibold text-[#262626]">Belum ada paket servis</h2>
                <p class="mt-3 max-w-xl text-base leading-7 text-[#262626]">Tambahkan paket servis pertama untuk mulai menampilkan layanan yang tersedia di halaman public.</p>
                <a href="{{ route('admin.service-packages.create') }}" class="mt-6 inline-block border border-[#262626] bg-[#262626] px-5 py-3 text-sm font-semibold text-white transition hover:bg-white hover:text-[#262626] focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">Tambah paket</a>
            </section>
        @else
            <div class="mt-8 overflow-x-auto border border-[#bbbbbb] bg-white">
                <table class="min-w-full text-left text-sm">
                    <thead class="border-b border-[#bbbbbb] bg-[#f1f1f1] text-xs font-semibold uppercase tracking-[0.08em] text-[#262626]">
                        <tr>
                            <th scope="col" class="px-5 py-4">Nama</th>
                            <th scope="col" class="px-5 py-4">Jenis</th>
                            <th scope="col" class="px-5 py-4">Estimasi</th>
                            <th scope="col" class="px-5 py-4">Durasi</th>
                            <th scope="col" class="px-5 py-4">Status</th>
                            <th scope="col" class="px-5 py-4"><span class="sr-only">Aksi</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#bbbbbb]">
                        @foreach ($servicePackages as $servicePackage)
                            <tr>
                                <td class="px-5 py-4 font-semibold text-[#262626]">{{ $servicePackage->name }}</td>
                                <td class="px-5 py-4">{{ $servicePackage->service_type }}</td>
                                <td class="px-5 py-4">Rp {{ number_format($servicePackage->estimated_price, 0, ',', '.') }}</td>
                                <td class="px-5 py-4">{{ $servicePackage->estimated_duration }}</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center gap-2">
                                        <span class="h-2 w-2 border border-[#262626] {{ $servicePackage->is_active ? 'bg-[#262626]' : 'bg-white' }}" aria-hidden="true"></span>
                                        {{ $servicePackage->is_active ? 'Aktif' : 'Tidak aktif' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-4">
                                        <a href="{{ route('admin.service-packages.edit', $servicePackage) }}" class="font-semibold underline underline-offset-4 focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">Edit<span class="sr-only"> {{ $servicePackage->name }}</span></a>
                                        <form method="POST" action="{{ route('admin.service-packages.destroy', $servicePackage) }}" onsubmit="return confirm('Hapus paket {{ addslashes($servicePackage->name) }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-semibold underline underline-offset-4 focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">Hapus<span class="sr-only"> {{ $servicePackage->name }}</span></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $servicePackages->links('admin.pagination') }}
        @endif
    </div>
@endsection
