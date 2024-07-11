<?php

namespace App\Http\Controllers\Student\Student;

use PDF;
use DNS1D;
use App\Models\Exam;
use App\Models\Instruction;
use Illuminate\Http\Request;
use App\Models\Examtimetable;
use App\Models\Examstudentseatno;
use App\Models\Studentordinace163;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function print_preview_exam_form()
    {   
        $student = Auth::guard('student')->user();
        $active_exam = Exam::select('id')->Where('status',1)->first();
        if($active_exam)
        {
            $exam_form_master=$student->examformmasters->where('exam_id', $active_exam->id)->first();
            if($exam_form_master)
            {   
                if($exam_form_master->printstatus==0)
                {   
                    $flag=1;
                    $pdf = PDF::loadView('pdf.student.exam_form.print_exam_form_pdf', compact('exam_form_master','flag'))->setPaper('a4', 'portrait')->setOptions(['images' => true, 'defaultFont' => 'sans-serif']);
                    $pdf->output();
                    $canvas = $pdf->getDomPDF()->getCanvas();
                    $canvas->set_opacity(.2,"Multiply");
                    $canvas->page_text($canvas->get_width()/5,  $canvas->get_height()/2, 'Print Preview', null, 70, array(0,0,0),2,2,-30);
                    return $pdf->stream('exam_form_preview.pdf');
                }
            }
        }
    }

    public function print_final_exam_form()
    {   
        $student = Auth::guard('student')->user();
        $active_exam = Exam::select('id')->Where('status',1)->first();
        if($active_exam)
        {
            $exam_form_master=$student->examformmasters->where('exam_id', $active_exam->id)->first();
            if($exam_form_master)
            {   
                if($exam_form_master->printstatus==0)
                {   
                    $exam_form_master->update(['printstatus'=>1]);
                }
                
                $pdf = PDF::loadView('pdf.student.exam_form.print_exam_form_pdf', compact('exam_form_master'))->setPaper('a4', 'portrait')->setOptions(['images' => true, 'defaultFont' => 'sans-serif']);
                $pdf->output();
                return $pdf->stream('exam_form.pdf');
            }
        }
    }

   
    public function print_exam_form_fee_recipet()
    {   
        $student = Auth::guard('student')->user();
        $active_exam = Exam::select('id')->Where('status',1)->first();
        if($active_exam)
        {
            $exam_form_master=$student->examformmasters->where('exam_id', $active_exam->id)->first();
            if($exam_form_master)
            {

                $pdf = PDF::loadView('pdf.student.exam_form.print_exam_form_fee_recipet', compact('exam_form_master'))->setPaper('A4');
                return $pdf->download('exam_form_fee_recipet.pdf');
            }
        }
    }

    public function download_hallticket_old(Request $request)
    {

        $student = Auth::guard('student')->user();
        $exam = Exam::where('status',1)->first();
        if($exam)
        {
            // $pendingfee = Pendigfees::where('memid', $student->memid)->where('feepaidstatus',1)->get();

            // $pendingstudfee = $pendingfee->sum('actual_fee') - $pendingfee->sum('paid_fee');

            // if ($pendingfee->sum('actual_fee') > $pendingfee->sum('paid_fee'))
            // {
            //     return back()->with('success', "Please Contact to Accounts Department for your Pending Fee is Rs- " . $pendingstudfee);
            // }


            ini_set('max_execution_time', 3000);
            ini_set('memory_limit', '2048M');

            $examseatno = $student->examstudentseatnos->last();

            $hallticketdata = collect();

        
            foreach ($examseatno->student->studentexamforms->where('exam_id', $exam->id)->sortBy('subject_id')  as $examform) 
            {
                if ($examform->subject->id == 1052 || $examform->subject->id == 969 ||  $examform->subject->id == 979 || $examform->subject->id == 989 ) 
                {
                    $hallticketdata->add([
                        'subject_sem' => $examform->subject->subject_sem,
                        'subject_code' => $examform->subject->subject_code,
                        'subject_prefix' => $examform->subject->subject_prefix,
                        'subject_name' => $examform->subject->subject_name,
                        'subject_type' => $examform->subject->subject_type,
                        'int_status' => $examform->int_status,
                        'int_practical_status' => $examform->int_practical_status,
                        'ext_status' => $examform->ext_status,
                        'examdate' => null,
                        'timeslot' => '@Department'
                    ]);

                } 
                else 
                {
                    $hallticketdata->add([
                        'subject_sem' => $examform->subject->subject_sem,
                        'subject_code' => $examform->subject->subject_code,
                        'subject_prefix' => $examform->subject->subject_prefix,
                        'subject_name' => $examform->subject->subject_name,
                        'subject_type' => $examform->subject->subject_type,
                        'int_status' => $examform->int_status,
                        'int_practical_status' => $examform->int_practical_status,
                        'ext_status' => $examform->ext_status,
                        'examdate' => Examtimetable::where('subject_id',$examform->subject->id)->whereHas('exampatternclass',function( $query){   $query->where('exam_id',Exam::where('status', '1')->get()->first()->id);  })->get()->first()->examdate??null,
                        'timeslot' => Examtimetable::where('subject_id',$examform->subject->id)->whereHas('exampatternclass',function( $query){   $query->where('exam_id',Exam::where('status', '1')->get()->first()->id); })->get()->first()->timetableslot->timeslot??null        
                    ]);
                }
            }
            
            $sortdata = $hallticketdata->sortBy([
                ['examdate', 'asc'],
                ['timeslot', 'asc'],
            ]);

            $instructions = Instruction::where('is_active',1)->where('instructiontype_id',2)->get();

            $pdf = PDF::loadView('pdf.student.hallticket.student_hallticket_old', compact('examseatno', 'exam', 'sortdata','instructions'))->setPaper('a4', 'portrait');

            $pdf->output();

            $canvas = $pdf->getDomPDF()->getCanvas();

            $height = $canvas->get_height();


            $canvas->page_text(10, 10, "Page: {PAGE_NUM} of {PAGE_COUNT}", null, 12, array(0, 0, 0));
            $examformmaster = $student->examformmasters->where('exam_id', $exam->id)->first();
            $examformmaster->update(['hallticketstatus' => 1]);

            return $pdf->download("Hall_Ticket_" . $examseatno->seatno . '.pdf');
        }
    }

    public function download_hallticket(Request $request)
    {
        $student = Auth::guard('student')->user();
        $exam = Exam::where('status',1)->first();
        if($exam)
        {   


            // $pendingfee = Pendigfees::where('memid', $student->memid)->where('feepaidstatus',1)->get();

            // $pendingstudfee = $pendingfee->sum('actual_fee') - $pendingfee->sum('paid_fee');

            // if ($pendingfee->sum('actual_fee') > $pendingfee->sum('paid_fee'))
            // {
            //     return back()->with('success', "Please Contact to Accounts Department for your Pending Fee is Rs- " . $pendingstudfee);
            // }

            ini_set('max_execution_time', 3000);
            ini_set('memory_limit', '2048M');

            $examseatno = $student->examstudentseatnos->last();

            Examstudentseatno::where('exam_patternclasses_id',$examseatno->exam_patternclasses_id)->update(['printstatus'=> 1]);

            $hallticketdata = collect();
        
            foreach ($examseatno->student->studentexamforms->where('exam_id', $exam->id)->sortBy('subject_id')  as $student_exam_form_entry) 
            {

                if ($student_exam_form_entry->subject->id == 1052 || $student_exam_form_entry->subject->id == 969 ||  $student_exam_form_entry->subject->id == 979 || $student_exam_form_entry->subject->id == 989 ) 
                {
                    $exam_date_apply=null;
                    $exam_time_apply='@Department';
                }else
                {
                    $exam_date_apply= Examtimetable::where('subject_id',$student_exam_form_entry->subject->id)->whereHas('exampatternclass',function( $query){   $query->where('exam_id',Exam::where('status', 1)->get()->first()->id);  })->get()->first()->examdate??null;
                    $exam_time_apply= Examtimetable::where('subject_id',$student_exam_form_entry->subject->id)->whereHas('exampatternclass',function( $query){   $query->where('exam_id',Exam::where('status', 1)->get()->first()->id); })->get()->first()->timetableslot->timeslot??null;
                }

                if(1)
                {
                    $subject_type = '';
                    $exam_date;
                    $exam_time;
            
                    if ($student_exam_form_entry->int_status == 1) 
                    {
                        $subject_type = 'I';
                    }
            
                    if ($student_exam_form_entry->ext_status == 1) 
                    {
                        if ( $student_exam_form_entry->subject->subject_type == 'IP') {
                            $subject_type =$subject_type . 'P';
                        } else {
                            $subject_type = $subject_type . 'E';
                        }
                    }
            
                    if ( $student_exam_form_entry->int_practical_status == 1) 
                    {
                        $subject_type = $subject_type . 'P';
                    }
            
                    if ($student_exam_form_entry->subject->subject_code == 'GR. 4B') {
                        $subject_type = 'I';
                    }
                    
                    if ($student_exam_form_entry->subject->subject_type == 'IG') 
                    {
                        $subject_type = 'IG';
                    } 
                    elseif ($student_exam_form_entry->subject->subject_type == 'G') 
                    {
                        $subject_type = 'G';
                    } elseif ($student_exam_form_entry->subject->subject_type == 'IEG') 
                    {
                        $subject_type =$subject_type . 'G';
                    }


                    if ($student_exam_form_entry->subject->subject_type == 'IEG' && ($subject_type == 'IE' || $subject_type == 'I' || $subject_type == 'E'))
                    {
                        $exam_date='-';
                        $exam_time='@Department';
                    }
                    else
                    {
                        if (strpos($student_exam_form_entry->subject->subject_prefix, 'VSC') !== false && ($subject_type == 'IP' || $subject_type == 'I' || $subject_type == 'P'))
                        { 
                            if (!is_null($student_exam_form_entry))
                            {   
                                if(is_null($exam_date_apply))
                                {
                                    $exam_date='-';
                                }else
                                {
                                    $exam_date= date('l, d-m-Y', strtotime($exam_date_apply));
                                }
            
                                $exam_time= $exam_time_apply.' @Department';
                            }
                        }
                        else
                        {
                            if (strpos($student_exam_form_entry->subject->subject_prefix, 'VSC') !== false && $subject_type == 'I')
                            {
                                if (!is_null($student_exam_form_entry))
                                {
                                    $exam_date='-';
                                    $exam_time='@Department';
                                }
                            }
                            else
                            {
                              if ($subject_type == 'IP' || $subject_type == 'I' || $subject_type == 'IG' || $subject_type == 'G' || $subject_type == 'P' || $subject_type == 'IEG')
                              {
                                $exam_date='-';
            
                                if($subject_type == 'G')
                                {
                                    $exam_time='@Gymkhana Department';
                                }else
                                {
                                    $exam_time='@Department';
                                }
                              }
                              else
                              {
                                if (!is_null($student_exam_form_entry))
                                {  
                                    
                                    if(is_null($exam_date_apply))
                                    {
                                        $exam_date='-';
                                    }
                                    else
                                    {
                                        $exam_date = date('l, d-m-Y', strtotime($exam_date_apply));
                                    }

                                    $exam_time =  $exam_time_apply;
                               }
                                    
                               
                              }
                              
                            }
                        }
                    }
                }


         
                $hallticketdata->add([
                    'subject_sem' => $student_exam_form_entry->subject->subject_sem,
                    'subject_code' => $student_exam_form_entry->subject->subject_code,
                    'subject_prefix' => $student_exam_form_entry->subject->subject_prefix,
                    'subject_name' => $student_exam_form_entry->subject->subject_name,
                    'subject_type' => $subject_type,
                    'exam_date' => $exam_date,
                    'exam_time' => $exam_time,     
                ]);
                
            }
            
            $sortdata = $hallticketdata->sortBy([
                ['examdate', 'asc'],
                ['timeslot', 'asc'],
            ]);

            $instructions = Instruction::where('is_active',1)->where('instructiontype_id',2)->get();

            $pdf = PDF::loadView('pdf.student.hallticket.student_hallticket', compact('examseatno', 'exam', 'sortdata','instructions'))->setPaper('a4', 'portrait');

            $pdf->output();

            $canvas = $pdf->getDomPDF()->getCanvas();

            $height = $canvas->get_height();


            $canvas->page_text(10, 10, "Page: {PAGE_NUM} of {PAGE_COUNT}", null, 12, array(0, 0, 0));
            $examformmaster = $student->examformmasters->where('exam_id', $exam->id)->first();
            $examformmaster->update(['hallticketstatus' => 1]);

            return $pdf->stream("Hall_Ticket_" . $examseatno->seatno . '.pdf');
        }
    }

    
    public function student_print_ordinace_163_form_fee_recipet(Request $request)
    {   
        $studentordinace163= Studentordinace163::find($request->student_ordinace_163_id);

        if($studentordinace163)
        {

            $pdf = PDF::loadView('pdf.student.ordinace.print_ordinace_163_form_fee_recipet', compact('studentordinace163'))->setPaper('A4');
            return $pdf->download('ordinace_163_form_fee_recipet.pdf');
        }        
    }
}
