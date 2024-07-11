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
        Schema::create('boarduniversities', function (Blueprint $table) {
            $table->id();
            $table->string('boarduniversity_name');           
            $table->boolean('is_active')->default(1); //Board 0 deactive  1 board  2 university
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boarduniversities');
    }
};
