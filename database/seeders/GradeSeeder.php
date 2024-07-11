<?php

namespace Database\Seeders;

use App\Models\Gradepoint;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Gradepoint::create( [
            'id'=>1,
            'max_percentage'=>100,
            'min_percentage'=>90,
            'grade_point'=>10,
            'grade_name'=>'O',
            'description'=>'Outstanding  ',
            'is_active'=>1,
             'created_at'=>'2024-04-26 09:26:34',
            'updated_at'=>'2024-04-26 09:26:34',
            'deleted_at'=>NULL,
            ] );
                        
            Gradepoint::create( [
            'id'=>2,
            'max_percentage'=>89,
            'min_percentage'=>75,
            'grade_point'=>9,
            'grade_name'=>'A+',
            'description'=>'Excellent',
            'is_active'=>1,
            'created_at'=>'2024-04-26 09:26:34',
            'updated_at'=>'2024-04-26 09:26:34',
            'deleted_at'=>NULL,
            ] );
                        
            Gradepoint::create( [
            'id'=>3,
            'max_percentage'=>74,
            'min_percentage'=>60,
            'grade_point'=>8,
            'grade_name'=>'A',
            'description'=>'Very Good ',
            'is_active'=>1,
             'created_at'=>'2024-04-26 09:26:34',
            'updated_at'=>'2024-04-26 09:26:34',
            'deleted_at'=>NULL,
            ] );
                        
            Gradepoint::create( [
            'id'=>4,
            'max_percentage'=>59,
            'min_percentage'=>55,
            'grade_point'=>7,
            'grade_name'=>'B+',
            'description'=>'Good',
            'is_active'=>1,
             'created_at'=>'2024-04-26 09:26:34',
            'updated_at'=>'2024-04-26 09:26:34',
            'deleted_at'=>NULL,
            ] );
                        
            Gradepoint::create( [
            'id'=>5,
            'max_percentage'=>54,
            'min_percentage'=>50,
            'grade_point'=>6,
            'grade_name'=>'B',
            'description'=>'Above Average ',
            'is_active'=>1,
             'created_at'=>'2024-04-26 09:26:34',
            'updated_at'=>'2024-04-26 09:26:34',
            'deleted_at'=>NULL,
            ] );
                        
            Gradepoint::create( [
            'id'=>6,
            'max_percentage'=>49,
            'min_percentage'=>45,
            'grade_point'=>5,
            'grade_name'=>'C',
            'description'=>'Average ',
            'is_active'=>1,
             'created_at'=>'2024-04-26 09:26:34',
            'updated_at'=>'2024-04-26 09:26:34',
            'deleted_at'=>NULL,
            ] );
                        
            Gradepoint::create( [
            'id'=>7,
            'max_percentage'=>44,
            'min_percentage'=>40,
            'grade_point'=>4,
            'grade_name'=>'D',
            'description'=>'Pass',
            'is_active'=>1,
             'created_at'=>'2024-04-26 09:26:34',
            'updated_at'=>'2024-04-26 09:26:34',
            'deleted_at'=>NULL,
            ] );
                        
            Gradepoint::create( [
            'id'=>8,
            'max_percentage'=>39,
            'min_percentage'=>0,
            'grade_point'=>0,
            'grade_name'=>'F',
            'description'=>'Fail',
            'is_active'=>1,
             'created_at'=>'2024-04-26 09:26:34',
            'updated_at'=>'2024-04-26 09:26:34',
            'deleted_at'=>NULL,
            ] );
                        
            Gradepoint::create( [
            'id'=>9,
            'max_percentage'=>-1,
            'min_percentage'=>-1,
            'grade_point'=>0,
            'grade_name'=>'FX ',
            'description'=>'Detained, Repeat the Course ',
            'is_active'=>1,
             'created_at'=>'2024-04-26 09:26:34',
            'updated_at'=>'2024-04-26 09:26:34',
            'deleted_at'=>NULL,
            ] );
                        
            Gradepoint::create( [
            'id'=>10,
            'max_percentage'=>-1,
            'min_percentage'=>-1,
            'grade_point'=>0,
            'grade_name'=>'II ',
            'description'=>'Incomplete -- Absent for Exam but continue for the',
            'is_active'=>1,
             'created_at'=>'2024-04-26 09:26:34',
            'updated_at'=>'2024-04-26 09:26:34',
            'deleted_at'=>NULL,
            ] );
                        
            Gradepoint::create( [
            'id'=>11,
            'max_percentage'=>-1,
            'min_percentage'=>-1,
            'grade_point'=>0,
            'grade_name'=>'PP ',
            'description'=>'Passed (Only for non credit courses) ',
            'is_active'=>1,
             'created_at'=>'2024-04-26 09:26:34',
            'updated_at'=>'2024-04-26 09:26:34',
            'deleted_at'=>NULL,
            ] );
                        
            Gradepoint::create( [
            'id'=>12,
            'max_percentage'=>-1,
            'min_percentage'=>-1,
            'grade_point'=>0,
            'grade_name'=>'NP ',
            'description'=>'Not Passed (Only for non credit courses) ',
            'is_active'=>1,
             'created_at'=>'2024-04-26 09:26:34',
            'updated_at'=>'2024-04-26 09:26:34',
            'deleted_at'=>NULL,
            ] );
    }
}
