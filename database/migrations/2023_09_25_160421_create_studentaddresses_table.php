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
        Schema::create('studentaddresses', function (Blueprint $table) {
        $table->id();
        $table->bigInteger('student_id')->unsigned()->nullable();
        $table->string('address',100)->nullable()->default(NULL);
        $table->bigInteger('taluka_id')->unsigned()->nullable()->default(NULL);
        $table->string('pincode', 200)->nullable()->default(NULL);
        $table->string('village_name',50)->nullable()->default(NULL);
        $table->string('locality_name',200)->nullable()->default(NULL);      
        $table->tinyInteger('is_same')->default(0)->comment('0-not defined, 1-same');
        // $table->tinyInteger('address_type')->nullable()->default(0)->comment('0-not defined, 1-current ,2-permanant');
        $table->bigInteger('addresstype_id')->unsigned()->nullable();
        $table->boolean('is_completed')->default(0); 
        $table->timestamps();
        $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        $table->foreign('taluka_id')->references('id')->on('talukas')->onDelete('cascade');
        $table->foreign('addresstype_id')->references('id')->on('addresstypes')->onDelete('cascade');    
        $table->unique(['student_id', 'addresstype_id']); //  [ 'column1', 'column2']
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('studentaddresses');
    }
};
