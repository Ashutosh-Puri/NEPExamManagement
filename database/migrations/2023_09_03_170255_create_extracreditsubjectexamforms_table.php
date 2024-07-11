<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtracreditsubjectexamformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //All field denotes student chosen the int ext practical project for that subject 
        Schema::create('extracreditsubjectexamforms', function (Blueprint $table) {
           $table->id();
           $table->string('prn',100);
           $table->tinyInteger('select_status')->default('0'); 
           $table->bigInteger('exam_id')->unsigned();
           $table->bigInteger('student_id')->unsigned();
           $table->bigInteger('subject_id')->unsigned();
           $table->bigInteger('examformmaster_id')->nullable()->unsigned();
           $table->timestamps();
           $table->foreign('exam_id')->references('id')->on('exams');
           $table->foreign('student_id')->references('id')->on('students');
           $table->foreign('subject_id')->references('id')->on('extracreditsubjects');
           $table->foreign('examformmaster_id')->references('id')->on('examformmasters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('extracreditsubjectexamforms');
    }
}
