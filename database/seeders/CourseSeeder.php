<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Coursecategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::create( [
            'id'=>1,
            'course_name'=>'B.A.',
            'course_code'=>'101',
            'fullname'=>'Bachelor of Arts',
            'special_subject'=>NULL,
            'course_type'=>'UG',
            'college_id'=>1,
            'programme_id'=>1,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>2,
            'course_name'=>'B.Com.',
            'course_code'=>'102',
            'fullname'=>'Bachelor of Commerce',
            'special_subject'=>NULL,
            'course_type'=>'UG',
            'college_id'=>1,
            'programme_id'=>2,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>3,
            'course_name'=>'B.Sc.',
            'course_code'=>'103',
            'fullname'=>'Bachelor of Science',
            'special_subject'=>NULL,
            'course_type'=>'UG',
            'college_id'=>1,
            'programme_id'=>3,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>4,
            'course_name'=>'B.Sc.(Computer Science )',
            'course_code'=>'104',
            'fullname'=>'Bachelor of Computer Science',
            'special_subject'=>NULL,
            'course_type'=>'UG',
            'college_id'=>1,
            'programme_id'=>3,
            'course_category_id'=>1,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>5,
            'course_name'=>'B.C.A.(Science)',
            'course_code'=>'105',
            'fullname'=>'Bachelor of Computer Applications (Science)',
            'special_subject'=>NULL,
            'course_type'=>'UG',
            'college_id'=>1,
            'programme_id'=>3,
            'course_category_id'=>1,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>6,
            'course_name'=>'B.B.A.',
            'course_code'=>'106',
            'fullname'=>'Bachelor of Business Administration',
            'special_subject'=>NULL,
            'course_type'=>'UG',
            'college_id'=>1,
            'programme_id'=>5,
            'course_category_id'=>1,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>7,
            'course_name'=>'B.B.A.(CA)',
            'course_code'=>'107',
            'fullname'=>'Bachelor of Business Administration(Computer Application)',
            'special_subject'=>NULL,
            'course_type'=>'UG',
            'college_id'=>1,
            'programme_id'=>5,
            'course_category_id'=>1,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>8,
            'course_name'=>'B.Voc.(Software Development)',
            'course_code'=>'108',
            'fullname'=>'Bachelor Vocation',
            'special_subject'=>'Software Development',
            'course_type'=>'UG',
            'college_id'=>1,
            'programme_id'=>4,
            'course_category_id'=>1,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>9,
            'course_name'=>'B.Voc.(Hospitality & Tourism)',
            'course_code'=>'109',
            'fullname'=>'Bachelor Vocation',
            'special_subject'=>'Hospitality & Tourism',
            'course_type'=>'UG',
            'college_id'=>1,
            'programme_id'=>4,
            'course_category_id'=>1,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>10,
            'course_name'=>'B.Voc.(Dairy Product & Processing)',
            'course_code'=>'110',
            'fullname'=>'Bachelor Vocation',
            'special_subject'=>'Dairy Product & Processing',
            'course_type'=>'UG',
            'college_id'=>1,
            'programme_id'=>4,
            'course_category_id'=>1,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>11,
            'course_name'=>'B.Voc.(Account & Taxation)',
            'course_code'=>'111',
            'fullname'=>'Bachelor Vocation',
            'special_subject'=>'Account & Taxation',
            'course_type'=>'UG',
            'college_id'=>1,
            'programme_id'=>4,
            'course_category_id'=>1,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>12,
            'course_name'=>'B.Voc.(Agriculture and soil Science)',
            'course_code'=>'112',
            'fullname'=>'Bachelor Vocation',
            'special_subject'=>'Agriculture and soil Science',
            'course_type'=>'UG',
            'college_id'=>1,
            'programme_id'=>4,
            'course_category_id'=>1,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>14,
            'course_name'=>'M.A. ECONOMICS',
            'course_code'=>'114',
            'fullname'=>'Master of Arts',
            'special_subject'=>'ECONOMICS',
            'course_type'=>'PG',
            'college_id'=>1,
            'programme_id'=>1,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>15,
            'course_name'=>'M.A. ENGLISH',
            'course_code'=>'115',
            'fullname'=>'Master of Arts',
            'special_subject'=>'ENGLISH',
            'course_type'=>'PG',
            'college_id'=>1,
            'programme_id'=>1,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>16,
            'course_name'=>'M.A. HINDI',
            'course_code'=>'116',
            'fullname'=>'Master of Arts',
            'special_subject'=>'HINDI',
            'course_type'=>'PG',
            'college_id'=>1,
            'programme_id'=>1,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>17,
            'course_name'=>'M.A. MARATHI',
            'course_code'=>'117',
            'fullname'=>'Master of Arts',
            'special_subject'=>'MARATHI',
            'course_type'=>'PG',
            'college_id'=>1,
            'programme_id'=>1,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>18,
            'course_name'=>'M.A. POLITICS',
            'course_code'=>'118',
            'fullname'=>'Master of Arts',
            'special_subject'=>'POLITICS',
            'course_type'=>'PG',
            'college_id'=>1,
            'programme_id'=>1,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>19,
            'course_name'=>'M.A. SANSKRIT',
            'course_code'=>'119',
            'fullname'=>'Master of Arts',
            'special_subject'=>'SANSKRIT',
            'course_type'=>'PG',
            'college_id'=>1,
            'programme_id'=>1,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>20,
            'course_name'=>'M.Sc. ZOOLOGY',
            'course_code'=>'120',
            'fullname'=>'Master of Science',
            'special_subject'=>'ZOOLOGY',
            'course_type'=>'PG',
            'college_id'=>1,
            'programme_id'=>3,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>21,
            'course_name'=>'M.Sc. COMPUTER SCIENCE',
            'course_code'=>'121',
            'fullname'=>'Master of Science',
            'special_subject'=>'COMPUTER SCIENCE',
            'course_type'=>'PG',
            'college_id'=>1,
            'programme_id'=>3,
            'course_category_id'=>1,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>22,
            'course_name'=>'M.Sc.BOTANY',
            'course_code'=>'122',
            'fullname'=>'Master of Science',
            'special_subject'=>'BOTANY',
            'course_type'=>'PG',
            'college_id'=>1,
            'programme_id'=>3,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>23,
            'course_name'=>'M.Sc. ANALYTICAL CHEMISTRY',
            'course_code'=>'123',
            'fullname'=>'Master of Science',
            'special_subject'=>'ANALYTICAL CHEMISTRY',
            'course_type'=>'PG',
            'college_id'=>1,
            'programme_id'=>3,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>24,
            'course_name'=>'M.Sc. ELECTRONICS',
            'course_code'=>'124',
            'fullname'=>'Master of Science',
            'special_subject'=>'ELECTRONICS',
            'course_type'=>'PG',
            'college_id'=>1,
            'programme_id'=>3,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>25,
            'course_name'=>'M.Sc. GEOGRAPHY',
            'course_code'=>'125',
            'fullname'=>'Master of Science',
            'special_subject'=>'GEOGRAPHY',
            'course_type'=>'PG',
            'college_id'=>1,
            'programme_id'=>3,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>26,
            'course_name'=>'M.Sc. MATHEMATICS',
            'course_code'=>'126',
            'fullname'=>'Master of Science',
            'special_subject'=>'MATHEMATICS',
            'course_type'=>'PG',
            'college_id'=>1,
            'programme_id'=>3,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>27,
            'course_name'=>'M.Sc. ORGANIC CHEMISTRY',
            'course_code'=>'127',
            'fullname'=>'Master of Science',
            'special_subject'=>'ORGANIC CHEMISTRY',
            'course_type'=>'PG',
            'college_id'=>1,
            'programme_id'=>3,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>28,
            'course_name'=>'M.Sc. PHYSICS',
            'course_code'=>'128',
            'fullname'=>'Master of Science',
            'special_subject'=>'PHYSICS',
            'course_type'=>'PG',
            'college_id'=>1,
            'programme_id'=>3,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>29,
            'course_name'=>'M. COM. (ADV. CO. A/CING & COST SYS.)',
            'course_code'=>'129',
            'fullname'=>'Master Of Commerce',
            'special_subject'=>'ADV. CO. A/CING & COST SYS.',
            'course_type'=>'PG',
            'college_id'=>1,
            'programme_id'=>2,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>30,
            'course_name'=>'M.COM.  (ADV. A/CING & TAXATION )',
            'course_code'=>'130',
            'fullname'=>'Master Of Commerce',
            'special_subject'=>'ADV. A/CING & TAXATION ',
            'course_type'=>'PG',
            'college_id'=>1,
            'programme_id'=>2,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>31,
            'course_name'=>'M.COM. (BUSINESS ADMINISTRATION)',
            'course_code'=>'131',
            'fullname'=>'Master Of Commerce',
            'special_subject'=>'BUSINESS ADMINISTRATION',
            'course_type'=>'PG',
            'college_id'=>1,
            'programme_id'=>2,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>32,
            'course_name'=>'Ph.D.',
            'course_code'=>'1001',
            'fullname'=>'Doctor of Philosophy',
            'special_subject'=>NULL,
            'course_type'=>'PHD',
            'college_id'=>1,
            'programme_id'=>5,
            'course_category_id'=>3,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>33,
            'course_name'=>'Ph.D.',
            'course_code'=>'1001',
            'fullname'=>'Doctor of Philosophy',
            'special_subject'=>'MARATHI',
            'course_type'=>'PHD',
            'college_id'=>1,
            'programme_id'=>5,
            'course_category_id'=>3,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>34,
            'course_name'=>'Ph.D.',
            'course_code'=>'1003',
            'fullname'=>'Doctor of Philosophy',
            'special_subject'=>'SANSKRIT',
            'course_type'=>'PHD',
            'college_id'=>1,
            'programme_id'=>5,
            'course_category_id'=>3,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>35,
            'course_name'=>' B.A. MARATHI',
            'course_code'=>NULL,
            'fullname'=>'Bachelor of Arts Marathi',
            'special_subject'=>'Marathi',
            'course_type'=>'UG',
            'college_id'=>1,
            'programme_id'=>1,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>36,
            'course_name'=>' B.A. ENGLISH',
            'course_code'=>NULL,
            'fullname'=>'Bachelor of Arts  English',
            'special_subject'=>'English',
            'course_type'=>'UG',
            'college_id'=>1,
            'programme_id'=>1,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>37,
            'course_name'=>' B.A. POLITICS',
            'course_code'=>NULL,
            'fullname'=>'Bachelor of Arts Politics',
            'special_subject'=>'Politics',
            'course_type'=>'UG',
            'college_id'=>1,
            'programme_id'=>1,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>38,
            'course_name'=>' B.A. HINDI',
            'course_code'=>NULL,
            'fullname'=>'Bachelor of Arts Hindi',
            'special_subject'=>'Hindi',
            'course_type'=>'UG',
            'college_id'=>1,
            'programme_id'=>1,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>39,
            'course_name'=>' B.A. ECONOMICS',
            'course_code'=>NULL,
            'fullname'=>'Bachelor of Arts Economics',
            'special_subject'=>'Economics',
            'course_type'=>'UG',
            'college_id'=>1,
            'programme_id'=>1,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>40,
            'course_name'=>' B.A. GEOGRAPHY',
            'course_code'=>NULL,
            'fullname'=>'Bachelor of Arts Geography',
            'special_subject'=>' Geography',
            'course_type'=>'UG',
            'college_id'=>1,
            'programme_id'=>1,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>41,
            'course_name'=>' B.A. YOGA',
            'course_code'=>NULL,
            'fullname'=>'Bachelor of Arts Yoga',
            'special_subject'=>'Yoga',
            'course_type'=>'UG',
            'college_id'=>1,
            'programme_id'=>1,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>42,
            'course_name'=>' B.A. SANSKRIT',
            'course_code'=>NULL,
            'fullname'=>'Bachelor of Arts Sanskrit',
            'special_subject'=>'Sanskrit',
            'course_type'=>'UG',
            'college_id'=>1,
            'programme_id'=>1,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>43,
            'course_name'=>'B.A. PHILOSOPHY',
            'course_code'=>NULL,
            'fullname'=>'Bachelor of Arts Philosophy',
            'special_subject'=>'Philosophy',
            'course_type'=>'UG',
            'college_id'=>1,
            'programme_id'=>1,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>44,
            'course_name'=>'B.COM. BANKING & FINANCE',
            'course_code'=>NULL,
            'fullname'=>'Bachelor of Commerce  Banking & Finance',
            'special_subject'=>' Banking & Finance',
            'course_type'=>'UG',
            'college_id'=>1,
            'programme_id'=>2,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>45,
            'course_name'=>'B.COM. BUSINESS ADMINISTRATION',
            'course_code'=>NULL,
            'fullname'=>'Bachelor of Commerce Businees Administration',
            'special_subject'=>'Businees Administration',
            'course_type'=>'UG',
            'college_id'=>1,
            'programme_id'=>2,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>46,
            'course_name'=>'B.COM. ACCOUNTING & TAXATION',
            'course_code'=>NULL,
            'fullname'=>'Bachelor of Commerce Accounting & Taxation',
            'special_subject'=>' Accounting & Taxation',
            'course_type'=>'UG',
            'college_id'=>1,
            'programme_id'=>2,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>47,
            'course_name'=>'B.Sc. CHEMISTRY',
            'course_code'=>NULL,
            'fullname'=>'Bachelor of Science Chemistry',
            'special_subject'=>'Chemistry',
            'course_type'=>'UG',
            'college_id'=>1,
            'programme_id'=>3,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>48,
            'course_name'=>'B.Sc. ELECTRONICS',
            'course_code'=>NULL,
            'fullname'=>'Bachelor of Science Electronics',
            'special_subject'=>' Electronics',
            'course_type'=>'UG',
            'college_id'=>1,
            'programme_id'=>3,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>49,
            'course_name'=>'B.Sc.  MATHEMATICS',
            'course_code'=>NULL,
            'fullname'=>'Bachelor of Science Mathematics',
            'special_subject'=>'Mathematics',
            'course_type'=>'UG',
            'college_id'=>1,
            'programme_id'=>3,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>50,
            'course_name'=>'B.Sc.  PHYSICS',
            'course_code'=>NULL,
            'fullname'=>'Bachelor of Science Physics',
            'special_subject'=>'Physics',
            'course_type'=>'UG',
            'college_id'=>1,
            'programme_id'=>3,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>51,
            'course_name'=>'B.Sc. MICROBIOLOGY',
            'course_code'=>NULL,
            'fullname'=>'Bachelor of Science Microbiology',
            'special_subject'=>' Microbiology',
            'course_type'=>'UG',
            'college_id'=>1,
            'programme_id'=>3,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>52,
            'course_name'=>'B.Sc.  BOTANY',
            'course_code'=>NULL,
            'fullname'=>'Bachelor of Science Botany',
            'special_subject'=>'Botany',
            'course_type'=>'UG',
            'college_id'=>1,
            'programme_id'=>3,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );

            Course::create( [
            'id'=>53,
            'course_name'=>'B.Sc.  ZOOLOGY',
            'course_code'=>NULL,
            'fullname'=>'Bachelor of Science Zoology',
            'special_subject'=>'Zoology',
            'course_type'=>'UG',
            'college_id'=>1,
            'programme_id'=>3,
            'course_category_id'=>2,
            'created_at'=>'2024-02-05 01:27:00',
            'updated_at'=>'2024-02-05 01:27:00',
            'deleted_at'=>NULL
            ] );
    }
}
