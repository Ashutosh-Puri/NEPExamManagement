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
        Schema::create('subjecttypemaster', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('subjectcategory_id')->nullable()->unsigned()->default(null);
            $table->foreign('subjectcategory_id')->references('id')->on('subjectcategories');
            $table->bigInteger('subjecttype_id')->nullable()->unsigned()->default(null);
            $table->foreign('subjecttype_id')->references('id')->on('subjecttypes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjecttypemaster');
    }
};
