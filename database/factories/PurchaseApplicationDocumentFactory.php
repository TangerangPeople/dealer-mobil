<?php

namespace Database\Factories;

use App\Models\PurchaseApplication;
use App\Models\PurchaseApplicationDocument;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<PurchaseApplicationDocument>
 */
class PurchaseApplicationDocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'purchase_application_id' => PurchaseApplication::factory(),
            'document_type' => 'customer-document',
            'document_label' => 'Dokumen pendukung',
            'file_path' => 'purchase-applications/'.Str::uuid().'/'.Str::random(20).'.pdf',
            'original_name' => 'dokumen.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 1024,
            'uploaded_at' => now(),
        ];
    }
}
