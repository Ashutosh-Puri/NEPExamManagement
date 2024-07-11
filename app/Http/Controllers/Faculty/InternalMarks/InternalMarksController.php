<?php

namespace App\Http\Controllers\Faculty\InternalMarks;

use PDF;
use App\Models\Exam;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Models\Internalmarksbatch;
use App\Http\Controllers\Controller;

class InternalMarksController extends Controller
{
    public function preview_marks(Request $request)
    {
        $exam = Exam::where('status', '1')->first();
        ini_set('max_execution_time', 3000);
        ini_set('memory_limit', '2048M');
        $batchid = $request->batch_marks_id;
        $intbatch = Internalmarksbatch::findorFail($batchid);

        $strmsg = "";
        $outofmarks = "";
        $subject = Subject::find($intbatch->subject_id);

        switch ($subject->subject_type)
        {
            case "G":
                $marksType = "subject_grade";
                $strmsg = "Grade";
                break;
            case "I":
                $marksType = "int_marks";
                $strmsg = "Internal ";
                $outofmarks = $intbatch->subject->subject_maxmarks_int;
                break;
            case "IG":
                if($intbatch->subject_type=="I"||$intbatch->subject_type=="IG")
                {
                    $marksType = "int_marks";
                    $strmsg = "Internal ";
                    $outofmarks = $intbatch->subject->subject_maxmarks_int;
                }
                break;
            case "IEG":
                if($intbatch->subject_type=="I")
                {
                    $marksType = "int_marks";
                    $strmsg = "Internal ";
                    $outofmarks = $intbatch->subject->subject_maxmarks_int;
                }
                else  if($intbatch->subject_type=="IEG")
                {
                    $marksType = "ext_marks";
                    $strmsg = "External ";
                    $outofmarks = $intbatch->subject->subject_maxmarks_ext;
                }
                break;
            case "IE":
            case "IP":
                $marksType = $intbatch->subject_type == 'I' ? 'int_marks' : ($intbatch->subject_type == 'P' ? 'ext_marks' : 'int_practical_marks');
                $strmsg = $intbatch->subject_type == 'I' ? 'Internal ' : ($intbatch->subject_type == 'P' ? 'External ' : 'Internal Practical ');
                $outofmarks = $intbatch->subject_type == 'I' ? $intbatch->subject->subject_maxmarks_int : ($intbatch->subject_type == 'P' ? $intbatch->subject->subject_maxmarks_ext : $intbatch->subject->subject_maxmarks_intpract);
                break;
            case "IEP":
                $marksType = $intbatch->subject_type == 'I' ? 'int_marks' : ($intbatch->subject_type == 'P' ? 'int_practical_marks' : 'ext_marks');
                $strmsg = $intbatch->subject_type == 'I' ? 'Internal ' : ($intbatch->subject_type == 'P' ? 'Internal Practical ' : 'External ');
                $outofmarks = $intbatch->subject_type == 'I' ? $intbatch->subject->subject_maxmarks_int : ($intbatch->subject_type == 'P' ? $intbatch->subject->subject_maxmarks_intpract : $intbatch->subject->subject_maxmarks_ext);
                break;
        }

        $a = array(array());
        $rows = 4;
        $cols = 4;
        $m = 1;

        $i = 0;
        $j = 0;
        $abcnt = 0;
        $totalstudcnt = 0;

        $sorted_seatno = $intbatch->intbatchseatnoallocations->sortBy('seatno');

        foreach ($sorted_seatno as $data)
        {
            if (!is_null($data->student->studentmarks->where('subject_id', $intbatch->subject_id)->last()))
            {
                $marks = str_pad(str_replace("-1", "AB", $data->student->studentmarks->where('exam_id', $exam->id)->where('subject_id', $intbatch->subject_id)->last()->$marksType), 2, $subject->subject_type == 'G' ? " " : "0", STR_PAD_LEFT);
                $a[$i][$j] = $data->seatno . ' => ' . $marks;
                $totalstudcnt++;
                if ($marks == -1 || $marks == 'Ab' || $marks == 'AB')
                {
                    $abcnt++;
                }
                if ($j < 20)
                {
                    $j++;
                }
                if ($j == 20)
                {
                    $i++;
                    $j = 0;
                }
            }
        }

        if ($j < 20)
        {
            for (; $j < 20; $j++)
            {
                $a[$i][$j] = " ";
            }
            $i++;
        }
        $n = $i;

        $intbatch->update([
            'status' => '3',
            'totalAbsent' => $abcnt,
            'totalMarksentry' =>  $totalstudcnt - $abcnt,
            'totalBatchsize' =>  $totalstudcnt,

        ]);
        $pdf = PDF::loadView('pdf.faculty.internal_marks.preview_marks_pdf', compact('intbatch', 'batchid', 'marksType', 'strmsg', 'outofmarks', 'a', 'n', 'exam'))->setPaper('a4', 'portrait');
        $pdf->setPaper('L');
        $pdf->output();
        $canvas = $pdf->getDomPDF()->getCanvas();
        $height = $canvas->get_height();
        $width = $canvas->get_width();
        $canvas->set_opacity(.2, 'Multiply');

        $canvas->page_text(
            $width / 5,
            $height / 2,
            'Print Preview',
            null,
            70,
            array(0, 0, 0),
            2,
            2,
            -30
        );
        $canvas->page_text(
            $width / 9,
            $height / 3,
            'Print Preview',
            null,
            70,
            array(0, 0, 0),
            2,
            2,
            -30
        );
        $canvas->page_text(10, 10, "Report Page: {PAGE_NUM} of {PAGE_COUNT}", null, 12, array(0, 0, 0));
        $batchname = $intbatch->created_at->year . $intbatch->subject_id . str_pad($intbatch->id, 5, '0', STR_PAD_LEFT);

        return $pdf->download("preview" . $batchname . '.pdf');
    }

    public function print_marks(Request $request)
    {
        $exam = Exam::where('status', '1')->first();
        ini_set('max_execution_time', 3000);
        ini_set('memory_limit', '2048M');
        $batchid = $request->print_marks_id;;
        $intbatch = Internalmarksbatch::findorFail($batchid);

        $strmsg = "";
        $outofmarks = "";
        $subject = Subject::find($intbatch->subject_id);

        switch ($subject->subject_type)
        {
        case "G":
            $marksType = "subject_grade";
            $strmsg = "Grade";
            break;
        case "I":
            $marksType = "int_marks";
            $strmsg = "Internal ";
            $outofmarks = $intbatch->subject->subject_maxmarks_int;
            break;
        case "IG":
            if($intbatch->subject_type=="I"||$intbatch->subject_type=="IG")
            {
                $marksType = "int_marks";
                $strmsg = "Internal ";
                $outofmarks = $intbatch->subject->subject_maxmarks_int;
            }
            break;
            case "IEG":
            if($intbatch->subject_type=="I")
            {
                $marksType = "int_marks";
                $strmsg = "Internal ";
                $outofmarks = $intbatch->subject->subject_maxmarks_int;
            }
            else  if($intbatch->subject_type=="IEG")
            {
                $marksType = "ext_marks";
                $strmsg = "External ";
                $outofmarks = $intbatch->subject->subject_maxmarks_ext;
            }
            break;
            case "IE":
            case "IP":
                $marksType = $intbatch->subject_type == 'I' ? 'int_marks' : ($intbatch->subject_type == 'P' ? 'ext_marks' : 'int_practical_marks');
                $strmsg = $intbatch->subject_type == 'I' ? 'Internal marks' : ($intbatch->subject_type == 'P' ? 'External marks' : 'Internal Practical Marks');
                $outofmarks = $intbatch->subject_type == 'I' ? $intbatch->subject->subject_maxmarks_int : ($intbatch->subject_type == 'P' ? $intbatch->subject->subject_maxmarks_ext : $intbatch->subject->subject_maxmarks_intpract);
                break;

            case "IEP":
                $marksType = $intbatch->subject_type == 'I' ? 'int_marks' : ($intbatch->subject_type == 'P' ? 'int_practical_marks' : 'ext_marks');
                $strmsg = $intbatch->subject_type == 'I' ? 'Internal marks' : ($intbatch->subject_type == 'P' ? 'Internal Practical Marks' : 'External marks');
                $outofmarks = $intbatch->subject_type == 'I' ? $intbatch->subject->subject_maxmarks_int : ($intbatch->subject_type == 'P' ? $intbatch->subject->subject_maxmarks_intpract : $intbatch->subject->subject_maxmarks_ext);
            break;
        }

        $a = array(array());
        $rows = 4;
        $cols = 4;
        $m = 1;

        $i = 0;
        $j = 0;

        $abcnt = 0;
        $totalstudcnt = 0;

        $sorted_seatno = $intbatch->intbatchseatnoallocations->sortBy('seatno');

        foreach ($sorted_seatno as $data)
        {
            if (!is_null($data->student->studentmarks->where('subject_id', $intbatch->subject_id)->last()))
            {
                $marks = str_pad(str_replace("-1", "AB", $data->student->studentmarks->where('exam_id', $exam->id)->where('subject_id', $intbatch->subject_id)->last()->$marksType), 2, $subject->subject_type == 'G' ? " " : "0", STR_PAD_LEFT);
                $a[$i][$j] = $data->seatno . ' => ' . $marks;
                $totalstudcnt++;
                if ($marks == -1 || $marks == 'Ab' || $marks == 'AB')
                {
                    $abcnt++;
                }
                if ($j < 20)
                {
                    $j++;
                }
                if ($j == 20)
                {
                    $i++;
                    $j = 0;
                }
            }
        }
        if ($j < 20)
        {
            for (; $j < 20; $j++)
            {
                $a[$i][$j] = " ";
            }
            $i++;
        }
        $n = $i;
        $intbatch->update([
            'totalAbsent' => $abcnt,
            'totalMarksentry' =>  $totalstudcnt - $abcnt,
            'totalBatchsize' =>  $totalstudcnt,
        ]);

        $pdf = PDF::loadView('pdf.faculty.internal_marks.preview_marks_pdf', compact('intbatch', 'batchid', 'marksType', 'strmsg', 'outofmarks', 'a', 'n', 'exam'))->setPaper('a4', 'portrait');

        $pdf->setPaper('L');
        $pdf->output();
        $canvas = $pdf->getDomPDF()->getCanvas();
        $height = $canvas->get_height();
        $width = $canvas->get_width();

        $canvas->page_text(10, 10, "Report Page: {PAGE_NUM} of {PAGE_COUNT}", null, 12, array(0, 0, 0));
        $canvas->page_text($height - 10, 10, "Report Page: {PAGE_NUM} of {PAGE_COUNT}", null, 12, array(0, 0, 0));
        $batchname = $intbatch->created_at->year . $intbatch->subject_id . str_pad($intbatch->id, 5, '0', STR_PAD_LEFT);
        return $pdf->download("print" . $batchname . '.pdf');
    }
}
