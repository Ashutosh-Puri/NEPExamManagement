<?php

namespace App\Livewire\Faculty\QuestionPaperBank;

use Excel;
use Mpdf\Mpdf;
use App\Models\Exam;
use App\Models\User;
use App\Models\Faculty;
use App\Models\Subject;
use Livewire\Component;
use App\Models\Paperset;
use App\Models\Examorder;
use App\Models\Exampanel;
use App\Models\Patternclass;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Papersubmission;
use Illuminate\Validation\Rule;
use App\Models\Questionpaperbank;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use Illuminate\Support\Facades\Auth;
use App\Jobs\Faculty\SendQuestionPaperConfirmationEmailJob;

class AllQuestionPaperBank extends Component
{

    use WithPagination;
    protected $listeners = ['delete-confirmed'=>'forcedelete'];

    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;
    public $confirmed_subject_ids=[];
    public $subject_ids=[];
    public $sets=[];
    public $exam;

    public function sort_column($column)
    {
        if( $this->sortColumn === $column)
        {
            $this->sortColumnBy=($this->sortColumnBy=="ASC")?"DESC":"ASC";
            return;
        }
        $this->sortColumn=$column;
        $this->sortColumnBy=="ASC";
    }

    public function confirm_uploaded_paper_sets()
    {   
       
        if($this->exam)
        {
            try 
            {
                DB::beginTransaction();
                $this->exam->papersubmissions()->where('is_confirmed',0)->where('chairman_id',Auth::guard('faculty')->user()->id)->update(['is_confirmed'=>1]);
                DB::commit();
                $this->dispatch('refreshChild');
               
                $this->dispatch('alert',type:'success',message:'Question Paper Sets Confirmed Successfully !!'  );
                $data=['faculty_id'=>Auth::guard('faculty')->user()->id,'type'=>'success','message'=>'Question Paper Sets Confirmed Successfully'];
                SendQuestionPaperConfirmationEmailJob::dispatch($data);
            } 
            catch (Exception $e) 
            {
                DB::rollBack();
                $this->dispatch('alert',type:'error',message:'Failed To Confirm Question Paper Sets !!'  );
            }
           
        }else
        {
            $this->dispatch('alert',type:'info',message:'Active Exam Not Found !!' );
        }
    }

    public function mount()
    {  
        $this->exam = Exam::where('status', 1)->first();
        $this->sets=Paperset::select('set_name','id')->get();   


         $this->subject_ids=  Examorder::withWhereHas('exampanel', function ($query) {
            $query->where('faculty_id', Auth::guard('faculty')->user()->id)
                ->where('examorderpost_id', 1);
        })


        ->withWhereHas('exampatternclass.exam', function ($query) {
            $query->where('status', 1);
        })
        ->get()->pluck('exampanel.subject_id');
        
    } 
    
    public function render()
    {   
        $papersubmission_query = Papersubmission::where('chairman_id',Auth::guard('faculty')->user()->id)->whereIn('subject_id', $this->subject_ids);
        $confirmed_subject_ids = $papersubmission_query->where('is_confirmed', 1)->pluck('subject_id');
        $papersubmissions=$papersubmission_query->where('is_confirmed', 1)->get();
        $subjects=Subject::whereNotIn('id', $confirmed_subject_ids)->whereIn('id', $this->subject_ids)->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);
        return view('livewire.faculty.question-paper-bank.all-question-paper-bank',compact('subjects','papersubmissions'))->extends('layouts.faculty')->section('faculty');
    }


}
