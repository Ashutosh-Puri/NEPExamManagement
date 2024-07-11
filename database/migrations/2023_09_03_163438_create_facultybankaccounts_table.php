<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('facultybankaccounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('faculty_id');
            $table->string('account_no', 50)->nullable();
            $table->string('bank_address', 50)->nullable();
            $table->string('bank_name', 50)->nullable();
            $table->string('branch_name', 50)->nullable();
            $table->string('branch_code', 50)->nullable();
            $table->string('account_type', 50)->nullable();
            $table->string('ifsc_code', 50)->nullable();
            $table->string('micr_code', 50)->nullable();
            $table->tinyInteger('acc_verified')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facultybankaccounts');
    }
};
