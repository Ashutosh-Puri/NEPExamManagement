<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('DROP FUNCTION IF EXISTS get_examfee_total_by_exam');
        
        DB::statement('
            CREATE FUNCTION get_examfee_total_by_exam(examfees_id INT, exam_id INT)
            RETURNS INT
            BEGIN
                DECLARE total_fee INT DEFAULT 0;
                SELECT SUM(fee_amount) INTO total_fee
                FROM studentexamformfees sf
                JOIN examformmasters ef ON ef.id = sf.examformmaster_id
                WHERE sf.examfees_id = examfees_id AND ef.exam_id = exam_id;
                RETURN total_fee;
            END
        ');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP FUNCTION IF EXISTS get_examfee_total_by_exam');
    }
};
