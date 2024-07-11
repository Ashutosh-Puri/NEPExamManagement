<?php

namespace App\Livewire\Faculty\HodExamFormReport;

use App\Models\Exam;
use App\Models\Subject;
use Livewire\Component;
use App\Models\Classview;
use App\Models\Academicyear;
use Livewire\WithPagination;
use App\Models\Examformmaster;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Renderless;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Faculty\HodExamForm\HodExamFormExport;

class AllHodExamFormReport extends Component
{
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'delete'];

    #[Locked]
    public $academic_years;
    #[Locked]
    public $exams;
    #[Locked]
    public $patternclasses;
    #[Locked]
    public $mode='all';

    public $inwardstatus;

    public $exam_id;

    public $academicyear_id;

    public $hod_subject_pc;

    public $search='';
    public $perPage=10;
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;

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

    public function clear()
    {
        $this->reset([
            'exam_id',
            'academicyear_id',
            'inwardstatus',
            'search',
        ]);
    }

    #[Renderless]
    public function export()
    {
        try
        {
            set_time_limit(600); // 600 sec  // 10 min
            ini_set('memory_limit', '1024M'); //  1GB

            $filename="Exam_Form_Report".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    return Excel::download(new HodExamFormExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    return Excel::download(new HodExamFormExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    return Excel::download(new HodExamFormExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Exam Form Report Exported Successfully !!');

            return $response;
        }
        catch (\Exception $e)
        {
            \Log::error($e);

            $this->dispatch('alert',type:'error',message:'Failed To Export Exam Form Report !!');
        }

    }

    public function mount()
    {
        $this->academic_years=Academicyear::select('id','year_name')->get();
        $this->exams=Exam::all();
        $this->patternclasses =Classview::select('id','classyear_name', 'course_name', 'pattern_name')->where('status',1)->get();

        $this->hod_subject_pc =Subject::whereHas('hodappointsubjects',function( $query){
            $query->where('faculty_id',Auth::guard('faculty')->user()->id);
        })->pluck('patternclass_id');

    }

    public function render()
    {
        $exam_form_masters = Examformmaster::whereIn('patternclass_id', $this->hod_subject_pc)
        ->with(['student', 'exam'])
        ->when($this->inwardstatus !== null, function ($query) {
            if ($this->inwardstatus == 1) {
                $query->where('inwardstatus', 1);
            } else {
                $query->where('inwardstatus', '!=', 1);
            }
        })
        ->when($this->exam_id, function ($query) {
            $query->where('exam_id', $this->exam_id);
        })
        ->when($this->academicyear_id, function ($query) {
            $query->whereHas('exam', function ($subQuery) {
                $subQuery->where('academicyear_id', $this->academicyear_id);
            });
        })
        ->when($this->search, function ($query, $search) {
            $query->search($search);
        })
        ->orderBy($this->sortColumn, $this->sortColumnBy)
        ->paginate($this->perPage);


        return view('livewire.faculty.hod-exam-form-report.all-hod-exam-form-report',compact('exam_form_masters'))->extends('layouts.faculty')->section('faculty');
    }
}
