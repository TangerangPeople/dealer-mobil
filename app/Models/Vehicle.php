<?php

namespace App\Models;

use Database\Factories\VehicleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    /** @use HasFactory<VehicleFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'brand',
        'name',
        'type',
        'slug',
        'year',
        'price',
        'color',
        'transmission',
        'engine_capacity',
        'mileage',
        'status',
        'description',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'price' => 'decimal:0',
            'engine_capacity' => 'integer',
            'mileage' => 'integer',
            'is_featured' => 'boolean',
        ];
    }

    /** @return BelongsTo<Category, $this> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** @return HasMany<VehicleImage, $this> */
    public function images(): HasMany
    {
        return $this->hasMany(VehicleImage::class);
    }

    /** @return HasMany<VehicleImage, $this> */
    public function vehicleImages(): HasMany
    {
        return $this->hasMany(VehicleImage::class);
    }

    /** @return HasOne<VehicleImage, $this> */
    public function primaryImage(): HasOne
    {
        return $this->hasOne(VehicleImage::class)->where('is_primary', true);
    }

    /** @return HasMany<Promotion, $this> */
    public function promotions(): HasMany
    {
        return $this->hasMany(Promotion::class);
    }

    /** @return HasMany<PurchaseApplication, $this> */
    public function purchaseApplications(): HasMany
    {
        return $this->hasMany(PurchaseApplication::class);
    }
}
