<?php

namespace App\Livewire\Faculty\InternalAudit\HodSubjectToolReport;

use App\Models\Subject;
use Livewire\Component;
use App\Models\Academicyear;
use Livewire\WithPagination;
use App\Models\Facultysubjecttool;
use Livewire\Attributes\Renderless;
use App\Models\Documentacademicyear;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Faculty\InternalAudit\HodSubjectTool\HodSubjectToolExport;

class AllHodSubjectToolReport extends Component
{
    use WithPagination;

    public $search='';
    public $perPage=10;
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;

    public $academicyear_id;
    public $academicyears=[];

    public $hod_subjects=[];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function resetinput()
    {
        $this->reset([
            "academicyear_id",
        ]);
    }

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


    #[Renderless]
    public function export()
    {
        try
        {
            $filename="Assigned_Tools_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new HodSubjectToolExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new HodSubjectToolExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new HodSubjectToolExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Subject Tool Data Exported Successfully !!');

            return $response;
        }
        catch (Exception $e)
        {
            Log::error($e);

            $this->dispatch('alert',type:'error',message:'Failed To Export Subject Tool Data !!');
        }
    }

    public function mount()
    {
        $this->academicyears = Documentacademicyear::pluck('year_name','id');

        $this->hod_subjects =Subject::whereHas('hodappointsubjects',function( $query){
            $query->where('faculty_id',Auth::guard('faculty')->user()->id);
        })->pluck('id');

    }

    public function render()
    {
        if($this->academicyear_id)
        {
            $faculty_head_subjects = Facultysubjecttool::with(['faculty','academicyear','subject','departmenthead','internaltoolmaster','facultysubjecttools'])
            ->where('academicyear_id',$this->academicyear_id)
            ->whereIn('subject_id',$this->hod_subjects)
            ->when($this->search, function($query, $search){
                $query->search($search);
            })->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);
        }
        else
        {
            // $faculty_head_subjects = Facultysubjecttool::with(['faculty','academicyear','subject','departmenthead','internaltoolmaster','facultysubjecttools'])
            // ->where('academicyear_id',Academicyear::where('active', 1)->value('id'))
            // ->whereIn('subject_id',$this->hod_subjects)
            // ->when($this->search, function($query, $search){
            //     $query->search($search);
            // })->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);
            $faculty_head_subjects = Facultysubjecttool::with(['faculty','academicyear','subject','departmenthead','internaltoolmaster','facultysubjecttools'])
            ->whereIn('subject_id',$this->hod_subjects)
            ->when($this->search, function($query, $search){
                $query->search($search);
            })->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);
        }

        return view('livewire.faculty.internal-audit.hod-subject-tool-report.all-hod-subject-tool-report',compact('faculty_head_subjects'))->extends('layouts.faculty')->section('faculty');
    }
}
