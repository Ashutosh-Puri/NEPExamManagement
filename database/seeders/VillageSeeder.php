<?php

namespace Database\Seeders;

use App\Models\Village;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VillageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Village::create([
            'id' => 1,
            'village_code' => 1,
            'village_name' => 'Sangamner',
            'village_name_local' => 'Sangamner',
            'taluka_id' => 5823,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);
        Village::create([
            'id' => 2,
            'village_code' => 2,
            'village_name' => 'Sangamner Kh',
            'village_name_local' => 'Sangamner Kh',
            'taluka_id' => 5823,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);
        Village::create([
            'id' => 3,
            'village_code' => 3,
            'village_name' => 'Akole',
            'village_name_local' => 'Akole',
            'taluka_id' => 5823,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        Village::create([
            'id' => 4,
            'village_code' => 3,
            'village_name' => 'Akole',
            'village_name_local' => 'Akole',
            'taluka_id' => 7094,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);
    }
}
