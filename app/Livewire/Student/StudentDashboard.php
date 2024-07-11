<?php

namespace App\Livewire\Student;

use App\Models\Exam;
use App\Models\Student;
use Livewire\Component;
use App\Models\Patternclass;
use App\Models\Exampatternclass;
use Illuminate\Support\Facades\Auth;

class StudentDashboard extends Component
{   
    public Student $student;
    public $exam_form;
    public $patternclass_id;
    public $active_exam_pattern_class;
    public $pattern_class;
    public $current_class_last_entry;

    
    public function mount()
    {   
        $this->student=Auth::guard('student')->user();
        
        if($this->student->is_profile_complete===0)
        {
            return $this->redirect('/student/profile',navigate:true);
        }
        
        // session()->flash('session-alert', [ ['type' => 'info', 'message' => 'Welcome To Dashboard'],]);

        $exam=Exam::where('status',1)->first();

        $this->patternclass_id=$this->student->patternclass_id;
        $this->current_class_last_entry=$this->student->currentclassstudents->last();

        if(isset($this->current_class_last_entry))
        {
            $this->patternclass_id=$this->current_class_last_entry->patternclass_id;
            
        }

       $this->active_exam_pattern_class = $exam->exampatternclasses()->where('patternclass_id',$this->patternclass_id)->where('launch_status',1)->first();
       $this->pattern_class = Patternclass::find($this->patternclass_id);
  
        $this->exam_form=$this->student->examformmasters()->get()->last();
    }

    public function render()
    {   

        
        return view('livewire.student.student-dashboard')->extends('layouts.student')->section('student');
    }
}
