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
        Schema::create('classyears', function (Blueprint $table) {
            $table->id();
            $table->string('classyear_name');
            $table->string('class_degree_name',100)->nullable();
            $table->tinyInteger('status')->default(1);//1 =>active ,0=>not active
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classyears');
    }
};
