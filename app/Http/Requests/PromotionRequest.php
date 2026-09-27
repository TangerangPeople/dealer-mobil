<?php

namespace App\Http\Requests;

use App\Models\Promotion;
use App\Models\Vehicle;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PromotionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    protected function prepareForValidation(): void
    {
        $slugSource = filled($this->input('slug')) ? $this->input('slug') : $this->input('title');

        $this->merge([
            'slug' => Str::slug((string) $slugSource),
        ]);
    }

    public function rules(): array
    {
        $promotion = $this->route('promotion');
        $promotionId = $promotion instanceof Promotion ? $promotion->getKey() : null;

        return [
            'vehicle_id' => ['nullable', 'integer', Rule::exists('vehicles', 'id')],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('promotions', 'slug')->ignore($promotionId)],
            'summary' => ['required', 'string', 'max:500'],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'image_alt_text' => ['nullable', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            if ($this->filled('vehicle_id')) {
                $vehicle = Vehicle::query()->withTrashed()->find($this->input('vehicle_id'));

                if ($vehicle === null) {
                    $validator->errors()->add('vehicle_id', 'Kendaraan yang dipilih tidak valid.');
                }
            }
        });
    }
}
