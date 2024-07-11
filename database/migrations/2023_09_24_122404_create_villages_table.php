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
        Schema::create('villages', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->integer('village_code');
            $table->string('village_name');
            $table->string('village_name_local')->collation('utf8mb4_general_ci')->charset('utf8mb4');
            $table->bigInteger('taluka_id')->unsigned()->nullable()->default(NULL);
            $table->foreign('taluka_id')->references('id')->on('talukas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('villages');
    }
};
