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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('role_name',50)->nullable();
            $table->bigInteger('roletype_id')->nullable()->unsigned()->default(null);
            $table->bigInteger('college_id')->nullable()->unsigned()->default(null);

            $table->foreign('college_id')->references('id')->on('colleges');
            $table->foreign('roletype_id')->references('id')->on('roletypes');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
