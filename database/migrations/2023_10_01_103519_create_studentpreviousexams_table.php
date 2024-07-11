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
        Schema::create('studentpreviousexams', function (Blueprint $table) {
            $table->id();
            $table->foreign('boarduniversity_id')->references('id')->on('boarduniversities')->onDelete('cascade');
            $table->bigInteger('educationalcourse_id')->nullable()->unsigned()->default(null);
            $table->bigInteger('student_id')->unsigned()->nullable();
            $table->string('passing_year');
            $table->string('passing_month');
            $table->string('seat_number');
            $table->integer('obtained_marks')->nullable();
            $table->integer('total_marks')->nullable();
            $table->float('percentage',5,2)->nullable();
            $table->float('cgpa',5,2)->nullable();
            $table->string("grade",3)->nullable();
            $table->timestamps();
            $table->unique(['student_id', 'educationalcourse_id']); //  [ 'column1', 'column2']
            $table->bigInteger('boarduniversity_id')->nullable()->unsigned()->default(null);
            $table->foreign('educationalcourse_id')->references('id')->on('educationalcourses')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studentpreviousexams');
    }
};
