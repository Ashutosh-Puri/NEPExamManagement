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
        DB::statement('DROP VIEW IF EXISTS exam_fee_view');
        DB::statement("
            CREATE VIEW exam_fee_view AS
            SELECT 
                ft.form_name,
                ef.fee_name,
                ef.remark,
                ec.id,
                ec.fee,
                ec.sem,
                ec.patternclass_id,
                ec.examfees_id,
                ec.active_status,
                ec.approve_status,
                ec.created_at,
                ec.updated_at,
                ec.deleted_at
            FROM 
                formtypemasters ft
            JOIN 
                examfeemasters ef ON ft.id = ef.form_type_id
            JOIN 
                examfeecourses ec ON ef.id = ec.examfees_id
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS exam_fee_view');
    }
};
