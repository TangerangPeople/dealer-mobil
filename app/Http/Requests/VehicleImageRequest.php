<?php

namespace App\Http\Requests;

use App\Models\Vehicle;
use App\Models\VehicleImage;
use Illuminate\Foundation\Http\FormRequest;

class VehicleImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_primary' => $this->boolean('is_primary'),
        ]);
    }

    public function rules(): array
    {
        $image = $this->route('vehicleImage');

        return [
            'image' => [$image instanceof VehicleImage ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120', 'dimensions:min_width=600,min_height=400,max_width=5000,max_height=5000'],
            'alt_text' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_primary' => ['boolean'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $vehicle = $this->route('vehicle');
            $image = $this->route('vehicleImage');

            if (! $vehicle instanceof Vehicle) {
                return;
            }

            if ($image instanceof VehicleImage && $image->vehicle_id !== $vehicle->id) {
                $validator->errors()->add('image', 'Foto tidak terkait dengan kendaraan ini.');

                return;
            }

            $sameOrderExists = $vehicle->vehicleImages()
                ->when($image instanceof VehicleImage, fn ($query) => $query->whereKeyNot($image->id))
                ->where('sort_order', $this->integer('sort_order'))
                ->exists();

            if ($sameOrderExists) {
                $validator->errors()->add('sort_order', 'Urutan foto sudah digunakan pada kendaraan ini.');
            }

            $willHavePrimary = $this->boolean('is_primary') || $vehicle->primaryImage()
                ->when($image instanceof VehicleImage, fn ($query) => $query->whereKeyNot($image->id))
                ->exists();

            if ($vehicle->status === 'available' && ! $willHavePrimary) {
                $validator->errors()->add('is_primary', 'Kendaraan available wajib memiliki minimal satu foto utama.');
            }
        });
    }
}
