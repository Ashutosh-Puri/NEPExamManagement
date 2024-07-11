<?php

namespace Database\Seeders;

use App\Models\Subjectcategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubjectcategorySeeder extends Seeder
{
    public function run(): void
    {
        Subjectcategory::create([
            'id' => 1,
            'subjectcategory' => 'Theory',
            'subjectcategory_shortname' => 'TH',
            'active' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        Subjectcategory::create([
            'id' => 2,
            'subjectcategory' => 'Practical',
            'subjectcategory_shortname' => 'PR',
            'active' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        Subjectcategory::create([
            'id' => 3,
            'subjectcategory' => 'Project',
            'subjectcategory_shortname' => 'PRJ',
            'active' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        Subjectcategory::create([
            'id' => 4,
            'subjectcategory' => 'Skill',
            'subjectcategory_shortname' => 'SKILL',
            'active' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        Subjectcategory::create([
            'id' => 5,
            'subjectcategory' => 'Grade',
            'subjectcategory_shortname' => 'GRD',
            'active' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        Subjectcategory::create([
            'id' => 6,
            'subjectcategory' => 'Theory/Practical',
            'subjectcategory_shortname' => 'TH/PR',
            'active' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        Subjectcategory::create([
            'id' => 7,
            'subjectcategory' => 'EVS',
            'subjectcategory_shortname' => 'EVS',
            'active' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);
    }
}
