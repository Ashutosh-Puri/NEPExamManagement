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
        Schema::create('examorders', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            
            $table->bigInteger('exampanel_id')->unsigned();
            $table->foreign('exampanel_id')->references('id')->on('exampanels');
          
            $table->bigInteger('exam_patternclass_id')->unsigned();
            $table->foreign('exam_patternclass_id')->references('id')->on('exam_patternclasses');
                  
            $table->string('description',50)->nullable();
            $table->string('token',50)->nullable();
            $table->tinyInteger('email_status')->default('0');  //0 means not sent
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examorders');
    }
};
