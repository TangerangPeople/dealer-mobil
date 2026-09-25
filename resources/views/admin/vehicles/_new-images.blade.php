<section class="mt-10 border-t border-[#bbbbbb] pt-8" aria-labelledby="new-images-heading">
    <h2 id="new-images-heading" class="text-xl font-semibold">Foto kendaraan</h2>
    <p class="mt-2 text-sm leading-6">Unggah hingga tiga foto saat ini. File harus JPG, PNG, atau WebP; maksimal 5 MB dan minimal 600 × 400 piksel.</p>

    <div class="mt-6 grid gap-5">
        @for ($index = 0; $index < 3; $index++)
            <fieldset class="border border-[#bbbbbb] bg-[#f1f1f1] p-4">
                <legend class="px-1 text-sm font-semibold">Foto {{ $index + 1 }}</legend>
                <div class="grid gap-4 md:grid-cols-[1fr_10rem_10rem_auto] md:items-end">
                    <div>
                        <label for="image-{{ $index }}" class="block text-sm font-semibold">File gambar</label>
                        <input id="image-{{ $index }}" name="images[{{ $index }}]" type="file" accept="image/jpeg,image/png,image/webp" class="mt-2 block w-full border border-[#bbbbbb] bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
                        @error("images.{$index}") <p class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="image-alt-{{ $index }}" class="block text-sm font-semibold">Alt text</label>
                        <input id="image-alt-{{ $index }}" name="image_alt_texts[{{ $index }}]" type="text" maxlength="255" value="{{ old("image_alt_texts.{$index}") }}" class="mt-2 block w-full border border-[#bbbbbb] bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
                        @error("image_alt_texts.{$index}") <p class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="image-order-{{ $index }}" class="block text-sm font-semibold">Urutan</label>
                        <input id="image-order-{{ $index }}" name="image_sort_orders[{{ $index }}]" type="number" min="0" value="{{ old("image_sort_orders.{$index}", $index) }}" class="mt-2 block w-full border border-[#bbbbbb] bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
                        @error("image_sort_orders.{$index}") <p class="mt-2 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex items-center gap-2 pb-3">
                        <input id="image-primary-{{ $index }}" name="primary_image_index" type="radio" value="{{ $index }}" @checked((string) old('primary_image_index') === (string) $index) class="h-4 w-4 border-[#262626] text-[#262626] focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">
                        <label for="image-primary-{{ $index }}" class="text-sm font-semibold">Foto utama</label>
                    </div>
                </div>
            </fieldset>
        @endfor
    </div>
    @error('primary_image_index') <p class="mt-3 text-sm font-semibold text-[#b91c1c]">{{ $message }}</p> @enderror
</section>
