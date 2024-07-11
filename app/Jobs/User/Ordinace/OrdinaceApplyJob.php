<?php

namespace App\Jobs\User\Ordinace;

use App\Models\Exam;
use App\Models\Gradepoint;
use App\Models\Studentmark;
use Livewire\WithPagination;
use App\Models\Studentresult;
use Illuminate\Bus\Queueable;
use App\Models\Exampatternclass;
use App\Models\Examstudentseatno;
use App\Models\Studentordinace163;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class OrdinaceApplyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Exampatternclass $exampatternclass;

    public function __construct($exampatternclass)
    {
        $this->exampatternclass = $exampatternclass;
    }

    public function handle(): void
    {
        DB::transaction(function () {

           $this->generate_final_result($this->exampatternclass);
        });
    }
    
    protected function generate_final_result(Exampatternclass $exampatternclass)
    {   

        $course_type = $exampatternclass->patternclass->courseclass->course->course_type;

        $student_seatnos = Examstudentseatno::with('student.currentclassstudents')->where('exam_patternclasses_id', $exampatternclass->id)->get();
             
        $this->collect_int_ext_marks($student_seatnos, $exampatternclass->exam_id);
       
        $this->apply_ordinace_one($student_seatnos, $exampatternclass->exam_id);

        if ($student_seatnos->count() > 0) 
        {
            foreach ($student_seatnos as $student_seatno) 
            {

                $currentclassstudents = $student_seatno->student->currentclassstudents;

                foreach ($currentclassstudents as $current_class_student) 
                {
                    //direct admission to second year 
                    if ($current_class_student->markssheetprint_status != -1) 
                    { 
                        if ($currentclassstudents->count() == 2) 
                        {
                            if ($current_class_student->sem == 1) 
                            {
                                $studresult = $student_seatno->student->studentresults->where('sem', 1)->last();

                                if (is_null($studresult)) 
                                {
                                    $subjects =$student_seatno->student->getsubjects($current_class_student->patternclass_id, $current_class_student->sem);

                                    $this->get_sgpa($student_seatno, $subjects,$exampatternclass->id, $current_class_student->sem,$exampatternclass->exam_id);

                                } else if ($studresult->sgpa == 0 ) 
                                {
                                    $subjects = $student_seatno->student->getsubjects($current_class_student->patternclass_id, $current_class_student->sem);

                                    $this->get_sgpa($student_seatno, $subjects,$exampatternclass->id, $current_class_student->sem,$exampatternclass->exam_id);
                                }

                            } else if ($current_class_student->sem == 2) 
                            {
                                $subjects = $student_seatno->student->getsubjects($current_class_student->patternclass_id, $current_class_student->sem);

                                $this->get_sgpa($student_seatno, $subjects,$exampatternclass->id, $current_class_student->sem,$exampatternclass->exam_id);

                            } else if ($current_class_student->sem == 3) 
                            {
                                $studresult = $student_seatno->student->studentresults->where('sem', 3)->last();

                                if (is_null($studresult)) 
                                {
                                    $subjects = $student_seatno->student->getsubjects($current_class_student->patternclass_id, $current_class_student->sem); 

                                    $this->get_sgpa($student_seatno, $subjects,$exampatternclass->id, $current_class_student->sem,$exampatternclass->exam_id);

                                } else if ($studresult->sgpa == 0 ) 
                                {
                                    $subjects = $student_seatno->student->getsubjects($current_class_student->patternclass_id, $current_class_student->sem); 

                                    $this->get_sgpa($student_seatno, $subjects,$exampatternclass->id, $current_class_student->sem,$exampatternclass->exam_id);
                                }

                            } else if ($current_class_student->sem == 4) 
                            {

                                $subjects = $student_seatno->student->getsubjects($current_class_student->patternclass_id, $current_class_student->sem);

                                $this->get_sgpa($student_seatno, $subjects,$exampatternclass->id, $current_class_student->sem,$exampatternclass->exam_id);

                            } else if ($current_class_student->sem == 5)
                            {
                                $studresult = $student_seatno->student->studentresults->where('sem',3)->last();
                               
                                if (is_null($studresult)) 
                                {
                                    $subjects = $student_seatno->student->getsubjects($current_class_student->patternclass_id, $current_class_student->sem);

                                    $this->get_sgpa($student_seatno, $subjects,$exampatternclass->id, $current_class_student->sem,$exampatternclass->exam_id);

                                } else if ($studresult->sgpa == 0 ) 
                                {
                                    $subjects = $student_seatno->student->getsubjects($current_class_student->patternclass_id, $current_class_student->sem);

                                    $this->get_sgpa($student_seatno, $subjects,$exampatternclass->id, $current_class_student->sem,$exampatternclass->exam_id);
                                }
                            } else if ($current_class_student->sem == 6)
                            {
                                $subjects = $student_seatno->student->getsubjects($current_class_student->patternclass_id, $current_class_student->sem);

                                $this->get_sgpa($student_seatno, $subjects,$exampatternclass->id, $current_class_student->sem,$exampatternclass->exam_id);
                            }

                        } else if ($currentclassstudents->count() == 1) 
                        {   
                            //direct fy, sy,ty admission

                            $subjects = $student_seatno->student->getsubjects($current_class_student->patternclass_id, $current_class_student->sem);

                            $this->get_sgpa($student_seatno, $subjects,$exampatternclass->id, $current_class_student->sem,$exampatternclass->exam_id);

                        }
                        else if ($currentclassstudents->count() == 3)
                        {   
                            // sy or direct to ty
                            if ($current_class_student->sem == 1 || $current_class_student->sem == 2) 
                            {
                                $studresult = $student_seatno->student->studentresults->where('sem', $current_class_student->sem)->last();

                                if ($studresult->resultstatus != 1) 
                                {

                                    $subjects = $student_seatno->student->getsubjects($current_class_student->patternclass_id, $current_class_student->sem);

                                    $this->get_sgpa($student_seatno, $subjects,$exampatternclass->id, $current_class_student->sem,$exampatternclass->exam_id);
                                }

                            } else if ($current_class_student->sem == 3 || $current_class_student->sem == 5) 
                            {
                                $studresult = $student_seatno->student->studentresults->where('sem', $current_class_student->sem)->last();

                                if (is_null($studresult) || $studresult->resultstatus != 1) 
                                {
                                    $subjects = $student_seatno->student->getsubjects($current_class_student->patternclass_id, $current_class_student->sem);

                                    $this->get_sgpa($student_seatno, $subjects,$exampatternclass->id, $current_class_student->sem,$exampatternclass->exam_id);
                                }

                            } else if ($current_class_student->sem == 4 || $current_class_student->sem == 6) 
                            {
                                $studresult = $student_seatno->student->studentresults->where('sem', $current_class_student->sem)->last();

                                if (is_null($studresult) || $studresult->resultstatus != 1) 
                                {
                                    $subjects = $student_seatno->student->getsubjects($current_class_student->patternclass_id, $current_class_student->sem);

                                    $this->get_sgpa($student_seatno, $subjects,$exampatternclass->id, $current_class_student->sem,$exampatternclass->exam_id);
                                }
                            }

                        } else if ($currentclassstudents->count() == 4) 
                        {   // sy 

                            if ($current_class_student->sem == 1 || $current_class_student->sem == 2 || $current_class_student->sem == 3 || $current_class_student->sem == 4 || $current_class_student->sem == 5 || $current_class_student->sem == 6) 
                            {
                                $studresult = $student_seatno->student->studentresults->where('sem', $current_class_student->sem)->last();
                                
                                if (is_null($studresult)) 
                                {
                                    $subjects = $student_seatno->student->getsubjects($current_class_student->patternclass_id, $current_class_student->sem);

                                    $this->get_sgpa($student_seatno, $subjects,$exampatternclass->id, $current_class_student->sem,$exampatternclass->exam_id);

                                } else if ($studresult->resultstatus != 1 ) {

                                    $subjects = $student_seatno->student->getsubjects($current_class_student->patternclass_id, $current_class_student->sem);

                                    $this->get_sgpa($student_seatno, $subjects,$exampatternclass->id, $current_class_student->sem,$exampatternclass->exam_id);
                                } else if ($studresult->sgpa == 0 ) {

                                    $subjects = $student_seatno->student->getsubjects($current_class_student->patternclass_id, $current_class_student->sem);

                                    $this->get_sgpa($student_seatno, $subjects,$exampatternclass->id, $current_class_student->sem,$exampatternclass->exam_id);
                                }
                            }
                            else if ($current_class_student->sem == 4) 
                            {
                                $subjects = $student_seatno->student->getsubjects($current_class_student->patternclass_id, $current_class_student->sem);

                                $this->get_sgpa($student_seatno, $subjects,$exampatternclass->id, $current_class_student->sem,$exampatternclass->exam_id);
                            }
                        } else if ($currentclassstudents->count() == 5) 
                        {   
                            // ty 
                            if ($current_class_student->sem == 1 || $current_class_student->sem == 2 || $current_class_student->sem == 3 || $current_class_student->sem == 4 || $current_class_student->sem == 5) 
                            {
                                $studresult =$student_seatno->student->studentresults->where('sem', $current_class_student->sem)->last();

                                if (is_null($studresult)) 
                                {
                                    $subjects =$student_seatno->student->getsubjects($current_class_student->patternclass_id, $current_class_student->sem);

                                    $this->get_sgpa($student_seatno, $subjects,$exampatternclass->id, $current_class_student->sem,$exampatternclass->exam_id);

                                } else if ($studresult->resultstatus != 1 ) {

                                    $subjects =$student_seatno->student->getsubjects($current_class_student->patternclass_id, $current_class_student->sem);

                                    $this->get_sgpa($student_seatno, $subjects,$exampatternclass->id, $current_class_student->sem,$exampatternclass->exam_id);

                                } else if ($studresult->sgpa == 0 ) 
                                {

                                    $subjects =$student_seatno->student->getsubjects($current_class_student->patternclass_id, $current_class_student->sem);

                                    $this->get_sgpa($student_seatno, $subjects,$exampatternclass->id, $current_class_student->sem,$exampatternclass->exam_id);
                                }
                            }
                            else if ($current_class_student->sem == 6) 
                            {
                                $subjects =$student_seatno->student->getsubjects($current_class_student->patternclass_id, $current_class_student->sem);

                                $this->get_sgpa($student_seatno, $subjects,$exampatternclass->id, $current_class_student->sem,$exampatternclass->exam_id);
                            }

                        } else if ($currentclassstudents->count() == 6) 
                        {   // ty 
                            if ($current_class_student->sem == 1 || $current_class_student->sem == 2 || $current_class_student->sem == 3 || $current_class_student->sem == 4 || $current_class_student->sem == 5) 
                            {
                                $studresult =$student_seatno->student->studentresults->where('sem', $current_class_student->sem)->last();

                                if (is_null($studresult)) 
                                {
                                    $subjects =$student_seatno->student->getsubjects($current_class_student->patternclass_id, $current_class_student->sem);

                                    $this->get_sgpa($student_seatno, $subjects,$exampatternclass->id, $current_class_student->sem,$exampatternclass->exam_id);

                                } else if ($studresult->resultstatus != 1 ) 
                                {


                                    $subjects =$student_seatno->student->getsubjects($current_class_student->patternclass_id, $current_class_student->sem);

                                    $this->get_sgpa($student_seatno, $subjects,$exampatternclass->id, $current_class_student->sem,$exampatternclass->exam_id);

                                } else if ($studresult->sgpa == 0 ) 
                                {
                                    $subjects =$student_seatno->student->getsubjects($current_class_student->patternclass_id, $current_class_student->sem); 

                                    $this->get_sgpa($student_seatno, $subjects,$exampatternclass->id, $current_class_student->sem,$exampatternclass->exam_id);
                                }
                            } else if ($current_class_student->sem == 6) 
                            {
                                $subjects =$student_seatno->student->getsubjects($current_class_student->patternclass_id, $current_class_student->sem);

                                $this->get_sgpa($student_seatno, $subjects,$exampatternclass->id, $current_class_student->sem,$exampatternclass->exam_id);
                            }
                        }
                    } 
                } 

                foreach ($currentclassstudents as $current_class_student) 
                {
                    if ($current_class_student->markssheetprint_status != -1) 
                    {

                        if ($current_class_student->pfstatus != 1) 
                        {
                            switch ($current_class_student->sem) 
                            {
                                case 2:
                                case 4:
                                case 6: 
                                
                                    $pfAbStatus = 1;

                                    $Sem1Data = Studentresult::where('student_id',$student_seatno->student_id)->where('sem', $current_class_student->sem - 1)->get()->last();
                                    $Sem2Data = Studentresult::where('student_id',$student_seatno->student_id)->where('sem', $current_class_student->sem)->get()->last();

                                    $x = $Sem1Data->semcreditearned + $Sem2Data->semcreditearned;
                                    $y = $Sem1Data->semtotalcredit + $Sem2Data->semtotalcredit;

                                    $z = $current_class_student->patternclass->credit;

                                    if ($x >= $z and $x < $y)
                                    {
                                        $pfAbStatus = 2; // Fail A.T.K.T
                                    }
                                    else if ($x < $z and $x >= 0) 
                                    {
                                        $pfAbStatus = 0; // Fail
                                    }

                                    if ($pfAbStatus == 1)
                                    {
                                        $pfAbStatus = 2; // Fail A.T.K.T
                                    }

                                    if ($pfAbStatus == 0)
                                    {
                                       $student_seatno->student->currentclassstudents->where('sem', $current_class_student->sem)->last()->update(['pfstatus' => $pfAbStatus, 'isregular' => 0,]);
                                    }
                                    else
                                    {
                                       $student_seatno->student->currentclassstudents->where('sem', $current_class_student->sem)->last()->update(['pfstatus' => $pfAbStatus,'isregular' => 1,]);
                                    }
                                break;
                            }
                        }
                    }
                }

                if ($course_type == "PG") 
                {
                    if ($student_seatno->student->currentclassstudents->last()->sem == 4)
                    {
                        $this->apply_ordinace_four($student_seatno,$exampatternclass->id,$exampatternclass->exam_id);
                    }   
                }

                if ($course_type == "UG") 
                {
                    if ($student_seatno->student->currentclassstudents->last()->sem == 6)
                    {
                        $this->apply_ordinace_four($student_seatno,$exampatternclass->id,$exampatternclass->exam_id);
                    }
                }

                $this->apply_ordinace_163($exampatternclass->exam_id);
                $this->apply_ordinace_two($student_seatno , $exampatternclass);
            }
        } 

    }

    protected function collect_int_ext_marks($student_seatnos, $exam_id)
    { 
        foreach ($student_seatnos as $student_seatno) 
        {   
            foreach ($student_seatno->student->studentmarks->where('exam_id', $exam_id) as $studentmarks) 
            {   
                if ($studentmarks->subject->subject_type != 'I' || $studentmarks->subject->subject_type != 'G') 
                {
                    if ($studentmarks->subject->subject_type === 'IEP') 
                    {
                        if (is_null($studentmarks->ext_marks)) 
                        {
                            $extstudmarks = $student_seatno->student->studentmarks->where('subject_id', $studentmarks->subject_id)->max('ext_marks');
                            $studentmarks->ext_marks = $extstudmarks;
                            $studentmarks->update();
                        }
                        
                        if (is_null($studentmarks->int_practical_marks)) 
                        {
                            $intpractstudmarks = $student_seatno->student->studentmarks->where('subject_id', $studentmarks->subject_id)->max('int_practical_marks');
                            $studentmarks->int_practical_marks =  $intpractstudmarks;
                            $studentmarks->update();
                        }
                        
                        if (is_null($studentmarks->int_marks)) {
                            $intstudmarks = $student_seatno->student->studentmarks->where('subject_id', $studentmarks->subject_id)->max('int_marks');
                            $studentmarks->int_marks = $intstudmarks;
                            
                            $studentmarks->update();
                        }
                        
                    } else 
                    {
                        if (!is_null($studentmarks->ext_marks) && is_null($studentmarks->int_marks)) 
                        {
                            $studmarks = $student_seatno->student->studentmarks->where('subject_id', $studentmarks->subject_id)->max('int_marks');
                            $studentmarks->int_marks = $studmarks;
                            $studentmarks->update();

                        }

                        if (is_null($studentmarks->ext_marks) and !is_null($studentmarks->int_marks)) 
                        {
                            $studmarks = $student_seatno->student->studentmarks->where('subject_id', $studentmarks->subject_id)->max('ext_marks');
                            $studentmarks->ext_marks = $studmarks;
                            $studentmarks->update();
                        }
                    }
                }
            }
        }
    }

    protected function apply_ordinace_one($student_seatnos, $exam_id)
    {   
        foreach ($student_seatnos as $student_seatno) 
        {
            $sem_1_class_ordinance = round(($student_seatno->exampatternclass->patternclass->sem1_total_marks) / 100);
            $sem_2_class_ordinance = round(($student_seatno->exampatternclass->patternclass->sem2_total_marks) / 100);

            foreach ($student_seatno->student->studentmarks->where('exam_id', $exam_id) as $studentmarks) 
            {
              
                if ($studentmarks->subject->subject_sem % 2 == 1) 
                {   
                    //Sem 1 3 5  odd sem
                   
                    $this->apply_ordinace($student_seatno, $studentmarks, $sem_1_class_ordinance);

                } else if ($studentmarks->subject->subject_sem % 2 == 0) 
                {
                    //sem 2 4 6  Even Sem

                    $this->apply_ordinace($student_seatno, $studentmarks, $sem_2_class_ordinance);
                }
            } 
        }
    }

    protected function apply_ordinace($student_seatno, $studentmarks, $sem_class_ordinance)
    {
        $total_marks = 0;

        if ($studentmarks->grade == 'F' || $studentmarks->grade == '-1' || is_null($studentmarks->grade) || $studentmarks->grade == 'Ab') 
        {
           
            if ($studentmarks->subject->subject_type == "G")
            {
                $studentmarks->grade = $studentmarks->subject_grade;

                $this->update_marks($studentmarks);

            } else 
            {
                
                $allpass = 0;

                if ($studentmarks->subject->subject_type == "IEP") 
                {
                    if ($studentmarks->ext_marks >= $studentmarks->subject->subject_extpassing && $studentmarks->int_marks >= $studentmarks->subject->subject_intpassing && $studentmarks->int_practical_marks >= $studentmarks->subject->subject_intpractpassing && $studentmarks->total >= $studentmarks->subject->subject_totalpassing) {
                        $allpass = 1;
                        if (($studentmarks->ext_marks + $studentmarks->int_marks + $studentmarks->int_practical_marks) != $studentmarks->total)
                        {
                            $allpass = 0;
                        }
                    }

                } else if ($studentmarks->subject->subject_type == "I" || $studentmarks->subject->subject_type == "IG") 
                {

                    if ($studentmarks->int_marks >= $studentmarks->subject->subject_intpassing && $studentmarks->total >= $studentmarks->subject->subject_totalpassing) 
                    {
                        $allpass = 1;

                        if ($studentmarks->int_marks != $studentmarks->total)
                        {
                            $allpass = 0;
                        }
                    }

                } else if ($studentmarks->ext_marks >= $studentmarks->subject->subject_extpassing && $studentmarks->int_marks >= $studentmarks->subject->subject_intpassing  && $studentmarks->total >= $studentmarks->subject->subject_totalpassing) 
                {
                    $allpass = 1;

                    if (($studentmarks->ext_marks + $studentmarks->int_marks) != $studentmarks->total)
                    {
                        $allpass = 0;
                    }

                } else 
                {

                    $allpass = 0;
                }
                
               
                if ($allpass == 0)  
                {   
                    //fail

                    $count = 0;
                    $absetflag = 0;

                    $total_marks = 0;
                    if ($studentmarks->ext_marks == -1 || $studentmarks->int_marks == -1 || $studentmarks->int_practical_marks == -1)
                    {
                        $absetflag = 1;
                    }

                    if ($studentmarks->ext_marks == -1 and $studentmarks->int_marks == -1)
                    {
                        $total_marks = -1;
                    }

                    if ($studentmarks->ext_marks != -1 and $studentmarks->int_marks != -1)
                    {
                        $total_marks = $studentmarks->ext_marks + $studentmarks->int_marks;
                    }
                    else if ($studentmarks->ext_marks == -1)
                    {
                        $total_marks = $studentmarks->int_marks;
                    }
                    else if ($studentmarks->int_marks == -1)
                    {
                        $total_marks = $studentmarks->ext_marks;
                    }

                    if ($studentmarks->subject->subject_type == "I" or $studentmarks->subject->subject_type == "IG") 
                    {
                        if ($studentmarks->int_marks == -1)
                        {
                            $total_marks = 0;
                        }
                        else
                        {
                            $total_marks = $studentmarks->int_marks;
                        }
                    }

                    if ($studentmarks->subject->subject_type == "IEP") 
                    {
                        if ($total_marks == -1 && $studentmarks->int_practical_marks == -1)
                        {

                            $total_marks = -1; //all IEP abscent
                        }


                        if ($total_marks != -1 && $studentmarks->int_practical_marks != -1)
                        {
                            $total_marks += $studentmarks->int_practical_marks;

                        }
                    }

                    // Both internal of External not Absent
                    if ($absetflag == 0) 
                    {

                        if ($studentmarks->ext_marks < $studentmarks->subject->subject_extpassing || $studentmarks->int_marks < $studentmarks->subject->subject_intpassing || $total_marks < $studentmarks->subject->subject_totalpassing) 
                        {
                            
                            if ($studentmarks->subject->subject_maxmarks <= 50)
                            {
                                $grace_marks = 2;
                            }
                            else if ($studentmarks->subject->subject_maxmarks <= 100)
                            {
                                $grace_marks = 3;
                            }
                            else if ($studentmarks->subject->subject_maxmarks <= 150)
                            {
                                $grace_marks = 4;
                            }
                            else if ($studentmarks->subject->subject_maxmarks <= 200)
                            {
                                $grace_marks = 5;
                            }
                            else if ($studentmarks->subject->subject_maxmarks <= 250)
                            {
                                $grace_marks = 6;
                            }
                            else if ($studentmarks->subject->subject_maxmarks <= 300)
                            {
                                $grace_marks = 7;
                            }
                            else if ($studentmarks->subject->subject_maxmarks <= 350)
                            {
                                $grace_marks = 8;
                            }
                            else if ($studentmarks->subject->subject_maxmarks <= 400)
                            {
                                $grace_marks = 9;
                            }

                            $extdollarmark = $studentmarks->subject->subject_extpassing - $studentmarks->ext_marks;
                            
                            $intdollarmark = $studentmarks->subject->subject_intpassing - $studentmarks->int_marks;

                            if ($studentmarks->subject->subject_type == "IEP")
                            {
                                $intpractdollarmark = $studentmarks->subject->subject_intpractpassing - $studentmarks->int_practical_marks;
                            }

                            //Get Prev ordinace from database 
                            
                            $count = $studentmarks->where('student_id', $studentmarks->student_id)->where('sem', $studentmarks->subject->subject_sem)->groupby('sem')->sum('ext_ordinance_one_marks');
                            
                            $count += $studentmarks->where('student_id', $studentmarks->student_id)->where('sem', $studentmarks->subject->subject_sem)->groupby('sem')->sum('int_ordinance_one_marks');
                            
                            $count += $studentmarks->where('student_id', $studentmarks->student_id)->where('sem', $studentmarks->subject->subject_sem)->groupby('sem')->sum('practical_ordinance_one_marks');
                            
                            
                            $grace_counter = 0;
                            
                            $grace_counter = $studentmarks->ext_ordinance_one_marks + $studentmarks->int_ordinance_one_marks;
                                                       
                            if ($studentmarks->subject->subject_type == "IEP")
                            {
                                
                                $grace_counter += $studentmarks->practical_ordinance_one_marks;
                            }
                            
                            // Give Ext Grance Marks
                            if ($extdollarmark > 0 && ($extdollarmark + $grace_counter) <= $grace_marks) 
                            {   

                                if ($count <= $sem_class_ordinance and $extdollarmark <= ($sem_class_ordinance - $count)) 
                                {
                                    $studentmarks->ext_ordinace_flag = 1;
                                    $studentmarks->ext_marks = $studentmarks->ext_marks + $extdollarmark;
                                    $studentmarks->ext_ordinance_one_marks = $extdollarmark;
                                    $total_marks += $extdollarmark;
                                    $count += $extdollarmark;
                                    $grace_counter += $extdollarmark;
                                }
                            }

                            // Give Int Practical Grance Marks
                            if ($studentmarks->subject->subject_type == "IEP") 
                            {
                                if ($intpractdollarmark > 0 and ($intpractdollarmark + $grace_counter) <= $grace_marks) 
                                {
                                    if ($count <= $sem_class_ordinance and $intpractdollarmark <= ($sem_class_ordinance - $count)) 
                                    {
                                        $studentmarks->practical_ordinace_flag = 1;
                                        $studentmarks->int_practical_marks = $studentmarks->int_practical_marks + $intpractdollarmark;
                                        $studentmarks->practical_ordinance_one_marks = $intpractdollarmark;
                                        $total_marks += $intpractdollarmark;
                                        $count += $intpractdollarmark;
                                        $grace_counter += $intpractdollarmark;
                                    }
                                }
                            }

                            // Give Int Grance Marks
                            if ($intdollarmark > 0 and ($intdollarmark + $grace_counter) <= $grace_marks) 
                            {
                                if ($count <= $sem_class_ordinance and $intdollarmark <= ($sem_class_ordinance - $count)) 
                                {
                                    $studentmarks->int_ordinace_flag = 1;
                                    $studentmarks->int_marks = $studentmarks->int_marks + $intdollarmark;
                                    $studentmarks->int_ordinance_one_marks = $intdollarmark;
                                    $total_marks += $intdollarmark;
                                }
                            }
                        }

                    }

                    if ($studentmarks->subject->subject_type == "I" or $studentmarks->subject->subject_type == "IG")
                    {
                        if ($studentmarks->int_marks == -1) 
                        {   
                            //for Absent
                            $total_marks = -1;
                            $studentmarks->grade = "-1";

                        } else 
                        {
                            $total_marks = $studentmarks->int_marks;
                            
                            if ($studentmarks->int_marks >= $studentmarks->subject->subject_intpassing)
                            {
                                $per = (($studentmarks->int_marks) / ($studentmarks->subject->subject_maxmarks)) * 100;
                            }
                            else
                            {
                                $per = 0;
                            }

                            $gradepoints = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade_name')->get();

                            foreach ($gradepoints as $gradepoint) 
                            {
                                $studentmarks->grade = $gradepoint->grade_name;
                                $studentmarks->grade_point = $gradepoint->grade_point;
                            }
                        }

                    } else 
                    {
                        if ($studentmarks->int_marks >= $studentmarks->subject->subject_intpassing and $studentmarks->ext_marks >= $studentmarks->subject->subject_extpassing) 
                        {
                            if ($studentmarks->subject->subject_type == "IEP") 
                            {
                                if ($studentmarks->int_practical_marks >= $studentmarks->subject->subject_intpractpassing)
                                {
                                    $per = (($studentmarks->int_marks + $studentmarks->ext_marks + $studentmarks->int_practical_marks) / ($studentmarks->subject->subject_maxmarks)) * 100;
                                }
                                else 
                                {
                                    $per = 0;
                                }

                            } else 
                            {

                                $per = (($studentmarks->int_marks + $studentmarks->ext_marks) / ($studentmarks->subject->subject_maxmarks)) * 100;
                            }

                        } 
                        else
                        {
                            $per = 0;
                        }
                        
                        $gradepoints = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade_name')->get();

                        foreach ($gradepoints as $gradepoint) 
                        {
                            $studentmarks->grade = $gradepoint->grade_name;
                            $studentmarks->grade_point = $gradepoint->grade_point;
                        }
                    }
                    $studentmarks->total = $total_marks;
                    
                    $this->update_marks($studentmarks);
                   
                } 
            } 
        } 
    }

    protected function update_marks(Studentmark $studentmarks)
    {
        $studentmarks->update();
    }

    protected function apply_ordinace_four($student_seatno,$exampatternclass_id,$exam_id)
    {
        $count = 0;
        $studentresult = null;
        $count_subject_fail = 0;
        $subjects_data = null;

        foreach ($student_seatno->studentresults->groupby('sem') as $student_result) 
        {
            if ($student_result->last()->sgpa == 0 || $student_result->last()->extracreditsstatus == 0) 
            {
                $count++;
                $studentresult = $student_result;
            }
        }
    
        if ($count == 1) 
        {
    
            $studentmarks = $studentresult->last()->student->studentmarks->where('patternclass_id', '<=', $studentresult->last()->exampatternclass->patternclass->id);
        
            foreach ($studentmarks->groupby('subject_id') as $studentmark) 
            {
                if ($studentmark->last()->grade == 'F' || $studentmark->last()->grade == 'Ab' || $studentmark->last()->grade == -1 || $studentmark->last()->performancecancel ==1) 
                { 
                    $count_subject_fail++;
                    $subjects_data = $studentmark;
                }
            }

            if ($count_subject_fail == 1) 
            {
    
    
                $ordinancelimit = 10;

                $prevordinaceone = $subjects_data->last()->int_ordinance_one_marks + $subjects_data->last()->practical_ordinance_one_marks + $subjects_data->last()->ext_ordinance_one_marks + $subjects_data->last()->total_ordinance_one_marks;
        
        
                $newordinacelimit = $ordinancelimit - $subjects_data->last()->int_ordinance_one_marks + $subjects_data->last()->practical_ordinance_one_marks + $subjects_data->last()->ext_ordinance_one_marks + $subjects_data->last()->total_ordinance_one_marks;
        
                if (($subjects_data->last()->subject->subject_totalpassing - $subjects_data->last()->total) <= $newordinacelimit) 
                {
                
                    //Internal Ordinace 

                    $int_shortfall = 0;
                    $ext_shortfall = 0;
                    $intpract_shortfall = 0;

                    switch ($subjects_data->last()->subject->subject_type) 
                    {
                        case 'IE':
                        case 'IP':
                        case 'IEG': 
                            //internal fail Ext Pass
            
                            if ( $subjects_data->last()->int_marks < $subjects_data->last()->subject->subject_intpassing && $subjects_data->last()->ext_marks >= $subjects_data->last()->subject->subject_extpassing) 
                            {
            
                                $int_shortfall = $subjects_data->last()->subject->subject_intpassing -  $subjects_data->last()->int_marks;
                
                                $shortfall =  $int_shortfall + $ext_shortfall;

                                if ($shortfall <=  $newordinacelimit) 
                                {
                                    $total_marks = $subjects_data->last()->ext_marks + $subjects_data->last()->int_marks + $shortfall;
                
                                    $per = ($total_marks / ($subjects_data->last()->subject->subject_maxmarks)) * 100;
                
                                    $gradepoints = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade_name')->get();
                
                                    $ordcheck = $subjects_data->last()->update([
                                        'int_marks' => $subjects_data->last()->int_marks + $int_shortfall,
                                        'grade_point' => $gradepoints->first()->grade_point,
                                        'total' => $total_marks,
                                        'grade' => $gradepoints->first()->grade_name,
                                        'int_ordinace_flag' => 4,
                                        'int_ordinance_one_marks' => $int_shortfall,
                                        'total_ordinancefour_marks' => $subjects_data->last()->total_ordinancefour_marks + $shortfall,
                                    ]);
                                }
                            }

                            //External fail

                            if ( $subjects_data->last()->ext_marks < $subjects_data->last()->subject->subject_extpassing && $subjects_data->last()->int_marks >= $subjects_data->last()->subject->subject_intpassing ) 
                            {
                                $ext_shortfall = $subjects_data->last()->subject->subject_extpassing -  $subjects_data->last()->ext_marks;
                
                                $shortfall =  $int_shortfall + $ext_shortfall;

                                if ($shortfall <=  $newordinacelimit) 
                                {
                                    $total_marks = $subjects_data->last()->ext_marks + $subjects_data->last()->int_marks + $shortfall;
                
                                    $per = ($total_marks / ($subjects_data->last()->subject->subject_maxmarks)) * 100;
                
                                    $gradepoints = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade_name')->get();
                
                                    $ordcheck = $subjects_data->last()->update([
                                        'ext_marks' => $subjects_data->last()->ext_marks + $ext_shortfall,
                                        'grade_point' => $gradepoints->first()->grade_point,
                                        'total' => $total_marks,
                                        'grade' => $gradepoints->first()->grade_name,
                                        'ext_ordinace_flag' => 4,
                                        'ext_ordinance_one_marks' => $ext_shortfall,
                                        'total_ordinancefour_marks' => $subjects_data->last()->total_ordinancefour_marks + $shortfall,
                                    ]);
                                }
                            }

                            //Int & Ext Fails

                            if ($subjects_data->last()->ext_marks < $subjects_data->last()->subject->subject_extpassing && $subjects_data->last()->int_marks < $subjects_data->last()->subject->subject_intpassing) 
                            {
                                $int_shortfall = $subjects_data->last()->subject->subject_intpassing -  $subjects_data->last()->int_marks;
                                $ext_shortfall = $subjects_data->last()->subject->subject_extpassing -  $subjects_data->last()->ext_marks;
                
                                $shortfall =  $int_shortfall + $ext_shortfall;
                
                                if ($shortfall <=  $newordinacelimit) 
                                {
                                    $total_marks = $subjects_data->last()->ext_marks + $subjects_data->last()->int_marks + $shortfall;
                
                                    $per = ($total_marks / ($subjects_data->last()->subject->subject_maxmarks)) * 100;
                
                                    $gradepoints = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade_name')->get();
                
                                    $ordcheck = $subjects_data->last()->update([
                                        'int_marks' => $subjects_data->last()->int_marks + $int_shortfall,
                                        'ext_marks' => $subjects_data->last()->ext_marks + $ext_shortfall,
                                        'grade_point' => $gradepoints->first()->grade_point,
                                        'total' => $total_marks,
                                        'grade' => $gradepoints->first()->grade_name,
                                        'int_ordinace_flag' => 4,
                                        'int_ordinance_one_marks' => $int_shortfall,
                                        'ext_ordinace_flag' => 4,
                                        'ext_ordinance_one_marks' => $ext_shortfall,
                                        'total_ordinancefour_marks' => $subjects_data->last()->total_ordinancefour_marks + $shortfall,
                                    ]);
                                }
                            }

                        break;
        
                        case 'IEP':
            
                            // Only Internal Fail

                            if (  $subjects_data->last()->int_marks < $subjects_data->last()->subject->subject_intpassing && $subjects_data->last()->int_practical_marks >= $subjects_data->last()->subject->subject_intpractpassing && $subjects_data->last()->ext_marks >= $subjects_data->last()->subject->subject_extpassing) 
                            {
            
                                $int_shortfall = $subjects_data->last()->subject->subject_intpassing -  $subjects_data->last()->int_marks;
                
                                $shortfall =  $int_shortfall + $ext_shortfall + $intpract_shortfall;
                                if ($shortfall <=  $newordinacelimit) 
                                {
                                    $total_marks = $subjects_data->last()->ext_marks + $subjects_data->last()->int_marks + $subjects_data->last()->int_practical_marks + $shortfall;
                
                                    $per = ($total_marks / ($subjects_data->last()->subject->subject_maxmarks)) * 100;
                
                                    $gradepoints = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade_name')->get();
                
                                    $ordcheck = $subjects_data->last()->update([
                                        'int_marks' => $subjects_data->last()->int_marks + $int_shortfall,
                                        'grade_point' => $gradepoints->first()->grade_point,
                                        'total' => $total_marks,
                                        'grade' => $gradepoints->first()->grade_name,
                                        'int_ordinace_flag' => 4,
                                        'int_ordinance_one_marks' => $int_shortfall,
                                        'total_ordinancefour_marks' => $subjects_data->last()->total_ordinancefour_marks + $shortfall,
                                    ]);
                                }
                            }

                            //Only Internal Practical fail

                            if ( $subjects_data->last()->int_marks >= $subjects_data->last()->subject->subject_intpassing && $subjects_data->last()->int_practical_marks < $subjects_data->last()->subject->subject_intpractpassing  && $subjects_data->last()->ext_marks >= $subjects_data->last()->subject->subject_extpassing) 
                            {
            
                                $intpract_shortfall = $subjects_data->last()->subject->subject_intpractpassing -  $subjects_data->last()->int_practical_marks;
                
                                $shortfall =  $int_shortfall + $ext_shortfall + $intpract_shortfall;
                                
                                if ($shortfall <=  $newordinacelimit) 
                                {
                                    $total_marks = $subjects_data->last()->ext_marks + $subjects_data->last()->int_marks + $subjects_data->last()->int_practical_marks + $shortfall;
                
                                    $per = ($total_marks / ($subjects_data->last()->subject->subject_maxmarks)) * 100;
                
                                    $gradepoints = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade_name')->get();
                
                                    $ordcheck = $subjects_data->last()->update([
                                        'int_practical_marks' => $subjects_data->last()->int_practical_marks + $intpract_shortfall,
                                        'grade_point' => $gradepoints->first()->grade_point,
                                        'total' => $total_marks,
                                        'grade' => $gradepoints->first()->grade_name,
                                        'practical_ordinace_flag' => 4,
                                        'practical_ordinance_one_marks' => $intpract_shortfall,
                                        'total_ordinancefour_marks' => $subjects_data->last()->total_ordinancefour_marks + $shortfall,
                                    ]);
                                }
                            }
            
            
                            //Only Externalfail  fail

                            if ( $subjects_data->last()->int_marks >= $subjects_data->last()->subject->subject_intpassing && $subjects_data->last()->int_practical_marks >= $subjects_data->last()->subject->subject_intpractpassing && $subjects_data->last()->ext_marks < $subjects_data->last()->subject->subject_extpassing) 
                            {
                                $ext_shortfall = $subjects_data->last()->subject->subject_extpassing -  $subjects_data->last()->ext_marks;
                
                                $shortfall =  $int_shortfall + $ext_shortfall + $intpract_shortfall;

                                if ($shortfall <=  $newordinacelimit) 
                                {
                                    $total_marks = $subjects_data->last()->ext_marks + $subjects_data->last()->int_marks + $subjects_data->last()->int_practical_marks + $shortfall;
                
                                    $per = ($total_marks / ($subjects_data->last()->subject->subject_maxmarks)) * 100;
                
                                    $gradepoints = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade_name')->get();
                
                                    $ordcheck = $subjects_data->last()->update([
                                        'ext_marks' => $subjects_data->last()->ext_marks + $ext_shortfall,
                                        'grade_point' => $gradepoints->first()->grade_point,
                                        'total' => $total_marks,
                                        'grade' => $gradepoints->first()->grade_name,
                                        'ext_ordinace_flag' => 4,
                                        'ext_ordinance_one_marks' => $ext_shortfall,
                                        'total_ordinancefour_marks' => $subjects_data->last()->total_ordinancefour_marks + $shortfall,
                                    ]);
                                }
                            }
            
            
                            //  Internal and External Fail

                            if ($subjects_data->last()->int_marks < $subjects_data->last()->subject->subject_intpassing && $subjects_data->last()->int_practical_marks >= $subjects_data->last()->subject->subject_intpractpassing && $subjects_data->last()->ext_marks < $subjects_data->last()->subject->subject_extpassing) 
                            {
            
                                $int_shortfall = $subjects_data->last()->subject->subject_intpassing -  $subjects_data->last()->int_marks;
                                $intpract_shortfall = 0;
                                $ext_shortfall = $subjects_data->last()->subject->subject_extpassing -  $subjects_data->last()->ext_marks;
                
                                $shortfall =  $int_shortfall + $ext_shortfall + $intpract_shortfall;

                                if ($shortfall <=  $newordinacelimit) 
                                {
                                    $total_marks = $subjects_data->last()->ext_marks + $subjects_data->last()->int_marks + $subjects_data->last()->int_practical_marks + $shortfall;
                
                                    $per = ($total_marks / ($subjects_data->last()->subject->subject_maxmarks)) * 100;
                
                                    $gradepoints = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade_name')->get();
                
                                    $ordcheck = $subjects_data->last()->update([
                                        'int_marks' => $subjects_data->last()->int_marks + $int_shortfall,
                                        'ext_marks' => $subjects_data->last()->ext_marks + $ext_shortfall,
                                        'grade_point' => $gradepoints->first()->grade_point,
                                        'total' => $total_marks,
                                        'grade' => $gradepoints->first()->grade_name,
                                        'int_ordinace_flag' => 4,
                                        'int_ordinance_one_marks' => $int_shortfall,
                                        'ext_ordinace_flag' => 4,
                                        'ext_ordinance_one_marks' => $ext_shortfall,
                                        'total_ordinancefour_marks' => $subjects_data->last()->total_ordinancefour_marks + $shortfall,
                                    ]);
                                }
                            }
            
                            //  Internal and Practical Fail

                            if ( $subjects_data->last()->int_marks < $subjects_data->last()->subject->subject_intpassing && $subjects_data->last()->int_practical_marks < $subjects_data->last()->subject->subject_intpractpassing  && $subjects_data->last()->ext_marks >= $subjects_data->last()->subject->subject_extpassing) 
                            {
                
                                $int_shortfall = $subjects_data->last()->subject->subject_intpassing -  $subjects_data->last()->int_marks;
                                $intpract_shortfall = $subjects_data->last()->subject->subject_intpractpassing -  $subjects_data->last()->int_practical_marks;
                                $ext_shortfall = 0;
                
                                $shortfall =  $int_shortfall + $ext_shortfall + $intpract_shortfall;

                                if ($shortfall <=  $newordinacelimit) 
                                {
                                    $total_marks = $subjects_data->last()->ext_marks + $subjects_data->last()->int_marks + $subjects_data->last()->int_practical_marks + $shortfall;
                
                                    $per = ($total_marks / ($subjects_data->last()->subject->subject_maxmarks)) * 100;
                
                                    $gradepoints = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade_name')->get();
                
                                    $ordcheck = $subjects_data->last()->update([
                                        'int_marks' => $subjects_data->last()->int_marks + $int_shortfall,
                                        'int_practical_marks' => $subjects_data->last()->int_practical_marks + $intpract_shortfall,
                                        'grade_point' => $gradepoints->first()->grade_point,
                                        'total' => $total_marks,
                                        'grade' => $gradepoints->first()->grade_name,
                                        'int_ordinace_flag' => 4,
                                        'int_ordinance_one_marks' => $int_shortfall,
                                        'practical_ordinace_flag' => 4,
                                        'practical_ordinance_one_marks' => $intpract_shortfall,
                                        'total_ordinancefour_marks' => $subjects_data->last()->total_ordinancefour_marks + $shortfall,
                                    ]);
                                }
                            }
            
                            //  External and Int Practical Fail

                            if ( $subjects_data->last()->int_marks >= $subjects_data->last()->subject->subject_intpassing && $subjects_data->last()->int_practical_marks < $subjects_data->last()->subject->subject_intpractpassing && $subjects_data->last()->ext_marks < $subjects_data->last()->subject->subject_extpassing) 
                            {
            
                                $int_shortfall = 0;
                                $intpract_shortfall = $subjects_data->last()->subject->subject_intpractpassing -  $subjects_data->last()->int_practical_marks;
                
                                $ext_shortfall = $subjects_data->last()->subject->subject_extpassing -  $subjects_data->last()->ext_marks;
                
                                $shortfall =  $int_shortfall + $ext_shortfall + $intpract_shortfall;

                                if ($shortfall <=  $newordinacelimit) 
                                {
                                    $total_marks = $subjects_data->last()->ext_marks + $subjects_data->last()->int_marks + $subjects_data->last()->int_practical_marks + $shortfall;
                
                                    $per = ($total_marks / ($subjects_data->last()->subject->subject_maxmarks)) * 100;
                
                                    $gradepoints = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade_name')->get();
                
                                    $ordcheck = $subjects_data->last()->update([
                                        'int_practical_marks' => $subjects_data->last()->int_practical_marks + $intpract_shortfall,
                                        'ext_marks' => $subjects_data->last()->ext_marks + $ext_shortfall,
                                        'grade_point' => $gradepoints->first()->grade_point,
                                        'total' => $total_marks,
                                        'grade' => $gradepoints->first()->grade_name,
                                        'practical_ordinace_flag' => 4,
                                        'practical_ordinance_one_marks' => $intpract_shortfall,
                                        'ext_ordinace_flag' => 4,
                                        'ext_ordinance_one_marks' => $ext_shortfall,
                                        'total_ordinancefour_marks' => $subjects_data->last()->total_ordinancefour_marks + $shortfall,
                                    ]);
                                }
                            }
            
            
                            // Internal , External and Int Practical Fails

                            if ( $subjects_data->last()->int_marks < $subjects_data->last()->subject->subject_intpassing && $subjects_data->last()->int_practical_marks < $subjects_data->last()->subject->subject_intpractpassing  && $subjects_data->last()->ext_marks < $subjects_data->last()->subject->subject_extpassing) 
                            {
                
                                $int_shortfall = $subjects_data->last()->subject->subject_intpassing -  $subjects_data->last()->int_marks;
                
                                $intpract_shortfall = $subjects_data->last()->subject->subject_intpractpassing -  $subjects_data->last()->int_practical_marks;
                
                                $ext_shortfall = $subjects_data->last()->subject->subject_extpassing -  $subjects_data->last()->ext_marks;
                
                                $shortfall =  $int_shortfall + $ext_shortfall + $intpract_shortfall;

                                if ($shortfall <=  $newordinacelimit) 
                                {
                                    $total_marks = $subjects_data->last()->ext_marks + $subjects_data->last()->int_marks + $subjects_data->last()->int_practical_marks + $shortfall;
                
                                    $per = ($total_marks / ($subjects_data->last()->subject->subject_maxmarks)) * 100;
                
                                    $gradepoints = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade_name')->get();
                
                                    $ordcheck = $subjects_data->last()->update([
                                        'int_marks' => $subjects_data->last()->int_marks + $int_shortfall,
                                        'int_practical_marks' => $subjects_data->last()->int_practical_marks + $intpract_shortfall,
                                        'ext_marks' => $subjects_data->last()->ext_marks + $ext_shortfall,
                                        'grade_point' => $gradepoints->first()->grade_point,
                                        'total' => $total_marks,
                                        'grade' => $gradepoints->first()->grade_name,
                                        'int_ordinace_flag' => 4,
                                        'int_ordinance_one_marks' => $int_shortfall,
                                        'practical_ordinace_flag' => 4,
                                        'practical_ordinance_one_marks' => $intpract_shortfall,
                                        'ext_ordinace_flag' => 4,
                                        'ext_ordinance_one_marks' => $ext_shortfall,
                                        'total_ordinancefour_marks' => $subjects_data->last()->total_ordinancefour_marks + $shortfall,
                                    ]);
                                }
                            }
                        break;
                        
                        case 'I':
                        case 'IG':
            
                            if ($subjects_data->last()->int_marks < $subjects_data->last()->subject->subject_intpassing) 
                            {
            
                                $int_shortfall = $subjects_data->last()->subject->subject_intpassing -  $subjects_data->last()->int_marks;
                
                                $shortfall =  $int_shortfall;

                                if ($shortfall <=  $newordinacelimit) 
                                {
                                    $total_marks =  $subjects_data->last()->int_marks + $shortfall;
                
                                    $per = ($total_marks / ($subjects_data->last()->subject->subject_maxmarks)) * 100;
                
                                    $gradepoints = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade_name')->get();
                
                                    $ordcheck = $subjects_data->last()->update([
                                        'int_marks' => $subjects_data->last()->int_marks + $int_shortfall,
                                        'grade_point' => $gradepoints->first()->grade_point,
                                        'total' => $total_marks,
                                        'grade' => $gradepoints->first()->grade_name,
                                        'int_ordinace_flag' => 4,
                                        'int_ordinance_one_marks' => $int_shortfall,
                                        'total_ordinancefour_marks' => $subjects_data->last()->total_ordinancefour_marks + $shortfall,
                                    ]);
                                }
                            }
                        break;
                        
                    }
                }
    
                $subjects = $student_seatno->student->getsubjects($studentresult->last()->exampatternclass->patternclass->id, $subjects_data->last()->subject->subject_sem);

                $this->get_sgpa($student_seatno, $subjects, $exampatternclass_id, $subjects_data->first()->subject->subject_sem, $exam_id);
            }
        }
    }

    protected function get_sgpa($student_seatno, $subjects,$exampatternclass_id, $sem,$exam_id)
    {
        $pf = 0;
        $total_credit_point = 0;
        $total_subject_credit = 0;
        $Absentcnt = 0;
        $subjectCnt = 0;
        $total_credit_earned = 0.0;
        $totalmarks = 0;
        $totaloutofmarks = 0;
        $passfail = 1;
        $pfAbStatus = 1;
      
        $extracreditsubpassfail = 1;

        foreach ($subjects as $sub) 
        {
    
            $v = $student_seatno->student->getmarks($sub->id, $exam_id);
          
            if ($v != '-5') 
            { 
                //Student Subject Data  -5 =>Empty
                if (!($v->subject->subject_type == 'G' or $v->subject->subject_type == 'IG' or $v->subject->subject_type == 'IEG')) 
                {
                    $totaloutofmarks += $v->subject->subject_maxmarks;
                    $subjectCnt++;
        
                    $total_credit_point = $total_credit_point + ($v->subject->subject_credit * $v->grade_point);
                    $total_subject_credit = $total_subject_credit + $v->subject->subject_credit;
        
                    if ($v->total != -1)
                    {
                        $totalmarks += $v->total;
                    }
        
                    if ($v->grade_point != 0)
                    {
                        $total_credit_earned = $total_credit_earned + $v->subject->subject_credit;
                    }
                    else
                    {
                        $passfail = 0;
                    }
        
                    if ($v->subject_grade == -1 || $v->subject_grade == 'Ab')
                    {
                        $Absentcnt++;
                    }

                } 
                else 
                {
                    if (($v->subject->subject_type == "G" and $v->grade == 'F') or ($v->subject->subject_type == "IG" and $v->grade == 'F') or ($v->subject->subject_type == "IEG" and $v->grade == 'F'))
                    {

                        $extracreditsubpassfail = 0;
                    }

                    if (($v->subject->subject_type == "G" and $v->grade == -1) or ($v->subject->subject_type == "IG" and $v->grade == 'F') or ($v->subject->subject_type == "IEG" and $v->grade == 'F'))
                    {
                        $extracreditsubpassfail = 0;

                    }
                }

                if (($v->subject->subject_type == "G" and $v->grade == 'F') or ($v->subject->subject_type == "IG" and $v->grade == 'F') or ($v->subject->subject_type == "IEG" and $v->grade == 'F'))
                {

                    $extracreditsubpassfail = 0;
                }
            }
        }
      
         
        if ($passfail == 0) //Any Subject Fail 
        {
            $pfAbStatus = 0;

        } else if ($passfail == 1) 
        {
            $pfAbStatus = 1;

            try 
            {
              if ($total_credit_earned != 0)
              {
                  $pf = number_format(round($total_credit_point / $total_credit_earned, 3), 3);
              }
              else
              {

                  $pf = 0;
              }
            }
            catch (\Exception $e) 
            {
            } 
        }
      
         
      
          if ($subjectCnt === $Absentcnt) 
          {
            //Add Absent Entry
            $pfAbStatus = 3;
           
          }
      
      
        Studentresult::upsert([
            'student_id' => $student_seatno->student_id,
            'totalmarks' => $totalmarks,
            'totaloutofmarks' => $totaloutofmarks,
            'seatno' => $student_seatno->seatno,
            'exam_patternclasses_id' => $exampatternclass_id,
            'sem' => $sem,
            'sgpa' => $pf,
            'semcreditearned' =>  $total_credit_earned,
            'semtotalcredit' => $total_subject_credit,
            'semtotalcreditpoints' => $total_credit_point,
            'resultstatus' => $pfAbStatus, 
            'extracreditsstatus' => $extracreditsubpassfail,
      
        ], 
        [
            'student_id', 'sem', 'exam_patternclasses_id'
        ]);
      
      
        $currentclass = $student_seatno->student->currentclassstudents->where('sem', $sem)->first();
      
        if ($sem == 1 || $sem == 3 || $sem == 5) 
        {
        
            $currentclass->update(['pfstatus' => $pfAbStatus == 0 ? 2 : $pfAbStatus]);
        }
    }

    protected function apply_ordinace_163($exam_id) 
    {
        $student_ordinace_163s = Studentordinace163::where('exam_id',$exam_id)->where('status',0)->where('is_applicable',1)->get();
        
        foreach ($student_ordinace_163s as $student_ordinace_163) 
        {
            $student_result = Studentresult::where('student_id', $student_ordinace_163->student_id)
            ->whereHas('exampatternclass', function($query) use ($exam_id){  $query->where('exam_id', $exam_id); })
            ->latest()
            ->first();
            
            if ($student_result) 
            {
                $student_result->update(['ordinance_163_marks' => $student_ordinace_163->marks]);
                $student_ordinace_163->update(['marksused' => $student_ordinace_163->marks,'status' => 1,]);
            }  
        }
    }

    protected function apply_ordinace_two($student_seatno , $exampatternclass) 
    {   
        
        $studentresult= Studentresult::with('student')
        ->where('exam_patternclasses_id','<=',$exampatternclass->id)
        ->where('student_id', $student_seatno->student_id)
        // ->where('extracreditsstatus',1)     
        ->select(['student_id', DB::raw('min(sem) as minsem'), DB::raw('max(sem) as maxsem'),])    
        ->groupBy('student_id')     
        ->first();
            
        if($studentresult)
        {
            $allsem = range( $studentresult->minsem,$studentresult->maxsem);

            $result = $this->student_sem_result( $allsem,$student_seatno,$exampatternclass);

            $grade= $result['grade'];
            $cgpa=  $result['cgpa'];

            $studresult = $student_seatno->student->studentresults()
            ->where('exam_patternclasses_id','<=',$exampatternclass->id)
            ->where('sgpa', '!=', '0')
            ->select([
                'student_id',DB::raw('sum(ordinance_163_marks) as ordinance_163_marks'),   
                'student_id',DB::raw('sum(totalmarks) as total_marks'),    
                'student_id',DB::raw('sum(totaloutofmarks) as total_out_of_marks'),     
            ])
            ->groupBy('student_id')     
            ->first();

            if ($studresult) 
            {

                Cgparesult::upsert([
                    'student_id' => $student_seatno->student_id,
                    'totalmarks' => $studresult->total_marks +  $studresult->ordinance_163_marks,
                    'totaloutofmarks' => $studresult->total_out_of_marks,
                    'seatno' => $student_seatno->seatno,
                    'exam_patternclass_id' =>$exampatternclass->id,
                    'grade' =>  $grade,
                    'cgpa' => $cgpa,
                ], 
                [
                    'student_id', 'seatno', 'exam_patternclass_id'
                ]);
             }
               
        }
    }

    protected function student_sem_result($allsem,$student_seatno,$exampatternclass)
    {
        $reject_result=0;
        $sem_total_credits=0;
        $sem_total_credit_points=0;
        $direct_second_year=0;

        $is_oridnance_one_four= $this->check_ordinace_one_four($student_seatno,$exampatternclass);
        
        foreach($allsem as $sem)
        {
                
            $studentresult= Studentresult::where('exam_patternclasses_id','<=',$exampatternclass->id)
            ->where('student_id', $student_seatno->student_id) 
            ->where('sem',$sem) 
            ->whereNot('sgpa',0)
            // ->where('extracreditsstatus',1)
            ->orderByDesc('id')
            ->first() ;

            if ($studentresult) 
            {
                $sem_total_credits= $sem_total_credits + $studentresult->semtotalcredit;
                $sem_total_credit_points = $sem_total_credit_points + $studentresult->semtotalcreditpoints;

                if($allsem['0']!=1)
                {
                    
                    $direct_second_year=1;
              
                }

                if($direct_second_year==1)
                {
                    $grade= ['grade'=>'Pass' ,'cgpa'=>0];;
                }   
                else
                {
                    $grade= $this->calculateCGPA($sem_total_credit_points,$sem_total_credits,$is_oridnance_one_four);

                }

               return $grade;
                
            }
            else
            {
                return null;
            }

        } 
    }

    protected function check_ordinace_one_four($student_seatno,$exampatternclass)
    {

        $sm=Studentmark::where('student_id',$student_seatno->student_id)
        ->where('exam_id','<=',$exampatternclass->exam_id)
        ->select([
            'student_id', DB::raw('sum(ext_ordinance_one_marks) as ext_ordinace_flag'),
            'student_id', DB::raw('sum(int_ordinance_one_marks) as int_ordinace_flag'),
            'student_id', DB::raw('sum(total_ordinance_one_marks) as total_ordinance_one_marks'),
            'student_id', DB::raw('sum(practical_ordinance_one_marks) as practical_ordinance_one_marks'),
            'student_id', DB::raw('sum(total_ordinancefour_marks) as total_ordinancefour_marks'),
        ])        
        ->groupBy('student_id')
        ->get() ;

        return ($sm->sum('ext_ordinace_flag')+$sm->sum('int_ordinace_flag')+$sm->sum('total_ordinance_one_marks')+$sm->sum('practical_ordinance_one_marks')+$sm->sum('total_ordinancefour_marks'));             
    }

    protected function calculateCGPA($sem_total_credit_points,$sem_total_credits,$is_oridnance_one_four)
    {   
        $cgpa= 0;

        if($sem_total_credits >0)
        {
            $cgpa=  round($sem_total_credit_points/$sem_total_credits,2);
        }
        
 
        $final_grade=null;
        $difference;

        if($cgpa>=9.50)
        {
            $final_grade="O";
        }
        else  if($cgpa>=8.25 && $cgpa<9.50)
        {
            $short_fall=$sem_total_credits*9.50 ;
            
            $difference=$short_fall-$sem_total_credit_points;
                                                      
            if($difference<=10 && $is_oridnance_one_four==0)
            {
                $final_grade="O $ 2";  $cgpa=9.50;
            }
            else  
            {
                $final_grade="A+";
            }
        }
        else  if($cgpa>=6.75 && $cgpa<8.25)
        {
            $short_fall=$sem_total_credits*8.25 ;
            $difference=$short_fall-$sem_total_credit_points;
                    
            if($difference<=10 && $is_oridnance_one_four==0)
            {
                $final_grade="A+ $ 2";  $cgpa=8.25;
            }
            else 
            {
                $final_grade="A";
            } 
        }  
        else  if($cgpa>=5.75 && $cgpa<6.75)
        {
            $short_fall=$sem_total_credits*6.75 ;
            $difference=$short_fall-$sem_total_credit_points;

            if($difference<=10 && $is_oridnance_one_four==0)
            {
                $final_grade="A $ 2";  $cgpa=6.75;
            }
            else  
            {
                $final_grade="B+";
            }
        }  
        else  if($cgpa>=5.25 && $cgpa<5.75)
        {
            $short_fall=$sem_total_credits*5.75 ;
            $difference=$short_fall-$sem_total_credit_points;
            if($difference<=10 && $is_oridnance_one_four==0)
            {
                $final_grade="B+ $ 2";  $cgpa=5.75;
            }
            else 
            {
                $final_grade="B";
            } 
        }
        else  if($cgpa>=4.75 && $cgpa<5.25)
        {
            $short_fall=$sem_total_credits*5.25 ;
            $difference=$short_fall-$sem_total_credit_points;
            
            if($difference<=10 && $is_oridnance_one_four==0)
            {
                $final_grade="B $ 2";  $cgpa=5.25;
            }
            else  
            {
                $final_grade="C";
            }
        }
        else  if($cgpa>=4. && $cgpa<4.75)
        {           
            $final_grade="D";
        }

        return ['grade'=>$final_grade ,'cgpa'=>$cgpa];
    }
 
}
