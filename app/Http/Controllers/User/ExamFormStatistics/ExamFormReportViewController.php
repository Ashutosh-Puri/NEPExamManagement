<?php

namespace App\Http\Controllers\User\ExamFormStatistics;

use PDF;
use App\Models\Exam;
use Illuminate\Http\Request;
use App\Models\Exampatternclass;
use App\Http\Controllers\Controller;

class ExamFormReportViewController extends Controller
{
    public function exam_form_report_view($exam_pattern_class_id, $status)
    {    

        $exam = Exam::where('status',1)->first();
        $flag = null;
        $data=collect();
        
        if($status == 4)
        {
            $exam_pattern_classes_raw_data=Exampatternclass::find($exam_pattern_class_id)->patternclass->admissiondatas->where('academicyear_id',$exam->academicyear_id)->groupBy('memid');

            foreach( $exam_pattern_classes_raw_data as  $exam_pattern_class_raw_data)
            {
               $data->push($exam_pattern_class_raw_data->first());
            }

            $flag = 'Admission';
        }

        if ($status == 0) 
        {
            $data = Exampatternclass::find($exam_pattern_class_id)->patternclass->examformmasters->where('printstatus', $status)->where('exam_id',$exam->id)->where('inwardstatus', '0');
            $flag = 'Incomplete';
        } 
        else if ($status == 1) 
        {
            $data = Exampatternclass::find($exam_pattern_class_id)->patternclass->examformmasters->where('printstatus', $status)->where('exam_id',$exam->id)->where('inwardstatus', '0');
            $flag = 'Yet To Inward';
        } 
        else if ($status == 2) 
        {
            $data = Exampatternclass::find($exam_pattern_class_id)->patternclass->examformmasters->where('inwardstatus', 1)->where('exam_id',$exam->id);
            $flag = 'Yet To Transaction';
        } 
        else if ($status == 3) 
        {
            $data = Exampatternclass::find($exam_pattern_class_id)->patternclass->examformmasters->where('inwardstatus', 2)->where('exam_id',$exam->id);

            $flag = 'Complete';
        }

        if (!$data->isEmpty()) 
        {
            if( $flag == 'Admission') 
            {

                $pdf = PDF::loadView('pdf.user.exam_form_statistics_report.exam_form_statistics_report', compact('data', 'flag'))->setPaper('A4');
            }
            else
            {
                $pdf = PDF::loadView('pdf.user.exam_form_statistics_report.exam_form_statistics_report', compact('data', 'flag'))->setPaper('A4', 'landscape');
            }
 
            return $pdf->download(trim(Exampatternclass::find($exam_pattern_class_id)->patternclass->courseclass->course->course_name).'-'.$flag.'.pdf');
            // return $pdf->stream( trim(Exampatternclass::find($exam_pattern_class_id)->patternclass->courseclass->course->course_name) . '.pdf');

            return view("pdf.user.exam_form_statistics_report.exam_form_statistics_report", compact('data', 'flag'))->extends('layouts.user')->section('user');
        }else
        {
            return redirect()->route('user.exam_form_statistics')->with('alert', ['type' => 'info', 'message' => 'Data Not Exists For This Selected Exam Pattern Class.']);
        }

    }
}
