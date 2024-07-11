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
        Schema::create('educationalcourses', function (Blueprint $table) {
            $table->id();
            $table->string('course_name');         
            $table->bigInteger('programme_id')->nullable()->unsigned()->default(null);
            $table->boolean('is_active')->default(1); //
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('programme_id')->references('id')->on('programmes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('educationalcourses');
    }
};
