@extends('layouts.admin')

@section('title', 'Tambah Kategori')

@section('content')
    <div class="max-w-3xl">
        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#262626]">Kategori kendaraan</p>
        <h1 class="mt-3 text-3xl font-light leading-tight sm:text-4xl">Tambah kategori</h1>
        <p class="mt-4 max-w-2xl text-base leading-7 text-[#262626]">Tambahkan pengelompokan kendaraan agar katalog dapat dikelola dengan konsisten.</p>

        <form method="POST" action="{{ route('admin.categories.store') }}" class="mt-10 border border-[#bbbbbb] bg-white p-6 sm:p-8">
            @csrf

            @include('admin.categories._form')
        </form>
    </div>
@endsection
