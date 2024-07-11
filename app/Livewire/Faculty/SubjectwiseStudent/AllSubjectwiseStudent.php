<?php

namespace App\Livewire\Faculty\SubjectwiseStudent;

use App\Models\Exam;
use App\Models\Subject;
use Livewire\Component;
use App\Models\Classview;
use App\Models\Academicyear;
use Livewire\WithPagination;
use App\Models\Examformmaster;
use Livewire\Attributes\Locked;
use App\Models\Studentexamform;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use Illuminate\Support\Facades\Auth;

class AllSubjectwiseStudent extends Component
{
    use WithPagination;

    #[Locked]
    public $mode='all';

    public $academicyear_id;

    public $hod_subjects;

    public $subject_sem;

    public $subject_code_name;

    public $student_count;

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

    // #[Renderless]
    // public function export()
    // {
    //     try
    //     {
    //         $filename="Subject_Types_".now();

    //         $response = null;

    //         switch ($this->ext) {
    //             case 'xlsx':
    //                 $response = Excel::download(new SubjectTypeExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
    //             break;
    //             case 'csv':
    //                 $response = Excel::download(new SubjectTypeExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
    //             break;
    //             case 'pdf':
    //                 $response = Excel::download(new SubjectTypeExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
    //             break;
    //         }

    //         $this->dispatch('alert',type:'success',message:'Subjectwise Student Data Exported Successfully !!');

    //         return $response;
    //     }
    //     catch (Exception $e)
    //     {
    //         Log::error($e);

    //         $this->dispatch('alert',type:'error',message:'Failed To Export Subjectwise Student Data !!');
    //     }
    // }

    public function mount()
    {
        $this->hod_subjects =Subject::whereHas('hodappointsubjects',function( $query){
            $query->where('faculty_id',Auth::guard('faculty')->user()->id);
        })->pluck('id');

    }

    public function render()
    {
        $subjects_with_student_counts = Subject::select('id', 'subject_sem', 'subject_code', 'subject_name')
            ->whereIn('id', $this->hod_subjects)
            ->withCount(['studentExamforms' => function ($query) {
                $query->distinct('student_id');
            }])
            ->when($this->search, function ($query, $search) {
                $query->search($search);
            })
            ->orderBy($this->sortColumn, $this->sortColumnBy)
            ->withTrashed()
            ->paginate($this->perPage);

        return view('livewire.faculty.subjectwise-student.all-subjectwise-student',compact('subjects_with_student_counts'))->extends('layouts.faculty')->section('faculty');
    }
}
