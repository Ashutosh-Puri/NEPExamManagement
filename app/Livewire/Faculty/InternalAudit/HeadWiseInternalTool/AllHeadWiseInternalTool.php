<?php

namespace App\Livewire\Faculty\InternalAudit\HeadWiseInternalTool;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use App\Models\Hodappointsubject;
use App\Models\Facultysubjecttool;
use Livewire\Attributes\Renderless;
use App\Models\Documentacademicyear;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Facultyinternaldocument;
use App\Exports\Faculty\InternalAudit\HeadwiseTool\HeadwiseToolExport;

class AllHeadWiseInternalTool extends Component
{
    use WithPagination;

    public $search='';
    public $perPage=10;
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;

    #[Locked]
    public $intenral_tool_subject_id;
    #[Locked]
    public $academicyears;
    #[Locked]
    public $mode='all';

    public $academicyear_id;

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
            $filename="Headwise_Tools_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new HeadwiseToolExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new HeadwiseToolExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new HeadwiseToolExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Headwise Tool Data Exported Successfully !!');

            return $response;
        }
        catch (Exception $e)
        {
            Log::error($e);

            $this->dispatch('alert',type:'error',message:'Failed To Export Headwise Tool Data !!');
        }
    }

    public function mount()
    {
        $this->academicyears = Documentacademicyear::where('active',1)->pluck('year_name','id');
    }

    public function render()
    {
        $faculty_internal_documents = Facultysubjecttool::orderBy($this->sortColumn, $this->sortColumnBy)
            ->when($this->search, function($query, $search){
                return $query->search($search);
            })->where('departmenthead_id',Auth::guard('faculty')->user()->id)
            ->paginate($this->perPage);

        return view('livewire.faculty.internal-audit.head-wise-internal-tool.all-head-wise-internal-tool',compact('faculty_internal_documents'))->extends('layouts.faculty')->section('faculty');
    }
}
