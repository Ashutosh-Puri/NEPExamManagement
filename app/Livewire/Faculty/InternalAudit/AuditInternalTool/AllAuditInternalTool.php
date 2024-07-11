<?php

namespace App\Livewire\Faculty\InternalAudit\AuditInternalTool;

use Livewire\Component;
use App\Models\Classview;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use App\Models\Internaltoolauditor;
use App\Models\Documentacademicyear;
use Illuminate\Support\Facades\Auth;
use App\Models\Facultyinternaldocument;

class AllAuditInternalTool extends Component
{
    use WithPagination;

    protected $listeners = ['remark-saved' => 'render'];

    public $academicyear_id;
    public $faculty_id;
    public $subject_id;
    public $patternclass_id;
    public $internaltooldocument_id;
    public $tool_name;
    public $document_fileName;
    public $document_filePath;
    public $departmenthead_id;
    public $verifybyfaculty_id;
    public $verificationremark;
    public $status;

    public $academicyears;
    public $pattern_classes;
    public $internaltooldocuments;

    #[Locked]
    public $audit_tool_id;
    #[Locked]
    public $delete_id;

    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $mode='all';
    public $ext;

    protected function rules()
    {
        return [
            'verificationremark' => ['required', 'string', 'max:255',],
        ];
    }

    public function messages()
    {
        return [
            'verificationremark.required' => 'The verification remark field is required.',
            'verificationremark.string' => 'The verification remark must be a string.',
            'verificationremark.max' => 'The verification remark may not be greater than :max characters.',
        ];
    }

    public function mount()
    {
        $this->academicyears = Documentacademicyear::where('active',1)->pluck('year_name','id');
        // $this->pattern_classes = Internaltoolauditor::select('id','patternclass_id')->with(['patternclass.pattern:pattern_name,id','patternclass.courseclass.course:course_name,id','patternclass.courseclass.classyear:classyear_name,id'])->where('faculty_id',Auth::guard('faculty')->user()->id)->where('status',1)->get();
        $pattern_classids = Internaltoolauditor::where('faculty_id',Auth::guard('faculty')->user()->id)->where('status',1)->pluck('patternclass_id');

        $this->pattern_classes = Classview::select('id','classyear_name', 'course_name', 'pattern_name')->whereIn('id',$pattern_classids)->where('status',1)->get();

    }

    public function render()
    {

        if($this->academicyear_id && $this->patternclass_id){
            $audit_internal_tools = Facultyinternaldocument::with(['facultysubjecttool', 'internaltooldocument'])
            ->orderBy($this->sortColumn, $this->sortColumnBy)
            ->when($this->search, function($query, $search) {
                return $query->search($search);
            })
            ->whereHas('facultysubjecttool', function($query) {
                $query->where('academicyear_id', $this->academicyear_id)
                    ->where('freeze_by_faculty', 1);
            })->paginate($this->perPage);
        }else{
            $audit_internal_tools = [];
        }

        return view('livewire.faculty.internal-audit.audit-internal-tool.all-audit-internal-tool',compact('audit_internal_tools'))->extends('layouts.faculty')->section('faculty');
    }
}
