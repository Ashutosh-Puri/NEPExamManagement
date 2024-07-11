<?php

namespace App\Livewire\Student\StudentExamForm;

use App\Models\Exam;
use Livewire\Component;
use App\Models\Examformmaster;
use Illuminate\Support\Facades\Auth;

class DeleteStudentExamForm extends Component
{   

    public function mount()
    {   
        $student= Auth::guard('student')->user();

        $exam=Exam::where('status',1)->first();

        if($exam)
        { 
            $currentprintstatus=$student->examformmasters->where('exam_id',$exam->id)->first();

            if(isset($currentprintstatus->printstatus) && $currentprintstatus->printstatus==1)
            {
                $this->dispatch('alert',type:'info',message:"Can't Delete  Your Exam Form Has Already Printed.");
                session()->flash('alert', ['type' => 'info', 'message' => "Can't Delete  Your Exam Form Has Been Already Printed."]);
            }
            else 
            {
                $exam_form_master=Examformmaster::where('student_id',$student->id)->where('printstatus',0)->first();
                if($exam_form_master)
                {
                    $exam_form_master->delete();
                    $this->dispatch('alert',type:'info',message:'Exam Form Deleted Successfully !!');
                    session()->flash('alert', ['type' => 'success', 'message' => 'Exam Form Deleted Successfully !!']);
                }
            }
            $this->redirect('/student/dashboard',navigate:true);
        }
    
    }
}
