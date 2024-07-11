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
        Schema::create('addresstypes', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('type_devnagari', 200)->nullable()->collation('utf8mb4_general_ci')->charset('utf8mb4');
           
            $table->boolean('is_active')->default(1); //
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresstypes');
    }
};
