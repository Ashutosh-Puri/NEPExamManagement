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
        Schema::create('currentclass_students', function (Blueprint $table) {
            $table->id();              
            $table->integer('sem');
            $table->integer('pfstatus')->default(-1)->comment('0-fail,1-pass,2-ATKT');//pass=1 fail=0 ATKT=2 status 
            $table->tinyInteger('ordinace1_flag')->default(0);
            $table->tinyInteger('ordinace2_flag')->default(0);
            $table->integer('isregular')->nullable()->default(null)->comment('0-repeter,1-regular'); //isregular Default NULL 0 => repeater 1=>regular
            $table->integer('is_directadmission')->default(0)->comment('0-prnt orignal,1-dublicate');//0 Means Print origal 1 Dublicate 
            $table->unsignedBigInteger('patternclass_id');
            $table->foreign('patternclass_id')->references('id')->on('pattern_classes');
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students');
            $table->unique(['student_id','sem','patternclass_id']);
            $table->unsignedBigInteger('college_id')->nullable()->default(null);
            $table->foreign('college_id')->references('id')->on('colleges');
            $table->unsignedBigInteger('academicyear_id');  //Major Minor
            $table->foreign('academicyear_id')->references('id')->on('academicyears');
            $table->tinyInteger('markssheetprint_status')->default(-1); 
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currentclass_students');
    }
};
