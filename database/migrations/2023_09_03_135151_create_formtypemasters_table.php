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
        Schema::create('formtypemasters', function (Blueprint $table) {
            $table->id();
            $table->string('form_name');
            $table->tinyInteger('is_active')->nullable()->default(0);// 0 :not active 1:active
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formtypemasters');
    }
};
