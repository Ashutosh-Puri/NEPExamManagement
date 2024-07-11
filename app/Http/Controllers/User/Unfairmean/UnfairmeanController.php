<?php

namespace App\Http\Controllers\User\Unfairmean;

use PDF;
use Mpdf\Mpdf;
use App\Models\Exam;
use App\Models\Unfairmeans;
use Illuminate\Http\Request;
use App\Models\Unfairmeansmaster;
use App\Http\Controllers\Controller;

class UnfairmeanController extends Controller
{
    public function unfairmeanattendance(){
        $exam=Exam::where('status','1')->get()->first();
        $unfairmean=Unfairmeansmaster::where('status',1)->get()->first();
        $unfaircases=Unfairmeans::whereRelation('exampatternclass','exam_id',$exam->id)
         ->where('email','1')
        ->get();

        $pdf = PDF::loadView('pdf.user.unfairmeans.unfairmeanattendance',compact('exam','unfaircases','unfairmean'))
        ->setPaper('a4')->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->download('Unfairmeans_Attendance.pdf');
    }

    public function unfairmeanreport(){
        $exam=Exam::where('status','1')->get()->first();
        // dd($exam);
        $unfaircases=Unfairmeans::where('email','1')
        ->whereRelation('exampatternclass','exam_id',$exam->id)
        ->get();
        // dd($unfaircases);
        $pdf = PDF::loadView('pdf.user.unfairmeans.unfairmeanfinalreport',compact('exam','unfaircases'))
        ->setPaper('a4')->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->download('Unfairmeans_Report.pdf');

    }

    public function performancecancelreport(){
        $exam = Exam::where('status', '1')->first(); // Assuming 'status' is unique, you can directly use first()
        $unfaircases = Unfairmeans::where('email', '1')->get();

        // Load the view for the PDF content
        $html = view('pdf.user.unfairmeans.performancecancelreport', compact('exam', 'unfaircases'))->render();

        // Initialize mPDF
        $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4', 'default_font' => 'sans-serif']);
        $mpdf->autoScriptToLang=true;
        $mpdf->autoLangToFont=true;

        // Write HTML content to PDF
        $mpdf->WriteHTML($html);

        // Output PDF as a stream
        $mpdf->Output('Performance_Cancel_Report.pdf', 'D');


     }
}
