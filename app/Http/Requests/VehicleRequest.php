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
        $slugSource = filled($this->input('slug')) ? $this->input('slug') : $this->input('name');

        $this->merge([
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
                Rule::exists('categories', 'id')->where(function ($query): void {
                    $query->where('is_active', true)->whereNull('deleted_at');
                }),
            ],
            'brand' => ['required', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('vehicles', 'slug')->ignore($vehicleId)],
            'year' => ['required', 'integer', 'min:1900', 'max:'.(int) now()->addYear()->year],
            'price' => ['required', 'numeric', 'min:0'],
            'color' => ['nullable', 'string', 'max:50'],
            'transmission' => ['nullable', 'string', 'max:30'],
            'engine_capacity' => ['nullable', 'integer', 'min:1'],
            'mileage' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['available', 'sold', 'reserved', 'inactive'])],
            'description' => ['nullable', 'string'],
            'is_featured' => ['boolean'],
            'images' => ['sometimes', 'array'],
            'images.*' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120', 'dimensions:min_width=600,min_height=400,max_width=5000,max_height=5000'],
            'image_alt_texts' => ['sometimes', 'array'],
            'image_alt_texts.*' => ['nullable', 'string', 'max:255'],
            'image_sort_orders' => ['sometimes', 'array'],
            'image_sort_orders.*' => ['nullable', 'integer', 'min:0'],
            'primary_image_index' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $status = (string) $this->input('status');
            $featured = $this->boolean('is_featured');

            if ($featured && $status === 'inactive') {
                $validator->errors()->add('is_featured', 'Kendaraan inactive tidak dapat ditandai sebagai featured.');
            }

            $vehicle = $this->route('vehicle');
            if ($vehicle instanceof Vehicle && $status === 'available') {
                $hasPrimary = $vehicle->primaryImage()->exists();

                if (! $hasPrimary && ! $this->hasPrimaryImageFromUpload()) {
                    $validator->errors()->add('status', 'Kendaraan available wajib memiliki minimal satu foto utama.');
                }
            }

            $categoryId = $this->input('category_id');
            if (filled($categoryId)) {
                $category = Category::withTrashed()->find($categoryId);

                if ($category && (! $category->is_active || ! is_null($category->deleted_at))) {
                    $validator->errors()->add('category_id', 'Kategori kendaraan harus aktif dan belum dihapus.');
                }
            }
        });
    }

    protected function hasPrimaryImageFromUpload(): bool
    {
        $primaryIndex = $this->input('primary_image_index');

        if (! is_numeric($primaryIndex)) {
            return false;
        }

        $index = (int) $primaryIndex;

        return $this->hasFile("images.{$index}") || $this->boolean("is_primary_{$index}");
    }
}
