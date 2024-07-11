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
        Schema::create('examformmasters', function (Blueprint $table) {
            $table->id();
            $table->integer('totalfee')->default(0);
            $table->integer('inwardstatus')->default(0);
            $table->timestamp('inwarddate')->nullable();
            $table->integer('feepaidstatus')->default(0);
            $table->integer('printstatus')->default(0);
            $table->tinyInteger('hallticketstatus')->default(0);
            $table->bigInteger('student_id')->unsigned();
            $table->foreign('student_id')->references('id')->on('students');
            $table->bigInteger('transaction_id')->nullable()->unsigned();
            $table->foreign('transaction_id')->references('id')->on('transactions');
            $table->bigInteger('college_id')->nullable()->unsigned();
            $table->foreign('college_id')->references('id')->on('colleges');
            $table->bigInteger('exam_id')->unsigned();
            $table->foreign('exam_id')->references('id')->on('exams');
            $table->bigInteger('patternclass_id')->unsigned();
            $table->foreign('patternclass_id')->references('id')->on('pattern_classes');
            $table->string('medium_instruction',50)->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('payment_received_user_id')->nullable()->unsigned();
            $table->foreign('payment_received_user_id')->references('id')->on('users');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->unique(['student_id', 'exam_id', 'patternclass_id']);
            $table->index(['printstatus', 'inwardstatus']);
            $table->index(['patternclass_id', 'exam_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examformmasters');
    }
};
