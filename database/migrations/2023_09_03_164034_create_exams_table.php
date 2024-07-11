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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('exam_name',100)->nullable();
            $table->string('month',100)->nullable();
            $table->tinyInteger('status')->default(0); //1 means Active 0 means not active
            $table->unsignedBigInteger('academicyear_id');
            $table->foreign('academicyear_id')->references('id')->on('academicyears');
            $table->tinyInteger('exam_sessions')->default(0); //0 means only Creared 1 means first Half 2 Means Second Half
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
