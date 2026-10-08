@extends('public.layouts.app')

@section('title', 'Simulasi Kredit | '.($settings->dealer_name ?? 'Dealer Mobil'))

@section('content')
    <section class="section-shell py-16 sm:py-20">
        <div class="max-w-3xl">
            <p class="section-label text-[#5f5f5f]">Perencanaan pembiayaan</p>
            <h1 class="mt-3 text-4xl font-light tracking-[-0.04em] text-[#1e1e1e] sm:text-5xl">Simulasi Kredit</h1>
            <p class="mt-5 text-base leading-7 text-[#4b4b4b]">Sesuaikan kendaraan, uang muka, tenor, dan bunga estimasi untuk memperoleh gambaran cicilan.</p>
        </div>

        @if ($vehicles->isEmpty())
            <div class="mt-10 border border-black/10 bg-white p-8" role="status">
                <p class="text-lg">Belum ada kendaraan tersedia untuk simulasi.</p>
                <a href="{{ route('vehicles.index') }}" class="mt-4 inline-flex text-sm underline underline-offset-4">Lihat katalog kendaraan</a>
            </div>
        @else
            @php
                $initialVehicle = $selectedVehicle ?? $vehicles->first();
                $initialPrice = (int) $initialVehicle->price;
                $initialDownPayment = (int) round($initialPrice * 0.2);
            @endphp

            <div class="mt-10 grid gap-8 lg:grid-cols-[1fr_0.9fr]">
                <form id="credit-form" class="space-y-6 border border-black/10 bg-white p-6 sm:p-8" novalidate>
                    <div>
                        <label for="vehicle-select" class="block text-sm font-medium text-[#3a3a3a]">Pilih kendaraan</label>
                        <select id="vehicle-select" class="mt-2 w-full border border-[#bbbbbb] bg-white px-4 py-3 text-base text-[#1e1e1e] focus:border-[#262626] focus:outline-none">
                            @foreach ($vehicles as $vehicle)
                                <option value="{{ $vehicle->slug }}" data-price="{{ (int) $vehicle->price }}" @selected($vehicle->id === $initialVehicle->id)>
                                    {{ $vehicle->brand }} {{ $vehicle->name }} ({{ $vehicle->slug }})
                                </option>
                            @endforeach
                        </select>
                        <p id="selected-vehicle-name" class="mt-2 text-sm text-[#5b5b5b]">{{ $initialVehicle->brand }} {{ $initialVehicle->name }}</p>
                    </div>

                    <div>
                        <label for="vehicle-price" class="block text-sm font-medium text-[#3a3a3a]">Harga kendaraan (Rp)</label>
                        <input id="vehicle-price" type="number" inputmode="numeric" value="{{ $initialPrice }}" min="1" step="1" required class="mt-2 w-full border border-[#bbbbbb] bg-white px-4 py-3 text-base text-[#1e1e1e] focus:border-[#262626] focus:outline-none">
                    </div>

                    <div>
                        <label for="down-payment" class="block text-sm font-medium text-[#3a3a3a]">Uang muka (Rp)</label>
                        <input id="down-payment" type="number" inputmode="numeric" value="{{ $initialDownPayment }}" min="1" max="{{ max(0, $initialPrice - 1) }}" step="1" required class="mt-2 w-full border border-[#bbbbbb] bg-white px-4 py-3 text-base text-[#1e1e1e] focus:border-[#262626] focus:outline-none">
                        <p class="mt-2 text-sm text-[#5b5b5b]">Persentase DP: <span id="down-payment-percentage">20%</span></p>
                    </div>

                    <div>
                        <label for="tenor" class="block text-sm font-medium text-[#3a3a3a]">Tenor</label>
                        <select id="tenor" class="mt-2 w-full border border-[#bbbbbb] bg-white px-4 py-3 text-base text-[#1e1e1e] focus:border-[#262626] focus:outline-none">
                            @foreach ([12, 24, 36, 48, 60] as $months)
                                <option value="{{ $months }}" @selected($months === 36)>{{ $months }} bulan</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="interest-rate" class="block text-sm font-medium text-[#3a3a3a]">Suku bunga estimasi per tahun (%)</label>
                        <input id="interest-rate" type="number" inputmode="decimal" value="3.5" min="0" max="100" step="0.1" required class="mt-2 w-full border border-[#bbbbbb] bg-white px-4 py-3 text-base text-[#1e1e1e] focus:border-[#262626] focus:outline-none">
                    </div>
                    <p id="simulation-error" class="hidden border-l-4 border-[#b91c1c] bg-[#f1f1f1] p-4 text-sm text-[#262626]" role="alert" aria-live="assertive"></p>
                </form>

                <aside class="border border-black/10 bg-[#f1f1f1] p-6 sm:p-8">
                    <p class="section-label text-[#5f5f5f]">Hasil estimasi</p>
                    <p id="monthly-installment" class="mt-3 text-3xl font-light text-[#1e1e1e] sm:text-4xl" aria-live="polite"></p>
                    <p id="tenor-label" class="mt-2 text-sm text-[#5b5b5b]"></p>

                    <dl class="mt-8 divide-y divide-black/10 border-y border-black/10 text-sm text-[#4b4b4b]">
                        <div class="flex justify-between gap-4 py-3"><dt>Harga kendaraan</dt><dd id="price-summary" class="text-right"></dd></div>
                        <div class="flex justify-between gap-4 py-3"><dt>DP</dt><dd id="down-summary" class="text-right"></dd></div>
                        <div class="flex justify-between gap-4 py-3"><dt>Persentase DP</dt><dd id="percentage-summary" class="text-right"></dd></div>
                        <div class="flex justify-between gap-4 py-3"><dt>Pokok pembiayaan</dt><dd id="principal-summary" class="text-right"></dd></div>
                        <div class="flex justify-between gap-4 py-3"><dt>Tenor</dt><dd id="tenor-summary" class="text-right"></dd></div>
                        <div class="flex justify-between gap-4 py-3"><dt>Bunga estimasi per tahun</dt><dd id="rate-summary" class="text-right"></dd></div>
                        <div class="flex justify-between gap-4 py-3"><dt>Total bunga</dt><dd id="total-interest-summary" class="text-right"></dd></div>
                        <div class="flex justify-between gap-4 py-3 font-medium text-[#262626]"><dt>Total pembayaran</dt><dd id="total-payment-summary" class="text-right"></dd></div>
                    </dl>

                    <p class="mt-6 text-xs leading-6 text-[#5b5b5b]">Simulasi menggunakan perhitungan estimasi bunga flat dan bukan penawaran pembiayaan resmi.</p>

                    <div class="mt-7 flex flex-col gap-3 sm:flex-row">
                        <a id="apply-purchase" href="{{ route('purchase.create', ['vehicle' => $initialVehicle->slug]) }}" class="inline-flex min-h-12 items-center justify-center border border-[#262626] bg-[#262626] px-5 py-3 text-center text-sm font-medium text-white transition hover:bg-white hover:text-[#262626]">Ajukan Pembelian</a>
                        <a id="chat-sales" href="{{ route('sales.chat', ['vehicle' => $initialVehicle->slug]) }}" target="_blank" rel="noopener noreferrer" class="inline-flex min-h-12 items-center justify-center border border-[#262626] px-5 py-3 text-center text-sm font-medium text-[#262626] transition hover:bg-[#262626] hover:text-white">Chat Sales</a>
                    </div>
                </aside>
            </div>
        @endif
    </section>

    @if ($vehicles->isNotEmpty())
        <script>
            const vehicleSelect = document.getElementById('vehicle-select');
            const priceInput = document.getElementById('vehicle-price');
            const downInput = document.getElementById('down-payment');
            const tenorInput = document.getElementById('tenor');
            const rateInput = document.getElementById('interest-rate');
            const errorElement = document.getElementById('simulation-error');
            const applyLink = document.getElementById('apply-purchase');
            const chatLink = document.getElementById('chat-sales');

            const formatCurrency = (amount) => new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0,
            }).format(amount);

            function updateSimulation() {
                const selectedOption = vehicleSelect.selectedOptions[0];
                const price = Number(priceInput.value);
                const downPayment = Number(downInput.value);
                const tenor = Number(tenorInput.value);
                const annualRate = Number(rateInput.value);
                const validTenors = [12, 24, 36, 48, 60];
                const isValid = Number.isSafeInteger(price) && price > 0
                    && Number.isSafeInteger(downPayment) && downPayment > 0 && downPayment < price
                    && validTenors.includes(tenor)
                    && Number.isFinite(annualRate) && annualRate >= 0 && annualRate <= 100;

                errorElement.textContent = isValid ? '' : 'Harga harus lebih dari nol, DP harus lebih dari nol dan kurang dari harga kendaraan, serta bunga harus antara 0–100%.';
                errorElement.classList.toggle('hidden', isValid);
                priceInput.setCustomValidity(Number.isSafeInteger(price) && price > 0 ? '' : 'Masukkan harga kendaraan yang valid.');
                downInput.setCustomValidity(Number.isSafeInteger(downPayment) && downPayment > 0 && downPayment < price ? '' : 'DP harus lebih dari nol dan kurang dari harga kendaraan.');
                rateInput.setCustomValidity(Number.isFinite(annualRate) && annualRate >= 0 && annualRate <= 100 ? '' : 'Bunga estimasi harus antara 0–100%.');
                applyLink.setAttribute('aria-disabled', String(!isValid));
                chatLink.setAttribute('aria-disabled', String(!isValid));
                applyLink.classList.toggle('pointer-events-none', !isValid);
                chatLink.classList.toggle('pointer-events-none', !isValid);
                applyLink.classList.toggle('opacity-50', !isValid);
                chatLink.classList.toggle('opacity-50', !isValid);

                document.getElementById('selected-vehicle-name').textContent = selectedOption.textContent.trim();
                document.getElementById('price-summary').textContent = formatCurrency(Math.max(price, 0));
                document.getElementById('down-summary').textContent = formatCurrency(Math.max(downPayment, 0));

                if (!isValid) {
                    document.getElementById('monthly-installment').textContent = '—';
                    document.getElementById('tenor-label').textContent = 'Periksa kembali nilai simulasi.';
                    document.getElementById('percentage-summary').textContent = '—';
                    document.getElementById('principal-summary').textContent = '—';
                    document.getElementById('tenor-summary').textContent = `${tenor} bulan`;
                    document.getElementById('rate-summary').textContent = `${annualRate || 0}%`;
                    document.getElementById('total-interest-summary').textContent = '—';
                    document.getElementById('total-payment-summary').textContent = '—';
                    return;
                }

                const percentage = (downPayment / price) * 100;
                const principal = price - downPayment;
                const totalInterest = principal * (annualRate / 100) * (tenor / 12);
                const totalPayment = principal + totalInterest;
                const monthlyInstallment = totalPayment / tenor;

                document.getElementById('down-payment-percentage').textContent = `${percentage.toLocaleString('id-ID', { maximumFractionDigits: 2 })}%`;
                document.getElementById('percentage-summary').textContent = `${percentage.toLocaleString('id-ID', { maximumFractionDigits: 2 })}%`;
                document.getElementById('principal-summary').textContent = formatCurrency(principal);
                document.getElementById('tenor-summary').textContent = `${tenor} bulan`;
                document.getElementById('rate-summary').textContent = `${annualRate.toLocaleString('id-ID')}% per tahun`;
                document.getElementById('total-interest-summary').textContent = formatCurrency(totalInterest);
                document.getElementById('total-payment-summary').textContent = formatCurrency(totalPayment);
                document.getElementById('monthly-installment').textContent = formatCurrency(monthlyInstallment);
                document.getElementById('tenor-label').textContent = `Estimasi cicilan per bulan selama ${tenor} bulan`;

                const vehicleSlug = selectedOption.value;
                applyLink.href = `${@json(url('/pengajuan-pembelian'))}/${encodeURIComponent(vehicleSlug)}`;
                const salesUrl = new URL(@json(route('sales.chat')));
                salesUrl.searchParams.set('vehicle', vehicleSlug);
                salesUrl.searchParams.set('credit', '1');
                salesUrl.searchParams.set('price', String(price));
                salesUrl.searchParams.set('down_payment', String(downPayment));
                salesUrl.searchParams.set('tenor', String(tenor));
                salesUrl.searchParams.set('annual_rate', String(annualRate));
                chatLink.href = salesUrl.toString();
            }

            vehicleSelect.addEventListener('change', () => {
                const price = Number(vehicleSelect.selectedOptions[0].dataset.price);
                priceInput.value = String(price);
                downInput.value = String(Math.round(price * 0.2));
                downInput.max = String(Math.max(0, price - 1));
                updateSimulation();
            });

            [priceInput, downInput, tenorInput, rateInput].forEach((input) => {
                input.addEventListener('input', updateSimulation);
                input.addEventListener('change', updateSimulation);
            });

            applyLink.addEventListener('click', (event) => {
                if (applyLink.getAttribute('aria-disabled') === 'true') {
                    event.preventDefault();
                }
            });
            chatLink.addEventListener('click', (event) => {
                if (chatLink.getAttribute('aria-disabled') === 'true') {
                    event.preventDefault();
                }
            });

            updateSimulation();
        </script>
    @endif
@endsection
