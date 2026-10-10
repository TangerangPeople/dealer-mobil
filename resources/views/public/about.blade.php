
@extends('public.layouts.app')

@section('title', ($settings?->dealer_name ?: config('app.name')) . ' | Tentang Dealer')

@section('content')
    @php
        $dealerName = $settings?->dealer_name ?: config('app.name');

        $services = is_array($settings?->services)
            ? array_values(array_filter(
                $settings->services,
                fn ($service) => is_string($service) && trim($service) !== ''
            ))
            : [];

        $openingHours = is_array($settings?->opening_hours)
            ? array_values(array_filter(
                $settings->opening_hours,
                fn ($hours) => is_array($hours)
            ))
            : [];

        $salesCount = $sales->count();

        $salesCardWidth = match (true) {
            $salesCount === 1 => 'w-full max-w-md',
            $salesCount === 2 => 'w-full sm:w-[calc(50%_-_12px)]',
            $salesCount === 3 => 'w-full sm:w-[calc(50%_-_12px)] xl:w-[calc(33.333%_-_16px)]',
            default => 'w-full sm:w-[calc(50%_-_12px)] xl:w-[calc(25%_-_18px)]',
        };
    @endphp

    <section class="section-shell py-12 sm:py-16 lg:py-20">
        {{-- Judul halaman --}}
        <header class="mx-auto max-w-4xl text-center">
            <p class="section-label">Profil Dealer</p>

            <h1 class="mt-4 break-words text-3xl font-light leading-tight tracking-[-0.04em] text-[#262626] sm:text-4xl lg:text-5xl [overflow-wrap:anywhere]">
                Tentang {{ $dealerName }}
            </h1>

            @if ($settings?->tagline)
                <p class="mx-auto mt-5 max-w-2xl text-base leading-7 text-[#5f5f5f] sm:text-lg sm:leading-8">
                    {{ $settings->tagline }}
                </p>
            @endif
        </header>

        {{-- Profil, kontak, layanan, dan jam operasional --}}
        <div class="mt-10 grid min-w-0 gap-8 lg:mt-14 lg:grid-cols-[minmax(0,1.2fr)_minmax(0,0.8fr)] lg:gap-12 xl:gap-16">

            {{-- Kolom profil dealer --}}
            <div class="min-w-0">
                <article class="border-y border-black/10 py-7 sm:py-9">
                    <p class="section-label">Sejarah dealer</p>

                    <div class="mt-5 break-words text-base leading-8 text-[#4b4b4b] [overflow-wrap:anywhere]">
                        @if ($settings?->history)
                            {{ $settings->history }}
                        @else
                            <p>Informasi sejarah dealer belum ditambahkan.</p>
                        @endif
                    </div>

                    <div class="mt-8 border-t border-black/10 pt-7 sm:pt-8">
                        <p class="section-label">Profil perusahaan</p>

                        <div class="mt-5 break-words text-base leading-8 text-[#4b4b4b] [overflow-wrap:anywhere]">
                            @if ($settings?->about_content)
                                {{ $settings->about_content }}
                            @else
                                <p>Informasi profil perusahaan belum ditambahkan.</p>
                            @endif
                        </div>
                    </div>
                </article>

                {{-- Informasi kontak --}}
                <div class="mt-8">
                    <p class="section-label">Informasi dealer</p>

                    <div class="mt-5 grid grid-cols-1 gap-0 border-y border-black/10 sm:grid-cols-2 sm:divide-x sm:divide-black/10">
                        <div class="min-w-0 py-5 sm:pr-5">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#777777]">
                                Alamat
                            </p>

                            <p class="mt-3 break-words text-base leading-7 text-[#262626] [overflow-wrap:anywhere]">
                                {{ $settings?->address ?: 'Alamat dealer belum diatur.' }}
                            </p>
                        </div>

                        <div class="min-w-0 border-t border-black/10 py-5 sm:border-t-0 sm:pl-5">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#777777]">
                                Telepon
                            </p>

                            <div class="mt-3">
                                @if ($settings?->phone)
                                    <a
                                        href="tel:{{ preg_replace('/[^0-9+]/', '', $settings->phone) }}"
                                        class="break-words text-base leading-7 text-[#262626] underline decoration-1 underline-offset-4 [overflow-wrap:anywhere]"
                                    >
                                        {{ $settings->phone }}
                                    </a>
                                @else
                                    <p class="text-base leading-7 text-[#5f5f5f]">
                                        Telepon dealer belum diatur.
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="min-w-0 border-t border-black/10 py-5 sm:pr-5">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#777777]">
                                Email
                            </p>

                            <div class="mt-3">
                                @if ($settings?->email)
                                    <a
                                        href="mailto:{{ $settings->email }}"
                                        class="break-words text-base leading-7 text-[#262626] underline decoration-1 underline-offset-4 [overflow-wrap:anywhere]"
                                    >
                                        {{ $settings->email }}
                                    </a>
                                @else
                                    <p class="text-base leading-7 text-[#5f5f5f]">
                                        Email dealer belum diatur.
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="min-w-0 border-t border-black/10 py-5 sm:pl-5">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#777777]">
                                WhatsApp
                            </p>

                            <p class="mt-3 break-words text-base leading-7 text-[#262626] [overflow-wrap:anywhere]">
                                {{ $settings?->whatsapp ?: 'WhatsApp dealer belum diatur.' }}
                            </p>
                        </div>
                    </div>

                    <a
                        href="{{ route('contact') }}"
                        class="button-primary mt-6 sm:w-auto"
                    >
                        Hubungi dealer
                    </a>
                </div>
            </div>

            {{-- Kolom layanan dan jam operasional --}}
            <aside class="min-w-0 self-start bg-[#f1f1f1] p-6 sm:p-8 lg:p-9">
                <div>
                    <p class="section-label">Layanan</p>

                    <h2 class="mt-3 text-2xl font-light leading-snug tracking-[-0.03em] text-[#262626] sm:text-3xl">
                        Layanan dealer
                    </h2>

                    @if (count($services))
                        <ul class="mt-6 divide-y divide-black/10 border-y border-black/10">
                            @foreach ($services as $service)
                                <li class="break-words py-4 text-sm leading-6 text-[#4b4b4b] [overflow-wrap:anywhere]">
                                    {{ $service }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="mt-5 border-y border-black/10 py-5 text-sm leading-7 text-[#5f5f5f]">
                            Informasi layanan belum ditambahkan melalui pengaturan dealer.
                        </p>
                    @endif
                </div>

                <div class="mt-9 border-t border-black/10 pt-7">
                    <p class="section-label">Jam operasional</p>

                    <h2 class="mt-3 text-2xl font-light tracking-[-0.03em] text-[#262626]">
                        Waktu pelayanan
                    </h2>

                    @if (count($openingHours))
                        <dl class="mt-5 divide-y divide-black/10">
                            @foreach ($openingHours as $hours)
                                @php
                                    $isClosed = filter_var(
                                        $hours['closed'] ?? false,
                                        FILTER_VALIDATE_BOOLEAN
                                    );

                                    $day = $hours['day'] ?? 'Hari';
                                    $open = $hours['open'] ?? null;
                                    $close = $hours['close'] ?? null;
                                @endphp

                                <div class="flex items-start justify-between gap-4 py-4 text-sm">
                                    <dt class="min-w-0 break-words leading-6 text-[#5f5f5f] [overflow-wrap:anywhere]">
                                        {{ $day }}
                                    </dt>

                                    <dd class="shrink-0 text-right font-medium leading-6 text-[#262626]">
                                        @if ($isClosed)
                                            Tutup
                                        @elseif ($open && $close)
                                            {{ $open }} – {{ $close }}
                                        @else
                                            Belum diatur
                                        @endif
                                    </dd>
                                </div>
                            @endforeach
                        </dl>
                    @else
                        <p class="mt-5 border-y border-black/10 py-5 text-sm leading-7 text-[#5f5f5f]">
                            Jam operasional belum diatur melalui pengaturan dealer.
                        </p>
                    @endif
                </div>
            </aside>
        </div>

        {{-- Tim sales --}}
        <section class="mt-14 border-t border-black/10 pt-10 sm:mt-16 sm:pt-12" aria-labelledby="sales-team-title">
            <header class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="section-label">Tim sales</p>

                    <h2
                        id="sales-team-title"
                        class="mt-3 text-3xl font-light tracking-[-0.04em] text-[#262626] sm:text-4xl"
                    >
                        Hubungi tim kami
                    </h2>
                </div>

                <p class="max-w-md text-sm leading-6 text-[#5f5f5f]">
                    Informasi kontak sales yang tersedia untuk membantu pertanyaan mengenai kendaraan.
                </p>
            </header>

            @if ($sales->isNotEmpty())
                <div
                    @class([
                        'mt-8 flex flex-wrap items-stretch justify-center gap-5 sm:gap-6',
                        'mx-auto max-w-md' => $salesCount === 1,
                        'mx-auto max-w-5xl' => $salesCount === 2,
                        'w-full' => $salesCount >= 3,
                    ])
                >
                    @foreach ($sales as $sale)
                        <article class="{{ $salesCardWidth }} editorial-card flex min-w-0 flex-col p-5 sm:p-6">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#777777]">
                                Tim sales
                            </p>

                            <h3 class="mt-3 break-words text-xl font-light leading-snug text-[#262626] sm:text-2xl [overflow-wrap:anywhere]">
                                {{ $sale->name }}
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-[#5f5f5f]">
                                {{ $sale->position ?: 'Posisi belum diatur' }}
                            </p>

                            <div class="mt-auto border-t border-black/10 pt-5">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.12em] text-[#777777]">
                                    WhatsApp
                                </p>

                                <p class="mt-2 break-words text-sm leading-6 text-[#262626] [overflow-wrap:anywhere]">
                                    {{ $sale->whatsapp ?: 'Nomor WhatsApp belum diatur.' }}
                                </p>

                                <a
                                    href="{{ route('sales.chat', ['sale' => $sale->getKey()]) }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="button-secondary mt-5 w-full"
                                >
                                    Chat sales
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="mt-8 border-y border-[#bbbbbb] py-8 text-sm leading-7 text-[#5f5f5f]">
                    Informasi tim sales belum tersedia.
                </div>
            @endif
        </section>
    </section>
@endsection
