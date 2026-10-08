<?php

namespace App\Models;

use Database\Factories\DealerSettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DealerSetting extends Model
{
    /** @use HasFactory<DealerSettingFactory> */
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'dealer_name',
        'logo_path',
        'tagline',
        'home_intro',
        'hero_title',
        'hero_description',
        'hero_image_path',
        'history',
        'about_content',
        'address',
        'phone',
        'email',
        'whatsapp',
        'opening_hours',
        'services',
        'map_url',
        'latitude',
        'longitude',
        'social_links',
    ];

    protected function casts(): array
    {
        return [
            'opening_hours' => 'array',
            'services' => 'array',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'social_links' => 'array',
        ];
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->resolvePublicUrl($this->logo_path);
    }

    public function getHeroImageUrlAttribute(): ?string
    {
        return $this->resolvePublicUrl($this->hero_image_path);
    }

    protected function resolvePublicUrl(?string $path): ?string
    {
        if ($path === null || $path === '' || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }
}
