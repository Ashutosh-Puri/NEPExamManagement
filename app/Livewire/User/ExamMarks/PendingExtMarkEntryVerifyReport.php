<?php

namespace App\Livewire\User\ExamMarks;

use App\Models\Exam;
use Livewire\Component;
use App\Models\Paperassesment;

class PendingExtMarkEntryVerifyReport extends Component
{   
    # By Ashutosh
    
    public function render()
    {   
        $exam = Exam::where('status',1)->first();

        ini_set('max_execution_time', 3000);
        ini_set('memory_limit', '2048M');
     
        $perPage = 10;

        $paperassesments = Paperassesment::withCount(['exambarcodes' => function ($query) { $query->whereNull('verified_marks'); }])->where('exam_id', $exam->id)->having('exambarcodes_count', '>', 0)->paginate($perPage);

     
        return view('livewire.user.exam-marks.pending-ext-mark-entry-verify-report', compact('exam', 'paperassesments'))->extends('layouts.user')->section('user');
    }
}
