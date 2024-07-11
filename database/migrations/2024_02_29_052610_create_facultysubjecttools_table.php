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
        Schema::create('facultysubjecttools', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('academicyear_id')->unsigned();
            $table->foreign('academicyear_id')->references('id')->on('document_academic_years');
            $table->bigInteger('faculty_id')->unsigned()->nullable();
            $table->foreign('faculty_id')->references('id')->on('faculties');
            $table->bigInteger('subject_id')->unsigned();
            $table->foreign('subject_id')->references('id')->on('subjects');
            $table->bigInteger('internaltoolmaster_id')->unsigned();
            $table->foreign('internaltoolmaster_id')->references('id')->on('internaltoolmasters');
            $table->bigInteger('departmenthead_id')->unsigned()->nullable();
            $table->foreign('departmenthead_id')->references('id')->on('faculties');
            $table->tinyInteger('freeze_by_faculty')->default(0)->comment('0-No, 1-Yes');
            $table->tinyInteger('freeze_by_hod')->default(0)->comment('0-No, 1-Yes');
            $table->foreign('verifybyfaculty_id')->references('id')->on('faculties')->comment('auditor_id');//  as auditor id
            $table->bigInteger('verifybyfaculty_id')->unsigned()->nullable();
            $table->tinyInteger('status')->default(0); // 0-Inactive 1-Active 2-Complete
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facultysubjecttools');
    }
};
