@extends('layouts.admin')

@section('title', 'Edit kendaraan')

@section('content')
    <div class="max-w-6xl">
        <div class="mb-6">
            <a href="{{ route('admin.vehicles.index') }}" class="mb-2 inline-block text-sm font-semibold text-[#262626] hover:underline">‹ Kembali ke daftar</a>
            <h1 class="text-3xl font-light text-[#262626]">Edit kendaraan</h1>
        </div>

        <div class="border border-[#bbbbbb] bg-white p-6 sm:p-8">
            <form action="{{ route('admin.vehicles.update', $vehicle) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                @include('admin.vehicles._form', ['vehicle' => $vehicle])

                <section class="border-t border-[#bbbbbb] pt-8" aria-labelledby="existing-images-heading">
                    <h2 id="existing-images-heading" class="text-xl font-semibold text-[#262626]">Foto kendaraan</h2>

                    @if ($vehicle->vehicleImages->isEmpty())
                        <p class="mt-3 text-base leading-7 text-[#262626]">Belum ada foto untuk kendaraan ini.</p>
                    @else
                        <div class="mt-6 grid gap-5">
                            @foreach ($vehicle->vehicleImages()->orderBy('sort_order')->orderBy('id')->get() as $image)
                                <div class="grid gap-4 border border-[#bbbbbb] bg-[#f1f1f1] p-4 md:grid-cols-[140px_1fr_1fr_1fr_auto] md:items-end">
                                    <div>
                                        <img src="{{ Storage::disk('public')->url($image->image_path) }}" alt="{{ $image->alt_text }}" class="h-20 w-full border border-[#bbbbbb] object-cover">
                                    </div>

                                    <form method="POST" action="{{ route('admin.vehicles.images.update', [$vehicle, $image]) }}" class="contents">
                                        @csrf
                                        @method('PUT')

                                        <div>
                                            <label for="alt_text_{{ $image->id }}" class="block text-sm font-semibold text-[#262626]">Alt text</label>
                                            <input id="alt_text_{{ $image->id }}" name="alt_text" type="text" maxlength="255" value="{{ old('alt_text', $image->alt_text) }}" class="mt-2 block w-full border border-[#bbbbbb] bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
                                        </div>

                                        <div>
                                            <label for="sort_order_{{ $image->id }}" class="block text-sm font-semibold text-[#262626]">Urutan</label>
                                            <input id="sort_order_{{ $image->id }}" name="sort_order" type="number" min="0" value="{{ old('sort_order', $image->sort_order) }}" class="mt-2 block w-full border border-[#bbbbbb] bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
                                        </div>

                                        <div class="flex items-center gap-3 pb-2">
                                            <input id="is_primary_{{ $image->id }}" name="is_primary" type="checkbox" value="1" @checked(old('is_primary', $image->is_primary)) class="h-4 w-4 border-[#262626] text-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
                                            <label for="is_primary_{{ $image->id }}" class="text-sm font-semibold text-[#262626]">Foto utama</label>
                                        </div>

                                        <div class="flex justify-end gap-3 md:justify-start">
                                            <button type="submit" class="border border-[#262626] bg-[#262626] px-4 py-2 text-xs font-semibold uppercase tracking-[0.08em] text-white transition hover:bg-white hover:text-[#262626] focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">Simpan</button>
                                        </div>
                                    </form>

                                    <form method="POST" action="{{ route('admin.vehicles.images.destroy', [$vehicle, $image]) }}" onsubmit="return confirm('Hapus foto {{ addslashes($image->alt_text) }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="border border-[#262626] bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.08em] text-[#262626] transition hover:bg-[#262626] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">Hapus</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>

                @include('admin.vehicles._new-images')

                <div class="flex justify-end">
                    <button type="submit" class="border border-[#262626] bg-[#262626] px-6 py-3 text-sm font-semibold text-white transition hover:bg-white hover:text-[#262626] focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
                        Perbarui kendaraan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
