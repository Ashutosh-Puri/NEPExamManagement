<?php

namespace App\Exports\User\ExamTimeTable;

use Illuminate\View\View;
use App\Models\Examtimetable;
use Maatwebsite\Excel\Concerns\FromView;


class ExportExamTimeTable implements FromView
{
    protected $examtimetable;
    protected $exampatternclass;
    protected $exam;


    function __construct($examtimetable,$epc,$exam)
    {
        $this->epc=$epc;
        $this->exam=$exam;
        $this->examtimetable=$examtimetable;
        
    }

    public function view(): View
    {
         
        return view('pdf.examtimetable.exporttimetable', [
            'examtimetable'=>$this->examtimetable,
            'exampatternclass'=>$this->exampatternclass,
            'exam'=>$this->exam,
            ]
        );
    }
}
