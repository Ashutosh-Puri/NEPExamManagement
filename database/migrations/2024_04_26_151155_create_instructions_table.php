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
        Schema::create('instructions', function (Blueprint $table) {
            $table->id();
            $table->text('instruction_name')->nullable();

            $table->bigInteger('user_id')->nullable()->unsigned()->default(null);
            $table->foreign('user_id')->references('id')->on('users');
            
            $table->bigInteger('college_id')->nullable()->unsigned()->default(null);
            $table->foreign('college_id')->references('id')->on('colleges');
            
            $table->bigInteger('instructiontype_id')->nullable()->unsigned()->default(null);
            $table->foreign('instructiontype_id')->references('id')->on('instructiontypes');

            $table->tinyInteger('is_active')->default('0');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructions');
    }
};
