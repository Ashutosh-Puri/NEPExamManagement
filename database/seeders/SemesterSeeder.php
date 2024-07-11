<?php

namespace Database\Seeders;

use App\Models\Semester;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Semester::create([
            'semester' => '1',
            'status' => 1,
            'created_at' => '2023-09-25 05:10:43',
            'updated_at' => '2023-09-25 05:10:43'
        ]);

        Semester::create([
            'semester' => '2',
            'status' => 1,
            'created_at' => '2023-09-25 05:10:43',
            'updated_at' => '2023-09-25 05:10:43'
        ]);

        Semester::create([
            'semester' => '3',
            'status' => 1,
            'created_at' => '2023-09-25 05:10:43',
            'updated_at' => '2023-09-25 05:10:43'
        ]);

        Semester::create([
            'semester' => '4',
            'status' => 1,
            'created_at' => '2023-09-25 05:10:43',
            'updated_at' => '2023-09-25 05:10:43'
        ]);

        Semester::create([
            'semester' => '5',
            'status' => 1,
            'created_at' => '2023-09-25 05:10:43',
            'updated_at' => '2023-09-25 05:10:43'
        ]);

        Semester::create([
            'semester' => '6',
            'status' => 1,
            'created_at' => '2023-09-25 05:10:43',
            'updated_at' => '2023-09-25 05:10:43'
        ]);
    }
}
