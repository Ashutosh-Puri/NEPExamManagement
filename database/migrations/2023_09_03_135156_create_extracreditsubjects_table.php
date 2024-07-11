<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtracreditsubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extracreditsubjects', function (Blueprint $table) {
            $table->id();
         
                $table->integer('subject_sem')->nullable();   //1
                $table->string('subject_name',100)->nullable(); //2
                $table->string('subject_code',50)->nullable(); //3
                $table->string('subject_category',50)->nullable(); //Theory Project Practical  //4 //extra
                $table->char('subject_option',3)->nullable(); //6
                $table->string('subject_type',50)->nullable(); //6  IE  - InternalExternal  IEP- Internal External Practical  IP-Internal Practical //ECS-extra credit subject
                $table->float('subject_credit',3,1)->nullable(); //7
                $table->string('course_type',20)->default(NULL)->comment('UG or PG'); //UG or PG
                $table->tinyInteger('status')->nullable()->default('0'); //6
                $table->bigInteger('patternclass_id')->nullable()->unsigned();
                $table->foreign('patternclass_id')->references('id')->on('pattern_classes');
      
                 
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
        Schema::dropIfExists('extracreditsubjects');
    }
}
