<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'phone' => $this->normalizePhoneNumber((string) $this->input('phone', '')),
            'whatsapp' => $this->normalizePhoneNumber((string) $this->input('whatsapp', '')),
            'vehicle_id' => $this->input('vehicle_id') !== null ? (int) $this->input('vehicle_id') : null,
            'service_package_id' => $this->input('service_package_id') !== null ? (int) $this->input('service_package_id') : null,
            'status' => $this->isMethod('POST') ? 'pending' : $this->input('status'),
        ]);
    }

    public function rules(): array
    {
        if ($this->isMethod('POST') || ($this->isMethod('PUT') && ! $this->has('status') && ! $this->has('admin_notes'))) {
            return [
                'customer_name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9]+$/', 'min:10'],
                'whatsapp' => ['required', 'string', 'max:20', 'regex:/^[0-9]+$/', 'min:10'],
                'vehicle_id' => [
                    'nullable',
                    'integer',
                    Rule::exists('vehicles', 'id')->where('status', 'available')->whereNull('deleted_at'),
                ],
                'vehicle_plate' => ['required', 'string', 'max:50'],
                'service_package_id' => [
                    'required',
                    'integer',
                    Rule::exists('service_packages', 'id')->where('is_active', true)->whereNull('deleted_at'),
                ],
                'service_type' => ['required', 'in:maintenance,repair,inspection'],
                'preferred_date' => ['required', 'date', 'after_or_equal:today'],
                'preferred_time' => ['required', 'date_format:H:i'],
                'complaint' => ['required', 'string', 'max:1000'],
                'notes' => ['nullable', 'string', 'max:1000'],
                'status' => ['required', Rule::in(['pending'])],
                'admin_notes' => ['nullable', 'string', 'max:1000'],
            ];
        }

        return [
            'status' => ['required', 'in:pending,approved,rescheduled,completed,cancelled'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
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
