<?php

namespace Database\Seeders;

use App\Models\Examorder;
use App\Models\Exampanel;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Models\Exampatternclass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ExamorderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Examorder::create([
            'user_id' => 1,
            'exampanel_id' => 1,
            'exam_patternclass_id' => 12,
            'email_status' => 1,
            'token' => 'trwffgte59unfjfdjksvf',
        ]);

        Examorder::create([
            'user_id' => 1,
            'exampanel_id' => 2,
            'exam_patternclass_id' => 12,
            'email_status' => 1,
            'token' => 'eurhvnxmrj467bvd',
        ]);

    }
}
