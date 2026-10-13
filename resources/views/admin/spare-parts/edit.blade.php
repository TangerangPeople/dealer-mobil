
@extends('layouts.admin')

@section('title', 'Edit Sparepart')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <header>
            <p class="text-xs font-semibold uppercase tracking-wider text-[#777777]">
                Inventaris / {{ $sparePart->code }}
            </p>
            <h1 class="mt-2 text-3xl font-light text-[#262626]">
                Edit Sparepart
            </h1>
        </header>

        <form
            method="POST"
            action="{{ route('admin.spare-parts.update', $sparePart) }}"
            class="border border-black/10 bg-white p-5 sm:p-7"
        >
            @csrf
            @method('PUT')
            @include('admin.spare-parts._form')
        </form>
    </div>
@endsection
