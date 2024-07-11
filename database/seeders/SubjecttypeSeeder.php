<?php

namespace Database\Seeders;

use App\Models\Subjecttype;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubjecttypeSeeder extends Seeder
{
    public function run(): void
    {
        Subjecttype::create([
            'id' => 1,
            'type_name' => 'IE',
            'description' => 'INTERNAL & EXTERNAL',
            'is_active' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);
        Subjecttype::create([
            'id' => 2,
            'type_name' => 'IP',
            'description' => 'INTERNAL & PRACTICAL',
            'is_active' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);
        Subjecttype::create([
            'id' => 3,
            'type_name' => 'IG',
            'description' => 'INTERNAL & GRADE',
            'is_active' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);
        Subjecttype::create([
            'id' => 4,
            'type_name' => 'I',
            'description' => 'INTERNAL',
            'is_active' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);
        Subjecttype::create([
            'id' => 5,
            'type_name' => 'P',
            'description' => 'PROJECT & DESERTATION',
            'is_active' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);
        Subjecttype::create([
            'id' => 6,
            'type_name' => 'G',
            'description' => 'ONLY GRADE',
            'is_active' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);
        Subjecttype::create([
            'id' => 7,
            'type_name' => 'IEP',
            'description' => 'INTERNAL PRACTICAL & EXTERNAL PRACTICAL',
            'is_active' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);
        Subjecttype::create([
            'id' => 8,
            'type_name' => 'IEG',
            'description' => 'INTERNAL & EXTERNAL GRADE',
            'is_active' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);


        Subjecttype::create([
            'id' => 9,
            'type_name' => 'E',
            'description' => 'ONLY EXTERNAL',
            'is_active' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);
    }
}
