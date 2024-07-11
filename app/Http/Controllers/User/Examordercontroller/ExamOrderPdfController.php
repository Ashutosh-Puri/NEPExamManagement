<?php

namespace App\Http\Controllers\User\Examordercontroller;

use PDF;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Examorder;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Examtimetable;
use Illuminate\Support\Carbon;
use App\Models\Exampatternclass;
use App\Http\Controllers\Controller;

class ExamOrderPdfController extends Controller
{
    public $token;

    public $examorder;
    public $examtimetables;


    public function order($id, $token)
    {
        $examorder = ExamOrder::find($id);
        // dd($examorder);

        if (isset($examorder) && $examorder->token === $token)
        {
            view()->share('pdf.examorder.examorder_pdf', compact('examorder'));


            $pdf = Pdf::loadView('pdf.user.examorder.examorder_pdf', compact('examorder'))->setOptions(['defaultFont' => 'sans-serif']);


            return $pdf->download('Exam_Order.pdf');
        } else
        {
            return abort(404);
        }
    }

    public function merge($id , $token)
    {
        $examorder = ExamOrder::find($id);
        // dd($examorder);

        if (isset($examorder) && $examorder->token === $token)
        {
            view()->share('pdf.user.examorder.mergeorder_pdf', compact('examorder'));


            $pdf = Pdf::loadView('pdf.examorder.mergeorder_pdf', compact('examorder'))->setPaper([0, 0, 841.89, 595.28], 'landscape');


            return $pdf->stream('Exam-Order.pdf');
        } else
        {
            // If exam order doesn't exist or token doesn't match, return 404
            return abort(404);
        }

    }

    public function cancelorder(Examorder $examorder)
    {
        if ($examorder && $examorder->token === $this->token) {
            view()->share('pdf.user.cancelorder.cancelorder_pdf');

            $pdf = Pdf::loadView('pdf.cancelorder.cancelorder_pdf')
                ->setOptions(['defaultFont' => 'sans-serif']);

              return $pdf->stream('Exam-Order.pdf');
        } else {

            return abort(404);

        }

    }

    public function resendorder(Examorder $examorder)
    {
        if ($examorder && $examorder->token === $this->token) {
            view()->share('pdf.examorder.examorder_pdf',compact('examorder'));

            $pdf = Pdf::loadView('pdf.examorder.examorder_pdf',compact('examorder'))
                ->setOptions(['defaultFont' => 'sans-serif']);

              return $pdf->download('Exam-Order.pdf');
        } else {

            return abort(404);

        }
    }


    // public function timetable(Exampatternclass $exampatternclass)

    // {
    //     $exam_time_table_data = Examtimetable::where('exam_patternclasses_id' , $exampatternclass->id)->get();

    //     $html = view('pdf.examtimetable.examtimetable_pdf', ['exam_time_table_data' => $exam_time_table_data])->render();

    //     // Configure Dompdf options
    //     $options = new Options();
    //     $options->set('isHtml5ParserEnabled', true);

    //     $dompdf = new Dompdf($options);
    //     $dompdf->loadHtml($html);

    //     $dompdf->setPaper('A4', 'landscape');

    //     $dompdf->render();

    //     $dompdf->stream('exam_timetable.pdf', ['Attachment' => false]);

    // }

}
