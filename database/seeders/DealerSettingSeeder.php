<?php

namespace Database\Seeders;

use App\Models\DealerSetting;
use Illuminate\Database\Seeder;

class DealerSettingSeeder extends Seeder
{
    public function run(): void
    {
        DealerSetting::query()->updateOrCreate(
            ['id' => 1],
            [
                'dealer_name' => 'Dealer Development',
                'address' => 'Alamat development belum dikonfigurasi.',
                'phone' => '620000000000',
                'email' => 'dealer@example.test',
                'whatsapp' => '620000000000',
            ]
        );
    }
}
