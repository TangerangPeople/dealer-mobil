@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('content')
    <div class="max-w-3xl">
        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#262626]">Kategori kendaraan</p>
        <h1 class="mt-3 text-3xl font-light leading-tight sm:text-4xl">Edit kategori</h1>
        <p class="mt-4 max-w-2xl text-base leading-7 text-[#262626]">Perbarui nama, URL, deskripsi, atau status kategori <span class="font-semibold">{{ $category->name }}</span>.</p>

        <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="mt-10 border border-[#bbbbbb] bg-white p-6 sm:p-8">
            @csrf
            @method('PUT')

            @include('admin.categories._form')
        </form>
    </div>
@endsection
