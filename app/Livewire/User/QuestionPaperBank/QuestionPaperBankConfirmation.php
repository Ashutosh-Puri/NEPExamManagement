<?php

namespace App\Livewire\User\QuestionPaperBank;

use App\Models\Exam;
use App\Models\Faculty;
use App\Models\Subject;
use Livewire\Component;
use App\Models\Examorder;
use App\Models\Exampanel;
use App\Models\Papersubmission;
use Illuminate\Support\Facades\DB;

class QuestionPaperBankConfirmation extends Component
{   
    # By Ashutosh
    public $exam;
    public $faculties=[];
    public $faculty_id;
    public $subjects=[];
    public $subject_id;

    public function reset_input()
    {   
        $this->faculty_id=null;
        $this->subject_id=null;
    }
    
    public function mount()
    {   
        $this->exam=Exam::where('status',1)->first();

        $exam_patternclass_ids = $this->exam->exampatternclasses->where('launch_status', 1)->pluck('id');
        $exampanel_ids= Examorder::whereIn('exam_patternclass_id',$exam_patternclass_ids)->where('email_status',1)->pluck('exampanel_id');
        $subject_ids =Exampanel::whereIn('id',$exampanel_ids)->where('examorderpost_id',1)->pluck('subject_id');
        $this->subjects =Subject::whereIn('id', $subject_ids)->pluck('subject_name','id');
        $faculty_ids =Exampanel::whereIn('id',$exampanel_ids)->where('examorderpost_id',1)->pluck('faculty_id');
        $this->faculties=Faculty::whereIn('id',$faculty_ids)->pluck('faculty_name','id');
    }

    public function close_one(Papersubmission $papersubmisstion)
    {   
        DB::beginTransaction();

        try
        {   
            $papersubmisstion->update(['is_confirmed'=>1]);

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Saved Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Save !!');
        }
       
    }

    public function close_all()
    {   
         
        DB::beginTransaction();

        try
        {  
            Papersubmission::where('exam_id',$this->exam->id)
            ->when($this->subject_id, function ($query) { $query->where('subject_id',$this->subject_id); })
            ->when($this->faculty_id, function ($query) { $query->where('chairman_id',$this->faculty_id); })
            ->where('is_confirmed',0)->update(['is_confirmed'=>1]);

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Saved Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Save !!');
        }
        
    }

    public function reopen_one(Papersubmission $papersubmisstion)
    {   
        DB::beginTransaction();

        try
        {  

            $papersubmisstion->update(['is_confirmed'=>0]);
            DB::commit();

            $this->dispatch('alert',type:'success',message:'Saved Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Save !!');
        }
    }

    public function reopen_all()
    {   
        DB::beginTransaction();

        try
        {  
            Papersubmission::where('exam_id',$this->exam->id)
            ->when($this->subject_id, function ($query) { $query->where('subject_id',$this->subject_id); })
            ->when($this->faculty_id, function ($query) { $query->where('chairman_id',$this->faculty_id); })
            ->where('is_confirmed',1)->update(['is_confirmed'=>0]);

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Saved Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Save !!');
        }
    }

    public function render()
    {   
        
        $papersubmissions_1=Papersubmission::where('exam_id',$this->exam->id)
        ->when($this->subject_id, function ($query) { $query->where('subject_id',$this->subject_id); })
        ->when($this->faculty_id, function ($query) { $query->where('chairman_id',$this->faculty_id); })
        ->where('is_confirmed',1)->get();

        $papersubmissions_0=Papersubmission::where('exam_id',$this->exam->id)
        ->when($this->subject_id, function ($query) { $query->where('subject_id',$this->subject_id); })
        ->when($this->faculty_id, function ($query) { $query->where('chairman_id',$this->faculty_id); })
        ->where('is_confirmed',0)->get();

        return view('livewire.user.question-paper-bank.question-paper-bank-confirmation',compact('papersubmissions_0','papersubmissions_1'))->extends('layouts.user')->section('user');
    }
}
