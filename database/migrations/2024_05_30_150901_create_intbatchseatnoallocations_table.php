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
        Schema::create('intbatchseatnoallocations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('intbatch_id')->unsigned();
            $table->foreign('intbatch_id')->references('id')->on('internalmarksbatches');
            $table->bigInteger('student_id')->unsigned();
            $table->foreign('student_id')->references('id')->on('students');
            $table->integer('seatno');
            $table->integer('marks')->nullable();
            $table->string('grade')->nullable();
            $table->bigInteger('status')->unsigned()->default(1);
            $table->foreign('status')->references('id')->on('studentinternalstatusmaster');
            $table->unique(['intbatch_id', 'student_id', 'seatno']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intbatchseatnoallocations');
    }
};
