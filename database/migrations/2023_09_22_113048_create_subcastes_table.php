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
        Schema::create('subcastes', function (Blueprint $table) {
            $table->id();
            $table->string('subcaste_name'); 
            $table->bigInteger('caste_id')->unsigned()->nullable()->default(null);
            $table->foreign('caste_id')->references('id')->on('castes')->onDelete('cascade');
            $table->boolean('is_active')->default(1); 
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subcastes');
    }
};
