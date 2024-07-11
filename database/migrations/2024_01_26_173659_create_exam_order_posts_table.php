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
        Schema::create('exam_order_posts', function (Blueprint $table) {
            $table->id();
            $table->string('post_name',50)->nullable();
            $table->tinyInteger('status')->default('0');// 0 :not active 1:active
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_order_posts');
    }
};
