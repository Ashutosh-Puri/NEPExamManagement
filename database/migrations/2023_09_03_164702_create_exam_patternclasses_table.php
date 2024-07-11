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
        Schema::create('exam_patternclasses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('exam_id')->unsigned();
            $table->foreign('exam_id')->references('id')->on('exams');
            $table->bigInteger('patternclass_id')->unsigned();
            $table->foreign('patternclass_id')->references('id')->on('pattern_classes');
            $table->date('result_date')->nullable()->default(null);;
            $table->integer('launch_status')->default(0);
            $table->timestamp('start_date')->nullable()->default(null);
            $table->timestamp('end_date')->nullable()->default(null);
            $table->timestamp('latefee_date')->nullable()->default(null);
            $table->timestamp('intmarksstart_date')->nullable()->default(null);
            $table->timestamp('intmarksend_date')->nullable()->default(null);
            $table->timestamp('finefee_date')->nullable()->default(null);
            $table->bigInteger('capmaster_id')->unsigned()->nullable()->default(null);
            $table->foreign('capmaster_id')->references('id')->on('capmasters');  
            $table->date('capscheduled_date')->nullable()->default(null);
            $table->date('papersettingstart_date')->nullable()->default(null);
            $table->date('papersubmission_date')->nullable()->default(null);
            $table->string('placeofmeeting',100)->nullable()->default(null);
            $table->string('description',50)->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['exam_id','patternclass_id']);
            $table->index(['exam_id','patternclass_id']);
             
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_patternclasses');
    }
};
