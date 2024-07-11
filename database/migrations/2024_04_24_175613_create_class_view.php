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
        DB::statement('DROP VIEW IF EXISTS class_view');

        DB::statement("
            CREATE VIEW class_view AS
            SELECT
            cc.classyear_id,
            cy.classyear_name,
            cc.course_id,
            c.course_name,
            c.course_type,
            c.course_category_id,
            c.programme_id,
            pc.id,
            pc.class_id,
            pc.pattern_id,
            p.pattern_name,
            pc.status,
            pc.created_at,
            pc.updated_at,
            pc.deleted_at
            FROM
                classyears cy
            JOIN
                course_classes cc ON cy.id = cc.classyear_id
            JOIN
                courses c ON cc.course_id = c.id
            LEFT JOIN
                pattern_classes pc ON cc.id = pc.class_id
            LEFT JOIN
                patterns p ON pc.pattern_id = p.id
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS class_view');
    }
};
