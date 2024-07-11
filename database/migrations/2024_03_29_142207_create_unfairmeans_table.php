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
        Schema::create('unfairmeans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('exam_patternclasses_id')->unsigned();
            $table->foreign('exam_patternclasses_id')->references('id')->on('exam_patternclasses');

            $table->bigInteger('exam_studentseatnos_id')->unsigned();
            $table->foreign('exam_studentseatnos_id')->references('id')->on('exam_studentseatnos');

            $table->bigInteger('student_id')->unsigned();
            $table->foreign('student_id')->references('id')->on('students');

            $table->bigInteger('memid')->nullable()->unsigned();

            $table->bigInteger('unfairmeansmaster_id')->unsigned();
            $table->foreign('unfairmeansmaster_id')->references('id')->on('unfairmeansmasters');

            $table->string('subject_id')->nullable()->default(null);

            $table->integer('punishment')->nullable()->default(0);
            
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('paid_status')->default(0);//feepaidstatus
            
            $table->tinyInteger('email')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unfairmeans');
    }
};
