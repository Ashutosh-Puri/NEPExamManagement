<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\College;
use App\Models\Capmaster;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CapmasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Capmaster::create( [
            'id'=>1,
            'cap_name'=>'Arts CAP',
            'place'=>'PB-01',
            'exam_id'=>8,
            'status'=>0,
            'college_id'=>1,
            'created_at'=>'2023-01-03 17:01:00',
            'updated_at'=>'2023-01-03 17:01:00',
            'deleted_at'=>NULL
            ] );
                        
            Capmaster::create( [
            'id'=>2,
            'cap_name'=>'Commerce CAP',
            'place'=>'PB-02',
            'exam_id'=>8,
            'status'=>0,
            'college_id'=>1,
            'created_at'=>'2023-01-03 17:01:00',
            'updated_at'=>'2023-01-03 17:01:00',
            'deleted_at'=>NULL
            ] );
                        
            Capmaster::create( [
            'id'=>3,
            'cap_name'=>'Science CAP',
            'place'=>'PB-03',
            'exam_id'=>8,
            'status'=>0,
            'college_id'=>1,
            'created_at'=>'2023-01-03 17:01:00',
            'updated_at'=>'2023-01-03 17:01:00',
            'deleted_at'=>NULL
            ] );
                        
            Capmaster::create( [
            'id'=>4,
            'cap_name'=>'Arts CAP',
            'place'=>'Geography Hall',
            'exam_id'=>9,
            'status'=>0,
            'college_id'=>1,
            'created_at'=>'2023-05-08 17:01:00',
            'updated_at'=>'2023-05-08 17:01:00',
            'deleted_at'=>NULL
            ] );
                        
            Capmaster::create( [
            'id'=>5,
            'cap_name'=>'Commerce CAP',
            'place'=>'Geography Hall',
            'exam_id'=>9,
            'status'=>0,
            'college_id'=>1,
            'created_at'=>'2023-05-08 17:01:00',
            'updated_at'=>'2023-05-08 17:01:00',
            'deleted_at'=>NULL
            ] );
                        
            Capmaster::create( [
            'id'=>6,
            'cap_name'=>'Science CAP',
            'place'=>'B.Voc. SD Dept.',
            'exam_id'=>9,
            'status'=>0,
            'college_id'=>1,
            'created_at'=>'2023-05-08 07:07:00',
            'updated_at'=>'2023-05-08 07:07:00',
            'deleted_at'=>NULL
            ] );
                        
            Capmaster::create( [
            'id'=>7,
            'cap_name'=>'Arts CAP',
            'place'=>'PB-01',
            'exam_id'=>11,
            'status'=>1,
            'college_id'=>1,
            'created_at'=>'2023-05-08 17:01:00',
            'updated_at'=>'2023-05-08 17:01:00',
            'deleted_at'=>NULL
            ] );
                        
            Capmaster::create( [
            'id'=>8,
            'cap_name'=>'Commerce CAP',
            'place'=>'PB-02',
            'exam_id'=>11,
            'status'=>1,
            'college_id'=>1,
            'created_at'=>'2023-01-03 17:01:00',
            'updated_at'=>'2023-01-03 17:01:00',
            'deleted_at'=>NULL
            ] );
                        
            Capmaster::create( [
            'id'=>9,
            'cap_name'=>'Science CAP',
            'place'=>'PB-03',
            'exam_id'=>11,
            'status'=>1,
            'college_id'=>1,
            'created_at'=>'2023-01-03 17:01:00',
            'updated_at'=>'2023-01-03 17:01:00',
            'deleted_at'=>NULL
            ] );
    }
}
