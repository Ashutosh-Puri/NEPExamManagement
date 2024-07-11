<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('studentinternalstatusmaster', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('short_name', 25);
            $table->tinyInteger('is_active')->default(0); // 0-Active 1-In-active
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('studentinternalstatusmaster');
    }
};
