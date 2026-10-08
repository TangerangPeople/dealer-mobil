<?php

namespace App\Models;

use Database\Factories\ServicePackageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServicePackage extends Model
{
    /** @use HasFactory<ServicePackageFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'service_type',
        'estimated_price',
        'estimated_duration',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'estimated_price' => 'integer',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /** @return HasMany<ServiceBooking, $this> */
    public function serviceBookings(): HasMany
    {
        return $this->hasMany(ServiceBooking::class);
    }
}
