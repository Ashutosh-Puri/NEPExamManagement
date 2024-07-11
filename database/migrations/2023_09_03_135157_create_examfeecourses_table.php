<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamfeecoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examfeecourses', function (Blueprint $table) {
            $table->id();      
            $table->integer('fee')->default(0); 
            $table->integer('sem')->nullable(); 
            $table->bigInteger('patternclass_id')->unsigned();
            $table->foreign('patternclass_id')->references('id')->on('pattern_classes');
            $table->bigInteger('examfees_id')->unsigned();
            $table->foreign('examfees_id')->references('id')->on('examfeemasters');
            $table->tinyInteger('active_status')->default('0');  //1 means ative for current pattern 
            $table->tinyInteger('approve_status')->default('0');  //1 means Approved by admin and not deleted 0 means vot approve and it can be modify 
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('examfeecourses');
    }
}
