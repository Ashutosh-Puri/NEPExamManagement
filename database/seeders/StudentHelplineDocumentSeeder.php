<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Studenthelplinequery;
use App\Models\Studenthelplinedocument;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StudentHelplineDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Studenthelplinedocument::create([
        //     'document_name' => 'Result',
        //     'student_helpline_query_id'=>Studenthelplinequery::inRandomOrder()->first()->id,
        //     'is_active' => 1,
        // ]);

        // Studenthelplinedocument::create([
        //     'document_name' => 'Aadhar Card',
        //     'student_helpline_query_id'=>Studenthelplinequery::inRandomOrder()->first()->id,
        //     'is_active' => 1,
        // ]);

        // Studenthelplinedocument::create([
        //     'document_name' => 'Pan  Card',
        //     'student_helpline_query_id'=>Studenthelplinequery::inRandomOrder()->first()->id,
        //     'is_active' => 1,
        // ]);
    }
}
