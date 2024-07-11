<?php

namespace App\Livewire\User\ExamMarks;

use App\Models\Exam;
use Livewire\Component;
use App\Models\Exambarcode;
use App\Models\Paperassesment;
use Illuminate\Support\Facades\DB;

class ExtMarkEntry extends Component
{   
    # By Ashutosh
    public $scanbarcode;
    public $barcode;
    public $modify;
    public $examiner;
    public $moderator;
    public $showFlag;
    public $lotnumber;
    public $paperassesments;
    public $autoflag;
    public $examiner_name;
    public $moderator_name;
  

    protected $rules = ['examiner'=>'required|numeric|gt:-1','scanbarcode'=>'required',];

    protected $messages = [
        'examiner.gt' => 'Examiner marks must be greater than Zero...',
        'examiner.numeric' => 'Examiner marks must be numeric...',
        'moderator.numeric' => 'Moderator marks must be numeric...',
        'moderator.gt' => 'Moderator marks must be greater than Zero...',
        'scanbarcode.required'=>'Scan or Enter Barcode...'
    ];
    
    public function updatedScanbarcode()
    {
        $exam = Exam::where('status',1)->first();
        $this->showFlag=false;
        $this->barcode=$this->scanbarcode;

        $exambarcode=Exambarcode::whereRelation('exampatternclass','exam_id','=',$exam->id)->where('id',$this->barcode)->first(); 
        
        if($exambarcode)
        {
            $this->showFlag=true;
            $this->lotnumber=$exambarcode->paperassesment_id;
            $paperassesment =Paperassesment::find($this->lotnumber);
            if($paperassesment)
            {

                if( isset($paperassesment->verified_by))
                {
                    $this->modify=false;
                    $this->dispatch('alert',type:'info',message:'Alredy Verified Not Allowed To Modify.'); 
                }else
                {
                    $this->modify=true;
                }
            }
            $this->paperassesments=$paperassesment->exambarcodes??'';
            $this->examiner=$exambarcode->examiner_marks;
            $this->moderator=$exambarcode->moderator_marks;
            $faculty=Paperassesment::find($this->lotnumber);
            $this->examiner_name=$faculty->examiner->faculty_name??"";
            $this->moderator_name=$faculty->moderator->faculty_name??"";
        }
    }

    public function addmarks()
    { 
        $this->validate();

        
        DB::beginTransaction();

        try 
        {  
            
            $exambarcode=Exambarcode::find($this->barcode);
            
            if($exambarcode)
            {
                if($this->examiner<=$exambarcode->subject->subject_maxmarks_ext &&   $this->moderator<=$exambarcode->subject->subject_maxmarks_ext)
                {
                    $exambarcode->update([
                        'examiner_marks'=>$this->examiner,
                        'moderator_marks'=>$this->moderator,
                    ]);

                    $this->scanbarcode=null;
                }
                else
                {   
                    $this->dispatch('alert',type:'info',message:'Ohhh , Marks greater than Subject passing..Please Check....'); 
                }
            }
            else
            {
                $this->scanbarcode=null;
            }
            
            if($exambarcode)
            {
                $this->showFlag=true;
                $this->lotnumber=$exambarcode->paperassesment_id;
                $this->paperassesments=Paperassesment::find($this->lotnumber)->exambarcodes;   
            }

            DB::commit();

        } catch (\Exception $e) 
        {
            DB::rollBack();

        }

    }

    public function render()
    {   
        if (!$this->showFlag && $this->scanbarcode)
        {
            $this->dispatch('alert',type:'info',message:'Invalid Barcode!!'); 
        }

        return view('livewire.user.exam-marks.ext-mark-entry')->extends('layouts.user')->section('user');
    }
}
