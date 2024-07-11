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
        Schema::create('internaltoolmasters', function (Blueprint $table) {
            $table->id();
            $table->string('toolname', 255);
            $table->string('course_type', 10);
            $table->tinyInteger('status')->default('0')->index();// 0 :not active 1:active
            $table->timestamps();
            $table->softDeletes();
        });
    }
    // 2024_02_29_044646_create_internaltoolauditors_table
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internaltoolmasters');
    }
};
