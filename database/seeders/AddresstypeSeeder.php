<?php

namespace Database\Seeders;

use App\Models\Addresstype;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddresstypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Addresstype::create([
            'id' => 1,
            'type' => 'Correspondence Address',
            'type_devnagari' => 'पत्रव्यवहाराचा पत्ता',
            'is_active' => 1,
            'created_at' => '2023-09-22 13:05:36',
            'updated_at' => '2023-09-22 13:05:36'
        ]);

        Addresstype::create([
            'id' => 2,
            'type' => 'Permanent Address',
            'type_devnagari' => 'कायमचा पत्ता',
            'is_active' => 1,
            'created_at' => '2023-09-22 13:05:36',
            'updated_at' => '2023-09-22 13:05:36'
        ]);
    }
}
