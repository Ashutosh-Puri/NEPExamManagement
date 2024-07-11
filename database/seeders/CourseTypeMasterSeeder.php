<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coursetypemaster;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CourseTypeMasterSeeder extends Seeder
{
    public function run(): void
    {
        Coursetypemaster::create([
            'course_type' => 'UG',
            'created_at' => '2023-10-01 10:17:33',
            'updated_at' => '2023-10-01 10:17:33'
        ]);
        Coursetypemaster::create([
            'course_type' => 'PG',
            'created_at' => '2023-10-01 10:17:33',
            'updated_at' => '2023-10-01 10:17:33'
        ]);
    }
}
