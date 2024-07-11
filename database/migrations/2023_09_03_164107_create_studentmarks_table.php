<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentmarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('studentmarks', function (Blueprint $table) {
            $table->id();
            // $table->string('prn',100);
            $table->integer('seatno');
            $table->bigInteger('student_id')->unsigned();
            $table->foreign('student_id')->references('id')->on('students');
            $table->bigInteger('subject_id')->unsigned();
            $table->foreign('subject_id')->references('id')->on('subjects');
            $table->tinyInteger('sem');
            $table->Integer('int_marks')->nullable(); 
            $table->Integer('int_practical_marks')->nullable(); // if subject having option  int_Marks+int_Practical_Marks

            $table->Integer('ext_marks')->nullable(); ;  //Practical or Theory 
            $table->integer('ext_practical_marks')->nullable();    //if subject having  option Practical + Theory
            $table->Integer('practical_marks')->nullable();     //Only Practical
           
            $table->Integer('project_marks')->nullable(); //Only Project
            $table->string('oral')->nullable(); //Only Oral 
            $table->string('subject_grade',5)->nullable();
            $table->string('grade');  //Only Grade

            
            $table->Integer('grade_point')->default(0);
            $table->Integer('gpa')->default(0);
            $table->Integer('total')->nullable()->default(0);

            $table->tinyInteger('int_ordinace_flag')->default(0);  //Ordinace one flag defalt is 0 / 1 means ordinace one is applied to that subject
            $table->Integer('int_ordinance_one_marks')->default(0); 
            $table->tinyInteger('ext_ordinace_flag')->default(0);  //Ordinace one flag defalt is 0 / 1 means ordinace one is applied to that subject
            $table->Integer('ext_ordinance_one_marks')->default(0); 
            $table->tinyInteger('practical_ordinace_flag')->default(0);  //Ordinace one flag defalt is 0 / 1 means ordinace one is applied to that subject
            $table->Integer('practical_ordinance_one_marks')->default(0); 
            $table->tinyInteger('total_ordinace_flag')->default(0);  //Ordinace one flag defalt is 0 / 1 means ordinace one is applied to that subject
            $table->Integer('total_ordinance_one_marks')->default(0); 
            $table->Integer('total_ordinancefour_marks')->default(0); 
            
            $table->bigInteger('exam_id')->unsigned();
            $table->foreign('exam_id')->references('id')->on('exams');
            $table->bigInteger('patternclass_id')->unsigned();
            $table->foreign('patternclass_id')->references('id')->on('pattern_classes');
            $table->tinyInteger('performancecancel')->default('0'); 
            $table->unique(['student_id','subject_id','seatno','exam_id']);
            
            $table->timestamps();

            
        });

        DB::unprepared('DROP TRIGGER IF EXISTS insert_studentmarks_trigger');
        DB::unprepared('DROP TRIGGER IF EXISTS update_total_marks_and_grade_trigger');


        DB::unprepared("
            CREATE TRIGGER insert_studentmarks_trigger
            BEFORE INSERT ON studentmarks
            FOR EACH ROW
            BEGIN
                -- Fetch passing thresholds for the subject
                SELECT 
                    subject_intpassing, 
                    subject_extpassing, 
                    subject_intpractpassing, 
                    subject_totalpassing,
                    subject_credit
                INTO 
                    @passing_intthreshold,
                    @passing_extthreshold,
                    @passing_practthreshold,
                    @passing_totalthreshold,
                    @passing_subject_credit
                FROM subjects
                WHERE id = NEW.subject_id;
            
                -- Calculate total marks
                SET NEW.total = NEW.int_marks + NEW.ext_marks + NEW.int_practical_marks;
            
                -- Check if the subject is passing or failing
                IF NEW.int_marks >= @passing_intthreshold AND 
                NEW.ext_marks >= @passing_extthreshold AND 
                NEW.total >= @passing_totalthreshold THEN
                    -- Calculate grade based on total marks
                    IF NEW.total >= 90 THEN
                        SET NEW.grade = 'O';
                        SET NEW.grade_point = 10;
                    ELSEIF NEW.total >= 75 THEN
                        SET NEW.grade = 'A+';
                        SET NEW.grade_point = 9;
                    ELSEIF NEW.total >= 60 THEN
                        SET NEW.grade = 'A';
                        SET NEW.grade_point = 8;
                    ELSEIF NEW.total >= 55 THEN
                        SET NEW.grade = 'B+';
                        SET NEW.grade_point = 7;
                    ELSEIF NEW.total >= 50 THEN
                        SET NEW.grade = 'B';
                        SET NEW.grade_point = 6;
                    ELSEIF NEW.total >= 45 THEN
                        SET NEW.grade = 'C';
                        SET NEW.grade_point = 5;
                    ELSE
                        SET NEW.grade = 'D';
                        SET NEW.grade_point = 4;
                    END IF;
                    
                    -- Calculate GPA based on grade point and subject credit
                    SET NEW.gpa = NEW.grade_point * @passing_subject_credit;
                ELSE
                    -- Subject is failing
                    SET NEW.grade = 'F';
                    SET NEW.grade_point = 0;
                    SET NEW.gpa = 0;
                END IF;
            END 
        ");

        DB::unprepared("
            CREATE TRIGGER update_total_marks_and_grade_trigger BEFORE UPDATE ON studentmarks
            FOR EACH ROW
            BEGIN
                DECLARE passing_intthreshold INT;
                DECLARE passing_extthreshold INT;
                DECLARE passing_practthreshold INT;
                DECLARE passing_totalthreshold INT;
                DECLARE passing_subject_credit INT;
                DECLARE subject_total_marks INT;
                DECLARE positive_int_marks INT;
                DECLARE positive_ext_marks INT;
                DECLARE positive_int_practical_marks INT;
                DECLARE total_percentage FLOAT;
            
                -- Ensure marks are non-negative
                IF NEW.int_marks < 0 THEN
                    SET positive_int_marks = 0;
                ELSE
                    SET positive_int_marks = NEW.int_marks;
                END IF;
            
                IF NEW.ext_marks < 0 THEN
                    SET positive_ext_marks = 0;
                ELSE
                    SET positive_ext_marks = NEW.ext_marks;
                END IF;
            
                IF NEW.int_practical_marks < 0 THEN
                    SET positive_int_practical_marks = 0;
                ELSE
                    SET positive_int_practical_marks = NEW.int_practical_marks;
                END IF;
            
                -- Calculate total marks
                SET NEW.total = positive_int_marks + positive_ext_marks + positive_int_practical_marks;
            
                -- Fetch passing thresholds for the subject from the subjects table
                SELECT 
                    subject_intpassing, 
                    subject_extpassing, 
                    subject_intpractpassing, 
                    subject_totalpassing,
                    subject_credit,
                    subject_maxmarks
                INTO 
                    passing_intthreshold,
                    passing_extthreshold,
                    passing_practthreshold,
                    passing_totalthreshold,
                    passing_subject_credit,
                    subject_total_marks
                FROM subjects
                WHERE id = NEW.subject_id;
            
                -- Check if the subject is passing or failing
                IF positive_int_marks >= passing_intthreshold AND 
                positive_ext_marks >= passing_extthreshold AND 
                positive_int_practical_marks >= passing_practthreshold AND 
                NEW.total >= passing_totalthreshold THEN

                  IF subject_total_marks > 0 THEN
                    SET total_percentage = ( NEW.total /subject_total_marks )*100 ;
                ELSE
                    SET total_percentage = 0;
                END IF;



                    -- Calculate grade based on total marks
                    IF total_percentage >= 90 THEN
                        SET NEW.grade = 'O';
                        SET NEW.grade_point = 10;
                        SET NEW.gpa = 10 * passing_subject_credit;
                    ELSEIF total_percentage >= 75 THEN
                        SET NEW.grade = 'A+';
                        SET NEW.grade_point = 9;
                        SET NEW.gpa = 9 * passing_subject_credit;
                    ELSEIF total_percentage >= 60 THEN
                        SET NEW.grade = 'A';
                        SET NEW.grade_point = 8;
                        SET NEW.gpa = 8 * passing_subject_credit;
                    ELSEIF total_percentage >= 55 THEN
                        SET NEW.grade = 'B+';
                        SET NEW.grade_point = 7;
                        SET NEW.gpa = 7 * passing_subject_credit;
                    ELSEIF total_percentage >= 50 THEN
                        SET NEW.grade = 'B';
                        SET NEW.grade_point = 6;
                        SET NEW.gpa = 6 * passing_subject_credit;
                    ELSEIF total_percentage >= 45 THEN
                        SET NEW.grade = 'C';
                        SET NEW.grade_point = 5;
                        SET NEW.gpa = 5 * passing_subject_credit;
                    ELSEIF total_percentage >= 40 THEN
                        SET NEW.grade = 'D';
                        SET NEW.grade_point = 4;
                        SET NEW.gpa = 4 * passing_subject_credit;
                    ELSE
                        SET NEW.grade = 'F';
                        SET NEW.grade_point = 0;
                        SET NEW.gpa = 0;
                    END IF;
                ELSE
                    -- Subject is failing
                    SET NEW.grade = 'F';
                    SET NEW.grade_point = 0;
                    SET NEW.gpa = 0;
                END IF;
            END;
        ");
        // DB::unprepared("
        //     CREATE TRIGGER update_total_marks_and_grade_trigger BEFORE UPDATE ON studentmarks
        //     FOR EACH ROW
        //     BEGIN
        //         DECLARE passing_intthreshold INT;
        //         DECLARE passing_extthreshold INT;
        //         DECLARE passing_practthreshold INT;
        //         DECLARE passing_totalthreshold INT;
        //         DECLARE passing_subject_credit INT;
        //         DECLARE positive_int_marks INT;
        //         DECLARE positive_ext_marks INT;
        //         DECLARE positive_int_practical_marks INT;
            
        //         -- Ensure marks are non-negative
        //         IF NEW.int_marks < 0 THEN
        //             SET positive_int_marks = 0;
        //         ELSE
        //             SET positive_int_marks = NEW.int_marks;
        //         END IF;
            
        //         IF NEW.ext_marks < 0 THEN
        //             SET positive_ext_marks = 0;
        //         ELSE
        //             SET positive_ext_marks = NEW.ext_marks;
        //         END IF;
            
        //         IF NEW.int_practical_marks < 0 THEN
        //             SET positive_int_practical_marks = 0;
        //         ELSE
        //             SET positive_int_practical_marks = NEW.int_practical_marks;
        //         END IF;
            
        //         -- Calculate total marks
        //         SET NEW.total = positive_int_marks + positive_ext_marks + positive_int_practical_marks;
            
        //         -- Fetch passing thresholds for the subject from the subjects table
        //         SELECT 
        //             subject_intpassing, 
        //             subject_extpassing, 
        //             subject_intpractpassing, 
        //             subject_totalpassing,
        //             subject_credit
        //         INTO 
        //             passing_intthreshold,
        //             passing_extthreshold,
        //             passing_practthreshold,
        //             passing_totalthreshold,
        //             passing_subject_credit
        //         FROM subjects
        //         WHERE id = NEW.subject_id;
            
        //         -- Check if the subject is passing or failing
        //         IF positive_int_marks >= passing_intthreshold AND 
        //         positive_ext_marks >= passing_extthreshold AND 
        //         positive_int_practical_marks >= passing_practthreshold AND 
        //         NEW.total >= passing_totalthreshold THEN
        //             -- Calculate grade based on total marks
        //             IF NEW.total >= 90 THEN
        //                 SET NEW.grade = 'O';
        //                 SET NEW.grade_point = 10;
        //                 SET NEW.gpa = 10 * passing_subject_credit;
        //             ELSEIF NEW.total >= 75 THEN
        //                 SET NEW.grade = 'A+';
        //                 SET NEW.grade_point = 9;
        //                 SET NEW.gpa = 9 * passing_subject_credit;
        //             ELSEIF NEW.total >= 60 THEN
        //                 SET NEW.grade = 'A';
        //                 SET NEW.grade_point = 8;
        //                 SET NEW.gpa = 8 * passing_subject_credit;
        //             ELSEIF NEW.total >= 55 THEN
        //                 SET NEW.grade = 'B+';
        //                 SET NEW.grade_point = 7;
        //                 SET NEW.gpa = 7 * passing_subject_credit;
        //             ELSEIF NEW.total >= 50 THEN
        //                 SET NEW.grade = 'B';
        //                 SET NEW.grade_point = 6;
        //                 SET NEW.gpa = 6 * passing_subject_credit;
        //             ELSEIF NEW.total >= 45 THEN
        //                 SET NEW.grade = 'C';
        //                 SET NEW.grade_point = 5;
        //                 SET NEW.gpa = 5 * passing_subject_credit;
        //             ELSEIF NEW.total >= 40 THEN
        //                 SET NEW.grade = 'D';
        //                 SET NEW.grade_point = 4;
        //                 SET NEW.gpa = 4 * passing_subject_credit;
        //             ELSE
        //                 SET NEW.grade = 'F';
        //                 SET NEW.grade_point = 0;
        //                 SET NEW.gpa = 0;
        //             END IF;
        //         ELSE
        //             -- Subject is failing
        //             SET NEW.grade = 'F';
        //             SET NEW.grade_point = 0;
        //             SET NEW.gpa = 0;
        //         END IF;
        //     END;
        // ");
    }

    public function down()
    {   
        Schema::dropIfExists('studentmarks');
        DB::unprepared('DROP TRIGGER IF EXISTS insert_studentmarks_trigger');
        DB::unprepared('DROP TRIGGER IF EXISTS update_total_marks_and_grade_trigger');

    }
}
