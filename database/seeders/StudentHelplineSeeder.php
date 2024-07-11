<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use Faker\Factory as Faker;
use App\Models\Studenthelpline;
use Illuminate\Database\Seeder;
use App\Models\Studenthelplinequery;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StudentHelplineSeeder extends Seeder
{
    public function run()
    {
        //     $faker = Faker::create();

        //     $studentIds = Student::pluck('id')->toArray();
        //     $queryIds = Studenthelplinequery::pluck('id')->toArray();
        //     $userIds = User::pluck('id')->toArray();

        //     for ($i = 0; $i < 100; $i++) 
        //     { 
        //         Studenthelpline::create([
        //             'student_id' => $faker->randomElement($studentIds),
        //             'student_helpline_query_id' => $faker->randomElement($queryIds),
        //             'status' => $faker->randomElement([0, 1, 2, 3, 4]),
        //             'remark' => $faker->sentence,
        //             'old_query' => $faker->sentence,
        //             'new_query' => $faker->sentence,
        //             'description' => $faker->paragraph,
        //             'approve_by' => $faker->randomElement($userIds),
        //             'verified_by' => $faker->randomElement($userIds),
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ]);
        //     }
    }
}
