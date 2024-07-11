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
        Schema::create('examsessions', function (Blueprint $table) {
            $table->id();
            $table->timestamp('from_date')->nullable()->default(null);
            $table->timestamp('to_date')->nullable()->default(null);
            $table->time('from_time')->nullable();
            $table->time('to_time')->nullable();
            $table->bigInteger('exam_id')->unsigned();
            $table->foreign('exam_id')->references('id')->on('exams');
            $table->tinyInteger('session_type')->default('1');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examsessions');
    }
};
