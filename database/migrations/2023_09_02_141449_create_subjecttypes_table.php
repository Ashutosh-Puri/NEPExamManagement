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
        Schema::create('subjecttypes', function (Blueprint $table) {
            $table->id();
            $table->string('type_name',30); // IE IP IG I P G IEP IEG E
            $table->string('description',100); // INTERNAL & EXTERNAL(IE) INTERNAL & PRACTICAL(IP) INTERNAL & GRADE(IG)
            $table->tinyInteger('is_active')->default('0');// 0 :not active 1:active
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjecttypes');
    }
};
