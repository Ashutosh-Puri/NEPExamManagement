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
        Schema::create('classroomblocks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('classroom_id')->unsigned()->nullable();
            $table->foreign('classroom_id')->references('id')->on('classrooms'); 

            $table->bigInteger('blockmaster_id')->unsigned()->nullable();
            $table->foreign('blockmaster_id')->references('id')->on('blockmasters'); 

            $table->tinyInteger('status');
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classroomblocks');
    }
};
