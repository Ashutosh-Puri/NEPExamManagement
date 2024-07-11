<?php

namespace App\Livewire\User\ExamForm;

use Excel;
use App\Models\Exam;
use Livewire\Component;
use App\Models\Classview;
use App\Models\Transaction;
use App\Models\Academicyear;
use App\Models\Patternclass;
use Livewire\WithPagination;
use App\Models\Examformmaster;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Renderless;
use App\Exports\User\ExamForm\ExamFormExport;

class AllExamForm extends Component
{   
    # By Ashutosh
    use WithPagination;

    #[Locked] 
    public $mode='all';
    public $perPage=10;
    public $search='';
    public $sortColumn="transactions.payment_date";
    public $sortColumnBy="ASC";
    public $ext;
    public $academicyear_id;
    public $exam_id;
    public $patternclass_id;
    public $fee_status;
    public $payment_status;
    #[Locked] 
    public $academic_years=[];
    #[Locked] 
    public $exams=[];
    #[Locked] 
    public $patternclasses=[];


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

    public function updatedSearch()
    {
        $this->resetPage();
    }

       
    #[Renderless]
    public function export()
    {   
        try 
        {
            set_time_limit(600);
            ini_set('memory_limit', '1024M'); 
            $filename="Exam_Form_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new ExamFormExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response =Excel::download(new ExamFormExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new ExamFormExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Exam Form Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            \Log::error($e);

            $this->dispatch('alert',type:'error',message:'Failed To Export Exam Form !!');
        }

    }

 
    public function mount()
    {   
        $this->academic_years=Academicyear::pluck('year_name','id');
        $this->exams=Exam::pluck('exam_name','id');
        $this->patternclasses =Classview::select('id','classyear_name', 'course_name', 'pattern_name')->where('status',1)->get();

        
    }

    public function clear()
    {   
        $this->reset([
            'exam_id',
            'patternclass_id',
            'academicyear_id',
            'fee_status',
            'payment_status',
            'search',
        ]);
    }

    public function render()
    {   
        $exam_form_masters = Examformmaster::with('transaction','patternclass.courseclass.course:course_name,id','patternclass.courseclass.classyear:classyear_name,id','patternclass.pattern:pattern_name,id','student:prn,student_name,memid,id','exam:id,exam_name')
        ->leftJoin('transactions', 'examformmasters.transaction_id', '=', 'transactions.id')
        ->when($this->payment_status, function ($query, $payment_status) {
            $query->whereHas('transaction', function ($subQuery) use ($payment_status) {
                $subQuery->where('status', $payment_status);
            });
        })
        ->when($this->exam_id, function ($query) {
          
            $query->where('exam_id',$this->exam_id);
            
        })
        ->when($this->patternclass_id, function ($query) {
          
            $query->where('patternclass_id',$this->patternclass_id);
            
        })
        ->when($this->fee_status, function ($query, $fee_status) {
            if($fee_status==1)
            {
                $query->where('feepaidstatus', 1);
            }elseif($fee_status==2)
            {
                $query->whereNot('feepaidstatus',1);
            }
        })
        ->when($this->academicyear_id, function ($query, $yearid) {
            $query->whereIn('exam_id', function ($subQuery) use ($yearid) {
                $subQuery->select('id')->from('exams')->where('academicyear_id', $yearid);
            });
        })
        ->when($this->search, function ($query, $search) { $query->search($search);})->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);
       
    
 
        return view('livewire.user.exam-form.all-exam-form',compact('exam_form_masters'))->extends('layouts.user')->section('user');
    }

}
