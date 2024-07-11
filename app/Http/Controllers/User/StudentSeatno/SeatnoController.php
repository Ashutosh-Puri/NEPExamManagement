<?php

namespace App\Http\Controllers\User\StudentSeatno;

use Mpdf\Mpdf;
use PDF;
use App\Models\Exam;
use Illuminate\Http\Request;
use App\Models\Exampatternclass;
use App\Models\Examstudentseatno;
use App\Http\Controllers\Controller;

class SeatnoController extends Controller
{
    #[Renderless]
    public function seatnopdf(Exampatternclass $exampatternclass)
    {
        ini_set('max_execution_time', 3000);
        ini_set('memory_limit', '2048M');

        $examseatnodata = Examstudentseatno::where('exam_patternclasses_id', $exampatternclass->id)->get();

        view()->share('examseatnodata', $examseatnodata);

        $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);

        $mpdf->WriteHTML(view('pdf.user.seatno.seatno_pdf', compact('examseatnodata')));

        $mpdf->Output('Seat_Number_' . str_replace(' ', '_', get_pattern_class_name($exampatternclass->patternclass->id)) . '.pdf', 'D');
    }
}
