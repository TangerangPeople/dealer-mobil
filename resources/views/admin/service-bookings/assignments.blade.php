
@extends('layouts.admin')

@section('title', 'Penugasan Mekanik')

@section('content')
    <div class="mx-auto max-w-7xl space-y-6">
        <header>
            <p class="text-xs font-semibold uppercase tracking-wider text-[#777777]">
                Operasional bengkel
            </p>
            <h1 class="mt-2 text-3xl font-light text-[#262626]">
                Penugasan Mekanik
            </h1>
            <p class="mt-2 text-sm text-[#666666]">
                Tentukan mekanik yang bertanggung jawab atas setiap booking servis.
            </p>
        </header>

        @if (session('status'))
            <div class="border border-black/10 bg-[#f5f5f5] px-4 py-3 text-sm" role="status">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <section class="overflow-x-auto border border-black/10 bg-white">
            <table class="w-full min-w-[850px] text-left text-sm">
                <thead class="bg-[#f5f5f5] text-xs uppercase tracking-wider text-[#666666]">
                    <tr>
                        <th class="px-4 py-4">Booking</th>
                        <th class="px-4 py-4">Kendaraan</th>
                        <th class="px-4 py-4">Jadwal</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-4 py-4">Mekanik</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-black/10">
                    @forelse ($bookings as $booking)
                        <tr>
                            <td class="px-4 py-4">
                                <p class="font-medium text-[#262626]">{{ $booking->customer_name }}</p>
                                <p class="mt-1 text-xs text-[#777777]">{{ $booking->phone }}</p>
                                <p class="mt-1 text-xs text-[#777777]">{{ $booking->vehicle_plate ?: '-' }}</p>
                            </td>

                            <td class="px-4 py-4">
                                <p>{{ $booking->vehicle?->name ?? 'Kendaraan pelanggan' }}</p>
                                <p class="mt-1 text-xs text-[#777777]">
                                    {{ $booking->servicePackage?->name ?? $booking->service_type ?? '-' }}
                                </p>
                            </td>

                            <td class="whitespace-nowrap px-4 py-4">
                                {{ $booking->preferred_date?->format('d/m/Y') ?? '-' }}
                                <p class="mt-1 text-xs text-[#777777]">
                                    {{ $booking->preferred_time ?: '-' }}
                                </p>
                            </td>

                            <td class="px-4 py-4">{{ ucfirst($booking->status) }}</td>

                            <td class="min-w-[260px] px-4 py-4">
                                @if (! in_array($booking->status, ['completed', 'cancelled'], true))
                                    <form
                                        method="POST"
                                        action="{{ route('admin.service-bookings.mechanic.update', $booking) }}"
                                        class="flex items-center gap-2"
                                    >
                                        @csrf
                                        @method('PUT')

                                        <select
                                            name="mechanic_id"
                                            aria-label="Pilih mekanik untuk booking {{ $booking->id }}"
                                            class="min-h-10 min-w-0 flex-1 border border-black/15 bg-white px-2 text-sm"
                                        >
                                            <option value="">Belum ditugaskan</option>

                                            @foreach ($mechanics as $mechanic)
                                                <option
                                                    value="{{ $mechanic->id }}"
                                                    @selected((int) $booking->mechanic_id === (int) $mechanic->id)
                                                >
                                                    {{ $mechanic->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <button
                                            type="submit"
                                            class="min-h-10 bg-[#262626] px-3 text-xs font-semibold text-white hover:bg-[#444444]"
                                        >
                                            Simpan
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-[#777777]">
                                        Booking telah ditutup.
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-[#777777]">
                                Belum ada booking servis.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="border-t border-black/10 px-4 py-4">
                {{ $bookings->links() }}
            </div>
        </section>
    </div>
@endsection
