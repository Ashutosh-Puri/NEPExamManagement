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
        Schema::create('blockallocations', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('block_id')->unsigned()->nullable();
            $table->foreign('block_id')->references('id')->on('blockmasters'); 

            $table->bigInteger('classroom_id')->unsigned()->nullable();
            $table->foreign('classroom_id')->references('id')->on('classrooms'); 

            $table->bigInteger('exampatternclass_id')->unsigned()->nullable();
            $table->foreign('exampatternclass_id')->references('id')->on('exam_patternclasses'); 

            $table->bigInteger('subject_id')->unsigned()->nullable();
            $table->foreign('subject_id')->references('id')->on('subjects'); 

            $table->bigInteger('faculty_id')->unsigned()->nullable();
            $table->foreign('faculty_id')->references('id')->on('faculties'); 

            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users'); 

            $table->bigInteger('college_id')->unsigned()->nullable();
            $table->foreign('college_id')->references('id')->on('colleges'); 

            $table->tinyInteger('status');

            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blockallocations');
    }
};
