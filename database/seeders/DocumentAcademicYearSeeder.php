<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Documentacademicyear;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DocumentAcademicYearSeeder extends Seeder
{
    public function run(): void
    {
        Documentacademicyear::create([
            'year_name' => '2020-21',
            'active' => 0,
            'start_date' => '2023-09-05 21:23:00',
            'end_date' => '2023-09-05 21:23:00',
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);
        Documentacademicyear::create([
            'year_name' => '2021-22',
            'active' => 1,
            'start_date' => '2023-09-05 21:23:00',
            'end_date' => '2023-09-05 21:23:00',
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);
        Documentacademicyear::create([
            'year_name' => '2022-23',
            'active' => 1,
            'start_date' => '2023-09-05 21:23:00',
            'end_date' => '2023-09-05 21:23:00',
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);
        Documentacademicyear::create([
            'year_name' => '2023-24',
            'active' => 1,
            'start_date' => '2023-09-05 21:23:00',
            'end_date' => '2025-04-23 04:00:13',
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);
    }
}
