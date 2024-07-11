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
        Schema::create('gradepoints', function (Blueprint $table) {
            $table->id();
            $table->integer('max_percentage')->default(0);
            $table->integer('min_percentage')->default(0);
            $table->integer('grade_point')->default(0);
            $table->string('grade_name',5)->nullable();
            $table->string('description',50)->nullable();
            $table->tinyInteger('is_active')->default('1');// 0 :not active 1:active
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gradepoints');
    }
};
