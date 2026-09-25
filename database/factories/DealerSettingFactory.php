<?php

namespace Database\Factories;

use App\Models\DealerSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<DealerSetting> */
class DealerSettingFactory extends Factory
{
    protected $model = DealerSetting::class;

    public function definition(): array
    {
        return [
            'id' => 1,
            'dealer_name' => 'Dealer Development',
            'logo_path' => null,
            'tagline' => null,
            'home_intro' => null,
            'hero_title' => null,
            'hero_description' => null,
            'hero_image_path' => null,
            'history' => null,
            'about_content' => null,
            'address' => 'Alamat development belum dikonfigurasi.',
            'phone' => '620000000000',
            'email' => 'dealer@example.test',
            'whatsapp' => '620000000000',
            'opening_hours' => null,
            'services' => null,
            'map_url' => null,
            'latitude' => null,
            'longitude' => null,
            'social_links' => null,
        ];
    }
}
