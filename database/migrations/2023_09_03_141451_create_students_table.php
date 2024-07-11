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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('prn',100)->nullable()->default(null);
            $table->string('university_prn',100)->nullable();
            $table->bigInteger('memid')->nullable()->unsigned();
            $table->string('eligibilityno')->nullable();
            $table->string('abcid')->nullable();
            $table->string('aadhar_card_no')->nullable();
            $table->string('student_name');          
            $table->string('mother_name')->nullable()->default(null);          
            $table->string('mobile_no')->nullable()->default(null);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('email_verified')->default(0); 
            $table->timestamp('mobile_no_verified_at')->nullable();
            $table->integer('mobile_no_verified')->default(0);
            $table->string('password');
            $table->timestamp('last_login')->nullable();
            $table->bigInteger('patternclass_id')->nullable()->unsigned()->default(null);
            $table->foreign('patternclass_id')->references('id')->on('pattern_classes')->onDelete('cascade');//Entry on which class
            $table->bigInteger('department_id')->nullable()->unsigned()->default(null);
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->bigInteger('college_id')->nullable()->unsigned()->default(null);
            $table->foreign('college_id')->references('id')->on('colleges')->onDelete('cascade');
            $table->tinyInteger('total_steps')->nullable()->default(6);   // Total Steps Of Form 
            $table->tinyInteger('current_step')->nullable()->default(1);   // Current Step Of Form
            $table->tinyInteger('is_profile_complete')->nullable()->default(0);   // profile  0-default , 1-completed
            $table->tinyInteger('status')->default(0);   //Approved Student only 0 not Approved and 1 is used to approve 
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
