<?php

namespace App\Http\Controllers;

use App\Models\Studentmark;
use Illuminate\Http\Request;
use App\Models\Gradepoint;
use App\Models\Studentordinace163;
use Illuminate\Support\Facades\Log;

class StudentmarkController extends Controller
{
    /**
     * Display a listing of the resource.
     *max_percentage min_percentage
     * @return \Illuminate\Http\Response
     */
    public function passes($value)
    {
        // $gp=Gradepoint::where('max_percentage', '>=', $value)->where('min_percentage','<=',$value)->select('grade_point','grade')->get();
        // foreach($gp as $g)
        // echo $g->grade." ".$g->grade_point;
        //return Gradepoint::where('max_percentage', '<=', $value)->where('min_percentage', '>=', $value)->doesntExist();
    }
    //sem 1 ordinance
    public function applyordinanceone($data)
    {
        $c = 0;
        $total_marks = 0;

        foreach ($data as $d) {
            $total_marks = 0;
            // important :-create table patternclass_ordinance
            $sem1classordinance = ($d->exampatternclasses->patternclass->sem1_total_marks) / 100;
            $sem2classordinance = ($d->exampatternclasses->patternclass->sem2_total_marks) / 100;

            foreach ($d->student->studentmarks  as $v) {
                if ($v->grade == 'F' or $v->grade == '-1' or is_null($v->grade) or $v->grade == 'Ab') {
                    if ($v->subject->subject_type == "G")  //e.g. physical eduction subject
                    {
                        $v->grade = $v->subject_grade;
                        $this->updatemarks($v);
                    } else {
                        $allpass = 0;

                        if ($v->subject->subject_type == "IEP") {

                            if ($v->ext_marks >= $v->subject->subject_extpassing and $v->int_marks >= $v->subject->subject_intpassing and $v->int_practical_marks >= $v->subject->subject_intpractpassing and $v->total >= $v->subject->subject_totalpassing) {
                                $allpass = 1;
                                if (($v->ext_marks + $v->int_marks + $v->int_practical_marks) != $v->total)
                                    $allpass = 0;
                            }
                        } else if ($v->subject->subject_type == "I") {

                            if ($v->int_marks >= $v->subject->subject_intpassing and $v->total >= $v->subject->subject_totalpassing) {
                                $allpass = 1;
                                if ($v->int_marks != $v->total)
                                    $allpass = 0;
                            }
                        } else if ($v->ext_marks >= $v->subject->subject_extpassing and $v->int_marks >= $v->subject->subject_intpassing  and $v->total >= $v->subject->subject_totalpassing) {
                            $allpass = 1;
                            if (($v->ext_marks + $v->int_marks) != $v->total)
                                $allpass = 0;
                        } else $allpass = 0;

                        if ($allpass == 0)  //fail
                        {
                            $count = 0;
                            $absetflag = 0;

                            $total_marks = 0;

                            if ($v->ext_marks == -1 || $v->int_marks == -1 || $v->int_practical_marks == -1)
                                $absetflag = 1;

                            // if ($v->ext_marks < $v->subject->subject_extpassing and $v->int_marks < $v->subject->subject_intpassing)
                            //     $absetflag = 1;
                            if ($v->ext_marks == -1 and $v->int_marks == -1)
                                $total_marks = -1;
                            if ($v->ext_marks != -1 and $v->int_marks != -1)
                                $total_marks = $v->ext_marks + $v->int_marks;
                            else if ($v->ext_marks == -1)
                                $total_marks = $v->int_marks;
                            else if ($v->int_marks == -1)
                                $total_marks = $v->ext_marks;
                            //for subject type is IEP 
                            if ($v->subject->subject_type == "I") {
                                if ($v->int_marks == -1)
                                    $total_marks = 0;
                                else
                                    $total_marks = $v->int_marks;
                                //dd($total_marks);

                            }
                            if ($v->subject->subject_type == "IEP") {
                                if ($total_marks == -1 and $v->int_practical_marks == -1)
                                    $total_marks = -1; //all IEP abscent


                                if ($total_marks != -1 and $v->int_practical_marks != -1)
                                    $total_marks += $v->int_practical_marks;

                                //  echo $d->prn . "=>" . $total_marks . " ";
                                // echo  $v->ext_marks . " " . $v->int_marks . " " . $v->int_practical_marks . "<br>";
                            }

                            // Both internal of External Absent
                            if ($absetflag == 0) {


                                if ($v->ext_marks < $v->subject->subject_extpassing or $v->int_marks < $v->subject->subject_intpassing or $total_marks < $v->subject->subject_totalpassing) {


                                    if ($v->subject->subject_maxmarks <= 50)
                                        $grace_marks = 2;
                                    else if ($v->subject->subject_maxmarks <= 100)
                                        $grace_marks = 3;
                                    else if ($v->subject->subject_maxmarks <= 150)
                                        $grace_marks = 4;
                                    else if ($v->subject->subject_maxmarks <= 200)
                                        $grace_marks = 5;
                                    else if ($v->subject->subject_maxmarks <= 250)
                                        $grace_marks = 6;
                                    else if ($v->subject->subject_maxmarks <= 300)
                                        $grace_marks = 7;
                                    else if ($v->subject->subject_maxmarks <= 350)
                                        $grace_marks = 8;
                                    else if ($v->subject->subject_maxmarks <= 400)
                                        $grace_marks = 9;

                                    $extdollarmark = $v->subject->subject_extpassing - $v->ext_marks;
                                    $intdollarmark = $v->subject->subject_intpassing - $v->int_marks;
                                    if ($v->subject->subject_type == "IEP")
                                        $intpractdollarmark = $v->subject->subject_intpractpassing - $v->int_practical_marks;


                                    //echo "Marks ".$v->ext_marks;
                                    //Get Prev ordinace from database 

                                    $count = $v->where('prn', $d->prn)->where('sem', $v->subject->subject_sem)->groupby('sem')->sum('ext_ordinance_one_marks');

                                    $count += $v->where('prn', $d->prn)->where('sem', $v->subject->subject_sem)->groupby('sem')->sum('int_ordinance_one_marks');
                                    // if ($v->subject->subject_type == "IEP")
                                    $count += $v->where('prn', $d->prn)->where('sem', $v->subject->subject_sem)->groupby('sem')->sum('practical_ordinance_one_marks');

                                    $grace_counter = 0;
                                    $grace_counter = $v->ext_ordinance_one_marks + $v->int_ordinance_one_marks;
                                    if ($v->subject->subject_type == "IEP")
                                        $grace_counter += $v->practical_ordinance_one_marks;

                                    if ($extdollarmark > 0 and ($extdollarmark + $grace_counter) <= $grace_marks) {
                                        //sem1
                                        // echo "ExtDoller " . $extdollarmark . " Count" . $count . "Grace " . $grace_marks . " Sem Ordinace" . $sem1classordinance;

                                        if ($count <= $sem1classordinance and $extdollarmark <= ($sem1classordinance - $count)) {
                                            // echo"OK";
                                            $v->ext_ordinace_flag = 1;
                                            $v->ext_marks = $v->ext_marks + $extdollarmark;
                                            $v->ext_ordinance_one_marks = $extdollarmark;
                                            $total_marks += $extdollarmark;
                                            $count += $extdollarmark;
                                            $grace_counter += $extdollarmark;

                                            // $this->updatemarks($v);
                                        }
                                    }
                                    // echo "<br>Count=>".$count."<br>";
                                    if ($v->subject->subject_type == "IEP") {
                                        if ($intpractdollarmark > 0 and ($intpractdollarmark + $grace_counter) <= $grace_marks) {
                                            if ($count <= $sem1classordinance and $intpractdollarmark <= ($sem1classordinance - $count)) {
                                                $v->practical_ordinace_flag = 1;
                                                $v->int_practical_marks = $v->int_practical_marks + $intpractdollarmark;
                                                $v->practical_ordinance_one_marks = $intpractdollarmark;
                                                $total_marks += $intpractdollarmark;
                                                $count += $intpractdollarmark;
                                                $grace_counter += $intpractdollarmark;
                                            }
                                        }
                                    }
                                    if ($intdollarmark > 0 and ($intdollarmark + $grace_counter) <= $grace_marks) {
                                        //sem1
                                        // echo "internal";
                                        if ($count <= $sem1classordinance and $intdollarmark <= ($sem1classordinance - $count)) {
                                            $v->int_ordinace_flag = 1;
                                            $v->int_marks = $v->int_marks + $intdollarmark;
                                            $v->int_ordinance_one_marks = $intdollarmark;
                                            $total_marks += $intdollarmark;
                                        }
                                    } //elseif
                                } //if

                            } //if Both Absent

                            if ($v->int_marks >= $v->subject->subject_intpassing and $v->ext_marks >= $v->subject->subject_extpassing) {
                                if ($v->subject->subject_type == "IEP") {
                                    if ($v->int_practical_marks >= $v->subject->subject_intpractpassing)
                                        $per = (($v->int_marks + $v->ext_marks + $v->int_practical_marks) / ($v->subject->subject_maxmarks)) * 100;
                                    else $per = 0;
                                } else
                                    $per = (($v->int_marks + $v->ext_marks) / ($v->subject->subject_maxmarks)) * 100;
                            } else $per = 0;


                            $gp = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade')->get();
                            foreach ($gp as $g) {
                                $v->grade = $g->grade;
                                $v->grade_point = $g->grade_point;
                            }
                            $v->total = $total_marks;


                            $this->updatemarks($v);
                            // $this->passes($total_marks);
                        } //if
                    } //else condition chk for not physical education subject
                } //if
            } //foreach
        } //foreach

    } //fun

    //sem 2 ordinance
    public function applyordinanceonesem2($data, $exam_id)
    {


        $c = 0;
        foreach ($data as $d) {

            // important :-create table patternclass_ordinance
            $sem1classordinance = round(($d->exampatternclasses->patternclass->sem1_total_marks) / 100);
            $sem2classordinance =round (($d->exampatternclasses->patternclass->sem2_total_marks) / 100);
        //   dd( $sem1classordinance." ".$sem2classordinance );
      //  dd($d->student->studentmarks->where('exam_id',$exam_id));
      //dd($d->student->studentmarks->where('exam_id',$exam_id)->where('subject_id','1110'));
        foreach ($d->student->studentmarks->where('exam_id',$exam_id) as $v) 
            {  
              
                 //$v
                // dd($v->subject->subject_sem);
                if ($v->subject->subject_sem % 2 == 1)  //Sem 1 3 5  odd sem
                {
                    // dd($v->subject->subject_sem);
                    $this->applyOrd($d, $v, $sem1classordinance);
                } else  if ($v->subject->subject_sem % 2 == 0) //sem 2 4 6  Even Sem
                {

                    $this->applyOrd($d, $v, $sem2classordinance);
                }
            } //foreach

        } //foreach

    } //fun
    public function collectintextmarks($data, $exam_id)
    { // echo "PP";
        foreach ($data as $d) { //echo $d;

            foreach ($d->student->studentmarks->where('exam_id', $exam_id) as $v) {
                if ($v->subject->subject_type != 'I' || $v->subject->subject_type != 'G') {
                    // dd($v->subject->subject_type);
                    if ($v->subject->subject_type === 'IEP') {


                        if (is_null($v->ext_marks)) {
                            $extstudmarks = $d->student->studentmarks->where('subject_id', $v->subject_id)->max('ext_marks');
                            $v->ext_marks = $extstudmarks;
                            $v->update();
                        }
                        if (is_null($v->int_practical_marks)) {
                            $intpractstudmarks = $d->student->studentmarks->where('subject_id', $v->subject_id)->max('int_practical_marks');
                            $v->int_practical_marks =  $intpractstudmarks;
                            $v->update();
                        }
                        if (is_null($v->int_marks)) {
                            $intstudmarks = $d->student->studentmarks->where('subject_id', $v->subject_id)->max('int_marks');
                            $v->int_marks = $intstudmarks;

                            $v->update();
                        }
                    } else {
                        if (!is_null($v->ext_marks) and is_null($v->int_marks)) {
                            $studmarks = $d->student->studentmarks->where('subject_id', $v->subject_id)->max('int_marks');
                            // echo "<br>".$v->subject_id." ".$studmarks." ".$v->id."<br>";
                            //$stm=Studentmark::find($v->id);
                            //  $values = array('int_marks'=>$studmarks);
                            $v->int_marks = $studmarks;
                            $v->update();
                            //dd("SAve");
                        }
                        if (is_null($v->ext_marks) and !is_null($v->int_marks)) {
                            $studmarks = $d->student->studentmarks->where('subject_id', $v->subject_id)->max('ext_marks');
                            // $stm=Studentmark::find($v->id);
                            $v->ext_marks = $studmarks;
                            $v->update();
                            // $values = array('ext_marks'=>$studmarks);
                            // $stm->update($values);
                            //echo $studmarks;   
                        }
                    }
                }
            }
        }
    }

    public  function checkpassfail($data, $exam_id)
    { // echo "PP"; 

        foreach ($data   as $d) {
            $Creditsum = 0;
            $gradesub = 0;
            foreach ($d->student->studentmarks()->where('exam_id', $exam_id)->where('grade', 'F')->get() as $cr) {
                if ($cr->subject->subject_type == 'G' || $cr->subject->subject_type == 'IG')
                    $gradesub += $cr->subject->subject_credit;
                $Creditsum += $cr->subject->subject_credit;
            }

            if ($Creditsum == 0) {
                $d->student->currentclassstudent()->create([
                    'prn' => $d->student->prn,
                    'sem' => '2',
                    'pfstatus' => '1',
                    'ordinace1_flag' => '0',
                    'ordinace2_flag ' => '0',
                    'markssheetprint_status' => '1',
                    'patternclass_id' => $d->student->patternclass_id,
                ]);
                //$d->student

                // dd("okPass".$d->student->id);
            } else
           if (($Creditsum - $gradesub) <= 20) {
                $d->student->currentclassstudent()->create([
                    'prn' => $d->student->prn,
                    'sem' => '2',
                    'pfstatus' => '2',
                    'ordinace1_flag' => '0',
                    'ordinace2_flag ' => '0',
                    'markssheetprint_status' => '1',
                    'patternclass_id' => $d->student->patternclass_id,
                ]);
            } else {
                $d->student->currentclassstudent()->create([
                    'prn' => $d->student->prn,
                    'sem' => '2',
                    'pfstatus' => '0',
                    'ordinace1_flag' => '0',
                    'ordinace2_flag ' => '0',
                    'markssheetprint_status' => '1',
                    'patternclass_id' => $d->student->patternclass_id,
                ]);
            }
        }
        dd("Record Added");
    }
    public function applyOrd($d, $v, $semclassordinance)
    {
        $total_marks = 0;
       
        if ($v->grade == 'F' or $v->grade == '-1' or is_null($v->grade) or $v->grade == 'Ab') {
          
            if ($v->subject->subject_type == "G")  //e.g. physical eduction subject
            {
                $v->grade = $v->subject_grade;
                $this->updatemarks($v);
            } else {
               
                $allpass = 0;
                if ($v->subject->subject_type == "IEP") {
                    if ($v->ext_marks >= $v->subject->subject_extpassing and $v->int_marks >= $v->subject->subject_intpassing and $v->int_practical_marks >= $v->subject->subject_intpractpassing and $v->total >= $v->subject->subject_totalpassing) {
                        $allpass = 1;
                        if (($v->ext_marks + $v->int_marks + $v->int_practical_marks) != $v->total)
                            $allpass = 0;
                    }
                } else if ($v->subject->subject_type == "I" or $v->subject->subject_type == "IG") {

                    if ($v->int_marks >= $v->subject->subject_intpassing and $v->total >= $v->subject->subject_totalpassing) {
                        $allpass = 1;
                        if ($v->int_marks != $v->total)
                            $allpass = 0;
                    }
                } else if ($v->ext_marks >= $v->subject->subject_extpassing and $v->int_marks >= $v->subject->subject_intpassing  and $v->total >= $v->subject->subject_totalpassing) 
                {
                    $allpass = 1;

                    if (($v->ext_marks + $v->int_marks) != $v->total)
                        $allpass = 0;
                } else $allpass = 0;

                if ($allpass == 0)  //fail
                {
                    $count = 0;
                    $absetflag = 0;

                    $total_marks = 0;
                    if ($v->ext_marks == -1 || $v->int_marks == -1 || $v->int_practical_marks == -1)
                        $absetflag = 1;

                    // if ($v->ext_marks < $v->subject->subject_extpassing and $v->int_marks < $v->subject->subject_intpassing)
                    //     $absetflag = 1;
                    if ($v->ext_marks == -1 and $v->int_marks == -1)
                        $total_marks = -1;
                    if ($v->ext_marks != -1 and $v->int_marks != -1)
                        $total_marks = $v->ext_marks + $v->int_marks;
                    else if ($v->ext_marks == -1)
                        $total_marks = $v->int_marks;
                    else if ($v->int_marks == -1)
                        $total_marks = $v->ext_marks;
                    //for subject type is IEP 
                    if ($v->subject->subject_type == "I" or $v->subject->subject_type == "IG") {
                        if ($v->int_marks == -1)
                            $total_marks = 0;
                        else
                            $total_marks = $v->int_marks;
                        //dd($total_marks);

                    }
                    if ($v->subject->subject_type == "IEP") {
                        if ($total_marks == -1 and $v->int_practical_marks == -1)
                            $total_marks = -1; //all IEP abscent


                        if ($total_marks != -1 and $v->int_practical_marks != -1)
                            $total_marks += $v->int_practical_marks;

                        // echo  $v->ext_marks . " " . $v->int_marks . " " . $v->int_practical_marks . "<br>";
                    }

                    // Both internal of External not Absent
                    if ($absetflag == 0) {


                        if ($v->ext_marks < $v->subject->subject_extpassing or $v->int_marks < $v->subject->subject_intpassing or $total_marks < $v->subject->subject_totalpassing) {


                            if ($v->subject->subject_maxmarks <= 50)
                                $grace_marks = 2;
                            else if ($v->subject->subject_maxmarks <= 100)
                                $grace_marks = 3;
                            else if ($v->subject->subject_maxmarks <= 150)
                                $grace_marks = 4;
                            else if ($v->subject->subject_maxmarks <= 200)
                                $grace_marks = 5;
                            else if ($v->subject->subject_maxmarks <= 250)
                                $grace_marks = 6;
                            else if ($v->subject->subject_maxmarks <= 300)
                                $grace_marks = 7;
                            else if ($v->subject->subject_maxmarks <= 350)
                                $grace_marks = 8;
                            else if ($v->subject->subject_maxmarks <= 400)
                                $grace_marks = 9;

                            $extdollarmark = $v->subject->subject_extpassing - $v->ext_marks;
                            $intdollarmark = $v->subject->subject_intpassing - $v->int_marks;
                            if ($v->subject->subject_type == "IEP")
                                $intpractdollarmark = $v->subject->subject_intpractpassing - $v->int_practical_marks;


                            //echo "Marks ".$v->ext_marks;
                            //Get Prev ordinace from database 

                            $count = $v->where('prn', $d->prn)->where('sem', $v->subject->subject_sem)->groupby('sem')->sum('ext_ordinance_one_marks');

                            $count += $v->where('prn', $d->prn)->where('sem', $v->subject->subject_sem)->groupby('sem')->sum('int_ordinance_one_marks');
                            // if ($v->subject->subject_type == "IEP")
                            $count += $v->where('prn', $d->prn)->where('sem', $v->subject->subject_sem)->groupby('sem')->sum('practical_ordinance_one_marks');

                            $grace_counter = 0;
                            $grace_counter = $v->ext_ordinance_one_marks + $v->int_ordinance_one_marks;
                            if ($v->subject->subject_type == "IEP")
                                $grace_counter += $v->practical_ordinance_one_marks;

                            if ($extdollarmark > 0 and ($extdollarmark + $grace_counter) <= $grace_marks) {
                                //sem1
                                // echo "ExtDoller " . $extdollarmark . " Count" . $count . "Grace " . $grace_marks . " Sem Ordinace" . $sem1classordinance;

                                if ($count <= $semclassordinance and $extdollarmark <= ($semclassordinance - $count)) {
                                    // echo"OK";
                                    $v->ext_ordinace_flag = 1;
                                    $v->ext_marks = $v->ext_marks + $extdollarmark;
                                    $v->ext_ordinance_one_marks = $extdollarmark;
                                    $total_marks += $extdollarmark;
                                    $count += $extdollarmark;
                                    $grace_counter += $extdollarmark;

                                    // $this->updatemarks($v);
                                }
                            }
                            // echo "<br>Count=>".$count."<br>";
                            if ($v->subject->subject_type == "IEP") {
                                if ($intpractdollarmark > 0 and ($intpractdollarmark + $grace_counter) <= $grace_marks) {
                                    if ($count <= $semclassordinance and $intpractdollarmark <= ($semclassordinance - $count)) {
                                        $v->practical_ordinace_flag = 1;
                                        $v->int_practical_marks = $v->int_practical_marks + $intpractdollarmark;
                                        $v->practical_ordinance_one_marks = $intpractdollarmark;
                                        $total_marks += $intpractdollarmark;
                                        $count += $intpractdollarmark;
                                        $grace_counter += $intpractdollarmark;
                                    }
                                }
                            }
                            if ($intdollarmark > 0 and ($intdollarmark + $grace_counter) <= $grace_marks) {
                                //sem1
                                // echo "internal";
                                if ($count <= $semclassordinance and $intdollarmark <= ($semclassordinance - $count)) {
                                    $v->int_ordinace_flag = 1;
                                    $v->int_marks = $v->int_marks + $intdollarmark;
                                    $v->int_ordinance_one_marks = $intdollarmark;
                                    $total_marks += $intdollarmark;
                                }
                            } //elseif
                        } //if

                    } //if Both not Absent
                    if ($v->subject->subject_type == "I" or $v->subject->subject_type == "IG")  //e.g. Human right,cyber security subject
                    {
                        if ($v->int_marks == -1) //for Absent
                        {
                            $total_marks = -1;
                            $v->grade = "-1";
                        } else {
                            $total_marks = $v->int_marks;
                            if ($v->int_marks >= $v->subject->subject_intpassing)
                                $per = (($v->int_marks) / ($v->subject->subject_maxmarks)) * 100;
                            else $per = 0;

                            $gp = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade')->get();
                            foreach ($gp as $g) {
                                $v->grade = $g->grade;
                                $v->grade_point = $g->grade_point;
                            }
                        }
                    } else {
                        if ($v->int_marks >= $v->subject->subject_intpassing and $v->ext_marks >= $v->subject->subject_extpassing) {
                            if ($v->subject->subject_type == "IEP") {
                                if ($v->int_practical_marks >= $v->subject->subject_intpractpassing)
                                    $per = (($v->int_marks + $v->ext_marks + $v->int_practical_marks) / ($v->subject->subject_maxmarks)) * 100;
                                else $per = 0;
                            } else {

                                $per = (($v->int_marks + $v->ext_marks) / ($v->subject->subject_maxmarks)) * 100;
                            }
                        } else $per = 0;


                        $gp = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade')->get();
                        foreach ($gp as $g) {
                            $v->grade = $g->grade;
                            $v->grade_point = $g->grade_point;
                        }
                    }
                    $v->total = $total_marks;
                    
                    $this->updatemarks($v);
                    // $this->passes($total_marks);
                } //if
            } //if pass in subject then no action taken
        } //else condition chk for not physical education subject
    }
    //PG Ordinace
    public function applypgordinanceone($data)
    {
        $c = 0;
        foreach ($data as $d) {
            // important :-create table patternclass_ordinance
            $sem1classordinance = ($d->exampatternclasses->patternclass->sem1_total_marks) / 100;
            $sem2classordinance = ($d->exampatternclasses->patternclass->sem2_total_marks) / 100;

            foreach ($d->student->studentmarks  as $v) {

                // if ($v->subject->subject_type == "I")  //e.g. Human right,cyber security subject
                // {
                //     if ($v->int_marks == -1)//for Absent
                //      {
                //            $total_marks = -1;
                //            $v->grade="Ab";
                //      }
                //     else
                //     {
                //         $total_marks=$v->int_marks;
                //     if ($v->int_marks >= $v->subject->subject_intpassing)
                //         $per =(($v->int_marks ) / ($v->subject->subject_maxmarks)) * 100;
                //     else $per=0;

                //     $gp = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade')->get();
                //         foreach ($gp as $g) {
                //             $v->grade = $g->grade;
                //             $v->grade_point = $g->grade_point;
                //         }
                //     }
                //         $v->total = $total_marks;

                //     $this->updatemarks($v);

                // } else
                // {
                $allpass = 0;
                if ($v->subject->subject_type == "IEP") {
                    if ($v->ext_marks >= $v->subject->subject_extpassing and $v->int_marks >= $v->subject->subject_intpassing and $v->int_practical_marks >= $v->subject->subject_intpractpassing and $v->total >= $v->subject->subject_totalpassing) {
                        $allpass = 1;
                        if (($v->ext_marks + $v->int_marks + $v->int_practical_marks) != $v->total)
                            $allpass = 0;
                    }
                } else if ($v->ext_marks >= $v->subject->subject_extpassing and $v->int_marks >= $v->subject->subject_intpassing  and $v->total >= $v->subject->subject_totalpassing) {
                    $allpass = 1;

                    if (($v->ext_marks + $v->int_marks) != $v->total)
                        $allpass = 0;
                } else $allpass = 0;

                if ($allpass == 0)  //fail
                {
                    $count = 0;
                    $absetflag = 0;

                    $total_marks = 0;
                    if ($v->ext_marks == -1 || $v->int_marks == -1 || $v->int_practical_marks == -1)
                        $absetflag = 1;

                    // if ($v->ext_marks < $v->subject->subject_extpassing and $v->int_marks < $v->subject->subject_intpassing)
                    //     $absetflag = 1;
                    if ($v->ext_marks == -1 and $v->int_marks == -1)
                        $total_marks = -1;
                    if ($v->ext_marks != -1 and $v->int_marks != -1)
                        $total_marks = $v->ext_marks + $v->int_marks;
                    else if ($v->ext_marks == -1)
                        $total_marks = $v->int_marks;
                    else if ($v->int_marks == -1)
                        $total_marks = $v->ext_marks;
                    //for subject type is IEP 

                    if ($v->subject->subject_type == "IEP") {
                        if ($total_marks == -1 and $v->int_practical_marks == -1)
                            $total_marks = -1; //all IEP abscent


                        if ($total_marks != -1 and $v->int_practical_marks != -1)
                            $total_marks += $v->int_practical_marks;

                        //  echo $d->prn . "=>" . $total_marks . " ";
                        // echo  $v->ext_marks . " " . $v->int_marks . " " . $v->int_practical_marks . "<br>";
                    }

                    // Both internal or External Absent
                    if ($absetflag == 0) {


                        if ($v->ext_marks < $v->subject->subject_extpassing or $v->int_marks < $v->subject->subject_intpassing or $total_marks < $v->subject->subject_totalpassing) {


                            if ($v->subject->subject_maxmarks <= 50)
                                $grace_marks = 2;
                            else if ($v->subject->subject_maxmarks <= 100)
                                $grace_marks = 3;
                            else if ($v->subject->subject_maxmarks <= 150)
                                $grace_marks = 4;
                            else if ($v->subject->subject_maxmarks <= 200)
                                $grace_marks = 5;
                            else if ($v->subject->subject_maxmarks <= 250)
                                $grace_marks = 6;
                            else if ($v->subject->subject_maxmarks <= 300)
                                $grace_marks = 7;
                            else if ($v->subject->subject_maxmarks <= 350)
                                $grace_marks = 8;
                            else if ($v->subject->subject_maxmarks <= 400)
                                $grace_marks = 9;

                            $extdollarmark = $v->subject->subject_extpassing - $v->ext_marks;
                            $intdollarmark = $v->subject->subject_intpassing - $v->int_marks;
                            if ($v->subject->subject_type == "IEP")
                                $intpractdollarmark = $v->subject->subject_intpractpassing - $v->int_practical_marks;


                            //echo "Marks ".$v->ext_marks;
                            //Get Prev ordinace from database 

                            $count = $v->where('prn', $d->prn)->where('sem', $v->subject->subject_sem)->groupby('sem')->sum('ext_ordinance_one_marks');

                            $count += $v->where('prn', $d->prn)->where('sem', $v->subject->subject_sem)->groupby('sem')->sum('int_ordinance_one_marks');
                            // if ($v->subject->subject_type == "IEP")
                            $count += $v->where('prn', $d->prn)->where('sem', $v->subject->subject_sem)->groupby('sem')->sum('practical_ordinance_one_marks');

                            $grace_counter = 0;
                            $grace_counter = $v->ext_ordinance_one_marks + $v->int_ordinance_one_marks;
                            if ($v->subject->subject_type == "IEP")
                                $grace_counter += $v->practical_ordinance_one_marks;

                            if ($extdollarmark > 0 and ($extdollarmark + $grace_counter) <= $grace_marks) {
                                //sem1
                                // echo "ExtDoller " . $extdollarmark . " Count" . $count . "Grace " . $grace_marks . " Sem Ordinace" . $sem1classordinance;

                                if ($count <= $sem1classordinance and $extdollarmark <= ($sem1classordinance - $count)) {
                                    // echo"OK";
                                    $v->ext_ordinace_flag = 1;
                                    $v->ext_marks = $v->ext_marks + $extdollarmark;
                                    $v->ext_ordinance_one_marks = $extdollarmark;
                                    $total_marks += $extdollarmark;
                                    $count += $extdollarmark;
                                    $grace_counter += $extdollarmark;

                                    // $this->updatemarks($v);
                                }
                            }
                            // echo "<br>Count=>".$count."<br>";
                            if ($v->subject->subject_type == "IEP") {
                                if ($intpractdollarmark > 0 and ($intpractdollarmark + $grace_counter) <= $grace_marks) {
                                    if ($count <= $sem1classordinance and $intpractdollarmark <= ($sem1classordinance - $count)) {
                                        $v->practical_ordinace_flag = 1;
                                        $v->int_practical_marks = $v->int_practical_marks + $intpractdollarmark;
                                        $v->practical_ordinance_one_marks = $intpractdollarmark;
                                        $total_marks += $intpractdollarmark;
                                        $count += $intpractdollarmark;
                                        $grace_counter += $intpractdollarmark;
                                    }
                                }
                            }
                            if ($intdollarmark > 0 and ($intdollarmark + $grace_counter) <= $grace_marks) {
                                //sem1
                                // echo "internal";
                                if ($count <= $sem1classordinance and $intdollarmark <= ($sem1classordinance - $count)) {
                                    $v->int_ordinace_flag = 1;
                                    $v->int_marks = $v->int_marks + $intdollarmark;
                                    $v->int_ordinance_one_marks = $intdollarmark;
                                    $total_marks += $intdollarmark;
                                }
                            } //elseif
                        } //if

                    } //if Both Absent
                    if ($v->subject->subject_type == "I")  //e.g. Human right,cyber security subject
                    {
                        if ($v->int_marks == -1) //for Absent
                        {
                            $total_marks = -1;
                            $v->grade = "Ab";
                        } else {
                            $total_marks = $v->int_marks;
                            if ($v->int_marks >= $v->subject->subject_intpassing)
                                $per = (($v->int_marks) / ($v->subject->subject_maxmarks)) * 100;
                            else $per = 0;

                            $gp = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade')->get();
                            foreach ($gp as $g) {
                                $v->grade = $g->grade;
                                $v->grade_point = $g->grade_point;
                            }
                        }
                    } else {
                        if ($v->int_marks >= $v->subject->subject_intpassing and $v->ext_marks >= $v->subject->subject_extpassing) {
                            if ($v->subject->subject_type == "IEP") {
                                if ($v->int_practical_marks >= $v->subject->subject_intpractpassing)
                                    $per = (($v->int_marks + $v->ext_marks + $v->int_practical_marks) / ($v->subject->subject_maxmarks)) * 100;
                                else $per = 0;
                            } else
                                $per = (($v->int_marks + $v->ext_marks) / ($v->subject->subject_maxmarks)) * 100;
                        } else $per = 0;


                        $gp = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade')->get();
                        foreach ($gp as $g) {
                            $v->grade = $g->grade;
                            $v->grade_point = $g->grade_point;
                        }
                    }
                    $v->total = $total_marks;
                    $this->updatemarks($v);

                    // $this->passes($total_marks);
                } //if
                // } //else condition chk for not physical education subject
            } //foreach
        } //foreach

    } //fun
    public function updatemarks(Studentmark $sm)
    {
        // $stud=Studentmark::find($sm->id);
        $sm->update();
    }

    //new rule for ordinance on total and passing on total
    //sem 2 ordinance
    public function applyordinanceoneontotal($data, $exam_id)
    {
        // dd($data );

        $c = 0;
        foreach ($data as $d) {

            // important :-create table patternclass_ordinance
            $sem1classordinance = round(($d->exampatternclasses->patternclass->sem1_total_marks) / 100);
            $sem2classordinance = round(($d->exampatternclasses->patternclass->sem2_total_marks) / 100);
            //   dd( $sem1classordinance." ".$sem2classordinance );
            //  dd($d->student->studentmarks->where('exam_id',$exam_id));
            foreach ($d->student->studentmarks->where('exam_id', $exam_id)  as $v) {

                //$v
                if ($v->subject->subject_sem % 2 == 1)  //Sem 1 3 5  odd sem
                {
                    //dd($v->subject->subject_sem);
                    $this->applyOrdontotal($d, $v, $sem1classordinance);
                } else  if ($v->subject->subject_sem % 2 == 0) //sem 2 4 6  Even Sem
                {

                    $this->applyOrdontotal($d, $v, $sem2classordinance);
                }
            } //foreach

        } //foreach

    } //fun

    //new rule 
    public function applyOrdontotal($d, $v, $semclassordinance)
    {
        $total_marks = 0;

        if ($v->grade == 'F' or $v->grade == '-1' or is_null($v->grade) or $v->grade == 'Ab') {

            if ($v->subject->subject_type == "G")  //e.g. physical eduction subject
            {
                $v->grade = $v->subject_grade;
                $this->updatemarks($v);
            } else {
                $allpass = 0;
                if ($v->subject->subject_type == "IEP") {
                    if ($v->ext_marks >= $v->subject->subject_extpassing and $v->int_marks >= $v->subject->subject_intpassing and $v->int_practical_marks >= $v->subject->subject_intpractpassing and $v->total >= $v->subject->subject_totalpassing) {
                        $allpass = 1;
                        if (($v->ext_marks + $v->int_marks + $v->int_practical_marks) != $v->total)
                            $allpass = 0;
                    }
                } else if ($v->subject->subject_type == "I" or $v->subject->subject_type == "IG") {

                    if ($v->int_marks >= $v->subject->subject_intpassing and $v->total >= $v->subject->subject_totalpassing) {
                        $allpass = 1;
                        if ($v->int_marks != $v->total)
                            $allpass = 0;
                    }
                } else if ($v->total >= $v->subject->subject_totalpassing) {

                    $allpass = 0;
                } else $allpass = 0;

                if ($allpass == 0)  //fail
                {
                    $count = 0;
                    $absetflag = 0;

                    $total_marks = 0;
                    if ($v->ext_marks == -1 || $v->int_marks == -1 || $v->int_practical_marks == -1)
                        $absetflag = 1;

                    // if ($v->ext_marks < $v->subject->subject_extpassing and $v->int_marks < $v->subject->subject_intpassing)
                    //     $absetflag = 1;
                    if ($v->ext_marks == -1 and $v->int_marks == -1)
                        $total_marks = -1;
                    if ($v->ext_marks != -1 and $v->int_marks != -1)
                        $total_marks = $v->ext_marks + $v->int_marks;
                    else if ($v->ext_marks == -1)
                        $total_marks = $v->int_marks;
                    else if ($v->int_marks == -1)
                        $total_marks = $v->ext_marks;
                    //for subject type is IEP 
                    if ($v->subject->subject_type == "I" or $v->subject->subject_type == "IG") {
                        if ($v->int_marks == -1)
                            $total_marks = 0;
                        else
                            $total_marks = $v->int_marks;
                        //dd($total_marks);

                    }
                    if ($v->subject->subject_type == "IEP") {
                        if ($total_marks == -1 and $v->int_practical_marks == -1)
                            $total_marks = -1; //all IEP abscent


                        if ($total_marks != -1 and $v->int_practical_marks != -1)
                            $total_marks += $v->int_practical_marks;

                        // echo  $v->ext_marks . " " . $v->int_marks . " " . $v->int_practical_marks . "<br>";
                    }

                    // Both internal of External not Absent
                    if ($absetflag == 0) {


                        //  if ($v->ext_marks < $v->subject->subject_extpassing or $v->int_marks < $v->subject->subject_intpassing or $total_marks < $v->subject->subject_totalpassing) 
                        if ($total_marks < $v->subject->subject_totalpassing) {


                            if ($v->subject->subject_maxmarks <= 50)
                                $grace_marks = 2;
                            else if ($v->subject->subject_maxmarks <= 100)
                                $grace_marks = 3;
                            else if ($v->subject->subject_maxmarks <= 150)
                                $grace_marks = 4;
                            else if ($v->subject->subject_maxmarks <= 200)
                                $grace_marks = 5;
                            else if ($v->subject->subject_maxmarks <= 250)
                                $grace_marks = 6;
                            else if ($v->subject->subject_maxmarks <= 300)
                                $grace_marks = 7;
                            else if ($v->subject->subject_maxmarks <= 350)
                                $grace_marks = 8;
                            else if ($v->subject->subject_maxmarks <= 400)
                                $grace_marks = 9;

                            $totaldollarmark = $v->subject->subject_totalpassing - $total_marks;
                            //$intdollarmark = $v->subject->subject_intpassing - $v->int_marks;
                            // if ($v->subject->subject_type == "IEP")
                            //     $intpractdollarmark = $v->subject->subject_intpractpassing - $v->int_practical_marks;


                            //echo "Marks ".$v->ext_marks;
                            //Get Prev ordinace from database 

                            $count = $v->where('prn', $d->prn)->where('sem', $v->subject->subject_sem)->groupby('sem')->sum('ext_ordinance_one_marks');

                            $count += $v->where('prn', $d->prn)->where('sem', $v->subject->subject_sem)->groupby('sem')->sum('int_ordinance_one_marks');
                            // if ($v->subject->subject_type == "IEP")
                            $count += $v->where('prn', $d->prn)->where('sem', $v->subject->subject_sem)->groupby('sem')->sum('practical_ordinance_one_marks');

                            $count += $v->where('prn', $d->prn)->where('sem', $v->subject->subject_sem)->groupby('sem')->sum('total_ordinance_one_marks');
                            $grace_counter = 0;
                            $grace_counter = $v->ext_ordinance_one_marks + $v->int_ordinance_one_marks + $v->total_ordinance_one_marks;
                            if ($v->subject->subject_type == "IEP")
                                $grace_counter += $v->practical_ordinance_one_marks;

                            if ($totaldollarmark > 0 and ($totaldollarmark + $grace_counter) <= $grace_marks) {
                                //sem1
                                // echo "ExtDoller " . $extdollarmark . " Count" . $count . "Grace " . $grace_marks . " Sem Ordinace" . $sem1classordinance;

                                if ($count <= $semclassordinance and $totaldollarmark <= ($semclassordinance - $count)) {
                                    // echo"OK";
                                    $v->total_ordinace_flag = 1;
                                    $v->total = $v->total + $totaldollarmark;
                                    $v->total_ordinance_one_marks = $totaldollarmark;
                                    $total_marks += $totaldollarmark;
                                    $count += $totaldollarmark;
                                    $grace_counter += $totaldollarmark;

                                    // $this->updatemarks($v);
                                }
                            }
                        } //if

                    } //if Both not Absent
                    if ($v->subject->subject_type == "I" or $v->subject->subject_type == "IG")  //e.g. Human right,cyber security subject
                    {
                        if ($v->int_marks == -1) //for Absent
                        {
                            $total_marks = -1;
                            $v->grade = "-1";
                        } else {
                            $total_marks = $v->int_marks;
                            //  if ($v->int_marks >= $v->subject->subject_intpassing) //need to discuss with dipa
                            $per = (($v->int_marks) / ($v->subject->subject_maxmarks)) * 100;
                            // else $per=0;

                            $gp = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade')->get();
                            foreach ($gp as $g) {
                                $v->grade = $g->grade;
                                $v->grade_point = $g->grade_point;
                            }
                        }
                    } else {
                        if ($total_marks >= $v->subject->subject_totalpassing) {
                            if ($v->subject->subject_type == "IEP") {
                                // if ($v->int_practical_marks >= $v->subject->subject_intpractpassing)
                                $per = (($total_marks) / ($v->subject->subject_maxmarks)) * 100;
                                //else $per = 0;
                            } else {
                                $per = (($total_marks) / ($v->subject->subject_maxmarks)) * 100;
                            }
                        } else $per = 0;

                        if ($absetflag == 1) {
                            $v->grade = 'F';
                            $v->grade_point = 0;
                        } else {

                            $gp = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade')->get();
                            foreach ($gp as $g) {
                                $v->grade = $g->grade;
                                $v->grade_point = $g->grade_point;
                            }
                        }
                    }
                    $v->total = $total_marks;

                    $this->updatemarks($v);
                    // $this->passes($total_marks);
                } //if
            } //if pass in subject then no action taken
        } //else condition chk for not physical education subject
    }
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Studentmark  $studentmark
     * @return \Illuminate\Http\Response
     */
    public function show(Studentmark $studentmark)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Studentmark  $studentmark
     * @return \Illuminate\Http\Response
     */
    public function edit(Studentmark $studentmark)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Studentmark  $studentmark
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Studentmark $studentmark)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Studentmark  $studentmark
     * @return \Illuminate\Http\Response
     */
    public function destroy(Studentmark $studentmark)
    {
        //
    }

    public function applyordinance163($exam_id = 9)
    {

      
        $ordinacemaxmarks = 5;
        $studentdata = Studentordinace163::where('exam_id', $exam_id)
            ->where('status', '0')
            ->get();
        foreach ($studentdata as $student) {
           
            if ($student->marksused <= $ordinacemaxmarks) {

                $remainingordinacemarks = $ordinacemaxmarks - $student->marksused;
            //    dd( $remainingordinacemarks);
                $marks = Studentmark::with('subject')
                     ->where('student_id', $student->student_id)
                    ->where('exam_id', $exam_id)
                    ->where(function ($query){
                        $query->where('grade', 'F')
                        ->orWhere('grade', NULL);
                    })
                    
                   
                    ->get();
                  
                if ($marks->count() != 0) {

                    foreach ($marks as $mark) {
                        
                        //5 marks limit condition

                        switch ($mark->subject->subject_type) {
                            case 'IE':
                            case 'IP':
                            case 'IEG':
                                     {
                          //   dd($mark);
                                    if ($mark->int_marks < $mark->subject->subject_intpassing) {
                                      
                                        $shortfall = $mark->subject->subject_intpassing - $mark->int_marks;
                                        if ($shortfall <= $remainingordinacemarks) {

                                            $mark->update([
                                                'int_ordinace_flag' => 2,
                                                'int_ordinance_one_marks' => $shortfall,
                                                'int_marks' => $mark->int_marks + $shortfall,
                                                'gpa' => 0,
                                                'total' => 0,
                                                'grade' => NULL,
                                                'grade_point' => 0,
                                            ]);
                                            $student->update([
                                                'marksused' => $student->marksused + $shortfall,
                                                'status' => 1,
                                            ]);
                                            $remainingordinacemarks -= $shortfall;
                                        }
                                    }
                                    if ($mark->ext_marks < $mark->subject->subject_extpassing) //
                                    {
                                    //    dd($marks);
                                        $shortfall = $mark->subject->subject_extpassing - $mark->ext_marks;
                                        if ($shortfall <= $remainingordinacemarks) {

                                            $mark->update([
                                                'ext_ordinace_flag' => 2,
                                                'ext_ordinance_one_marks' => $shortfall,
                                                'ext_marks' => $mark->ext_marks + $shortfall,
                                                'gpa' => 0,
                                                'total' => 0,
                                                'grade' => NULL,
                                                'grade_point' => 0,
                                            ]);

                                            $student->update([
                                                'marksused' => $student->marksused + $shortfall,
                                                'status' => 1,
                                            ]);

                                            $remainingordinacemarks -= $shortfall;
                                        }
                                    }


                                    break;
                                }
                            case 'IEP':

                                {
                                    //   dd($mark);

                                    if ($mark->int_practical_marks < $mark->subject->subject_intpractpassing) {
                                                
                                        $shortfall = $mark->subject->subject_intpractpassing - $mark->int_practical_marks;
                                        if ($shortfall <= $remainingordinacemarks) {

                                            $mark->update([
                                                'practical_ordinace_flag' => 2,
                                                'practical_ordinance_one_marks' => $shortfall,
                                                'int_practical_marks' => $mark->int_practical_marks + $shortfall,
                                                'gpa' => 0,
                                                'total' => 0,
                                                'grade' => NULL,
                                                'grade_point' => 0,
                                            ]);
                                            $student->update([
                                                'marksused' => $student->marksused + $shortfall,
                                                'status' => 1,
                                            ]);
                                            $remainingordinacemarks -= $shortfall;
                                        }
                                    }
                                              if ($mark->int_marks < $mark->subject->subject_intpassing) {
                                                
                                                  $shortfall = $mark->subject->subject_intpassing - $mark->int_marks;
                                                  if ($shortfall <= $remainingordinacemarks) {
          
                                                      $mark->update([
                                                          'int_ordinace_flag' => 2,
                                                          'int_ordinance_one_marks' => $shortfall,
                                                          'int_marks' => $mark->int_marks + $shortfall,
                                                          'gpa' => 0,
                                                          'total' => 0,
                                                          'grade' => NULL,
                                                          'grade_point' => 0,
                                                      ]);
                                                      $student->update([
                                                          'marksused' => $student->marksused + $shortfall,
                                                          'status' => 1,
                                                      ]);
                                                      $remainingordinacemarks -= $shortfall;
                                                  }
                                              }
                                              if ($mark->ext_marks < $mark->subject->subject_extpassing) //
                                              {
                                              //    dd($marks);
                                                  $shortfall = $mark->subject->subject_extpassing - $mark->ext_marks;
                                                  if ($shortfall <= $remainingordinacemarks) {
          
                                                      $mark->update([
                                                          'ext_ordinace_flag' => 2,
                                                          'ext_ordinance_one_marks' => $shortfall,
                                                          'ext_marks' => $mark->ext_marks + $shortfall,
                                                          'gpa' => 0,
                                                          'total' => 0,
                                                          'grade' => NULL,
                                                          'grade_point' => 0,
                                                      ]);
          
                                                      $student->update([
                                                          'marksused' => $student->marksused + $shortfall,
                                                          'status' => 1,
                                                      ]);
          
                                                      $remainingordinacemarks -= $shortfall;
                                                  }
                                              }
          
          
                                              break;
                                          }

                                break;
                           
                            case 'I':
                            case 'IG':
                                if ($mark->int_marks < $mark->subject->subject_intpassing) {
                                      
                                    $shortfall = $mark->subject->subject_intpassing - $mark->int_marks;
                                    if ($shortfall <= $remainingordinacemarks) {

                                        $mark->update([
                                            'int_ordinace_flag' => 2,
                                            'int_ordinance_one_marks' => $shortfall,
                                            'int_marks' => $mark->int_marks + $shortfall,
                                            'gpa' => 0,
                                            'total' => 0,
                                            'grade' => NULL,
                                            'grade_point' => 0,
                                        ]);
                                        $student->update([
                                            'marksused' => $student->marksused + $shortfall,
                                            'status' => 1,
                                        ]);
                                        $remainingordinacemarks -= $shortfall;
                                    }
                                }
                                break;
                            
                        }
                    }
                }
            }
        }
        dd("Ordinace Applied");
    }
}
