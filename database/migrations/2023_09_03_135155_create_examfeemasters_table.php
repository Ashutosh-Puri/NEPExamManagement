<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamfeemastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examfeemasters', function (Blueprint $table) {
            $table->id();
            $table->string('fee_name', 200)->nullable();
            $table->integer('default_professional_fee')->nullable();
            $table->integer('default_non_professional_fee')->nullable();
            $table->bigInteger('form_type_id')->unsigned()->nullable();
            $table->foreign('form_type_id')->references('id')->on('formtypemasters');
            $table->bigInteger('apply_fee_id')->unsigned()->nullable();
            $table->foreign('apply_fee_id')->references('id')->on('applyfeemasters');
            $table->tinyInteger('active_status')->default('1');  //Active Master 1 means active 0 means not active
            $table->tinyInteger('approve_status')->default('0');  //1 means Approved by admin and not deleted 0 means vot approve and it can be modify 
            $table->string('remark',50)->nullable(); //6
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
        Schema::dropIfExists('examfeemasters');
    }
}
