<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DealerSettingRequest extends FormRequest
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
            'opening_hours' => $this->normalizeOpeningHours($this->input('opening_hours')),
            'services' => $this->normalizeServices($this->input('services')),
            'social_links' => $this->normalizeSocialLinks($this->input('social_links')),
            'latitude' => $this->input('latitude') !== null && $this->input('latitude') !== '' ? (float) $this->input('latitude') : null,
            'longitude' => $this->input('longitude') !== null && $this->input('longitude') !== '' ? (float) $this->input('longitude') : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'dealer_name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'home_intro' => ['nullable', 'string'],
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_description' => ['nullable', 'string'],
            'history' => ['nullable', 'string'],
            'about_content' => ['nullable', 'string'],
            'address' => ['required', 'string'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^[0-9]+$/', 'min:10'],
            'email' => ['required', 'email', 'max:255'],
            'whatsapp' => ['required', 'string', 'max:20', 'regex:/^[0-9]+$/', 'min:10'],
            'opening_hours' => ['nullable', 'array'],
            'opening_hours.*.day' => ['required_with:opening_hours', 'string', 'max:20'],
            'opening_hours.*.open' => ['nullable', 'string', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
            'opening_hours.*.close' => ['nullable', 'string', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
            'opening_hours.*.closed' => ['required_with:opening_hours', 'boolean'],
            'services' => ['nullable', 'array'],
            'services.*' => ['string', 'max:255'],
            'map_url' => ['nullable', 'url', 'max:2048'],
            'latitude' => ['nullable', 'numeric', 'min:-90', 'max:90'],
            'longitude' => ['nullable', 'numeric', 'min:-180', 'max:180'],
            'social_links' => ['nullable', 'array'],
            'social_links.*' => ['nullable', 'url', 'max:2048'],
            'logo' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120', 'dimensions:min_width=600,min_height=400,max_width=5000,max_height=5000'],
            'hero_image' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120', 'dimensions:min_width=600,min_height=400,max_width=5000,max_height=5000'],
            'remove_logo' => ['nullable', 'boolean'],
            'remove_hero_image' => ['nullable', 'boolean'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $whatsapp = (string) $this->input('whatsapp', '');
            if ($whatsapp !== '' && ! preg_match('/^62[0-9]{9,15}$/', $whatsapp)) {
                $validator->errors()->add('whatsapp', 'Nomor WhatsApp harus dalam format internasional seperti 6281234567890.');
            }

            $socialLinks = $this->input('social_links');
            if (is_array($socialLinks)) {
                foreach ($socialLinks as $platform => $url) {
                    if (! is_string($platform) || ! is_string($url)) {
                        $validator->errors()->add('social_links', 'Format tautan sosial tidak valid.');
                        break;
                    }

                    if (! in_array(strtolower((string) $platform), ['instagram', 'facebook', 'youtube', 'x', 'tiktok', 'linkedin'], true)) {
                        $validator->errors()->add('social_links', 'Platform media sosial tidak diizinkan.');
                        break;
                    }
                }
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

    protected function normalizeOpeningHours(mixed $value): ?array
    {
        if (! is_array($value)) {
            return null;
        }

        $normalized = [];

        foreach ($value as $index => $entry) {
            if (! is_array($entry)) {
                continue;
            }

            $day = trim((string) ($entry['day'] ?? ''));
            $closed = filter_var($entry['closed'] ?? false, FILTER_VALIDATE_BOOLEAN);

            $normalized[] = [
                'day' => $day,
                'open' => $closed ? null : $this->normalizeTime($entry['open'] ?? null),
                'close' => $closed ? null : $this->normalizeTime($entry['close'] ?? null),
                'closed' => $closed,
            ];
        }

        return $normalized;
    }

    protected function normalizeServices(mixed $value): ?array
    {
        if (! is_array($value)) {
            return null;
        }

        return array_values(array_filter(array_map(fn ($item) => is_string($item) ? trim($item) : null, $value), fn ($item) => $item !== null && $item !== ''));
    }

    protected function normalizeSocialLinks(mixed $value): ?array
    {
        if (! is_array($value)) {
            return null;
        }

        $normalized = [];

        foreach ($value as $platform => $url) {
            if (! is_string($platform) || ! is_string($url)) {
                continue;
            }

            $platform = strtolower(trim($platform));
            if ($platform === '') {
                continue;
            }

            $normalized[$platform] = trim($url);
        }

        return $normalized;
    }

    protected function normalizeTime(mixed $value): ?string
    {
        if (! is_string($value) || $value === '') {
            return null;
        }

        return preg_match('/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/', $value) ? $value : null;
    }
}
