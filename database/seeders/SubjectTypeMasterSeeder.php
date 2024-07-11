<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subjecttypemaster;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubjectTypeMasterSeeder extends Seeder
{
    public function run(): void
    {
        Subjecttypemaster::create([
            'subjectcategory_id' => 1,
            'subjecttype_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);
        Subjecttypemaster::create([
            'subjectcategory_id' => 1,
            'subjecttype_id' => 4,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);
        Subjecttypemaster::create([
            'subjectcategory_id' => 1,
            'subjecttype_id' => 6,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);
        Subjecttypemaster::create([
            'subjectcategory_id' => 1,
            'subjecttype_id' => 8,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);
        Subjecttypemaster::create([
            'subjectcategory_id' => 2,
            'subjecttype_id' => 2,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);
        Subjecttypemaster::create([
            'subjectcategory_id' => 2,
            'subjecttype_id' => 7,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);
        Subjecttypemaster::create([
            'subjectcategory_id' => 2,
            'subjecttype_id' => 5,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);
        Subjecttypemaster::create([
            'subjectcategory_id' => 3,
            'subjecttype_id' => 5,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);
        Subjecttypemaster::create([
            'subjectcategory_id' => 3,
            'subjecttype_id' => 6,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);
        Subjecttypemaster::create([
            'subjectcategory_id' => 3,
            'subjecttype_id' => 4,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);
        Subjecttypemaster::create([
            'subjectcategory_id' => 3,
            'subjecttype_id' => 2,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);
    }
}
