<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Internaltoolauditor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InternalToolAuditorSeeder extends Seeder
{
    public function run(): void
    {
        Internaltoolauditor::create([
            'patternclass_id' => 10,
            'faculty_id' => 134,
            'academicyear_id' => 3,
            'evaluationdate' => NULL,
            'status' => 1,
            'created_at' => '2023-02-18 10:46:52',
            'updated_at' => '2023-02-18 10:46:52'
        ]);

        Internaltoolauditor::create([
            'patternclass_id' => 11,
            'faculty_id' => 134,
            'academicyear_id' => 3,
            'evaluationdate' => NULL,
            'status' => 1,
            'created_at' => '2023-02-18 10:46:52',
            'updated_at' => '2023-02-18 10:46:52'
        ]);

        Internaltoolauditor::create([
            'patternclass_id' => 12,
            'faculty_id' => 134,
            'academicyear_id' => 3,
            'evaluationdate' => NULL,
            'status' => 1,
            'created_at' => '2023-02-18 10:46:52',
            'updated_at' => '2023-02-18 10:46:52'
        ]);
    }
}
