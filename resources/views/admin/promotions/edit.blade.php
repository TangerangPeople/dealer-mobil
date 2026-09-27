@extends('layouts.admin')

@section('title', 'Edit Promo')

@section('content')
    <div class="max-w-4xl">
        <div class="border-b border-[#bbbbbb] pb-8">
            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#262626]">Promo</p>
            <h1 class="mt-3 text-3xl font-light leading-tight sm:text-4xl">Edit promo</h1>
        </div>

        @if ($errors->any())
            <div class="mt-6 border-l-4 border-[#b91c1c] bg-white p-4" role="alert">
                <p class="text-sm font-semibold text-[#262626]">Terdapat beberapa masalah pada data yang dikirim.</p>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.promotions.update', $promotion) }}" enctype="multipart/form-data" class="mt-8 rounded-none border border-[#bbbbbb] bg-white p-6 sm:p-8">
            @csrf
            @method('PUT')
            @include('admin.promotions._form')
        </form>
    </div>
@endsection
