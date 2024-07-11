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
        Schema::create('patterns', function (Blueprint $table) {
            $table->id();
            $table->string('pattern_name', 50)->nullable();
            $table->string('pattern_startyear',100)->nullable();
            $table->string('pattern_valid',100)->nullable();
            $table->tinyInteger('status')->default('1');  //Pattern is active 1 or not 0
            $table->unsignedBigInteger('college_id')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('college_id')->references('id')->on('colleges')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patterns');
    }
};
