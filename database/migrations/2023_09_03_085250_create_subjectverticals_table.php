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
        Schema::create('subjectverticals', function (Blueprint $table) {
            $table->id();
            $table->string('subject_vertical')->nullable(); //Major Minor
            $table->string('subjectvertical_shortname',10)->nullable();

            $table->bigInteger('subjectbuckettype_id')->nullable()->unsigned()->default(null)->index();
            $table->foreign('subjectbuckettype_id')->references('id')->on('subjectbuckettypemaster');

            $table->tinyInteger('is_active')->default('0')->index();// 0 :not active 1:active for departmet specific classes 2:active for all classes

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjectverticals');
    }
};
