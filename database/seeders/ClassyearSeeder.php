<?php

namespace Database\Seeders;

use App\Models\Classyear;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassyearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Classyear::create([
            'id' => 1,
            'classyear_name' => 'F.Y.',
            'class_degree_name' => 'Diploma',
            'status' => '1',
        ]);
        Classyear::create([
            'id' => 2,
            'classyear_name' => 'S.Y.',
            'class_degree_name' => 'Advanced Diploma',
            'status' => '1',
        ]);
        Classyear::create([
            'id' => 3,
            'classyear_name' => 'T.Y.',
            'class_degree_name' => 'Degree',
            'status' => '1',
        ]);
        Classyear::create([
            'id' => 4,
            'classyear_name' => 'I',
            'class_degree_name' => 'Honours',
            'status' => '1',
        ]);
        Classyear::create([
            'id' => 5,
            'classyear_name' => 'II',
            'class_degree_name' => 'Master',
            'status' => '1',
        ]);
    }
}
