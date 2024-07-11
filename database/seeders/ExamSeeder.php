<?php

namespace Database\Seeders;

use App\Models\Exam;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Exam::create( [
            'id'=>1,
            'exam_name'=>'OCT-NOV 2020',
            'month'=>'OCTOBER',
            'status'=>0,
            'academicyear_id'=>1,
            'exam_sessions'=>1,
            'deleted_at'=>NULL,
            'created_at'=>'2021-06-02 21:12:00',
            'updated_at'=>'2021-06-08 00:19:00'
            ] );
                        
            Exam::create( [
            'id'=>2,
            'exam_name'=>'Nov-20',
            'month'=>'NOVEMBER',
            'status'=>0,
            'academicyear_id'=>1,
            'exam_sessions'=>1,
            'deleted_at'=>NULL,
            'created_at'=>'2021-06-21 02:44:00',
            'updated_at'=>'2023-08-10 09:08:00'
            ] );
                        
            Exam::create( [
            'id'=>3,
            'exam_name'=>'Mar-21',
            'month'=>'MARCH',
            'status'=>0,
            'academicyear_id'=>1,
            'exam_sessions'=>2,
            'deleted_at'=>NULL,
            'created_at'=>'2021-07-20 03:31:00',
            'updated_at'=>'2023-08-10 09:09:00'
            ] );
                        
            Exam::create( [
            'id'=>4,
            'exam_name'=>'May-21',
            'month'=>'MAY',
            'status'=>0,
            'academicyear_id'=>1,
            'exam_sessions'=>2,
            'deleted_at'=>NULL,
            'created_at'=>'2021-07-20 03:31:00',
            'updated_at'=>'2021-07-20 03:31:00'
            ] );
                        
            Exam::create( [
            'id'=>5,
            'exam_name'=>'Oct-21',
            'month'=>'OCTOBER',
            'status'=>0,
            'academicyear_id'=>2,
            'exam_sessions'=>1,
            'deleted_at'=>NULL,
            'created_at'=>'2021-10-21 03:31:00',
            'updated_at'=>'2023-08-10 08:59:00'
            ] );
                        
            Exam::create( [
            'id'=>7,
            'exam_name'=>'Mar-22',
            'month'=>'MARCH',
            'status'=>0,
            'academicyear_id'=>2,
            'exam_sessions'=>2,
            'deleted_at'=>NULL,
            'created_at'=>'2021-10-21 03:31:00',
            'updated_at'=>'2023-08-10 09:07:00'
            ] );
                        
            Exam::create( [
            'id'=>8,
            'exam_name'=>'Oct-22',
            'month'=>'OCTOBER',
            'status'=>0,
            'academicyear_id'=>3,
            'exam_sessions'=>1,
            'deleted_at'=>NULL,
            'created_at'=>'2022-10-21 03:14:00',
            'updated_at'=>'2023-08-14 06:25:00'
            ] );
                        
            Exam::create( [
            'id'=>9,
            'exam_name'=>'Mar-23',
            'month'=>'MARCH',
            'status'=>0,
            'academicyear_id'=>3,
            'exam_sessions'=>2,
            'deleted_at'=>NULL,
            'created_at'=>'2023-03-20 10:03:00',
            'updated_at'=>'2024-03-02 06:58:00'
            ] );
                        
            Exam::create( [
            'id'=>10,
            'exam_name'=>'Jul-23',
            'month'=>'JULY',
            'status'=>0,
            'academicyear_id'=>3,
            'exam_sessions'=>2,
            'deleted_at'=>NULL,
            'created_at'=>'2023-07-20 10:03:00',
            'updated_at'=>'2024-03-02 06:57:00'
            ] );
                        
            Exam::create( [
            'id'=>11,
            'exam_name'=>'OCT-NOV  2023',
            'month'=>'OCTOBER',
            'status'=>0,
            'academicyear_id'=>4,
            'exam_sessions'=>1,
            'deleted_at'=>NULL,
            'created_at'=>'2023-08-28 10:03:00',
            'updated_at'=>'2024-02-21 11:21:00'
            ] );
                        
            Exam::create( [
            'id'=>12,
            'exam_name'=>'Mar-24',
            'month'=>'MARCH',
            'status'=>1,
            'academicyear_id'=>4,
            'exam_sessions'=>2,
            'deleted_at'=>NULL,
            'created_at'=>'2024-02-02 17:28:00',
            'updated_at'=>'2024-03-05 09:04:00'
            ] );
    }
}
