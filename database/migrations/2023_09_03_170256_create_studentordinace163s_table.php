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
        Schema::create('studentordinace163s', function (Blueprint $table) {
            $table->id();
            $table->integer('seatno')->nullable();
            $table->bigInteger('student_id')->unsigned();
            $table->foreign('student_id')->references('id')->on('students');
            $table->bigInteger('patternclass_id')->unsigned();
            $table->foreign('patternclass_id')->references('id')->on('pattern_classes');
            $table->bigInteger('exam_id')->unsigned();
            $table->foreign('exam_id')->references('id')->on('exams');
            $table->bigInteger('ordinace163master_id')->unsigned();
            $table->foreign('ordinace163master_id')->references('id')->on('ordinace163masters');
            $table->integer('marks')->default(0);
            $table->integer('marksused')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->integer('fee')->nullable()->default(20);
            $table->bigInteger('transaction_id')->unsigned()->nullable();
            $table->foreign('transaction_id')->references('id')->on('transactions');
            $table->timestamp('payment_date')->nullable();
            $table->tinyInteger('is_fee_paid')->default(0);
            $table->tinyInteger('is_applicable')->default(0); //0 Means not Applicable
            $table->timestamps();
            $table->unique(['student_id', 'patternclass_id','exam_id', 'ordinace163master_id'], 'unique_student_exam_ordinace');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studentordinace163s');
    }
};
