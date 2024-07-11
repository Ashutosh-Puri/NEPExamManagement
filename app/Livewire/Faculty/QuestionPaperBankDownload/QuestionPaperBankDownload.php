<?php

namespace App\Livewire\Faculty\QuestionPaperBankDownload;

use Carbon\Carbon;
use App\Models\Exam;
use App\Models\College;
use App\Models\Setting;
use Livewire\Component;
use App\Models\Paperset;
use App\Models\Examorder;
use App\Models\Exampanel;
use Livewire\WithPagination;
use App\Models\Examtimetable;
use App\Models\Subjectbucket;
use App\Models\Timetableslot;
use App\Models\Papersubmission;
use Illuminate\Support\Facades\Auth;

class QuestionPaperBankDownload extends Component
{   
    use WithPagination;
    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;
    public $exam;
    public $subject_ids=[];
    public $pappersets=[];
    public $exam_patternclass_ids=[];


    public function mount()
    {   
        $this->exam = Exam::where('status', 1)->first();
        $this->exam_patternclass_ids = $this->exam->exampatternclasses()->where('launch_status', 1)->pluck('id');
        $exampanel_ids= Examorder::whereIn('exam_patternclass_id',$this->exam_patternclass_ids)->where('email_status',1)->pluck('exampanel_id');
        $this->subject_ids =Exampanel::whereIn('id',$exampanel_ids)->where('faculty_id',Auth::guard('faculty')->user()->id)->where('examorderpost_id',1)->pluck('subject_id');
        $this->pappersets = Paperset::get();
    }


    public function render()
    {   
        $intervalInMinutes =120;

        $college=College::where('is_default',1)->first();

        if($college)
        {
            $setting=Setting::where('college_id',$college->id)->first();
            if ($setting) 
            {
            
               $intervalInMinutes =$setting->exam_time_interval;
            }
        }
        
        $currentDateTime = Carbon::now();
    
        $startTime = \DateTime::createFromFormat('H:i:s',  $currentDateTime->toTimeString())->format('H:i:s');
        $endTime = \DateTime::createFromFormat('H:i:s', $currentDateTime->addMinutes($intervalInMinutes)->toTimeString())->format('H:i:s');

        $papersubmissions = collect();

        if ($this->exam) 
        {   
            $timeslot_ids=Timetableslot::whereBetween('start_time',[$startTime, $endTime])->pluck('id');
            $subject_ids_filtered = Examtimetable::whereIn('timeslot_id',$timeslot_ids)->whereIn('subject_id',$this->subject_ids)->where('status',1)->whereIn('exam_patternclasses_id', $this->exam_patternclass_ids)->whereDate('examdate',date('Y-m-d'))->pluck('subject_id');
            $papersubmissions = Papersubmission::where('is_confirmed', 1)->whereIn('subject_id', $subject_ids_filtered)->paginate($this->perPage);
        }

        
        return view('livewire.faculty.question-paper-bank-download.question-paper-bank-download', compact('papersubmissions'))->extends('layouts.faculty')->section('faculty');
    }
}
