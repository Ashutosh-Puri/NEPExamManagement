<?php

namespace App\Livewire\User\Ordinace;

use App\Models\Exam;
use Livewire\Component;
use App\Models\Classview;
use App\Models\Ordinace163master;
use App\Models\Studentordinace163;
use Illuminate\Support\Facades\DB;

class Ordinace163StudentMarkEntry extends Component
{   
    public $ordinace_163s=[];
    public $ordinace_163_marks=[];
    public $pattern_classes=[];
    public $ordinace163master_id;
    public $patternclass_id;
    public $exam;

    

    public function save_all_marks()
    {   
        $validatedData = $this->validate([
            "ordinace_163_marks.*" => ['required', 'integer', 'min:0', 'max:100'],
        ],[
            'ordinace_163_marks.*.required' => 'Enter Marks',
            'ordinace_163_marks.*.integer' => 'The marks must be an integer',
            'ordinace_163_marks.*.min' => 'The marks must be at least 0',
            'ordinace_163_marks.*.max' => 'The marks may not be greater than 100',
        ]);

        if(empty($this->ordinace_163_marks))
        {
            $this->dispatch('alert',type:'info',message:'Enter Marks to Update !!');

            return false;
        }

        $studentordinace163=[];

        foreach ($this->ordinace_163_marks as $student_ordinace_163_id => $marks) 
        {
            $studentordinace163[]=[
                'id'=>$student_ordinace_163_id,
                'is_applicable'=>1,
                'marks'=>$marks,
            ];
        }


        DB::beginTransaction();
        try 
        {
           
            foreach ($studentordinace163 as $studentData) 
            {
                $student = Studentordinace163::find($studentData['id']);
                if ($student) 
                {
                    $student->marks = $studentData['marks'];
                    $student->is_applicable = $studentData['is_applicable'];
                    $student->save();
                }
            }
    
            
            DB::commit();

            $this->dispatch('alert',type:'success',message:'Marks Updated Successfully !!'  );
        } catch (\Exception $e) {
         
            DB::rollBack();
            $this->dispatch('alert',type:'error',message:'Failed to Update Marks  !!');
        }
        $studentordinace163=[];
        $this->ordinace_163_marks=[];
    }


    public function save_marks(Studentordinace163 $studentordinace163)
    {   
        $validatedData = $this->validate([
            "ordinace_163_marks.".$studentordinace163->id."" => ['required', 'integer', 'min:0', 'max:100'],
        ],[
            'ordinace_163_marks.'.$studentordinace163->id.'.required' => 'Enter Marks',
            'ordinace_163_marks.'.$studentordinace163->id.'.integer' => 'The marks must be an integer',
            'ordinace_163_marks.'.$studentordinace163->id.'.min' => 'The marks must be at least 0',
            'ordinace_163_marks.'.$studentordinace163->id.'.max' => 'The marks may not be greater than 100',
        ]);

        DB::beginTransaction();

        try 
        {   
            $studentordinace163->marks=$this->ordinace_163_marks[$studentordinace163->id];
            $studentordinace163->is_applicable=1;
            $studentordinace163->update();

            DB::commit();
            $this->dispatch('alert',type:'success',message:'Marks Updated Successfully !!'  );
        }catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Marks  !!');
        }
    
    }
    
    public function clear()
    {   
        $this->ordinace163master_id=null;
        $this->patternclass_id=null;
    }

    public function mount()
    {   
        $this->ordinace_163s =Ordinace163master::where('status',1)->pluck('activity_name','id');
        $this->exam=Exam::where('status',1)->first();
        $this->pattern_classes = Classview::select('id','classyear_name', 'course_name', 'pattern_name')->where('status',1)->get();
    }

    
    public function render()
    {   
        $studentordinace163s=Studentordinace163::with('ordinace163master:activity_name,id','student:student_name,id','exam:exam_name,id','patternclass.courseclass.course:course_name,id','patternclass.courseclass.classyear:classyear_name,id','patternclass.pattern:pattern_name,id','transaction:status,id,razorpay_payment_id')
        ->where('exam_id',$this->exam->id)
        // ->where('is_fee_paid',1)
        ->where('status',0)
        ->where('is_applicable',0)
        ->when($this->patternclass_id, function ($query, $patternclass_id) {  $query->where('patternclass_id',$patternclass_id); })
        ->when($this->ordinace163master_id, function ($query, $ordinace163master_id) {  $query->where('ordinace163master_id',$ordinace163master_id); })
        ->get();
        return view('livewire.user.ordinace.ordinace163-student-mark-entry',compact('studentordinace163s'))->extends('layouts.user')->section('user');
    }
}
