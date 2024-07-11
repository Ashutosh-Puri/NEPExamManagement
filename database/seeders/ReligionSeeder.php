<?php

namespace Database\Seeders;

use App\Models\Religion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReligionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Religion::create([
            'id' => 1,
            'religion_name' => 'Hindu',
            'is_active' => 1,
            'created_at' => '2023-09-22 13:05:36',
            'updated_at' => '2023-09-22 13:05:36'
        ]);

        Religion::create([
            'id' => 2,
            'religion_name' => 'Muslim',
            'is_active' => 1,
            'created_at' => '2023-09-22 13:05:36',
            'updated_at' => '2023-09-22 13:05:36'
        ]);

        Religion::create([
            'id' => 3,
            'religion_name' => 'Christian',
            'is_active' => 1,
            'created_at' => '2023-09-22 13:05:36',
            'updated_at' => '2023-09-22 13:05:36'
        ]);

        Religion::create([
            'id' => 4,
            'religion_name' => 'Sikh',
            'is_active' => 1,
            'created_at' => '2023-09-22 13:05:36',
            'updated_at' => '2023-09-22 13:05:36'
        ]);

        Religion::create([
            'id' => 5,
            'religion_name' => 'Buddhist',
            'is_active' => 1,
            'created_at' => '2023-09-22 13:05:36',
            'updated_at' => '2023-09-22 13:05:36'
        ]);

        Religion::create([
            'id' => 6,
            'religion_name' => 'Jain',
            'is_active' => 1,
            'created_at' => '2023-09-22 13:05:36',
            'updated_at' => '2023-09-22 13:05:36'
        ]);

        Religion::create([
            'id' => 7,
            'religion_name' => 'Other Religion',
            'is_active' => 1,
            'created_at' => '2023-09-22 13:05:36',
            'updated_at' => '2023-09-22 13:05:36'
        ]);


    }
}
