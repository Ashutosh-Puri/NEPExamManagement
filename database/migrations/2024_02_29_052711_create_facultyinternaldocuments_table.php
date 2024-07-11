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
        Schema::create('facultyinternaldocuments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('facultysubjecttool_id')->unsigned();
            $table->foreign('facultysubjecttool_id')->references('id')->on('facultysubjecttools')->onDelete('cascade');
            $table->bigInteger('internaltooldocument_id')->unsigned();
            $table->foreign('internaltooldocument_id')->references('id')->on('internaltooldocuments');
            $table->string('document_fileName', 255)->nullable();
            $table->string('document_filePath', 255)->nullable();
            $table->string('verificationremark', 255)->nullable();
            $table->tinyInteger('status')->default(0); // 0-Inactive 1-Active 2-Complete
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facultyinternaldocuments');
    }
};