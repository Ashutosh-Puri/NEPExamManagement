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
        Schema::create('exambodies', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('faculty_id')->nullable()->unsigned()->default(null);
            $table->foreign('faculty_id')->references('id')->on('faculties');

            $table->bigInteger('role_id')->nullable()->unsigned()->default(null);
            $table->foreign('role_id')->references('id')->on('roles');

            $table->bigInteger('user_id')->nullable()->unsigned()->default(null);
            $table->foreign('user_id')->references('id')->on('users');
            
            $table->bigInteger('college_id')->nullable()->unsigned()->default(null);
            $table->foreign('college_id')->references('id')->on('colleges');

            $table->text('profile_photo_path')->nullable();

            $table->text('sign_photo_path')->nullable();
            
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
        Schema::dropIfExists('exambodies');
    }
};
