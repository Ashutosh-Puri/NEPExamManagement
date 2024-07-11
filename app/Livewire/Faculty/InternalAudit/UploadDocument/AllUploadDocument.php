<?php

namespace App\Livewire\Faculty\InternalAudit\UploadDocument;

use Livewire\Component;
use App\Models\Classview;
use Livewire\WithFileUploads;
use Livewire\Attributes\Locked;
use App\Models\Facultysubjecttool;
use Illuminate\Support\Facades\DB;
use App\Models\Documentacademicyear;
use App\Models\Internaltooldocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Facultyinternaldocument;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Exports\Faculty\InternalAudit\FacultyInternalDocument\FacultyInternalDocumentExport;

class AllUploadDocument extends Component
{
    protected $listeners = ['delete-confirmed'=>'delete','form-submitted' => 'render', 'freeze-confirmed' => 'freeze'];

    public $academicyear_id;

    #[Locked]
    public $academicyears;
    #[Locked]
    public $semesters=[];
    #[Locked]
    public $pattern_classes=[];
    #[Locked]
    public $subjects=[];
    #[Locked]
    public $uploaded_documents=[];
    #[Locked]
    public $facultyinternaldocuments=[];
    #[Locked]
    public $delete_id;
    #[Locked]
    public $freeze_id;

    public $semester_id;
    public $patternclass_id;
    public $subject_id;
    public $uploaded_document_count;
    public $tool_freezed;
    public $required_document_count;

    public function updated($propertyName, $value)
    {
        if($propertyName == 'academicyear_id'){
            $this->loadPatternClasses();

        }elseif($propertyName == 'patternclass_id'){
            $this->loadSemesters($value);

        }elseif($propertyName == 'semester_id'){
            $this->loadSubjects($value);

        }
    }

    public function setmode($mode)
    {
        session(['is_mode' =>true]);
    }

    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }

    public function freezeconfirmation($id)
    {
        $this->freeze_id=$id;
        $this->dispatch('delete-confirmation');
    }

    public function loadPatternClasses()
    {
        $user_id = Auth::guard('faculty')->user()->id;

        $documents_data = Facultysubjecttool::where('academicyear_id',$this->academicyear_id)->with([
            'subject.patternclass.courseclass.classyear:id,classyear_name',
            'subject.patternclass.courseclass.course:id,course_name',
            'subject.patternclass.pattern:id,pattern_name'
        ])
        ->where('faculty_id', $user_id)
        ->get();

        $patternClassIds = $documents_data->pluck('subject.patternclass_id')->unique();

        $this->pattern_classes = Classview::select('id','classyear_name', 'course_name', 'pattern_name')->whereIn('id',$patternClassIds)->where('status',1)->get();
    }

    public function loadSemesters($patternclass_id)
    {
        $user_id = Auth::guard('faculty')->user()->id;

        $documents_data = Facultysubjecttool::whereHas('subject', function ($query) use ($patternclass_id) {
                $query->where('patternclass_id', $patternclass_id);
            })
            ->with(['subject' => function ($query) {
                $query->select('id', 'patternclass_id', 'subject_sem');
            }])
            ->where('faculty_id', $user_id)
            ->get();
        $this->semesters = $documents_data->pluck('subject.subject_sem')->unique();
    }

    public function loadSubjects($semester)
    {
        $user_id = Auth::guard('faculty')->user()->id;

        $this->subjects = Facultysubjecttool::where('faculty_id', $user_id)
            ->whereHas('subject', function ($query) use ($semester) {
                $query->where('subject_sem', $semester);
            })
            ->with(['subject:id,subject_name,subject_code'])
            ->get()
            ->pluck('subject', 'id')->unique();
    }

    public function mount()
    {
        $this->academicyears = Documentacademicyear::where('active',1)->pluck('year_name','id');
    }

    public function delete()
    {
        DB::beginTransaction();

        try
        {
            $inttool_doc = Facultyinternaldocument::withTrashed()->find($this->delete_id);


            if ($inttool_doc->document_fileName) {
                File::delete($inttool_doc->document_filePath);
            }

            $inttool_doc->update([
                'document_fileName' => null,
                'document_filePath' => null,
                'updated_at' => now(),
            ]);

            $this->delete_id = null;

            DB::commit();

            $this->dispatch('alert', type: 'success', message: 'Internal Tool Document Deleted Successfully !!');

        } catch (\Illuminate\Database\QueryException $e) {

            DB::rollBack();

            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'info',message:'This record is associated with another data. You cannot delete it !!');
            }else
            {
                $this->dispatch('alert',type:'error',message:'Failed To Delete Internal Tool Document Data !!');
            }
        }
    }

    public function all_document_uploaded($facultysubjecttool_ids)
    {
        $faculty_internal_documents = Facultyinternaldocument::whereIn('facultysubjecttool_id',$facultysubjecttool_ids)->get();

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

    public function freeze_tool($uploaded_documents)
    {
        DB::beginTransaction();

        try {

            $all_uploaded_documents = collect($uploaded_documents);

            $facultysubjecttool_ids = $all_uploaded_documents->pluck('facultysubjecttool_id')->unique();

            $result = $this->all_document_uploaded($facultysubjecttool_ids);

            $internal_tool_document_ids = Facultyinternaldocument::whereIn('facultysubjecttool_id', $facultysubjecttool_ids)->pluck('id');

            Facultysubjecttool::whereIn('id', $facultysubjecttool_ids)->update(['freeze_by_faculty' => 1]);

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

        $freeze_by_faculty = Facultysubjecttool::where('id', $facultysubjecttool_id)
            ->where('freeze_by_faculty', 1)
            ->exists();

        if ($uploaded_document_count !== $total_document_count || $freeze_by_faculty) {
            return false;
        }

        return true;
    }


    public function render()
    {
        if ($this->subject_id)
        {

            $this->facultyinternaldocuments = Facultyinternaldocument::whereHas('facultysubjecttool',function( $query){
                $query->where('faculty_id',Auth::guard('faculty')->user()->id)->where('subject_id',$this->subject_id) ->where('academicyear_id',$this->academicyear_id);
            })
            ->with(['internaltooldocument.internaltooldocumentmaster:id,doc_name','internaltooldocument.internaltoolmaster:id,toolname',])
            ->whereNull('document_fileName')
            ->whereNull('document_filePath')
            ->get();

            // $this->required_document_count = $this->facultyinternaldocuments->count();
            // dump($this->required_document_count);

        } else {
            $this->facultyinternaldocuments = [];
        }

        if ($this->subject_id) {
            $this->uploaded_documents = Facultyinternaldocument::whereHas('facultysubjecttool', function ($query) {
                    $query->where('faculty_id', Auth::guard('faculty')->user()->id)
                          ->where('subject_id', $this->subject_id)
                          ->where('academicyear_id', $this->academicyear_id);
                })
                ->whereNotNull('document_fileName')
                ->whereNotNull('document_filePath')
                ->get();
            // $this->uploaded_document_count = $this->uploaded_documents->count();
            // dump($this->uploaded_document_count);
        } else {
            $this->uploaded_documents = [];
        }

        return view('livewire.faculty.internal-audit.upload-document.all-upload-document')->extends('layouts.faculty')->section('faculty');
    }
}
