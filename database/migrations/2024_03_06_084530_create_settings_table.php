<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('show_abcid')->nullable()->defulat(1)->comment('0-hide,1-show');
            $table->tinyInteger('abcid_required')->nullable()->defulat(1)->comment('0-optional,1-required');
            $table->tinyInteger('statement_of_marks_is_year_wise')->nullable()->defulat(1)->comment('0-sem wise,1-class wise');
            $table->tinyInteger('question_paper_apply_watermark')->nullable()->defulat(1);
            $table->string('question_paper_pdf_master_password')->nullable();
            $table->integer('exam_time_interval')->nullable()->unsigned()->default(120);
            $table->bigInteger('college_id')->nullable()->unsigned()->default(null);
            $table->foreign('college_id')->references('id')->on('colleges')->onDelete('cascade');
            $table->bigInteger('user_id')->nullable()->unsigned()->default(null);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
