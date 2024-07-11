<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Database\Seeders\ExamSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\BlockSeeder;
use Database\Seeders\CasteSeeder;
use Database\Seeders\GradeSeeder;
use Database\Seeders\MonthSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\CourseSeeder;
use Database\Seeders\TalukaSeeder;
use Database\Seeders\CollegeSeeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\PatternSeeder;
use Database\Seeders\SansthaSeeder;
use Database\Seeders\SettingSeeder;
use Database\Seeders\StudentSeeder;
use Database\Seeders\SubjectSeeder;
use Database\Seeders\ApplyFeeSeeder;
use Database\Seeders\BuildingSeeder;
use Database\Seeders\DistrictSeeder;
use Database\Seeders\FormTypeSeeder;
use Database\Seeders\PapersetSeeder;
use Database\Seeders\ReligionSeeder;
use Database\Seeders\RoletypeSeeder;
use Database\Seeders\SemesterSeeder;
use Database\Seeders\CapmasterSeeder;
use Database\Seeders\ClassroomSeeder;
use Database\Seeders\ClassyearSeeder;
use Database\Seeders\DepatmentSeeder;
use Database\Seeders\ExamPanelSeeder;
use Database\Seeders\ProgrammeSeeder;
use Database\Seeders\BloodgroupSeeder;
use Database\Seeders\AddresstypeSeeder;
use Database\Seeders\CourseclassSeeder;
use Database\Seeders\DesignationSeeder;
use Database\Seeders\ExambarcodeSeeder;
use Database\Seeders\ExamsessionSeeder;
use Database\Seeders\FacultyHeadSeeder;
use Database\Seeders\FacultyRoleSeeder;
use Database\Seeders\InstructionSeeder;
use Database\Seeders\StudentmarkSeeder;
use Database\Seeders\SubjecttypeSeeder;
use Database\Seeders\AcademicyearSeeder;
use Database\Seeders\GendermasterSeeder;
use Database\Seeders\PatternclassSeeder;
use Database\Seeders\PrefixmasterSeeder;
use Database\Seeders\PreviousYearSeeder;
use Database\Seeders\AdmissionDataSeeder;
use Database\Seeders\CasteCategorySeeder;
use Database\Seeders\DepatmenttypeSeeder;
use Database\Seeders\ExamFeeMasterSeeder;
use Database\Seeders\ExamOrderPostSeeder;
use Database\Seeders\ExamTimeTableSeeder;
use Database\Seeders\SubjectBucketSeeder;
use Database\Seeders\SubjectcreditSeeder;
use Database\Seeders\TimeTableSlotSeeder;
use Database\Seeders\BanknamemasterSeeder;
use Database\Seeders\CourseCategorySeeder;
use Database\Seeders\ExamFeeCoursesSeeder;
use Database\Seeders\ExamformmasterSeeder;
use Database\Seeders\FacultyProfileSeeder;
use Database\Seeders\PaperassesmentSeeder;
use Database\Seeders\StudentProfileSeeder;
use Database\Seeders\BlockallocationSeeder;
use Database\Seeders\BoarduniversitySeeder;
use Database\Seeders\InstructiontypeSeeder;
use Database\Seeders\StudentHelplineSeeder;
use Database\Seeders\SubjectcategorySeeder;
use Database\Seeders\SubjectverticalSeeder;
use Database\Seeders\CourseTypeMasterSeeder;
use Database\Seeders\DepartmentPrefixSeeder;
use Database\Seeders\ExamPatternclassSeeder;
use Database\Seeders\StudentadmissionSeeder;
use Database\Seeders\EducationalcourseSeeder;
use Database\Seeders\ExamstudentseatnoSeeder;
use Database\Seeders\HodAppointSubjectSeeder;
use Database\Seeders\SubjectTypeMasterSeeder;
use Database\Seeders\FacultyBankAccountSeeder;
use Database\Seeders\InternalToolMasterSeeder;
use Database\Seeders\StudentexamformoneSeeder;
use Database\Seeders\StudentexamformtwoSeeder;
use Database\Seeders\CurrentclassstudentSeeder;
use Database\Seeders\InternalToolAuditorSeeder;
use Database\Seeders\DocumentAcademicYearSeeder;
use Database\Seeders\InternalToolDocumentSeeder;
use Database\Seeders\StudentHelplineQuerySeeder;
use Database\Seeders\AshutoshAdmissionDataSeeder;
use Database\Seeders\StudentexamformfeeoneSeeder;
use Database\Seeders\StudentexamformfeetwoSeeder;
use Database\Seeders\StudentHelplineDocumentSeeder;
use Database\Seeders\SubjectBucketTypeMasterSeeder;
use Database\Seeders\InternalToolDocumentMasterSeeder;
use Database\Seeders\StudentinternalstatusmasterSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\StudentHelplineUploadedDocumentSeeder;


class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $this->call([
            GradeSeeder::class,
            PreviousYearSeeder::class,
            MonthSeeder::class,
            AcademicyearSeeder::class,
            DocumentAcademicYearSeeder::class,
            ProgrammeSeeder::class,
            DesignationSeeder::class,
            RoletypeSeeder::class,
            SubjectBucketTypeMasterSeeder::class,
            SubjectcategorySeeder::class,
            SubjecttypeSeeder::class,
            BloodgroupSeeder::class,
            ReligionSeeder::class,
            GendermasterSeeder::class,
            AddresstypeSeeder::class,
            ClassyearSeeder::class,
            BoarduniversitySeeder::class,
            PrefixmasterSeeder::class,
            BanknamemasterSeeder::class,
            SemesterSeeder::class,
            SubjectcreditSeeder::class,
            DepatmenttypeSeeder::class,
            ExamSeeder::class,
            StudentHelplineQuerySeeder::class,
            CasteCategorySeeder::class,
            ApplyFeeSeeder::class,
            FormTypeSeeder::class,
            CountrySeeder::class,
            ExamOrderPostSeeder::class,
            BuildingSeeder::class,
            CourseCategorySeeder::class,
            InstructiontypeSeeder::class,
            PapersetSeeder::class,
            TimeTableSlotSeeder::class,
            ExamsessionSeeder::class,
            ClassroomSeeder::class,                         //Building
            ExamFeeMasterSeeder::class,                     // ApplyFeeSeeder , FormTypeSeeder
            CasteSeeder::class,                             // CasteCategorySeeder
            StateSeeder::class,                             // CountrySeeder
            DistrictSeeder::class,                          // StateSeeder
            TalukaSeeder::class,                            // DistrictSeeder
            StudentHelplineDocumentSeeder::class,           // StudenthelplineQuerySeeder
            SansthaSeeder::class,                           // Sanstha, University
            CollegeSeeder::class,                           // College
            PatternSeeder::class,                           // College
            CapmasterSeeder::class,                         // College , Exam
            CourseSeeder::class,                            // College , Programme
            RoleSeeder::class,                              // College , Roletype
            EducationalcourseSeeder::class,                 // Programme
            DepatmentSeeder::class,                         // College ,DepatmenttypeSeeder
            UserSeeder::class,
            InstructionSeeder::class,                       // College,Department ,Role
            CourseclassSeeder::class,                       // classyear,course ,courseclass,college
            PatternclassSeeder::class,                      // Pattern , Patternclass
            ExamPatternclassSeeder::class,                  // Exam ,Patternclass ,CapmasterSeeder
            StudentSeeder::class,                           // College , Patternclass
            StudentProfileSeeder::class,                    // Patterncalss , caste , castecategory , Addresstype ,University, Educationalcourse
            ExamstudentseatnoSeeder::class,
            FacultyProfileSeeder::class,                    // college  ,department , role ,facultybanck account
            FacultyRoleSeeder::class,                       // faculty, user, role
            FacultyBankAccountSeeder::class,                // Faculty, Banknamemaster
            SubjectverticalSeeder::class,                   // Subjectbuckettype
            SubjectTypeMasterSeeder::class,                 // Subjectcategory, Subjecttype
            SubjectSeeder::class,                           // subjectcategory , subjecttype , patternclass , classyear , department , college
            ExamTimeTableSeeder::class,
            SubjectBucketSeeder::class,                     // department , patternclass , subjectcategory ,subject , academicyear
            AdmissionDataSeeder::class,                     // User,College,Patternclass,Subject,Academicyear
            StudentHelplineSeeder::class,                   // Student , Studenthelplinequery ,User
            StudentHelplineUploadedDocumentSeeder::class,   // Studenthelpline,Studenthelplinedocument
            ExamFeeCoursesSeeder::class,                    // Patternclass, Examfeemaster
            ExamformmasterSeeder::class,                    // Examform master,examfeemaster
            StudentexamformfeeoneSeeder::class,             // Student, Transaction ,College ,Exam ,Patternclass ,User
            StudentexamformfeetwoSeeder::class,             // Student, Transaction ,College ,Exam ,Patternclass ,User
            StudentexamformoneSeeder::class,                // Student ,College ,Exam ,Patternclass ,subject
            StudentexamformtwoSeeder::class,                // Student ,College ,Exam ,Patternclass ,subject
            FacultyHeadSeeder::class,                       // Faculty, Department
            HodAppointSubjectSeeder::class,                 // Faculty, Subject, Patternclass
            InternalToolMasterSeeder::class,
            InternalToolDocumentMasterSeeder::class,
            InternalToolDocumentSeeder::class,
            InternalToolAuditorSeeder::class,               // Patternclass, Faculty, Academicyear,
            CourseTypeMasterSeeder::class,
            DepartmentPrefixSeeder::class,
            ExamPanelSeeder::class,                         // user , faculty , examorderpost , subject
            SettingSeeder::class,                           // College , User
            BlockmasterSeeder::class,
            BlockSeeder::class,
            BlockallocationSeeder::class,
            PaperassesmentSeeder::class,
            StudentadmissionSeeder::class,
            ExambarcodeSeeder::class,
            StudentmarkSeeder::class,
            StudentresultSeeder::class,
            CurrentclassstudentSeeder::class,
            StudentinternalstatusmasterSeeder::class,
        ]);
    }
}
