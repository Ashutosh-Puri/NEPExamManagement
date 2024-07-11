<?php

namespace Database\Seeders;

use App\Models\Bloodgroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BloodgroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Bloodgroup::create([
            'id' => 1,
            'bloodgroup' => 'O+',
            'is_active' => 1,
            'created_at' => '2023-09-22 13:05:36',
            'updated_at' => '2023-09-22 13:05:36'
        ]);

        Bloodgroup::create([
            'id' => 2,
            'bloodgroup' => 'O-',
            'is_active' => 1,
            'created_at' => '2023-09-22 13:05:36',
            'updated_at' => '2023-09-22 13:05:36'
        ]);

        Bloodgroup::create([
            'id' => 3,
            'bloodgroup' => 'A+',
            'is_active' => 1,
            'created_at' => '2023-09-22 13:05:36',
            'updated_at' => '2023-09-22 13:05:36'
        ]);

        Bloodgroup::create([
            'id' => 4,
            'bloodgroup' => 'A-',
            'is_active' => 1,
            'created_at' => '2023-09-22 13:05:36',
            'updated_at' => '2023-09-22 13:05:36'
        ]);

        Bloodgroup::create([
            'id' => 5,
            'bloodgroup' => 'B+',
            'is_active' => 1,
            'created_at' => '2023-09-22 13:05:36',
            'updated_at' => '2023-09-22 13:05:36'
        ]);

        Bloodgroup::create([
            'id' => 6,
            'bloodgroup' => 'B-',
            'is_active' => 1,
            'created_at' => '2023-09-22 13:05:36',
            'updated_at' => '2023-09-22 13:05:36'
        ]);

        Bloodgroup::create([
            'id' => 7,
            'bloodgroup' => 'AB+',
            'is_active' => 1,
            'created_at' => '2023-09-22 13:05:36',
            'updated_at' => '2023-09-22 13:05:36'
        ]);

        Bloodgroup::create([
            'id' => 8,
            'bloodgroup' => 'AB-',
            'is_active' => 1,
            'created_at' => '2023-09-22 13:05:36',
            'updated_at' => '2023-09-22 13:05:36'
        ]);

    }
}
