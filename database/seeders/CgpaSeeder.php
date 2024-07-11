<?php

namespace Database\Seeders;

use App\Models\Cgpa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CgpaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cgpa::create([
            'id' => 1,
            'max_gp' => 9.50,
            'min_gp' => 20.00,
            'grade' => 'O',
            'description' => 'O(Outstanding)',
            'created_at' => '2023-09-05 04:17:49',
            'updated_at' => '2023-09-05 04:17:49'
        ]);



        Cgpa::create([
            'id' => 2,
            'max_gp' => 8.25,
            'min_gp' => 9.50,
            'grade' => 'A+',
            'description' => 'A+(Excellent)',
            'created_at' => '2023-09-05 04:17:49',
            'updated_at' => '2023-09-05 04:17:49'
        ]);

        Cgpa::create([
            'id' => 3,
            'max_gp' => 6.75,
            'min_gp' => 8.25,
            'grade' => 'A ',
            'description' => 'A (Very Good)',
            'created_at' => '2023-09-05 04:17:49',
            'updated_at' => '2023-09-05 04:17:49'
        ]);



        Cgpa::create([
            'id' => 4,
            'max_gp' => 5.75,
            'min_gp' => 6.75,
            'grade' => 'B+',
            'description' => 'B+(Good)',
            'created_at' => '2023-09-05 04:17:49',
            'updated_at' => '2023-09-05 04:17:49'
        ]);



        Cgpa::create([
            'id' => 5,
            'max_gp' => 5.25,
            'min_gp' => 5.75,
            'grade' => 'B',
            'description' => 'B(Above',
            'created_at' => '2023-09-05 04:17:49',
            'updated_at' => '2023-09-05 04:17:49'
        ]);



        Cgpa::create([
            'id' => 6,
            'max_gp' => 4.75,
            'min_gp' => 5.25,
            'grade' => 'C',
            'description' => 'C(Average)',
            'created_at' => '2023-09-05 04:17:49',
            'updated_at' => '2023-09-05 04:17:49'
        ]);



        Cgpa::create([
            'id' => 7,
            'max_gp' => 4.00,
            'min_gp' => 4.75,
            'grade' => 'D',
            'description' => 'D(Pass)',
            'created_at' => '2023-09-05 04:17:49',
            'updated_at' => '2023-09-05 04:17:49'
        ]);
    }
}
