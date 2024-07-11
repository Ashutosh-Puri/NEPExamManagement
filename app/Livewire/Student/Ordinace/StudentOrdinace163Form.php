<?php

namespace App\Livewire\Student\Ordinace;

use Livewire\Component;
use App\Models\Examstudentseatno;
use App\Models\Ordinace163master;
use App\Models\Studentordinace163;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StudentOrdinace163Form extends Component
{
    public $ordinace_163s=[];
    public $ordinace163master_id;

    protected function rules()
    {
        return [
            'ordinace163master_id' => ['required'],
        ];
    }

    public function messages()
    {   
        return [
            'ordinace163master_id.required' => 'The Activity field is required.',
        ];

    }
    public function resetinput()
    {   
        $this->reset(
            [
                'ordinace163master_id',
            ]
        );
    }


    public function add()
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {   
            $student_id = Auth::guard('student')->user()->id;
            $exam_seat_number = Examstudentseatno::where('student_id', $student_id)->latest()->first();
            if($exam_seat_number)
            {
                $exam_id = $exam_seat_number->exampatternclass->exam_id;
                $patternclass_id = $exam_seat_number->exampatternclass->patternclass_id;
                $seatno = $exam_seat_number->seatno;

                $existingRecord = Studentordinace163::where('student_id', $student_id)->where('patternclass_id', $patternclass_id)->where('exam_id', $exam_id)->where('ordinace163master_id', $this->ordinace163master_id)->first();
                if($existingRecord)
                {
                    $this->dispatch('alert',type:'info',message:'You already applied for this. You cannot apply again !!');
                    return false;
                }
                else
                {
                    $student_ordinace_163=Studentordinace163::create([
                        'seatno'=>$seatno,
                        'student_id'=>$student_id,
                        'patternclass_id'=>$patternclass_id,
                        'exam_id'=>$exam_id,
                        'ordinace163master_id'=>$this->ordinace163master_id,
                        'fee'=>20,
                        'status'=>0,
                    ]);
                }
      
            }

            DB::commit();

            $this->resetinput();

            $this->dispatch('alert',type:'success',message:'Ordinace 163 Created Successfully !!');

        } catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Ordinace 163 !!');
        } 
    }
    public function mount()
    {   
        $this->ordinace_163s =Ordinace163master::where('status',1)->pluck('activity_name','id');
    }

    public function render()
    {   
        return view('livewire.student.ordinace.student-ordinace163-form')->extends('layouts.student')->section('student');
    }
}
