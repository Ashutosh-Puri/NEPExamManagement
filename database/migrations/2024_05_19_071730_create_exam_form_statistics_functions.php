<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('DROP FUNCTION IF EXISTS getTotalStudents');
        DB::statement('DROP FUNCTION IF EXISTS getIncompleteForms');
        DB::statement('DROP FUNCTION IF EXISTS getYetToInwardForms');
        DB::statement('DROP FUNCTION IF EXISTS getInwardCompletedForms');
        DB::statement('DROP FUNCTION IF EXISTS getTotalFeeReceived');
        
        DB::statement('
            CREATE FUNCTION getTotalStudents(examPatternClassId INT, academicYearId INT)
            RETURNS INT
            BEGIN
                RETURN (
                    SELECT COUNT(DISTINCT admissiondatas.memid)
                    FROM admissiondatas
                    WHERE admissiondatas.patternclass_id = examPatternClassId
                    AND admissiondatas.academicyear_id = academicYearId
                );
            END
        ');

        DB::statement('
            CREATE FUNCTION getIncompleteForms(examPatternClassId INT, examId INT)
            RETURNS INT
            BEGIN
                RETURN (
                    SELECT COUNT(*)
                    FROM examformmasters
                    WHERE examformmasters.patternclass_id = examPatternClassId
                    AND examformmasters.exam_id = examId
                    AND examformmasters.printstatus = 0
                    AND examformmasters.inwardstatus = 0
                );
            END
        ');

        DB::statement('
            CREATE FUNCTION getYetToInwardForms(examPatternClassId INT, examId INT)
            RETURNS INT
            BEGIN
                RETURN (
                    SELECT COUNT(*)
                    FROM examformmasters
                    WHERE examformmasters.patternclass_id = examPatternClassId
                    AND examformmasters.exam_id = examId
                    AND examformmasters.printstatus = 1
                    AND examformmasters.inwardstatus = 0
                );
            END
        ');

        DB::statement('
            CREATE FUNCTION getInwardCompletedForms(examPatternClassId INT, examId INT)
            RETURNS INT
            BEGIN
                RETURN (
                    SELECT COUNT(*)
                    FROM examformmasters
                    WHERE examformmasters.patternclass_id = examPatternClassId
                    AND examformmasters.exam_id = examId
                    AND examformmasters.inwardstatus = 1
                );
            END
        ');

        DB::statement('
            CREATE FUNCTION getTotalFeeReceived(examPatternClassId INT, examId INT)
            RETURNS DECIMAL(10, 2)
            BEGIN
                RETURN (
                    SELECT SUM(examformmasters.totalfee)
                    FROM examformmasters
                    WHERE examformmasters.patternclass_id = examPatternClassId
                    AND examformmasters.exam_id = examId
                    AND examformmasters.inwardstatus = 1
                );
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP FUNCTION IF EXISTS getTotalStudents');
        DB::statement('DROP FUNCTION IF EXISTS getIncompleteForms');
        DB::statement('DROP FUNCTION IF EXISTS getYetToInwardForms');
        DB::statement('DROP FUNCTION IF EXISTS getInwardCompletedForms');
        DB::statement('DROP FUNCTION IF EXISTS getTotalFeeReceived');
    }
};
