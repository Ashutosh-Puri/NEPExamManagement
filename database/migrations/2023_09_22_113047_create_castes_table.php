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
        Schema::create('castes', function (Blueprint $table) {
            $table->id();
            $table->integer('sno')->default('0');
            $table->string('caste_name'); 
            $table->bigInteger('caste_category_id')->unsigned()->nullable()->default(null);
            $table->foreign('caste_category_id')->references('id')->on('caste_categories')->onDelete('cascade');
            $table->boolean('is_active')->default(1); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('castes');
    }
};
