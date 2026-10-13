<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceBookingSparePart extends Model
{
    protected $table = 'service_booking_spare_parts';

    protected $fillable = [
        'service_booking_id',
        'spare_part_id',
        'quantity',
        'unit_price',
        'notes',
        'used_by',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price' => 'decimal:2',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(ServiceBooking::class, 'service_booking_id');
    }

    public function sparePart(): BelongsTo
    {
        return $this->belongsTo(SparePart::class);
    }

    public function usedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'used_by');
    }

    public function getSubtotalAttribute(): float
    {
        return (float) $this->unit_price * $this->quantity;
    }
}
