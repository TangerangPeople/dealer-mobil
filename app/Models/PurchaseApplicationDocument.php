<?php

namespace App\Models;

use Database\Factories\PurchaseApplicationDocumentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseApplicationDocument extends Model
{
    /** @use HasFactory<PurchaseApplicationDocumentFactory> */
    use HasFactory;

    protected $fillable = [
        'purchase_application_id',
        'document_type',
        'document_label',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
        'uploaded_at',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'uploaded_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<PurchaseApplication, $this> */
    public function purchaseApplication(): BelongsTo
    {
        return $this->belongsTo(PurchaseApplication::class);
    }
}
