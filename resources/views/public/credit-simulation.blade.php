@extends('public.layouts.app')

@section('title', 'Simulasi Kredit')

@section('content')
    <section class="mx-auto max-w-5xl px-4 py-16 sm:px-6 lg:px-8">
        <p class="text-xs uppercase tracking-[0.24em] text-neutral-500">Kredit</p>
        <h1 class="mt-4 text-4xl font-light text-neutral-900 sm:text-5xl">Simulasi Kredit</h1>

        <div class="mt-10 grid gap-8 lg:grid-cols-[1fr_0.8fr]">
            <div class="border border-neutral-200 bg-neutral-50 p-8">
                <form class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700">Harga kendaraan</label>
                        <input type="number" value="500000000" class="mt-2 w-full border border-neutral-300 bg-white px-4 py-3 text-neutral-900 focus:border-neutral-900 focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700">Uang muka</label>
                        <input type="number" value="150000000" class="mt-2 w-full border border-neutral-300 bg-white px-4 py-3 text-neutral-900 focus:border-neutral-900 focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700">Tenor</label>
                        <select class="mt-2 w-full border border-neutral-300 bg-white px-4 py-3 text-neutral-900 focus:border-neutral-900 focus:outline-none">
                            <option>12 bulan</option>
                            <option>24 bulan</option>
                            <option selected>36 bulan</option>
                            <option>48 bulan</option>
                            <option>60 bulan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700">Bunga</label>
                        <input type="number" value="3.5" step="0.1" class="mt-2 w-full border border-neutral-300 bg-white px-4 py-3 text-neutral-900 focus:border-neutral-900 focus:outline-none" />
                    </div>
                </form>
            </div>

            <aside class="border border-neutral-200 bg-white p-8">
                <p class="text-xs uppercase tracking-[0.2em] text-neutral-500">Estimasi</p>
                <h2 class="mt-3 text-3xl font-light text-neutral-900">Rp 10.394.000</h2>
                <p class="mt-4 text-sm text-neutral-600">Per bulan untuk tenor 36 bulan</p>
                <div class="mt-8 space-y-3 text-sm text-neutral-700">
                    <div class="flex justify-between"><span>Harga mobil</span><span>Rp 500.000.000</span></div>
                    <div class="flex justify-between"><span>Uang muka</span><span>Rp 150.000.000</span></div>
                    <div class="flex justify-between"><span>Pinjaman</span><span>Rp 350.000.000</span></div>
                    <div class="flex justify-between"><span>Bunga</span><span>3,5%</span></div>
                </div>
                <a href="{{ route('contact') }}" class="mt-8 inline-flex border border-neutral-900 bg-neutral-900 px-5 py-3 text-sm font-medium text-white hover:bg-neutral-700">
                    Konsultasi langsung
                </a>
            </aside>
        </div>
    </section>
@endsection
