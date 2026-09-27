<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'phone' => $this->normalizePhoneNumber((string) $this->input('phone', '')),
            'whatsapp' => $this->normalizePhoneNumber((string) $this->input('whatsapp', '')),
            'sort_order' => (int) $this->input('sort_order', 0),
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9]+$/', 'min:10'],
            'whatsapp' => ['required', 'string', 'max:20', 'regex:/^[0-9]+$/', 'min:10'],
            'position' => ['nullable', 'string', 'max:100'],
            'is_active' => ['boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $whatsapp = (string) $this->input('whatsapp', '');
            if ($whatsapp !== '' && ! preg_match('/^62[0-9]{9,15}$/', $whatsapp)) {
                $validator->errors()->add('whatsapp', 'Nomor WhatsApp harus dalam format digit internasional, contoh: 6281234567890.');
            }

            $phone = (string) $this->input('phone', '');
            if ($phone !== '' && ! preg_match('/^62[0-9]{9,15}$/', $phone)) {
                $validator->errors()->add('phone', 'Nomor telepon harus berisi digit valid.');
            }
        });
    }

    protected function normalizePhoneNumber(string $value): string
    {
        $digits = preg_replace('/\D+/', '', $value) ?? '';

        if ($digits === '') {
            return '';
        }

        if (str_starts_with($digits, '0')) {
            return '62'.substr($digits, 1);
        }

        if (str_starts_with($digits, '62')) {
            return $digits;
        }

        if (str_starts_with($digits, '8')) {
            return '62'.$digits;
        }

        return $digits;
    }
}
