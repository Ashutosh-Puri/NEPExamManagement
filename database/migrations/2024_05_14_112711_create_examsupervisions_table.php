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
        Schema::create('examsupervisions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('faculty_id')->unsigned()->nullable();
            $table->foreign('faculty_id')->references('id')->on('faculties');

            $table->date('supervision_date')->nullable()->default(null);
          
           
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
           
            $table->bigInteger('exam_id')->unsigned();
            $table->foreign('exam_id')->references('id')->on('exams');

            $table->bigInteger('examsession_id')->unsigned();
            $table->foreign('examsession_id')->references('id')->on('examsessions');
           
            $table->bigInteger('adjustfaculty_id')->unsigned()->nullable();
            $table->foreign('adjustfaculty_id')->references('id')->on('faculties');

            $table->tinyInteger('email_status')->default('0'); 
           
            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examsupervisions');
    }
};
