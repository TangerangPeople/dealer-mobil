<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StorePurchaseApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user('customer') !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+()\s-]{8,20}$/'],
            'customer_whatsapp' => ['required', 'string', 'max:20', 'regex:/^[0-9+()\s-]{8,20}$/'],
            'customer_address' => ['required', 'string', 'max:2000'],
            'purchase_method' => ['required', Rule::in(['cash', 'financing', 'consultation'])],
            'financing_preference' => ['nullable', 'required_if:purchase_method,financing', 'string', 'max:1000'],
            'customer_note' => ['nullable', 'string', 'max:2000'],
            'documents' => ['sometimes', 'array', 'max:5'],
            'documents.*.document_type' => ['nullable', 'string', 'alpha_dash', 'max:60'],
            'documents.*.document_label' => ['nullable', 'string', 'max:120'],
            'documents.*.file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'mimetypes:application/pdf,image/jpeg,image/png,image/webp', 'max:5120'],
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator): void {
            foreach ((array) $this->file('documents', []) as $index => $file) {
                if ($file && ! $this->input("documents.{$index}.document_type")) {
                    $validator->errors()->add("documents.{$index}.document_type", 'Tentukan jenis dokumen yang diunggah.');
                }

                if ($file && ! $this->input("documents.{$index}.document_label")) {
                    $validator->errors()->add("documents.{$index}.document_label", 'Berikan nama dokumen yang diunggah.');
                }
            }
        }];
    }
}
