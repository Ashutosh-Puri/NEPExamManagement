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
        Schema::create('cgparesults', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_id')->unsigned();
            $table->foreign('student_id')->references('id')->on('students');
            $table->bigInteger('exam_patternclass_id')->unsigned();
            $table->foreign('exam_patternclass_id')->references('id')->on('exam_patternclasses');
            $table->integer('seatno');
            $table->float('cgpa',8,3)->nullable()->default(null);
            $table->string('grade')->nullable()->default(null);
            $table->integer('totalmarks')->nullable()->default(0);  
            $table->integer('totaloutofmarks')->nullable()->default(0);
            $table->timestamps();
            $table->unique(['student_id','seatno','exam_patternclass_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cgparesults');
    }
};
