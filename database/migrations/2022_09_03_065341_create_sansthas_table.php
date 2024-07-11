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
        Schema::create('sansthas', function (Blueprint $table) {
            $table->id();
            $table->string('sanstha_name')->nullable();         
            $table->string('sanstha_address')->default(null)->nullable();
            $table->string('sanstha_chairman_name',50)->default(null)->nullable();
            $table->string('sanstha_website_url',50)->default(null)->nullable();
            $table->string('sanstha_contact_no',20)->default(null)->nullable();
            $table->tinyInteger('status')->default('0');// 0 :not active 1:active
            $table->SoftDeletes();
            $table->timestamps();

               // $table->string('city',100)->nullable();
            // $table->string('district',100)->nullable();
            // $table->string('state',100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sansthas');
    }
};
