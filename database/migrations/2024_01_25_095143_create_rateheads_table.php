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
        Schema::create('rateheads', function (Blueprint $table) {
            $table->id();
            $table->string('headname');
            $table->string('type',2);
            $table->tinyInteger('status')->default(1);
            $table->integer('noofcredit')->nullable()->default(null);
            $table->string('course_type',20);
            $table->decimal('amount',8,2)->default(null);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rateheads');
    }
};
