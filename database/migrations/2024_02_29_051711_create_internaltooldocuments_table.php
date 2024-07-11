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
        Schema::create('internaltooldocuments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('internaltooldoc_id')->nullable()->unsigned();
            $table->foreign('internaltooldoc_id')->references('id')->on('internaltooldocumentmasters');
            $table->bigInteger('internaltoolmaster_id')->nullable()->unsigned();
            $table->foreign('internaltoolmaster_id')->references('id')->on('internaltoolmasters');
            $table->tinyInteger('is_multiple')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internaltooldocuments');
    }
};
