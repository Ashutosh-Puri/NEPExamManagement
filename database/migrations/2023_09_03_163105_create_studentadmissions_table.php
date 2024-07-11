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
        Schema::create('studentadmissions', function (Blueprint $table) {
            $table->id();
            
            $table->bigInteger('student_id')->unsigned()->nullable();
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
          
            $table->bigInteger('patternclass_id')->unsigned()->nullable();
            $table->foreign('patternclass_id')->references('id')->on('pattern_classes')->onDelete('cascade');
           
            $table->bigInteger('academicyear_id')->unsigned();
            $table->foreign('academicyear_id')->references('id')->on('academicyears')->onDelete('cascade');
            
            $table->tinyInteger('status')->nullable();
            $table->unique(['student_id','patternclass_id','academicyear_id'],'student_pattern_academicyear');
           
            $table->bigInteger('college_id')->nullable()->unsigned()->default(null);
            $table->foreign('college_id')->references('id')->on('colleges')->onDelete('cascade');
           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studentadmissions');
    }
};
