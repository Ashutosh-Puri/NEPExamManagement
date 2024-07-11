<?php

namespace App\Livewire\User\InternalAudit\InternalToolAuditor;

use App\Models\Course;
use App\Models\Faculty;
use Livewire\Component;
use App\Models\Classview;
use App\Models\Department;
use App\Models\Courseclass;
use App\Models\Patternclass;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use App\Models\Internaltoolauditor;
use Livewire\Attributes\Renderless;
use App\Models\Documentacademicyear;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\User\InternalAudit\InternalToolAuditor\InternalToolAuditorExport;

class AllInternalToolAuditor extends Component
{   
    ### By Ashutosh
    use WithPagination;
    protected $listeners = ['delete-confirmed'=>'delete'];

    public $faculty_id;
    #[Locked]
    public $faculties;

    public $academicyear_id;
    #[Locked]
    public $academicyears;

    public $department_id;
    #[Locked]
    public $departments;

    public $evaluationdate;
    public $status;

    public $course_id;
    #[Locked]
    public $courses;

    public $patternclass_id;
    #[Locked]
    public $pattern_classes;

    #[Locked]
    public $delete_id;
    #[Locked]
    public $internaltool_auditor_id;


    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    #[Locked]
    public $mode='all';
    public $ext;


    protected function rules()
    {
        return [
            'academicyear_id' => ['required',Rule::exists(Documentacademicyear::class,'id')],
            'course_id' => ['required',Rule::exists(Course::class,'id')],
            'patternclass_id' => ['required',Rule::exists(Patternclass::class,'id')],
            'department_id' => ['required',Rule::exists(Department::class,'id')],
            'faculty_id' => ['required',Rule::exists(Faculty::class,'id')],
        ];
    }

    public function messages()
    {
        $messages = [
            'academicyear_id.required' => 'The academic year field is required.',
            'academicyear_id.exists' => 'The selected academic year is invalid.',
            'course_id.required' => 'The course field is required.',
            'course_id.exists' => 'The selected course is invalid.',
            'patternclass_id.required' => 'The pattern class field is required.',
            'patternclass_id.exists' => 'The selected pattern class is invalid.',
            'faculty_id.required' => 'The faculty field is required.',
            'faculty_id.exists' => 'The selected faculty is invalid.',
            'department_id.required' => 'The department field is required.',
            'department_id.exists' => 'The selected academic year is invalid.',
        ];
        return $messages;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }

    public function resetinput()
    {
        $this->reset(
            [
                'academicyear_id',
                'course_id',
                'internaltool_auditor_id',
                'patternclass_id',
                'department_id',
                'faculty_id'
            ]
        );
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

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function setmode($mode)
    {
        if($mode=='add')
        {
            $this->resetinput();
        }
        $this->mode=$mode;
        $this->resetValidation();
    }

    public function save()
    {
        $validatedData = $this->validate();

        DB::beginTransaction();

        try
        {

            $internaltool_auditor = Internaltoolauditor::create($validatedData);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Internal Tool Auditor Assigned Successfully');

        } catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Assign Internal Tool Auditor.');
        }
    }


    public function edit(Internaltoolauditor $internaltool_auditor)
    {
        if ($internaltool_auditor){
            $this->internaltool_auditor_id = $internaltool_auditor->id;
            $this->academicyear_id= $internaltool_auditor->academicyear_id;
            $this->course_id = $internaltool_auditor->patternclass->courseclass->course->id;
            $this->patternclass_id= $internaltool_auditor->patternclass_id;
            $this->department_id= $internaltool_auditor->faculty->department_id;
            $this->faculty_id= $internaltool_auditor->faculty_id;
        }else{
            $this->dispatch('alert',type:'error',message:'Internal Tool Auditor Details Not Found');
        }
        $this->mode='edit';
    }

    public function update(Internaltoolauditor $internaltool_auditor)
    {
        $validatedData = $this->validate();

        DB::beginTransaction();

        try
        {

            $internaltool_auditor->update($validatedData);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Internal Tool Auditor Updated Successfully');
        }
        catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Error To Update Internal Tool Auditor');
        }
    }

    #[Renderless]
    public function export()
    {
        try
        {
            set_time_limit(600);
            ini_set('memory_limit', '1024M');

            $filename="Internal_Tool_Auditor_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new InternalToolAuditorExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new InternalToolAuditorExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new InternalToolAuditorExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Internal Tool Auditor Exported Successfully !!');

            return $response;
        }
        catch (\Exception $e)
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Internal Tool Auditor !!');
        }
    }

    public function changestatus(Internaltoolauditor $internaltool_auditor)
    {
        DB::beginTransaction();

        try {

            $internaltool_auditor->status = $internaltool_auditor->status == 0 ? 1 : 0;

            $internaltool_auditor->update();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Internal Tool Auditor Status Updated Successfully !!');

        } catch (\Exception $e) {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Update Internal Tool Auditor Status !!');

        }
    }


    public function delete()
    {   
        DB::beginTransaction();
        try
        {
            $internaltool_auditor = Internaltoolauditor::withTrashed()->find($this->delete_id);
            $internaltool_auditor->forceDelete();
            $this->delete_id = null;
            DB::commit();
            $this->dispatch('alert',type:'success',message:'Internal Tool Auditor Deleted Successfully !!');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'error',message:'This record is associated with another data. You cannot delete it !!');
            }
            else
            {
                $this->dispatch('alert',type:'error',message:'Failed To Delete Internal Tool Auditor !!');
            }
        }
    }

    public function softdelete($id)
    {
        DB::beginTransaction();

        try
        {
            $internaltool_auditor = Internaltoolauditor::withTrashed()->find($id);
            $internaltool_auditor->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Internal Tool Auditor Soft Deleted Successfully !!');
        }
        catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Internal Tool Auditor !!');
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();

        try
        {
            $internaltool_auditor = Internaltoolauditor::withTrashed()->find($id);
            $internaltool_auditor->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Internal Tool Auditor Restored Successfully !!');
        }
        catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Internal Tool Auditor !!');
        }
    }


    public function view(Internaltoolauditor $internaltool_auditor)
    {
        if ($internaltool_auditor){
            $this->academicyear_id= isset($internaltool_auditor->academicyear->year_name) ? $internaltool_auditor->academicyear->year_name : '';
            $this->course_id = isset($internaltool_auditor->patternclass->courseclass->course->course_name) ? $internaltool_auditor->patternclass->courseclass->course->course_name : '';
            $this->patternclass_id =  get_pattern_class_name($internaltool_auditor->patternclass_id);
            $this->department_id= isset($internaltool_auditor->faculty->department->dept_name) ? $internaltool_auditor->faculty->department->dept_name : '';
            $this->faculty_id= isset($internaltool_auditor->faculty->faculty_name) ? $internaltool_auditor->faculty->faculty_name : '';

        }else{
            $this->dispatch('alert',type:'error',message:'Internal Tool Auditor Details Not Found');
        }
        $this->mode='view';
    }

    public function mount()
    {
        $this->academicyears = Documentacademicyear::where('active',1)->pluck('year_name','id');
        $this->courses = Course::pluck('course_name','id');
    }

    public function render()
    {
        if($this->mode !== 'all' ){
            if($this->course_id){
                $course_classes = Courseclass::where('course_id', $this->course_id)->pluck('id');
                $this->pattern_classes = Classview::select('id','classyear_name', 'course_name', 'pattern_name')->where('status',1)->whereIn('class_id', $course_classes)->get();
            }else{
                $this->pattern_classes = [];
            }

            if($this->patternclass_id){
                $this->departments = Department::select('id','dept_name')->where('status',1)->get();
            }else{
                $this->departments = [];
            }

            if($this->patternclass_id){
                $this->faculties = Faculty::select('id','faculty_name')->where('department_id', $this->department_id)->where('active',1)->whereNotNull('department_id')->get();
            }else{
                $this->faculties = [];
            }
        }
        $internaltool_auditors = Internaltoolauditor::with(['academicyear:year_name,id', 'faculty:faculty_name,id', 'faculty.department:dept_name,id', 'patternclass.pattern:pattern_name,id', 'patternclass.courseclass.classyear:classyear_name,id', 'patternclass.courseclass.course:course_name,id'])->when($this->search, function($query, $search){
            $query->search($search);
        })->orderBy($this->sortColumn, $this->sortColumnBy)->withTrashed()->paginate($this->perPage);
        return view('livewire.user.internal-audit.internal-tool-auditor.all-internal-tool-auditor',compact('internaltool_auditors'))->extends('layouts.user')->section('user');
    }
}
