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
        Schema::create('student_helplines', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_id')->unsigned();
            $table->foreign('student_id')->references('id')->on('students');
            $table->bigInteger('student_helpline_query_id')->unsigned();
            $table->foreign('student_helpline_query_id')->references('id')->on('student_helpline_queries');
            $table->tinyInteger('status')->nullable()->default(0)->comment('0-pending,1-verified,2-approve,3-cancel,4-reject');
            $table->string('remark')->nullable();
            $table->string('old_query')->nullable();
            $table->string('new_query')->nullable();
            $table->text('description')->nullable();
            $table->bigInteger('approve_by')->nullable()->unsigned()->index();
            $table->foreign('approve_by')->references('id')->on('users');
            $table->bigInteger('verified_by')->nullable()->unsigned()->index();
            $table->foreign('verified_by')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_helplines');
    }
};
