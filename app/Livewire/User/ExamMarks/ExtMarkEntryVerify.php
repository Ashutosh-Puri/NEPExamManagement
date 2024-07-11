<?php

namespace App\Livewire\User\ExamMarks;

use App\Models\Exam;
use Livewire\Component;
use App\Models\Exambarcode;
use App\Models\Studentmark;
use Livewire\WithPagination;
use App\Models\Paperassesment;
use Illuminate\Support\Facades\Auth;

class ExtMarkEntryVerify extends Component
{   
    # By Ashutosh
    use WithPagination;

    public $lot_number;
    public $chunk_size=30;
    public $bundel_number;
    public $bag_number;
    public $verified_marks = [];

    
    

    public function save_final_marks()
    {   
        $this->validate([
            'lot_number' => ['required', 'integer', 'min:1'],
            'bundel_number' => ['required', 'integer',],
            'bag_number' => ['required', 'integer',],
            'verified_marks.*' => ['required'],
        ],[
            'verified_marks.*.required' => 'Marks field is required.',
            'lot_number.required' => 'Lot Number field is required.',
            'bag_number.required' => 'Bag Number field is required.',
            'bundel_number.required' => 'Bundel Number field is required.',
        ]);

        $paperassessment = Paperassesment::find($this->lot_number);
        $paperassessment->update([
            'verified_by' => Auth::guard('user')->user()->id,
            'bundle_no'=>$this->bundel_number,
            'rack_no'=>$this->bag_number,
        ]);

       $barcode_count= Exambarcode::where('paperassesment_id', $this->lot_number)->where('status',0)->count();
               
        if(count($this->verified_marks)== $barcode_count)
        {
            foreach ($this->verified_marks as $key => $verified_mark) 
            {
                $final_barcode = Exambarcode::find($key);

                if (((is_null($final_barcode->moderator_marks)) ? $final_barcode->examiner_marks : $final_barcode->moderator_marks) == $verified_mark) 
                {
                    $final_barcode->update([
                        'verified_marks' => $verified_mark,
                    ]);

                   $stud_mar= Studentmark::upsert([
                        'seatno'=>trim($final_barcode->exam_studentseatnos->seatno),
                        'student_id'=>trim($final_barcode->exam_studentseatnos->student_id),
                        'subject_id'=>trim($final_barcode->subject_id),
                        'sem'=>trim($final_barcode->subject->subject_sem),
                        'exam_id'=>trim($final_barcode->exampatternclass->exam_id),
                        'patternclass_id'=>trim($final_barcode->exampatternclass->patternclass_id),
                        'ext_marks'=>$final_barcode->verified_marks ,            
                    ], ['student_id', 'subject_id', 'seatno', 'exam_id']);
                    
                    $this->lot_number="";

                    $this->dispatch('alert',type:'success',message:'Student Marks Saved Successfully !!'); 

                }else
                {
                    $this->verified_marks[$key]=null;

                    $this->addError('verified_marks', 'The Verified Marks Is Invalid.');
                }
            }

        }else
        {   
            $this->dispatch('alert',type:'info',message:'Please Fill All Marks'); 
        }

        $this->bundel_number=null;
        $this->bag_number=null;
    }

    public function updatedLotNumber()
    {
        
        $paperassessment = Paperassesment::find($this->lot_number);

        if (is_null($paperassessment) || ($paperassessment->exambarcodes->count() == 0))
        {
            $this->dispatch('alert',type:'info',message:'Invalid Lot Number'); 
        }
        else
        {
            $this->bundel_number= $paperassessment->bundle_no;
            $this->bag_number= $paperassessment->rack_no;
        }
    }

    public function find_lot_papers()
    {   
        $this->validate( [
            'lot_number' => ['required', 'integer', 'min:1'],
        ],[
            'lot_number.required' => 'Lot Number field is required.',
        ]);

        $paperassessment = Paperassesment::find($this->lot_number);

        $this->verified_marks = Exambarcode::where('paperassesment_id', $this->lot_number)->where('status',0)->whereNotNull('verified_marks')->pluck('verified_marks', 'id');

        if (is_null($paperassessment) || ($paperassessment->exambarcodes->count() == 0))
        {   
            $this->dispatch('alert',type:'info',message:'Invalid Lot Number'); 
        }
    }

    
    public function render()
    {   

        $exam=Exam::where('status',1)->first();
        $exambarcode=collect([]);

        if($this->lot_number)
        {
            $exambarcode=Exambarcode::where('paperassesment_id',$this->lot_number)
            ->where('status',0)
            ->get();
            
            if(count($exambarcode)==0)
            {
                $this->dispatch('alert',type:'info',message:'Barcode Not Found For Current Exam !!'); 
    
            }else if(count($exambarcode) >60)
            {   
                $exambarcode=collect([]);
                $this->dispatch('alert',type:'info',message:'Maximum Lot Size 60 You Cannot Fill Marks For This Lot !');
            }
        }


        return view('livewire.user.exam-marks.ext-mark-entry-verify',compact('exambarcode'))->extends('layouts.user')->section('user');
    }
}
