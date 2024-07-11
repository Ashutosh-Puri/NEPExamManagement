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
        Schema::create('universities', function (Blueprint $table) {
            $table->id();
            $table->string('university_name')->nullable();
            $table->string('university_address')->default(null)->nullable();
            $table->string('university_website_url',100)->default(null)->nullable();
            $table->string('university_email',100)->default(null)->nullable();
            $table->string('university_contact_no',50)->default(null)->nullable();
            $table->text('university_logo_path')->nullable();
            $table->tinyInteger('status')->default('0');// 0 :not active 1:active
            $table->SoftDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('universities');
    }
};
