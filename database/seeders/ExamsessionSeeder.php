<?php

namespace Database\Seeders;

use App\Models\Examsession;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ExamsessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Examsession::create( [
            'id' => 1,
            'exam_id'=>12,
            'from_date'=>'2024-04-15',
            'to_date'=>'2024-04-30',
            'from_time'=>'10:00',
            'to_time'=>'13:00',
            'session_type'=>'1'
          ]);

        Examsession::create( [
            'id' => 2,
            'exam_id'=>12,
            'from_date'=>'2024-04-15',
            'to_date'=>'2024-04-30',
            'from_time'=>'14:00',
            'to_time'=>'17:00',
            'session_type'=>'2'
          ]);

        Examsession::create( [
            'id' => 3,
            'exam_id'=>12,
            'from_date'=>'2024-05-02',
            'to_date'=>'2024-05-15',
            'from_time'=>'10:00',
            'to_time'=>'13:00',
            'session_type'=>'1'
          ]);

        Examsession::create( [
            'id' => 4,
            'exam_id'=>12,
            'from_date'=>'2024-05-02',
            'to_date'=>'2024-05-15',
            'from_time'=>'14:00',
            'to_time'=>'17:00',
            'session_type'=>'2'
          ]);

        Examsession::create( [
            'id' => 5,
            'exam_id'=>12,
            'from_date'=>'2024-05-16',
            'to_date'=>'2024-05-29',
            'from_time'=>'10:00',
            'to_time'=>'13:00',
            'session_type'=>'1'
          ]);

        Examsession::create( [
            'id' => 6,
            'exam_id'=>12,
            'from_date'=>'2024-05-16',
            'to_date'=>'2024-05-29',
            'from_time'=>'14:00',
            'to_time'=>'17:00',
            'session_type'=>'2'
          ]);

        
          
    }
}
