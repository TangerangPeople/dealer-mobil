@extends('layouts.admin')

@section('title', 'Tambah Kendaraan')

@section('content')
    <div class="max-w-5xl">
        <p class="text-xs font-semibold uppercase tracking-[0.16em]">Kendaraan</p>
        <h1 class="mt-3 text-3xl font-light leading-tight sm:text-4xl">Tambah kendaraan</h1>
        <p class="mt-4 max-w-2xl text-base leading-7">Simpan informasi unit dan foto yang akan dikelola melalui dashboard.</p>

        <form method="POST" action="{{ route('admin.vehicles.store') }}" enctype="multipart/form-data" class="mt-10 border border-[#bbbbbb] bg-white p-6 sm:p-8">
            @csrf
            @include('admin.vehicles._form')
            @include('admin.vehicles._new-images')

            <div class="mt-8 flex flex-col-reverse gap-3 border-t border-[#bbbbbb] pt-6 sm:flex-row">
                <a href="{{ route('admin.vehicles.index') }}" class="border border-[#262626] bg-white px-5 py-3 text-center text-sm font-semibold transition hover:bg-[#262626] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">Batal</a>
                <button type="submit" class="border border-[#262626] bg-[#262626] px-5 py-3 text-sm font-semibold text-white transition hover:bg-white hover:text-[#262626] focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">Simpan kendaraan</button>
            </div>
        </form>
    </div>
@endsection
