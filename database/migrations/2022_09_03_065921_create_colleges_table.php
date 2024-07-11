<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('colleges', function (Blueprint $table) {
            $table->id();
            $table->string('college_name')->nullable();
            $table->string('college_name_marathi')->nullable();
            $table->string('principal_name')->nullable();
            $table->string('college_address')->default(null)->nullable();
            $table->string('college_website_url',100)->default(null)->nullable();
            $table->string('college_email',100)->default(null)->nullable();
            $table->string('college_contact_no',50)->default(null)->nullable();
            $table->text('college_logo_path')->nullable();
            $table->bigInteger('sanstha_id')->nullable()->unsigned()->default(null);
            $table->foreign('sanstha_id')->references('id')->on('sansthas')->onDelete('cascade');
            $table->bigInteger('university_id')->nullable()->unsigned()->default(null);
            $table->foreign('university_id')->references('id')->on('universities')->onDelete('cascade');       
            $table->softDeletes();
            $table->timestamps();
            $table->tinyInteger('status')->default('0');// 0 :not active 1:active
            $table->tinyInteger('is_default')->default('0');// 0 :not active 1:active
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colleges');
    }
};
