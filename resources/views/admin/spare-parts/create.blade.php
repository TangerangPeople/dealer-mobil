
@extends('layouts.admin')

@section('title', 'Tambah Sparepart')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <header>
            <p class="text-xs font-semibold uppercase tracking-wider text-[#777777]">
                Inventaris
            </p>
            <h1 class="mt-2 text-3xl font-light text-[#262626]">
                Tambah Sparepart
            </h1>
        </header>

        <form
            method="POST"
            action="{{ route('admin.spare-parts.store') }}"
            class="border border-black/10 bg-white p-5 sm:p-7"
        >
            @csrf
            @include('admin.spare-parts._form')
        </form>
    </div>
@endsection
