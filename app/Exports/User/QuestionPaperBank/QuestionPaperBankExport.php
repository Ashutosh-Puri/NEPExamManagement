<?php

namespace App\Exports\User\QuestionPaperBank;


use App\Models\Exam;
use App\Models\Paperset;
use App\Models\Examformmaster;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class QuestionPaperBankExport implements FromView , ShouldAutoSize
{

    public function view(): View
    {   
        $papersets = Paperset::get();
        $exam = Exam::where('status', 1)->first();
        $exam_form_masters = Examformmaster::where('exam_id', $exam->id)->get();

        return view('pdf.user.question_paper_bank.question_paper_bank_pdf',compact('papersets','exam','exam_form_masters'));
    }
}