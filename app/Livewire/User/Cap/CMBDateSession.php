<?php

namespace App\Livewire\User\Cap;

use App\Models\Exam;
use Livewire\Component;
use App\Models\Examtimetable;

class CMBDateSession extends Component
{   
    # By Ashutosh
    public $dates=[];
    public $exam;
    public $selectedallsession=null;
    public $allsession=[];

    public function updatedSelectedallsession($id)
    {
        
        $this->dates = $this->exam->examsessions->where('id',$id)->unique('from_date', 'to_date')->first();
        $this->allsession=$this->exam->examsessions->where('id',$id)->unique('from_date', 'to_date');
        $this->dates=Examtimetable::whereBetween('examdate',[$this->dates->from_date, $this->dates->to_date])->get()->sortBy('examdate')->unique('examdate')->pluck('examdate');
       
    }
    
    public function mount()
    {  
        $this->exam =Exam::where('status',1)->first();
    } 
    
    public function render()
    {   
        $this->allsession=$this->exam->examsessions->unique('from_date', 'to_date');

        return view('livewire.user.cap.c-m-b-date-session')->extends('layouts.user')->section('user');
    }
}
