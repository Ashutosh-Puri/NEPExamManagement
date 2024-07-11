<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Studenthelplinequery;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StudentHelplineQuerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $q1 = Studenthelplinequery::create([
            'query_name' => 'Student Name',
            'is_active' => 1,
        ]);

        $q2 = Studenthelplinequery::create([
            'query_name' => 'Mobile Number',
            'is_active' => 1,
        ]);

        $q3 = Studenthelplinequery::create([
            'query_name' => 'Email ID',
            'is_active' => 1,
        ]);

        $q4 = Studenthelplinequery::create([
            'query_name' => 'Mother Name',
            'is_active' => 1,
        ]);

        $q4->studenthelplinedocuments()->create([
            'document_name' => 'Result / LC / .etc',
            'is_active' => 1,
        ]);

        $q5 = Studenthelplinequery::create([
            'query_name' => 'Result Correction',
            'is_active' => 1,
        ]);

        $q5->studenthelplinedocuments()->create([
            'document_name' => 'Result',
            'is_active' => 1,
        ]);

        $q6 = Studenthelplinequery::create([
            'query_name' => 'Performance Cancel',
            'is_active' => 1,
        ]);

        $q7 = Studenthelplinequery::create([
            'query_name' => 'Result Activation',
            'is_active' => 1,
        ]);

        $q8 = Studenthelplinequery::create([
            'query_name' => 'Pending Fee',
            'is_active' => 1,
        ]);

        $q9 = Studenthelplinequery::create([
            'query_name' => 'Exam Form Correction',
            'is_active' => 1,
        ]);

        $q9->studenthelplinedocuments()->create([
            'document_name' => 'Exam Form',
            'is_active' => 1,
        ]);

        $q10 = Studenthelplinequery::create([
            'query_name' => 'Exam Form Subjcet',
            'is_active' => 1,
        ]);

        $q10->studenthelplinedocuments()->create([
            'document_name' => 'Exam Form',
            'is_active' => 1,
        ]);
    }
}
