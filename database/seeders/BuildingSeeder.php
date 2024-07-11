<?php

namespace Database\Seeders;

use App\Models\Building;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Building::create([
            'id' => 1,
            'building_name' => 'PB',
            'priority' => 'High',
            'status' => 1
        ]);
        Building::create([
            'id' => 2,
            'building_name' => 'CM',
            'priority' => 'Low',
            'status' => 1
        ]);

        Building::create([
            'id' => 3,
            'building_name' => 'BCS',
            'priority' => 'min',
            'status' => 1
        ]);

        Building::create([
            'id' => 4,
            'building_name' => 'RB',
            'priority' => 'max',
            'status' => 1
        ]);

        Building::create([
            'id' => 5,
            'building_name' => 'PH',
            'priority' => 'max',
            'status' => 1
        ]);

        Building::create([
            'id' => 6,
            'building_name' => 'SB',
            'priority' => 'max',
            'status' => 1
        ]);

        Building::create([
            'id' => 7,
            'building_name' => 'NEW',
            'priority' => 'max',
            'status' => 1
        ]);
    }
}
