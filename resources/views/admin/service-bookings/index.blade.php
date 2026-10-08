@extends('layouts.admin')

@section('title', 'Booking Service')

@section('content')
    <div class="max-w-6xl">
        <div class="flex flex-col gap-6 border-b border-[#bbbbbb] pb-8 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#262626]">Layanan</p>
                <h1 class="mt-3 text-3xl font-light leading-tight sm:text-4xl">Booking service</h1>
            </div>
        </div>

        @if (session('success'))
            <div class="mt-6 border-l-4 border-[#166534] bg-white p-4 text-sm leading-6 text-[#262626]" role="status">
                <span class="font-semibold">Berhasil.</span> {{ session('success') }}
            </div>
        @endif

        <form method="GET" action="{{ route('admin.service-bookings.index') }}" class="mt-8 grid gap-4 border border-[#bbbbbb] bg-white p-5 md:grid-cols-[1fr_auto_auto] md:items-end">
            <div>
                <label for="search" class="block text-sm font-semibold text-[#262626]">Cari booking</label>
                <input id="search" name="search" type="search" value="{{ $search }}" placeholder="Nama, telepon, plat" class="mt-2 block w-full border border-[#bbbbbb] bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
            </div>
            <div>
                <label for="status" class="block text-sm font-semibold text-[#262626]">Status</label>
                <select id="status" name="status" class="mt-2 block w-full border border-[#bbbbbb] bg-white px-4 py-3 text-base outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
                    <option value="">Semua status</option>
                    <option value="pending" @selected($status === 'pending')>Pending</option>
                    <option value="approved" @selected($status === 'approved')>Approved</option>
                    <option value="rescheduled" @selected($status === 'rescheduled')>Rescheduled</option>
                    <option value="completed" @selected($status === 'completed')>Completed</option>
                    <option value="cancelled" @selected($status === 'cancelled')>Cancelled</option>
                </select>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="border border-[#262626] bg-[#262626] px-5 py-3 text-sm font-semibold text-white transition hover:bg-white hover:text-[#262626] focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">Terapkan</button>
                <a href="{{ route('admin.service-bookings.index') }}" class="border border-[#262626] bg-white px-5 py-3 text-sm font-semibold text-[#262626] transition hover:bg-[#262626] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">Reset</a>
            </div>
        </form>

        @if ($serviceBookings->isEmpty())
            <section class="mt-8 border border-[#bbbbbb] bg-white p-8 sm:p-10">
                <h2 class="text-xl font-semibold text-[#262626]">Belum ada booking</h2>
                <p class="mt-3 max-w-xl text-base leading-7 text-[#262626]">Belum ada permintaan booking service yang masuk.</p>
            </section>
        @else
            <div class="mt-8 overflow-x-auto border border-[#bbbbbb] bg-white">
                <table class="min-w-full text-left text-sm">
                    <thead class="border-b border-[#bbbbbb] bg-[#f1f1f1] text-xs font-semibold uppercase tracking-[0.08em] text-[#262626]">
                        <tr>
                            <th scope="col" class="px-4 py-4">Nama</th>
                            <th scope="col" class="px-4 py-4">Mobil</th>
                            <th scope="col" class="px-4 py-4">Plat</th>
                            <th scope="col" class="px-4 py-4">Paket</th>
                            <th scope="col" class="px-4 py-4">Tanggal</th>
                            <th scope="col" class="px-4 py-4">Status</th>
                            <th scope="col" class="px-4 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#bbbbbb]">
                        @foreach ($serviceBookings as $booking)
                            <tr>
                                <td class="px-4 py-4 font-semibold text-[#262626]">
                                    {{ $booking->customer_name }}
                                    <div class="mt-1 text-xs text-[#262626]">{{ $booking->whatsapp }}</div>
                                </td>
                                <td class="px-4 py-4">{{ $booking->vehicle?->brand ?? '-' }} {{ $booking->vehicle?->name ?? '-' }}</td>
                                <td class="px-4 py-4">{{ $booking->vehicle_plate }}</td>
                                <td class="px-4 py-4">{{ $booking->servicePackage?->name ?? '-' }}</td>
                                <td class="px-4 py-4">{{ $booking->preferred_date?->format('d M Y') ?? '-' }} / {{ $booking->preferred_time }}</td>
                                <td class="px-4 py-4">
                                    <form method="POST" action="{{ route('admin.service-bookings.update', $booking) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="border border-[#bbbbbb] bg-white px-2 py-2 text-sm outline-none focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
                                            @foreach(['pending','approved','rescheduled','completed','cancelled'] as $statusValue)
                                                <option value="{{ $statusValue }}" @selected($booking->status === $statusValue)>{{ $statusValue }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="border border-[#262626] bg-[#262626] px-3 py-2 text-xs font-semibold text-white transition hover:bg-white hover:text-[#262626]">Update</button>
                                    </form>
                                </td>
                                <td class="px-4 py-4">
                                    <form method="POST" action="{{ route('admin.service-bookings.destroy', $booking) }}" onsubmit="return confirm('Hapus booking {{ addslashes($booking->customer_name) }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-semibold underline underline-offset-4 focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $serviceBookings->links('admin.pagination') }}
        @endif
    </div>
@endsection
