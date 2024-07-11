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
        Schema::create('paperassesments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('subject_id')->unsigned()->nullable();
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->integer('total_papers')->default('0');
            
            $table->bigInteger('examinerfaculty_id')->unsigned()->nullable();
            $table->foreign('examinerfaculty_id')->references('id')->on('faculties')->onDelete('cascade');
            $table->integer('totalexamine')->default(0);          
            $table->float('examiner_rate',8,2)->default(0);
            $table->float('examiner_amount',8,2)->nullable()->default(null);
          
            $table->bigInteger('moderatorfaculty_id')->unsigned()->nullable();
            $table->foreign('moderatorfaculty_id')->references('id')->on('faculties')->onDelete('cascade');
            $table->integer('totalmoderation')->default(0);
            $table->float('moderator_rate',8,2)->default(0);
            $table->float('moderator_amount',8,2)->nullable()->default(null);
           
       

            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('verified_by')->unsigned()->nullable();
            $table->foreign('verified_by')->references('id')->on('users');
            
            $table->bigInteger('exam_id')->unsigned()->nullable();
            $table->foreign('exam_id')->references('id')->on('exams');
            $table->integer('bundle_no')->nullable()->default(null);
            $table->integer('rack_no')->nullable()->default(null);
           
            $table->bigInteger('billcreated_by')->unsigned()->nullable();
            $table->foreign('billcreated_by')->references('id')->on('users');
            $table->date('bill_date')->nullable()->default(null);
     
           
            $table->bigInteger('college_id')->nullable()->unsigned()->default(null);
            $table->foreign('college_id')->references('id')->on('colleges');
            $table->tinyInteger('status')->default('0');
          
          
            
  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paperassesments');
    }
};
