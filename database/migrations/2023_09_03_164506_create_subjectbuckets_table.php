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
        //  classwise bucket for student
        Schema::create('subjectbuckets', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('department_id')->unsigned();  //Major Minor
            $table->foreign('department_id')->references('id')->on('departments');//For which department bucket is created

            $table->bigInteger('patternclass_id')->unsigned();  //Major Minor
            $table->foreign('patternclass_id')->references('id')->on('pattern_classes');

            $table->char('subject_division',1)->default('A'); //A -All B ,C,D

            // $table->bigInteger('subjectcategory_id')->unsigned();  //Major Minor
            // $table->foreign('subjectcategory_id')->references('id')->on('subjectcategories');

            $table->bigInteger('subjectvertical_id')->unsigned()->nullable();
            $table->foreign('subjectvertical_id')->references('id')->on('subjectverticals');

            // $table->integer('subject_categoryno')->default(0);  //OE1

            $table->bigInteger('subject_id')->unsigned();  //Major Minor
            $table->foreign('subject_id')->references('id')->on('subjects');
            $table->bigInteger('academicyear_id')->unsigned();  //Major Minor
            $table->foreign('academicyear_id')->references('id')->on('academicyears');
            $table->tinyInteger('status')->default('0');  //active 1 or not 0
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjectbuckets');
    }
};
