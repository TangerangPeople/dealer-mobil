<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServicePackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'estimated_price' => (int) $this->input('estimated_price', 0),
            'sort_order' => (int) $this->input('sort_order', 0),
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'service_type' => ['required', 'in:maintenance,repair,inspection'],
            'description' => ['nullable', 'string'],
            'estimated_price' => ['required', 'integer', 'min:0'],
            'estimated_duration' => ['required', 'string', 'max:100'],
            'is_active' => ['boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ];
    }
}
