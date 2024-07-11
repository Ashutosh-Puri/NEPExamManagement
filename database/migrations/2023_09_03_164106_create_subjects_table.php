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
    {// offered by the department
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('academicyear_id')->unsigned();
            $table->foreign('academicyear_id')->references('id')->on('academicyears');

            $table->integer('subject_sem')->default(0);   //1

            $table->bigInteger('subjectvertical_id')->unsigned()->nullable();  // SEC AEC VEC CC M OE DSC DSE VSC IKS OJT FP CES RP
            $table->foreign('subjectvertical_id')->references('id')->on('subjectverticals');

            $table->integer('subject_order')->default(NULL); //for maintaning the order of subject
            $table->string('subject_code',50)->default(NULL); //3
            $table->string('subject_name_prefix',50)->nullable()->default(NULL); //DSC-1
            $table->string('subject_name',100)->default(NULL); //2

            $table->bigInteger('subjectcategory_id')->unsigned()->nullable(); // Theory Practical
            $table->foreign('subjectcategory_id')->references('id')->on('subjectcategories');

            $table->string('subject_type',50)->default(NULL); //6  IE  - InternalExternal  IEP- Internal External Practical  IP-Internal Practical

            $table->float('subject_credit',4,1)->default(0); //7

            $table->integer('subject_maxmarks')->nullable()->default(0);  //8
            $table->integer('subject_maxmarks_int')->nullable()->default(0);//10
            $table->integer('subject_maxmarks_intpract')->nullable()->default(0);
            $table->integer('subject_maxmarks_ext')->nullable()->default(0);//9

            $table->tinyInteger('is_panel')->nullable()->default(1); //1 =>Yes ,0=>No
            $table->tinyInteger('no_of_sets')->nullable()->default(3); //3 =>3 Sets ,0=>No

            $table->integer('subject_totalpassing')->nullable()->default(0); //11
            $table->integer('subject_intpassing')->nullable()->default(0);//13
            $table->Integer('subject_intpractpassing')->nullable()->default(0);
            $table->integer('subject_extpassing')->nullable()->default(0);//12

            $table->char('subject_optionalgroup',3)->nullable(); //6 not confirmed

            $table->bigInteger('patternclass_id')->nullable()->unsigned()->default(null);
            $table->foreign('patternclass_id')->references('id')->on('pattern_classes');

            $table->bigInteger('classyear_id')->nullable()->unsigned()->default(null);
            $table->foreign('classyear_id')->references('id')->on('classyears');


            $table->bigInteger('user_id')->unsigned()->nullable()->default(null);  //User who add the subject
            $table->foreign('user_id')->references('id')->on('users');

            $table->bigInteger('faculty_id')->unsigned()->nullable()->default(null);  //Faculty who add the subject
            $table->foreign('faculty_id')->references('id')->on('faculties');

            $table->bigInteger('department_id')->nullable()->unsigned();  //Major Minor
            $table->foreign('department_id')->references('id')->on('departments');//subject belongs to department

            $table->bigInteger('college_id')->nullable()->unsigned()->default(null);
            $table->foreign('college_id')->references('id')->on('colleges');

            $table->tinyInteger('status')->default(1);//1 =>subject in use ,0=>not in use

            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
