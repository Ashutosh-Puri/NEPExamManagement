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
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->integer('state_code');
            $table->string('state_name');
            $table->integer('census_code');//2011
            $table->char('state_or_UT',2); 
            $table->bigInteger('country_id')->unsigned()->nullable()->default(NULL);
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
          
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};
