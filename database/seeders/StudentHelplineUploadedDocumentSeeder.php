<?php

namespace Database\Seeders;

use App\Models\Studenthelpline;
use Illuminate\Database\Seeder;
use App\Models\Studenthelplinedocument;
use App\Models\Studenthelplineuploadeddocument;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;

class StudentHelplineUploadedDocumentSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $helplineIds = Studenthelpline::pluck('id')->toArray();
        $documentIds = Studenthelplinedocument::pluck('id')->toArray();

        for ($i = 0; $i < 100; $i++) {
            Studenthelplineuploadeddocument::create([
                'student_helpline_id' => $faker->randomElement($helplineIds),
                'helpline_document_id' => $faker->randomElement($documentIds),
                'helpline_document_path' => $faker->imageUrl($width = 640, $height = 480, 'nature'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
