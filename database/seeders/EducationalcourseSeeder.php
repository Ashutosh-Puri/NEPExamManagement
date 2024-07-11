<?php

namespace Database\Seeders;

use App\Models\Educationalcourse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EducationalcourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Educationalcourse::create([
            'id' => 1,
            'course_name' => 'SSC',
            'programme_id' => NULL,
            'is_active' => 1,
            'created_at' => '2023-10-01 09:32:52',
            'updated_at' => '2023-10-01 09:32:52'
        ]);

        Educationalcourse::create([
            'id' => 2,
            'course_name' => 'HSC',
            'programme_id' => 1,
            'is_active' => 1,
            'created_at' => '2023-10-01 09:32:52',
            'updated_at' => '2023-10-01 09:32:52'
        ]);

    }
}
