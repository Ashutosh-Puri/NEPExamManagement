<?php

namespace App\Livewire\User\Examsupervision;

use App\Models\Exam;
use App\Models\Faculty;
use Livewire\Component;
use App\Models\Department;
use App\Models\Examsession;
use App\Models\Examtimetable;
use App\Models\Examsupervision;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AllExamsupervision extends Component
{

    #[Locked]
    public $dates =[];
    #[Locked]
    public $faculties = [];
    #[Locked]
    public $sessiontype = [];
    #[Locked]
    public $allsession = [];
    #[Locked]
    public $departments = [];

    public $exam;
    public $supervisionchart = []; 
    public $selecteddepartment;
    public $selectedallsession;

    public function updatedSelectedallsession($id)
    {   

        $this->faculties = Faculty::where('college_id',1)->pluck('faculty_name','id');
        $dates = $this->exam->examsessions->where('id', $id)->unique('from_date', 'to_date')->first();
        $this->sessiontype = $this->exam->examsessions->where('from_date', $dates->from_date)->where('to_date', $dates->to_date)->unique('session_type');
        // $this->allsession = $this->exam->examsessions->where('id', $id)->unique('from_date', 'to_date');
        $this->dates = ExamTimetable::whereBetween('examdate', [$dates->from_date, $dates->to_date])->get()->sortBy('examdate')->unique('examdate')->pluck('examdate');
    }

    public function updatedSelecteddepartment($id)
    {
        $this->faculties = Faculty::where('college_id',1)->where('department_id', $id)->pluck('faculty_name','id');
    }


    public function updatedSupervisionchart()
    {
        
        DB::beginTransaction();
        try 
        {
            $user_id= Auth::guard('user')->user()->id;

            foreach ($this->supervisionchart as $faculty_id => $dates) 
            {
                foreach ($dates as $date => $examsessions) 
                {   
                    foreach ($examsessions as $examsession_id => $check) 
                    {   
                        if($check)
                        {
                            Examsupervision::withTrashed()->firstOrCreate([
                                'faculty_id' => $faculty_id,
                                'supervision_date' => $date,
                                'exam_id' => $this->exam->id,
                                'examsession_id' => $examsession_id,
                                'user_id' =>$user_id ,
                            ]);
    
                            $this->dispatch('alert',type:'success',message:'Supervison Recorded Successfully !!');
                        }else
                        {
                            Examsupervision::withTrashed()->where('faculty_id', $faculty_id )->where('exam_id', $this->exam->id)->where('supervision_date', $date)->where('examsession_id', $examsession_id)->forceDelete();
                            $this->dispatch('alert',type:'success',message:'Supervison Revert Successfully !!');
                        }
    
                    }
                    
                }
            }

            $this->supervisionchart=[];
            DB::commit();
            
        } catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Record Supervison !!');
        }
    }

    public function checkmydate($date)
    {
        $tempDate = explode('-', $date);

        if (empty($tempDate[0]))
        {
            return false;
        }
        else
        {
            if (checkdate($tempDate[1], $tempDate[2], $tempDate[0]))
            {
                return true;
            }
        }
    }

    public function mount()
    {
        $this->departments = Department::where('status',1)->pluck('dept_name','id');
        $this->exam = Exam::with('examsessions')->select('id')->where('status',1)->first();
        $this->allsession = $this->exam->examsessions->unique('from_date', 'to_date');
    }
        
        
    public function render()
    {   

        $examsupervisions = Examsupervision::where('exam_id', $this->exam->id)->select('faculty_id', 'supervision_date', 'examsession_id')->get()->groupBy(['faculty_id', 'supervision_date', 'examsession_id'], true);


        foreach ($examsupervisions as $faculty_id => $dates) 
        {
            foreach ($dates as $supervision_date => $examsessions) 
            {
                foreach ($examsessions as $examsession_id => $v) 
                {
                    $this->supervisionchart[$faculty_id]["$supervision_date"][$examsession_id] = true;
                }
            }
        }

        return view('livewire.user.examsupervision.all-examsupervision')->extends('layouts.user')->section('user');
    }
}
