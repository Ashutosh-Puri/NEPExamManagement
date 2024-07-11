<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Faculty;
use App\Models\Student;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LoginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::create([
        //     'name' => 'user',
        //     'email' => 'user@gmail.com',
        //     'email_verified_at' => now(),
        //     'password' => Hash::make('123456789'),
        //     'remember_token' => Str::random(10),
        //     'college_id' => 1,
        //     'role_id' => 1,
        //     'user_contact_no' => 1234567890,
        // ]);


        $faculty = Faculty::create([
            'id' => 9999999,
            'faculty_name' => 'faculty',
            'email' => 'faculty@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123456789'),
            'remember_token' => Str::random(10),
        ]);

        $faculty->roles()->attach(1, [
            'status' => 1,
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Student::create([
        //     'id'=>999999,
        //     'student_name' => 'Student Name',
        //     'mother_name' =>'Mother Name',
        //     'mobile_no' => '1234567890',
        //     'memid' => '12345',
        //     'email' => 'student@gmail.com',
        //     'email_verified_at' => now(),
        //     'password' => Hash::make('123456789'),
        //     'remember_token' => Str::random(10),
        // ]);


    }
}
