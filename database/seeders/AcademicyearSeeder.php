<?php

namespace Database\Seeders;

use App\Models\Academicyear;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AcademicyearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Academicyear::create([
            'id' => 1,
            'year_name' => '2020-21',
            'active' => 0,
            'created_at' => '2022-04-19 04:00:13',
            'updated_at' => '2022-04-19 04:00:13',
        ]);

        Academicyear::create([
            'id' => 2,
            'year_name' => '2021-22',
            'active' => 0,
            'created_at' => '2022-04-19 04:00:13',
            'updated_at' => '2022-04-19 04:00:13',
        ]);

        Academicyear::create([
            'id' => 3,
            'year_name' => '2022-23',
            'active' => 0,
            'created_at' => '2022-10-28 04:00:13',
            'updated_at' => '2022-10-28 04:00:13',
        ]);

        Academicyear::create([
            'id' => 4,
            'year_name' => '2023-24',
            'active' => 1,
            'created_at' => '2022-10-28 04:00:13',
            'updated_at' => '2022-10-28 04:00:13',
        ]);
    }
}
