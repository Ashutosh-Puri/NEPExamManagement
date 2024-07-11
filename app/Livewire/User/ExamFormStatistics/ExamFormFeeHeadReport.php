<?php

namespace App\Livewire\User\ExamFormStatistics;

use App\Models\Exam;
use Livewire\Component;
use App\Models\Classview;
use Livewire\WithPagination;
use App\Models\Examfeecourse;
use App\Models\Examformmaster;
use App\Models\Exampatternclass;
use App\Models\Studentexamformfee;

class ExamFormFeeHeadReport extends Component
{     
    # By Ashutosh  
    public $pattern_class_id;
    public $patternclasses=[];
    public Exam $active_exam;

    public function clear()
    {   
         $this->reset([
        'pattern_class_id'
        ]);    
    }
    
    public function mount()
    {    
        $this->active_exam=Exam::where('status',1)->first();
        $this->patternclasses = Classview::where('status',1)->get();
    }


    protected function getExamFeeStatistics($pattern_class_id)
    {

        set_time_limit(600);

        $examfeecourses = Examfeecourse::with('examfee')
            ->where('patternclass_id', $pattern_class_id)
            ->where('active_status', 1)
            ->orderBy('examfees_id', 'ASC')
            ->orderBy('sem', 'ASC')
            ->get();

        $statistics = collect([]);

        foreach ($examfeecourses as $exam_fee_course) 
        {   
            $query_1=$query_2 =Studentexamformfee::whereIn('examformmaster_id', $exam_fee_course->patternclass->examformmasters->where('exam_id', $this->active_exam->id)->where('inwardstatus', 1)->pluck('id'));

            $form_count =  $query_1->where('examfees_id', $exam_fee_course->examfees_id)->count();

            $fee_count = $query_2->where('examfees_id', $exam_fee_course->examfees_id)->sum('fee_amount');

            $statistics->push([
                'exam_fees_id' => $exam_fee_course->examfees_id,
                'sem' => $exam_fee_course->sem,
                'fee_name' => $exam_fee_course->examfee->fee_name,
                'fee' => $exam_fee_course->fee,
                'form_count' => $form_count,
                'total_fee' => $fee_count,
            ]);
        }

        return $statistics;
    }

    public function render()
    {    
        $exampatternclasses = Exampatternclass::where('exam_id', $this->active_exam->id)->where('patternclass_id', $this->pattern_class_id)->get();
        $examfeestatistics = $this->getExamFeeStatistics($this->pattern_class_id);
    
        return view('livewire.user.exam-form-statistics.exam-form-fee-head-report',compact('exampatternclasses','examfeestatistics'))->extends('layouts.user')->section('user');
    }
}
