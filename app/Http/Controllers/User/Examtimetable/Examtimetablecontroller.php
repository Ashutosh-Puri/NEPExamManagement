<?php

namespace App\Http\Controllers\User\Examtimetable;

use PDF;
use Excel;
use App\Models\Exam;
use App\Models\Exambody;
use Illuminate\Http\Request;
use App\Models\Examtimetable;
use App\Models\Instruction;
use App\Models\Exampatternclass;
use App\Http\Controllers\Controller;
use App\Exports\User\ExamTimeTable\ExportExamTimeTable;

class Examtimetablecontroller extends Controller
{
    public function exam_time_table_pdf(Exampatternclass $exampatternclass)   
    {
        $exam=Exam::where('status',1)->get();
        $instructions = Instruction::where('is_active',1)->where('instructiontype_id',1)->get();

        $principal=Exambody::where('role_id',3)->where('is_active',1)->first();
        $ceo=Exambody::where('role_id',4)->where('is_active',1)->first();
       
        $exam_time_table_data = Examtimetable::where('exam_patternclasses_id' , $exampatternclass->id)->get();
        if (isset($exam_time_table_data)) 
        {
            // view()->share('pdf.examtimetable.examtimetable_pdf', compact('exam_time_table_data','exam'));
       
            $name=get_pattern_class_name($exampatternclass->patternclass->id);

            $pdf = Pdf::loadView('pdf.user.examtimetable.examtimetable_pdf',compact('exam_time_table_data','exam','name','instructions','principal','ceo','exampatternclass')) ->setPaper('a4')->setOptions(['defaultFont' => 'sans-serif']);
            
            return $pdf->download('Exam_Timetable_'.str_replace(' ', '_', get_pattern_class_name($exampatternclass->patternclass->id)).'.pdf');
        } else 
        {
            return abort(404);
        }
    }

    public function exam_time_table_excel(Exampatternclass $exampatternclass)
    {
        $epc=Exampatternclass::find($exampatternclass);
        $exam=Exam::where('status','1')->get();
        $examtimetable=ExamTimetable::where('exam_patternclasses_id',$exampatternclass)->get();

       
        if (isset($examtimetable)) 
        {
            return Excel::download(new ExportExamTimeTable($examtimetable,$epc,$exam), $exampatternclass->patternclass->courseclass->course->course_name." ".$exam->first()->exam_name." Exam timetable.xlsx");
        }
        else
        {
            return abort(404);
        }
    }

}
