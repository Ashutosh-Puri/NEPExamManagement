<?php

namespace Database\Seeders;

use App\Models\Gendermaster;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GendermasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Gendermaster::create([
            'gender' => 'Male',
            'gender_shortform' => 'M',
            'is_active' => 1,
            'created_at' => '2023-09-25 05:10:43',
            'updated_at' => '2023-09-25 05:10:43'
        ]);

        Gendermaster::create([
            'gender' => 'Female',
            'gender_shortform' => 'F',
            'is_active' => 1,
            'created_at' => '2023-09-25 05:10:43',
            'updated_at' => '2023-09-25 05:10:43'
        ]);

        Gendermaster::create([
            'gender' => 'Transgender',
            'gender_shortform' => 'T',
            'is_active' => 0,
            'created_at' => '2023-09-25 05:10:43',
            'updated_at' => '2023-09-25 05:10:43'
        ]);
    }
}
