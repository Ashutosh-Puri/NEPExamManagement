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
        Schema::create('caste_categories', function (Blueprint $table) {
            $table->id();
            $table->string('caste_category');  
            $table->string('caste_category_desc');  //fullname
            $table->float('reservation',5,3);  //fullname
            $table->boolean('is_active')->default(1); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caste_categories');
    }
};
