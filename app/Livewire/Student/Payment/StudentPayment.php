<?php

namespace App\Livewire\Student\Payment;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class StudentPayment extends Component
{   
    public $student;


    public function mount()
    {
        $this->student = Auth::guard('student')->user();

        
    }
    
    public function render()
    {   
        $exam_form_masters=$this->student->examformmasters()->with('exam','transaction')->get();
        $student_ordinace_163s=$this->student->studentordinace163s()->get();

        return view('livewire.student.payment.student-payment',compact('exam_form_masters','student_ordinace_163s'))->extends('layouts.student')->section('student');
    }
}
