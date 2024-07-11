<?php

namespace App\Livewire\Faculty\InternalAudit\AssignTool;

use App\Models\Course;
use App\Models\Subject;
use Livewire\Component;
use App\Models\Classview;
use App\Models\Courseclass;
use App\Models\Patternclass;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use App\Models\Hodappointsubject;
use App\Models\Facultysubjecttool;
use App\Models\Internaltoolmaster;
use Illuminate\Support\Facades\DB;
use App\Models\Internaltoolauditor;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Renderless;
use App\Models\Documentacademicyear;
use App\Models\Internaltooldocument;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Facultyinternaldocument;
use Illuminate\Support\Facades\Session;
use App\Exports\Faculty\InternalAudit\AssignTool\AssignToolExport;


class AllAssignTool extends Component
{
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'delete'];

    public $search='';
    public $perPage=10;
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;

    #[Locked]
    public $assignedtool_id;
    #[Locked]
    public $delete_id;
    #[Locked]
    public $mode='all';
    #[Locked]
    public $courses;
    #[Locked]
    public $patternclasses=[];
    #[Locked]
    public $subjects=[];
    public $subjects_filter=[];
    #[Locked]
    public $internaltools;
    #[Locked]
    public $academicyears=[];

    public $academicyear_id;
    public $course_id;
    public $patternclass_id;
    public $subject_id;
    public $counter=1;
    public $selected_tools=[];

    public $pattern_class_ids;
    public $subject_ids;
    public $document_names;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updated($propertyName, $value)
    {
        if($propertyName == 'academicyear_id'){
            $this->loadFilteredSubjects($value);
        }elseif($propertyName == 'course_id'){
            $this->loadPatternClasses($value);
        }elseif($propertyName == 'patternclass_id'){
            $this->loadSubjects($value);
        }
    }

    public function loadFilteredSubjects($value)
    {
        $this->subjects_filter = Subject::select('id','patternclass_id')->with(['academicyear','patternclass'])->where('academicyear_id', '<=', $value)->get();
        $this->pattern_classes=[];
    }

    public function loadPatternClasses($course_id)
    {
        $this->subjects=[];
        $this->patternclasses = Classview::select('id','classyear_name', 'course_name', 'pattern_name')
        ->whereIn('id',$this->subjects_filter->pluck('patternclass_id')->unique())
        ->whereIn('class_id',Courseclass::where('course_id',$course_id)->pluck('id')->unique())
        ->where('status',1)
        ->get();
    }

    public function loadSubjects($patternclass_id)
    {
        $this->subjects =  $this->subjects_filter->where('patternclass_id',$patternclass_id);
    }

    public function setmode($mode)
    {
        if($mode=='add')
        {
            $this->resetinput();
        }
        if($mode=='edit')
        {
            $this->resetValidation();
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

    protected function rules()
    {
        return [
            'academicyear_id' => ['required', Rule::exists(Documentacademicyear::class,'id')],
            'course_id' => ['required', Rule::exists(Course::class,'id')],
            'patternclass_id' => ['required', Rule::exists(Patternclass::class,'id')],
            'subject_id' => ['required', Rule::exists(Subject::class,'id')],
            'selected_tools' => ['required', Rule::exists(Internaltoolmaster::class,'id')],
        ];
    }

    public function messages()
    {
        return [
            'academicyear_id.required' => 'The academic year ID field is required.',
            'academicyear_id.exists' => 'The selected academic year does not exist.',
            'course_id.required' => 'The course ID field is required.',
            'course_id.exists' => 'The selected course does not exist.',
            'patternclass_id.required' => 'The pattern class ID field is required.',
            'patternclass_id.exists' => 'The selected pattern class does not exist.',
            'subject_id.required' => 'The subject ID field is required.',
            'subject_id.exists' => 'The selected subject does not exist.',
            'selected_tools.required' => 'The internal tools field is required.',
            'selected_tools.exists' => 'The selected internal tool does not exist.',
        ];
    }

    public function resetinput()
    {
        $this->reset([
            "academicyear_id",
            "course_id",
            "patternclass_id",
            "subject_id",
            "selected_tools",
        ]);
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
                    $response = Excel::download(new AssignToolExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new AssignToolExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new AssignToolExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
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

    public function save()
    {
        $validatedData = $this->validate();

        DB::beginTransaction();

        try {

            $headId = Hodappointsubject::select('faculty_id')->where('patternclass_id', $this->patternclass_id)->where('subject_id', $this->subject_id)->first();

            $assignedToolData = [];
            foreach ($this->selected_tools as $toolId)
            {
                $Facultysubjecttool = Facultysubjecttool::create(
                    [
                        'academicyear_id' => $validatedData['academicyear_id'],
                        'faculty_id' => Auth::guard('faculty')->user()->id,
                        'subject_id' => $validatedData['subject_id'],
                        'internaltoolmaster_id' => $toolId,
                        'departmenthead_id' => $headId->faculty_id,
                        'status' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                $toolDocuments = Internaltooldocument::where('internaltoolmaster_id', $toolId)->get();

                if ($toolDocuments->isNotEmpty())
                {
                    foreach ($toolDocuments as $document)
                    {
                        $Facultysubjecttool->facultysubjecttools()->create([
                           'internaltooldocument_id'=>$document->id,
                        ]);
                    }
                }
            }

                $this->dispatch('alert', type: 'success', message: 'Tool Assigned To Subject Successfully !!');

                $this->resetinput();

                if (session('is_mode')) {
                    $this->redirect('/faculty/upload/documents', navigate: true);
                } else {
                    $this->setmode('all');
                }

                Session::forget('is_mode');

            DB::commit();

        } catch (\Exception $e) {

            Log::error($e);

            DB::rollback();

            $this->dispatch('alert', type: 'error', message: 'Failed To Assign The Tool For The Subject Please Try Again !!');
        }
    }

    public function delete()
    {
        DB::beginTransaction();

        try
        {
            $assigned_tool = Facultysubjecttool::find($this->delete_id);

            $assigned_tool->forceDelete();

            $this->delete_id = null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Assigned Tool Deleted Successfully !!');

        } catch (\Illuminate\Database\QueryException $e) {

            DB::rollBack();

            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'info',message:'This record is associated with another data. You cannot delete it !!');
            }else
            {
                $this->dispatch('alert',type:'error',message:'Failed To Delete Assigned Tool Data !!');
            }
        }
    }

    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }

    public function view(Facultysubjecttool $assigned_tool)
    {
        if ($assigned_tool) {

            $facultyinternaldocuments = Facultysubjecttool::where('subject_id', $assigned_tool->subject_id)->get();

            $this->academicyear_id = $assigned_tool->academicyear->year_name;
            $this->course_id = $assigned_tool->subject->patternclass->courseclass->course->course_name;
            $pattern = isset($assigned_tool->subject->patternclass->pattern->pattern_name) ? $assigned_tool->subject->patternclass->pattern->pattern_name : '';
            $classyear = isset($assigned_tool->subject->patternclass->courseclass->classyear->classyear_name) ? $assigned_tool->subject->patternclass->courseclass->classyear->classyear_name : '';
            $course = isset($assigned_tool->subject->patternclass->courseclass->course->course_name) ? $assigned_tool->subject->patternclass->courseclass->course->course_name : '';
            $this->patternclass_id = $pattern.' '.$classyear.' '.$course;
            $this->subject_id = $assigned_tool->subject->subject_name;
            $this->assignedtool_id = $assigned_tool->internaltoolmaster->toolname;
            $this->document_names = $assigned_tool->facultysubjecttools;

            $this->setmode('view');
        } else {
            $this->dispatch('alert', type: 'error', message: 'Assigned Internal Tool Not Found');
        }
    }


    public function changestatus(Facultysubjecttool $assigned_tool)
    {
        DB::beginTransaction();

        try {

            $assigned_tool->status = $assigned_tool->status == 0 ? 1 : 0;

            $assigned_tool->update();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Assigned Tool Status Updated Successfully !!');

        } catch (\Exception $e) {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Update Assigned Tool Status !!');

        }
    }

    public function mount($mode="all")
    {
        $this->mode=$mode;

        $this->academicyears = Documentacademicyear::where('active',1)->pluck('year_name','id');
        $this->courses = Course::select('id', 'course_name', 'course_type',)->with(['courseclasses'])->get();

    }

    public function render()
    {
        if($this->mode !== 'all' && $this->mode !== 'view')
        {
            if ($this->subject_id)
            {
                $course = Course::find($this->course_id);
                $this->internaltools = Internaltoolmaster::select('id','toolname')
                    ->where('course_type', $course->course_type)
                    ->where('status', 1)
                    ->get();
            } else {
                $this->internaltools = [];
            }
        }
        $assigned_int_tools = Facultysubjecttool::with(['faculty','academicyear','subject','internaltoolmaster','facultysubjecttools'])->when($this->search, function($query, $search){
            $query->search($search);
        })->where('departmenthead_id',Auth::guard('faculty')->user()->id)->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);
        return view('livewire.faculty.internal-audit.assign-tool.all-assign-tool',compact('assigned_int_tools'))->extends('layouts.faculty')->section('faculty');
    }
}
