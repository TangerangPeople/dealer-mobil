<?php

namespace App\Models;

use Database\Factories\PurchaseApplicationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseApplication extends Model
{
    /** @use HasFactory<PurchaseApplicationFactory> */
    use HasFactory;

    public const STATUSES = ['pending', 'reviewing', 'approved', 'rejected', 'completed'];

    public const PURCHASE_METHODS = ['cash', 'financing', 'consultation'];

    protected $fillable = [
        'application_number',
        'user_id',
        'vehicle_id',
        'vehicle_brand',
        'vehicle_name',
        'vehicle_type',
        'vehicle_year',
        'vehicle_price',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_whatsapp',
        'customer_address',
        'purchase_method',
        'financing_preference',
        'customer_note',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'vehicle_year' => 'integer',
            'vehicle_price' => 'decimal:0',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<Vehicle, $this> */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /** @return HasMany<PurchaseApplicationDocument, $this> */
    public function documents(): HasMany
    {
        return $this->hasMany(PurchaseApplicationDocument::class);
    }
}
