@extends('layouts.admin')

@section('title', 'Tambah Paket Servis')

@section('content')
    <div class="max-w-4xl">
        <div class="mb-8">
            <a href="{{ route('admin.service-packages.index') }}" class="inline-block text-sm font-semibold text-[#262626] hover:underline">← Kembali ke daftar</a>
            <h1 class="mt-3 text-3xl font-light leading-tight sm:text-4xl">Tambah paket servis</h1>
        </div>

        <form method="POST" action="{{ route('admin.service-packages.store') }}" class="space-y-8 border border-[#bbbbbb] bg-white p-6 sm:p-8">
            @csrf
            @include('admin.service-packages._form')
        </form>
    </div>
@endsection
