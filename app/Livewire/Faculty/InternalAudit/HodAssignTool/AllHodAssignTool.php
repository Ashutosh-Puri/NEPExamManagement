<?php

namespace App\Livewire\Faculty\InternalAudit\HodAssignTool;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use App\Models\Hodappointsubject;
use App\Models\Facultysubjecttool;
use App\Models\Internaltoolmaster;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Models\Documentacademicyear;
use App\Models\Internaltooldocument;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Facultyinternaldocument;
use App\Exports\Faculty\InternalAudit\HodAssignTool\HodAssignToolExport;

class AllHodAssignTool extends Component
{
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'delete'];

    public $search='';
    public $perPage=10;
    public $sortColumn="id";
    public $sortColumnBy="DESC";
    public $ext;

    #[Locked]
    public $internaltooldocuments;
    #[Locked]
    public $academicyears;
    #[Locked]
    public $mode='all';

    public $academicyear_id;
    public $faculty_id;
    public $subject_id;
    public $tool_name;
    public $document_fileName;
    public $document_filePath;
    public $departmenthead_id;
    public $verifybyfaculty_id;
    public $verificationremark;
    public $status;
    public $subject_head_ids;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function setmode($mode)
    {
        if($mode=='all')
        {
            $this->resetinput();
        }
        $this->mode=$mode;
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

    public function resetinput()
    {
        $this->reset([
            'academicyear_id',
        ]);
    }

    #[Renderless]
    public function export()
    {
        try
        {
            $filename="Hod_Assing_Tool".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new HodAssignToolExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new HodAssignToolExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new HodAssignToolExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Assigned Tool Data Exported Successfully !!');

            return $response;
        }
        catch (Exception $e)
        {
            Log::error($e);

            $this->dispatch('alert',type:'error',message:'Failed To Export Assigned Tool Data !!');
        }
    }

    public function all_document_uploaded($facultysubjecttool_id)
    {
        $faculty_internal_documents = Facultyinternaldocument::where('facultysubjecttool_id',$facultysubjecttool_id)->get();

        foreach ($faculty_internal_documents as $document) {

            $fieldsToCheck = ['document_fileName', 'document_filePath',];


            foreach ($fieldsToCheck as $field) {

                if (!is_null($document->$field)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function freeze_tool($facultysubjecttool_id)
    {
        DB::beginTransaction();

        try {
            $result = $this->all_document_uploaded($facultysubjecttool_id);

            $internal_tool_document_ids = Facultyinternaldocument::where('facultysubjecttool_id', $facultysubjecttool_id)->pluck('id');

            Facultysubjecttool::where('id', $facultysubjecttool_id)->update(['freeze_by_hod' => 1]);

            DB::commit();

            $this->dispatch('alert', type: 'success', message: 'Tool Saved Successfully !!');
        } catch (\Exception $e) {

            DB::rollback();

            $this->dispatch('alert', type: 'info', message: 'Some of tool document is not uploaded !!');
        }
    }

    public function show_freeze_button($facultysubjecttool_id)
    {
        $uploaded_document_count = Facultyinternaldocument::where('facultysubjecttool_id', $facultysubjecttool_id)
            ->whereNotNull('document_fileName')
            ->whereNotNull('document_filePath')
            ->count();

        $internal_tool_master_id = Facultysubjecttool::where('id', $facultysubjecttool_id)
            ->pluck('internaltoolmaster_id')
            ->first();

        $total_document_count = Internaltooldocument::whereHas('internaltoolmaster', function ($query) use ($internal_tool_master_id) {
            $query->where('id', $internal_tool_master_id);
        })->count();

        $freeze_by_hod = Facultysubjecttool::where('id', $facultysubjecttool_id)
            ->where('freeze_by_hod', 1)
            ->exists();

        if ($uploaded_document_count !== $total_document_count || $freeze_by_hod) {
            return false;
        }

        return true;
    }

    public function view(Facultyinternaldocument $faculty_internal_document)
    {
        if ($faculty_internal_document){
            $this->academicyear_id = (isset($faculty_internal_document->facultysubjecttool->academicyear->year_name) ?  $faculty_internal_document->facultysubjecttool->academicyear->year_name : '');
            $this->tool_name= (isset($faculty_internal_document->internaltooldocument->internaltoolmaster->toolname) ?  $faculty_internal_document->internaltooldocument->internaltoolmaster->toolname : '');
            $this->faculty_id= (isset($faculty_internal_document->facultysubjecttool->faculty->faculty_name) ?  $faculty_internal_document->facultysubjecttool->faculty->faculty_name : '');
            $this->subject_id= (isset($faculty_internal_document->facultysubjecttool->subject->subject_name) ?  $faculty_internal_document->facultysubjecttool->subject->subject_name : '');
            $this->departmenthead_id = (isset($faculty_internal_document->facultysubjecttool->faculty->faculty_name) ?  $faculty_internal_document->facultysubjecttool->faculty->faculty_name : '');
            $this->status = $faculty_internal_document->status;
            $facultysubjecttool_id = Facultysubjecttool::where('id',$faculty_internal_document->facultysubjecttool_id)->pluck('id');
            $this->internaltooldocuments = Facultyinternaldocument::where('facultysubjecttool_id',$facultysubjecttool_id)->get();

        }else{
            $this->dispatch('alert',type:'error',message:'Faculty Internal Tool Document Details Not Found');
        }
        $this->setmode('view');
    }

    public function mount()
    {
        $this->academicyears = Documentacademicyear::where('active',1)->get();
        $this->subject_head_ids = Hodappointsubject::where('faculty_id', auth()->id())->pluck('subject_id');

    }

    // public function render()
    // {
    //     $faculty_internal_documents = null;
    //     $groupedInternalDocuments = null;

    //     if ($this->mode !== 'view') {
    //         if ($this->academicyear_id !== null) {
    //             $faculty_internal_documents = Facultyinternaldocument::with(['facultysubjecttool', 'internaltooldocument.internaltooldocumentmaster',])
    //             ->orderBy($this->sortColumn, $this->sortColumnBy)
    //             ->when($this->search, function ($query, $search) {
    //                 return $query->search($search);
    //             })
    //             ->whereHas('facultysubjecttool', function ($query) {
    //                 $query->whereIn('subject_id', $this->subject_head_ids);
    //             })
    //             ->paginate($this->perPage);

    //             $groupedInternalDocuments = $faculty_internal_documents->groupBy(['facultysubjecttool.academicyear_id', 'facultysubjecttool.subject_id']);

    //             if ($faculty_internal_documents->isEmpty()) {
    //                 $this->dispatch('alert', type: 'info', message: 'Records Not Found This Academic Year !!');
    //             }

    //         } else {
    //             $faculty_internal_documents = Facultyinternaldocument::with(['facultysubjecttool', 'internaltooldocument.internaltooldocumentmaster',])
    //             ->orderBy($this->sortColumn, $this->sortColumnBy)
    //             ->when($this->search, function ($query, $search) {
    //                 return $query->search($search);
    //             })
    //             ->whereHas('facultysubjecttool', function ($query) {
    //                 $query->whereIn('subject_id', $this->subject_head_ids);
    //             })
    //             ->paginate($this->perPage);

    //             $groupedInternalDocuments = $faculty_internal_documents->groupBy(['facultysubjecttool.academicyear_id', 'facultysubjecttool.subject_id']);
    //         }
    //     } else {
    //         $faculty_internal_documents = Facultyinternaldocument::with(['facultysubjecttool', 'internaltooldocument.internaltooldocumentmaster',])
    //         ->orderBy($this->sortColumn, $this->sortColumnBy)
    //         ->when($this->search, function ($query, $search) {
    //             return $query->search($search);
    //         })
    //         ->whereHas('facultysubjecttool', function ($query) {
    //             $query->whereIn('subject_id', $this->subject_head_ids);
    //         })
    //         ->paginate($this->perPage);

    //         $groupedInternalDocuments = $faculty_internal_documents->groupBy(['facultysubjecttool.academicyear_id', 'facultysubjecttool.subject_id']);
    //     }

    //     return view('livewire.faculty.internal-audit.hod-assign-tool.all-hod-assign-tool', compact('faculty_internal_documents','groupedInternalDocuments'))->extends('layouts.faculty')->section('faculty');
    // }

    public function render()
    {
        $faculty_internal_documents = Facultyinternaldocument::with(['facultysubjecttool', 'internaltooldocument.internaltooldocumentmaster'])
            ->orderBy($this->sortColumn, $this->sortColumnBy)
            ->when($this->search, function ($query, $search) {
                return $query->search($search);
            })
            ->whereHas('facultysubjecttool', function ($query) {
                $query->whereIn('subject_id', $this->subject_head_ids);
            });

        if ($this->mode !== 'view' && $this->academicyear_id !== null) {
            $academicyear_ids = is_array($this->academicyear_id) ? $this->academicyear_id : [$this->academicyear_id];
            $faculty_internal_documents->whereHas('facultysubjecttool', function ($query) use ($academicyear_ids) {
                $query->whereIn('academicyear_id', $academicyear_ids);
            });
        }

        $faculty_internal_documents = $faculty_internal_documents->paginate($this->perPage);
        $groupedInternalDocuments = $faculty_internal_documents->groupBy(['facultysubjecttool.academicyear_id', 'facultysubjecttool.subject_id']);

        if ($faculty_internal_documents->isEmpty() && $this->mode !== 'view') {
            $this->dispatch('alert', type: 'info', message: 'Records Not Found This Academic Year !!');
        }

        return view('livewire.faculty.internal-audit.hod-assign-tool.all-hod-assign-tool', compact('faculty_internal_documents', 'groupedInternalDocuments'))->extends('layouts.faculty')->section('faculty');
    }
}
