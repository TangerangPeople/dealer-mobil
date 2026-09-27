<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GalleryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_published' => $this->boolean('is_published'),
            'sort_order' => (int) $this->input('sort_order', 0),
        ]);
    }

    public function rules(): array
    {
        $isEditing = $this->route('gallery') !== null;

        return [
            'title' => ['required', 'string', 'max:255'],
            'gallery_type' => ['required', 'string', 'max:30', 'in:vehicle,showroom,event,other'],
            'image' => [
                $isEditing ? 'nullable' : 'required',
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
                'dimensions:min_width=600,min_height=400,max_width=5000,max_height=5000',
            ],
            'alt_text' => ['required', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:1000'],
            'is_published' => ['nullable', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $galleryType = (string) $this->input('gallery_type', '');
            if ($galleryType !== '' && ! in_array($galleryType, ['vehicle', 'showroom', 'event', 'other'], true)) {
                $validator->errors()->add('gallery_type', 'Tipe galeri tidak valid.');
            }
        });
    }
}
