<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentexamformfeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('studentexamformfees', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('examformmaster_id')->unsigned();
            $table->bigInteger('examfees_id')->unsigned();
            $table->integer('fee_amount')->default(0);;
            $table->timestamps();
            $table->foreign('examformmaster_id')->references('id')->on('examformmasters')->onDelete('cascade');
            $table->foreign('examfees_id')->references('id')->on('examfeemasters');
            $table->index(['examfees_id', 'examformmaster_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('studentexamformfees');
    }
}
