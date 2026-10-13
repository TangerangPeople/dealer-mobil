<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">

    <title>Login Staf | {{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f1f1f1] font-sans text-[#262626] antialiased">

    <a
        href="#login-form"
        class="sr-only left-4 top-4 z-50 border border-[#262626] bg-white px-4 py-2 text-sm font-semibold focus:not-sr-only focus:absolute"
    >
        Lewati ke formulir login
    </a>

    <main class="grid min-h-screen lg:grid-cols-2">

        {{-- PANEL INFORMASI --}}
        <section
            class="flex min-h-72 flex-col justify-between bg-[#262626] px-6 py-8 text-white sm:px-10 lg:min-h-screen lg:px-16 lg:py-14"
        >
            <div class="text-sm font-semibold uppercase tracking-[0.2em]">
                {{ config('app.name') }}
            </div>

            <div class="max-w-xl py-12 lg:py-0">
                <p class="mb-4 text-xs font-semibold uppercase tracking-[0.16em] text-[#bbbbbb]">
                    Area Terbatas
                </p>

                <h1 class="text-4xl font-light leading-tight sm:text-5xl">
                    Satu akses untuk operasional dealer yang lebih terkelola.
                </h1>
            </div>

            <p class="max-w-md text-sm leading-6 text-[#bbbbbb]">
                Masuk menggunakan akun staf yang telah terdaftar untuk
                mengakses fitur sesuai tanggung jawab dan hak akses Anda.
            </p>
        </section>


        {{-- FORM LOGIN --}}
        <section class="flex items-center bg-white px-6 py-12 sm:px-10 lg:px-16">
            <div class="mx-auto w-full max-w-md">

                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#262626]">
                    Portal Staf
                </p>

                <h2 class="mt-3 text-3xl font-light leading-tight sm:text-4xl">
                    Masuk ke dashboard
                </h2>

                <p class="mt-4 text-base leading-7 text-[#666666]">
                    Masukkan email dan kata sandi akun staf Anda.
                </p>


                {{-- PESAN ERROR --}}
                @if ($errors->any())
                    <div
                        id="login-error"
                        class="mt-8 border-l-4 border-[#b91c1c] bg-[#f1f1f1] px-4 py-3 text-sm leading-6"
                        role="alert"
                        aria-live="assertive"
                    >
                        <p class="font-semibold">
                            Login tidak dapat diproses.
                        </p>

                        <p class="mt-1">
                            Email atau kata sandi tidak valid.
                            Periksa kembali data Anda dan coba lagi.
                        </p>
                    </div>
                @endif


                <form
                    id="login-form"
                    class="mt-8 space-y-6"
                    method="POST"
                    action="{{ route('admin.login.store') }}"
                >
                    @csrf

                    {{-- EMAIL --}}
                    <div>
                        <label
                            for="email"
                            class="block text-sm font-semibold"
                        >
                            Email
                        </label>

                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            autocomplete="username"
                            inputmode="email"
                            autofocus
                            required
                            @if ($errors->has('email'))
                                aria-invalid="true"
                                aria-describedby="login-error"
                            @endif
                            class="mt-2 block w-full border border-[#bbbbbb] bg-white px-4 py-3 text-base outline-none transition focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2"
                        >
                    </div>


                    {{-- KATA SANDI --}}
                    <div>
                        <label
                            for="password"
                            class="block text-sm font-semibold"
                        >
                            Kata sandi
                        </label>

                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            required
                            @if ($errors->has('password'))
                                aria-invalid="true"
                                aria-describedby="login-error"
                            @endif
                            class="mt-2 block w-full border border-[#bbbbbb] bg-white px-4 py-3 text-base outline-none transition focus:border-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2"
                        >
                    </div>


                    {{-- TOMBOL LOGIN --}}
                    <button
                        type="submit"
                        class="w-full border border-[#262626] bg-[#262626] px-5 py-3 text-sm font-semibold text-white transition hover:bg-white hover:text-[#262626] focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2"
                    >
                        Masuk ke dashboard
                    </button>
                </form>

                <p class="mt-8 text-xs leading-5 text-[#777777]">
                    Akses dashboard ditentukan berdasarkan role akun
                    yang terdaftar.
                </p>

            </div>
        </section>

    </main>

</body>
</html>
