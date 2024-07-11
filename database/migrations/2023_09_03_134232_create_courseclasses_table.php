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
        Schema::create('course_classes', function (Blueprint $table) {
            $table->id();      
            $table->unsignedBigInteger('classyear_id')->nullable()->default(null);;
            $table->foreign('classyear_id')->references('id')->on('classyears');
            $table->unsignedBigInteger('course_id');
            $table->foreign('course_id')->references('id')->on('courses');
            $table->unsignedBigInteger('nextyearclass_id')->nullable();
            $table->foreign('nextyearclass_id')->references('id')->on('course_classes');
            $table->unsignedBigInteger('college_id')->nullable()->default(null);
            $table->foreign('college_id')->references('id')->on('colleges');
            $table->timestamps();
            $table->softDeletes();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_classes');
    }
};
