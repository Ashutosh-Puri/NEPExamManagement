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
        Schema::create('internalmarksbatches', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('exam_patternclasses_id')->unsigned();
            $table->foreign('exam_patternclasses_id')->references('id')->on('exam_patternclasses');
            $table->bigInteger('subject_id')->unsigned();
            $table->foreign('subject_id')->references('id')->on('subjects');
            $table->string('subject_type',5)->nullable();

            $table->bigInteger('faculty_id')->nullable()->unsigned();
            $table->foreign('faculty_id')->references('id')->on('faculties')->onDelete('cascade');;

            $table->Integer('status')->unsigned()->default(0);
            $table->integer('totalBatchsize')->nullable();
            $table->integer('totalAbsent')->nullable();
            $table->integer('totalMarksentry')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internalmarksbatches');
    }
};
