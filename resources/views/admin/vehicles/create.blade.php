@extends('layouts.admin')

@section('title', 'Tambah kendaraan')

@section('content')
    <div class="max-w-6xl">
        <div class="mb-6">
            <a href="{{ route('admin.vehicles.index') }}" class="mb-2 inline-block text-sm font-semibold text-[#262626] hover:underline">‹ Kembali ke daftar</a>
            <h1 class="text-3xl font-light text-[#262626]">Tambah kendaraan</h1>
        </div>

        <div class="border border-[#bbbbbb] bg-white p-6 sm:p-8">
            <form action="{{ route('admin.vehicles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                @include('admin.vehicles._form', ['vehicle' => new App\Models\Vehicle])
                @include('admin.vehicles._new-images')

                <div class="flex justify-end">
                    <button type="submit" class="border border-[#262626] bg-[#262626] px-6 py-3 text-sm font-semibold text-white transition hover:bg-white hover:text-[#262626] focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
                        Simpan kendaraan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
