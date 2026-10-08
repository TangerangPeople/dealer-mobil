@extends('public.layouts.app')

@section('title', 'Masuk | '.($settings->dealer_name ?? 'Dealer Mobil'))

@section('content')
    <section class="section-shell max-w-3xl py-16 sm:py-20">
        <p class="section-label text-[#5f5f5f]">Akun pelanggan</p>
        <h1 class="mt-3 text-4xl font-light tracking-[-0.04em] text-[#1e1e1e]">Masuk</h1>
        <p class="mt-4 max-w-xl text-sm leading-7 text-[#4b4b4b]">Masuk untuk melanjutkan pengajuan pembelian dan melihat perkembangannya.</p>

        @if ($errors->any())
            <div class="mt-6 border-l-4 border-[#b91c1c] bg-white p-4 text-sm" role="alert">
                Email atau kata sandi tidak valid.
            </div>
        @endif

        <form method="POST" action="{{ route('customer.login.store') }}" class="mt-8 max-w-xl space-y-5 border border-black/10 bg-white p-6 sm:p-8">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required class="mt-2 block w-full border border-[#bdbdbd] bg-white px-4 py-3 text-sm focus:border-[#262626] focus:outline-none">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium">Kata sandi</label>
                <div class="relative mt-2">
                    <input id="password" name="password" type="password" autocomplete="current-password" required class="block w-full border border-[#bdbdbd] bg-white px-4 py-3 pr-11 text-sm focus:border-[#262626] focus:outline-none">
                    <button type="button" class="absolute inset-y-0 right-0 flex items-center px-3 text-sm text-[#262626]" data-password-toggle="password" aria-label="Tampilkan password" aria-pressed="false">Tampilkan</button>
                </div>
            </div>
            <button type="submit" class="border border-[#262626] bg-[#262626] px-5 py-3 text-sm font-medium text-white transition hover:bg-white hover:text-[#262626]">Masuk</button>
            <p class="text-sm text-[#4b4b4b]">Belum memiliki akun? <a href="{{ route('customer.register') }}" class="underline underline-offset-4">Daftar</a></p>
        </form>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-password-toggle]').forEach((button) => {
                button.addEventListener('click', () => {
                    const targetInput = document.getElementById(button.dataset.passwordToggle);
                    const isPassword = targetInput.type === 'password';

                    targetInput.type = isPassword ? 'text' : 'password';
                    button.textContent = isPassword ? 'Sembunyikan' : 'Tampilkan';
                    button.setAttribute('aria-label', isPassword ? 'Sembunyikan password' : 'Tampilkan password');
                    button.setAttribute('aria-pressed', String(isPassword));
                });
            });
        });
    </script>
@endpush
