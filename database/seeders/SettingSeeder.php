<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->insert([
            'college_id' => 1,
            'user_id' => 1,
            'show_abcid' => 1,
            'abcid_required' => 0,
            'statement_of_marks_is_year_wise' => 1,
            'question_paper_apply_watermark' => 1,
            'exam_time_interval' => 120,
            'question_paper_pdf_master_password' => 'Admin@Password',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
