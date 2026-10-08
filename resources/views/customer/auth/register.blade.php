@extends('public.layouts.app')

@section('title', 'Daftar | '.($settings->dealer_name ?? 'Dealer Mobil'))

@section('content')
    <section class="section-shell max-w-3xl py-16 sm:py-20">
        <p class="section-label text-[#5f5f5f]">Akun pelanggan</p>
        <h1 class="mt-3 text-4xl font-light tracking-[-0.04em] text-[#1e1e1e]">Daftar akun</h1>
        <p class="mt-4 max-w-xl text-sm leading-7 text-[#4b4b4b]">Buat akun untuk mengajukan pembelian dan memantau status pengajuan.</p>

        @if ($errors->any())
            <div class="mt-6 border-l-4 border-[#b91c1c] bg-white p-4 text-sm" role="alert">
                <p class="font-medium">Pendaftaran tidak dapat diproses.</p>
                <ul class="mt-2 list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('customer.register.store') }}" class="mt-8 max-w-xl space-y-5 border border-black/10 bg-white p-6 sm:p-8">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium">Nama lengkap</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" required class="mt-2 block w-full border border-[#bdbdbd] bg-white px-4 py-3 text-sm focus:border-[#262626] focus:outline-none @error('name') border-[#b91c1c] @enderror">
                @error('name')<p class="mt-1 text-sm text-[#b91c1c]" role="alert">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="email" class="block text-sm font-medium">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required class="mt-2 block w-full border border-[#bdbdbd] bg-white px-4 py-3 text-sm focus:border-[#262626] focus:outline-none @error('email') border-[#b91c1c] @enderror">
                @error('email')<p class="mt-1 text-sm text-[#b91c1c]" role="alert">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium">Nomor telepon</label>
                <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel" required class="mt-2 block w-full border border-[#bdbdbd] bg-white px-4 py-3 text-sm focus:border-[#262626] focus:outline-none @error('phone') border-[#b91c1c] @enderror">
                @error('phone')<p class="mt-1 text-sm text-[#b91c1c]" role="alert">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="whatsapp" class="block text-sm font-medium">Nomor WhatsApp</label>
                <input id="whatsapp" name="whatsapp" type="tel" value="{{ old('whatsapp') }}" required class="mt-2 block w-full border border-[#bdbdbd] bg-white px-4 py-3 text-sm focus:border-[#262626] focus:outline-none @error('whatsapp') border-[#b91c1c] @enderror">
                @error('whatsapp')<p class="mt-1 text-sm text-[#b91c1c]" role="alert">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium">Password</label>
                <div class="relative mt-2">
                    <input id="password" name="password" type="password" autocomplete="new-password" required aria-describedby="password-help password-status" class="block w-full border border-[#bdbdbd] bg-white px-4 py-3 pr-11 text-sm focus:border-[#262626] focus:outline-none @error('password') border-[#b91c1c] @enderror" data-password-input>
                    <button type="button" class="absolute inset-y-0 right-0 flex items-center px-3 text-sm text-[#262626]" data-password-toggle="password" aria-label="Tampilkan password" aria-pressed="false">Tampilkan</button>
                </div>
                <div id="password-help" class="mt-2 text-sm text-[#4b4b4b]">Password minimal 8 karakter.</div>
                <div id="password-status" class="mt-1 text-sm text-[#4b4b4b]">Gunakan password yang mudah Anda ingat tetapi sulit ditebak.</div>
                @error('password')<p class="mt-1 text-sm text-[#b91c1c]" role="alert">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium">Konfirmasi Password</label>
                <div class="relative mt-2">
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required aria-describedby="password-confirmation-help password-confirmation-status" class="block w-full border border-[#bdbdbd] bg-white px-4 py-3 pr-11 text-sm focus:border-[#262626] focus:outline-none @error('password_confirmation') border-[#b91c1c] @enderror" data-password-input="confirmation">
                    <button type="button" class="absolute inset-y-0 right-0 flex items-center px-3 text-sm text-[#262626]" data-password-toggle="password_confirmation" aria-label="Tampilkan konfirmasi password" aria-pressed="false">Tampilkan</button>
                </div>
                <div id="password-confirmation-help" class="mt-2 text-sm text-[#4b4b4b]">Masukkan kembali password yang sama.</div>
                <div id="password-confirmation-status" class="mt-1 text-sm text-[#4b4b4b]">Konfirmasi password tidak sama.</div>
                @error('password_confirmation')<p class="mt-1 text-sm text-[#b91c1c]" role="alert">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="border border-[#262626] bg-[#262626] px-5 py-3 text-sm font-medium text-white transition hover:bg-white hover:text-[#262626]">Daftar</button>
            <p class="text-sm text-[#4b4b4b]">Sudah memiliki akun? <a href="{{ route('customer.login') }}" class="underline underline-offset-4">Masuk</a></p>
        </form>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const passwordInput = document.getElementById('password');
            const confirmationInput = document.getElementById('password_confirmation');
            const passwordStatus = document.getElementById('password-status');
            const confirmationStatus = document.getElementById('password-confirmation-status');

            const updatePasswordState = () => {
                const value = passwordInput.value;
                if (value.length === 0) {
                    passwordStatus.textContent = 'Gunakan password yang mudah Anda ingat tetapi sulit ditebak.';
                    passwordStatus.className = 'mt-1 text-sm text-[#4b4b4b]';
                    return;
                }

                if (value.length < 8) {
                    passwordStatus.textContent = 'Password belum memenuhi minimum 8 karakter.';
                    passwordStatus.className = 'mt-1 text-sm text-[#b91c1c]';
                    return;
                }

                passwordStatus.textContent = 'Panjang password memenuhi minimum.';
                passwordStatus.className = 'mt-1 text-sm text-[#166534]';
            };

            const updateConfirmationState = () => {
                const password = passwordInput.value;
                const confirmation = confirmationInput.value;

                if (confirmation.length === 0) {
                    confirmationStatus.textContent = 'Masukkan kembali password yang sama.';
                    confirmationStatus.className = 'mt-1 text-sm text-[#4b4b4b]';
                    return;
                }

                if (password !== confirmation) {
                    confirmationStatus.textContent = 'Konfirmasi password tidak sama.';
                    confirmationStatus.className = 'mt-1 text-sm text-[#b91c1c]';
                    return;
                }

                confirmationStatus.textContent = 'Konfirmasi password sesuai.';
                confirmationStatus.className = 'mt-1 text-sm text-[#166534]';
            };

            passwordInput.addEventListener('input', () => {
                updatePasswordState();
                updateConfirmationState();
            });
            confirmationInput.addEventListener('input', updateConfirmationState);

            const toggleButtons = document.querySelectorAll('[data-password-toggle]');
            toggleButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    const targetId = button.dataset.passwordToggle;
                    const targetInput = document.getElementById(targetId);
                    const isPassword = targetInput.type === 'password';

                    targetInput.type = isPassword ? 'text' : 'password';
                    button.textContent = isPassword ? 'Sembunyikan' : 'Tampilkan';
                    button.setAttribute('aria-label', isPassword ? 'Sembunyikan password' : 'Tampilkan password');
                    button.setAttribute('aria-pressed', String(isPassword));
                });
            });

            updatePasswordState();
            updateConfirmationState();
        });
    </script>
@endpush
