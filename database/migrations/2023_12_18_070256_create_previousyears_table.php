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
        Schema::create('previousyears', function (Blueprint $table) {
            $table->id();
            $table->string('year_name',40)->nullable();
            $table->tinyInteger('is_active')->default('1');// 0 :not active 1:active
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('previousyears');
    }
};
