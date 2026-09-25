@extends('layouts.admin')

@section('title', 'Dashboard Administrator')

@section('content')
    <div class="max-w-4xl">
        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#262626]">Ringkasan</p>
        <h1 class="mt-3 text-3xl font-light leading-tight sm:text-4xl">Dashboard Administrator</h1>
        <p class="mt-5 max-w-2xl text-base leading-7 text-[#262626]">Area administrasi telah aktif. Modul pengelolaan kendaraan, promo, galeri, dan informasi dealer akan tersedia pada tahap berikutnya.</p>

        <section class="mt-10 border border-[#bbbbbb] bg-white p-6 sm:p-8" aria-labelledby="admin-access-heading">
            <h2 id="admin-access-heading" class="text-xl font-semibold">Akses administrator terverifikasi</h2>
            <p class="mt-3 text-base leading-7">Anda berhasil masuk dengan akun yang memiliki izin administrator.</p>
        </section>
    </div>
@endsection
