<?php

namespace App\Http\Requests;

use App\Models\Category;
use App\Models\Vehicle;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class VehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    protected function prepareForValidation(): void
    {
        $name = trim((string) $this->input('name'));
        $slugSource = filled($this->input('slug')) ? $this->input('slug') : $name;

        $this->merge([
            'brand' => trim((string) $this->input('brand')),
            'name' => $name,
            'type' => trim((string) $this->input('type')),
            'slug' => Str::slug((string) $slugSource),
            'is_featured' => $this->boolean('is_featured'),
        ]);
    }

    public function rules(): array
    {
        $vehicle = $this->route('vehicle');
        $vehicleId = $vehicle instanceof Vehicle ? $vehicle->getKey() : null;

        return [
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')->where(fn ($query) => $query->where('is_active', true)->whereNull('deleted_at')),
            ],
            'brand' => ['required', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('vehicles', 'slug')->ignore($vehicleId)],
            'year' => ['required', 'integer', 'min:1900', 'max:'.now()->addYear()->year],
            'price' => ['required', 'numeric', 'min:0'],
            'color' => ['nullable', 'string', 'max:50'],
            'transmission' => ['nullable', 'string', 'max:30'],
            'engine_capacity' => ['nullable', 'integer', 'min:1'],
            'mileage' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['available', 'sold', 'reserved', 'inactive'])],
            'description' => ['nullable', 'string'],
            'is_featured' => ['boolean'],
            'images' => ['nullable', 'array', 'max:8'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120', 'dimensions:min_width=600,min_height=400,max_width=5000,max_height=5000'],
            'image_alt_texts' => ['nullable', 'array'],
            'image_alt_texts.*' => ['nullable', 'string', 'max:255'],
            'image_sort_orders' => ['nullable', 'array'],
            'image_sort_orders.*' => ['nullable', 'integer', 'min:0'],
            'primary_image_index' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function after(): array
    {
        return [function ($validator): void {
            $files = $this->file('images', []);
            $altTexts = $this->input('image_alt_texts', []);
            $sortOrders = $this->input('image_sort_orders', []);
            $primaryIndex = $this->input('primary_image_index');

            foreach ($files as $index => $file) {
                if ($file === null) {
                    continue;
                }

                if (blank($altTexts[$index] ?? null)) {
                    $validator->errors()->add("image_alt_texts.{$index}", 'Alt text wajib diisi untuk setiap foto yang diunggah.');
                }

                if (! array_key_exists($index, $sortOrders) || $sortOrders[$index] === '') {
                    $validator->errors()->add("image_sort_orders.{$index}", 'Urutan wajib diisi untuk setiap foto yang diunggah.');
                }
            }

            $hasIncomingPrimary = $primaryIndex !== null && array_key_exists((int) $primaryIndex, $files);
            $vehicle = $this->route('vehicle');
            $hasExistingPrimary = $vehicle instanceof Vehicle && $vehicle->primaryImage()->exists();

            if ($this->input('status') === 'available' && ! $hasIncomingPrimary && ! $hasExistingPrimary) {
                $validator->errors()->add('status', 'Kendaraan available wajib memiliki minimal satu foto utama.');
            }

            if ($primaryIndex !== null && ! $hasIncomingPrimary) {
                $validator->errors()->add('primary_image_index', 'Pilih foto utama dari file yang benar-benar diunggah.');
            }

            if ($this->boolean('is_featured') && $this->input('status') === 'inactive') {
                $validator->errors()->add('is_featured', 'Kendaraan inactive tidak dapat ditandai sebagai featured.');
            }
        }];
    }

    public function attributes(): array
    {
        return [
            'category_id' => 'kategori',
            'brand' => 'merek',
            'name' => 'nama kendaraan',
            'type' => 'tipe',
            'slug' => 'slug',
            'year' => 'tahun',
            'price' => 'harga',
            'engine_capacity' => 'kapasitas mesin',
            'mileage' => 'kilometer',
            'status' => 'status',
            'is_featured' => 'featured',
            'images.*' => 'foto kendaraan',
        ];
    }
}
