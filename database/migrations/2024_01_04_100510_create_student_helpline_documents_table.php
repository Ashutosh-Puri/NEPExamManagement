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
        Schema::create('student_helpline_documents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_helpline_query_id')->unsigned();
            $table->string('document_name');
            // $table->tinyInteger('is_required')->nullable()->default(0);
            $table->tinyInteger('is_active')->nullable()->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('student_helpline_query_id')->references('id')->on('student_helpline_queries');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_helpline_documents');
    }
};
