<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentresultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('studentresults', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_id')->unsigned();
            $table->foreign('student_id')->references('id')->on('students');
            $table->bigInteger('exam_patternclasses_id')->unsigned();
            $table->foreign('exam_patternclasses_id')->references('id')->on('exam_patternclasses');
            $table->integer('seatno')->nullable()->default(0);
            $table->integer('sem')->nullable()->default(0);
            $table->integer('isregular')->nullable()->default(0)->comment('0-repeater 1-pass 2-regular'); //isregular Default NULL 0 => repeater 1=>pass 2=>regular
            $table->float('sgpa',8,3)->nullable()->default(null);
            $table->float('semcreditearned',8,3)->nullable()->default(0);//Students Earned Credit
            $table->float('semtotalcredit',8,3)->nullable()->default(0);//All Credits for That Sem
            $table->integer('totalmarks')->nullable()->default(0);  
            $table->integer('totaloutofmarks')->nullable()->default(0);
            $table->float('semtotalcreditpoints',8,3)->nullable()->default(0);
            $table->tinyInteger('resultstatus')->nullable()->comment('0-fail , 1-Pass ,2-ATKT,3-Absent');//result status 0=>fail and 1 => passed ,2=>ATKT , 3=>ABSENT
            $table->tinyInteger('extracreditsstatus')->nullable()->comment('0-fail , 1-Pass');;//result status 0=>ExtraCreditfail and 1 => passed 
            $table->Integer('ordinance_163_marks')->default('0'); 
            $table->Integer('noncgpasubjecttotal')->default('0'); 
            $table->unique(['student_id','sem','exam_patternclasses_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('studentresults');
    }
}
