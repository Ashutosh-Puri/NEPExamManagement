<?php

namespace App\Livewire\Faculty\AssignSubject;

use Excel;
use App\Models\Course;
use App\Models\Subject;
use Livewire\Component;
use App\Models\Semester;
use App\Models\Classview;
use App\Models\Department;
use App\Models\Courseclass;
use App\Models\Academicyear;
use App\Models\Patternclass;
use Livewire\WithPagination;
use App\Models\Subjectbucket;
use App\Models\Subjectcategory;
use App\Models\Subjectvertical;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Exports\Faculty\AssignSubject\AssignSubjectExport;
use App\Exports\Faculty\AssignedSubject\AllAssignedSubjectExport;

class AllAssignSubject extends Component
{
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'delete'];

    public $search='';
    public $perPage=10;
    public $sortColumn="id";
    public $sortColumnBy="DESC";
    public $ext;

    #[Locked]
    public $assignsubject_id;
    #[Locked]
    public $delete_id;
    #[Locked]
    public $mode='add';
    #[Locked]
    public $semesters;
    #[Locked]
    public $pattern_classes;
    #[Locked]
    public $subjects;
    #[Locked]
    public $courses;
    #[Locked]
    public $subject_verticals;
    #[Locked]
    public $departments;

    public $subjectvertical_id;
    public $department_id;
    public $course_id;
    public $patternclass_id;
    public $pattern_id;
    public $course_class_id;
    public $subject_sem;
    public $subject_id;
    public $academicyear_id;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
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
            'department_id' => ['required',Rule::exists(Department::class,'id')],
            'patternclass_id' => ['required',Rule::exists(Patternclass::class,'id')],
            'subjectvertical_id' => ['required',Rule::exists(Subjectvertical::class,'id')],
            'subject_id' => ['required',Rule::exists(Subject::class,'id')],
            'subject_sem' => ['required',Rule::exists(Semester::class,'id')],
        ];
    }

    public function messages()
    {
        return [
            'department_id.required' => 'The department field is required.',
            'patternclass_id.required' => 'The patternclass field is required.',
            'subjectvertical_id.required' => 'The subject vertical field is required.',
            'subjectvertical_id.required' => 'The subject vertical field is required.',
            'subject_id.required' => 'The subject field is required.',
            'subject_sem.required' => 'The subject semester is required.',
        ];
    }

    public function resetinput()
    {
        $this->reset([
            'department_id',
            'course_id',
            'subject_sem',
            'patternclass_id',
            'subjectvertical_id',
            'subject_id',
            'academicyear_id',
        ]);
    }

    #[Renderless]
    public function export()
    {
        try
        {
            $filename="Assigned_Subjects_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new AssignSubjectExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new AssignSubjectExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new AssignSubjectExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Assigned Subject Data Exported Successfully !!');

            return $response;
        }
        catch (Exception $e)
        {
            Log::error($e);

            $this->dispatch('alert',type:'error',message:'Failed To Export Assigned Subject Data !!');
        }
    }

    public function save()
    {
        DB::beginTransaction();

        try
        {
            $validatedData = $this->validate();
            $activeAcademicYearId = Academicyear::where('active', 1)->value('id');
            $validatedData['academicyear_id'] = $activeAcademicYearId;

            Subjectbucket::create($validatedData);

            DB::commit();

            $this->resetinput();

            $this->setmode('add');

            $this->dispatch('alert',type:'success',message:'Subject Assigned Successfully !!');

        } catch(\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Assign Subject. Please try again !!');
        }
    }

    public function edit(Subjectbucket $assignsubject)
    {
        if ($assignsubject){
            $subject = Subject::find($assignsubject->subject_id);
            $this->assignsubject_id = $assignsubject->id;
            $this->subjectvertical_id= $assignsubject->subjectvertical_id;
            $this->department_id= $assignsubject->department_id;
            $this->course_id = $assignsubject->patternclass->courseclass->course->id;
            $this->patternclass_id= $assignsubject->patternclass_id;
            $this->subject_sem= $subject->subject_sem;
            $this->subject_id= $subject->id;
            $this->setmode('edit');
        }else{
            $this->dispatch('alert',type:'error',message:'Assigned Subject Details Not Found');
        }
    }

    public function update(Subjectbucket $assignsubject)
    {
        $validatedData = $this->validate();
        $activeAcademicYearId = Academicyear::where('active', 1)->value('id');
        $validatedData['academicyear_id'] = $activeAcademicYearId;

        DB::beginTransaction();

        try
        {
            $assignsubject->update($validatedData);

            DB::commit();

            $this->resetinput();

            $this->setmode('add');

            $this->dispatch('alert', type: 'success', message: 'Assigned Subject Updated Successfully !!');
        }catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert', type: 'error', message: 'Failed To Update Assigned Subject. Please try again !!');
        }
    }

    public function delete()
    {
        DB::beginTransaction();

        try
        {
            $assignsubject = Subjectbucket::withTrashed()->find($this->delete_id);

            $assignsubject->forceDelete();

            $this->delete_id = null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Assigned Subject Deleted Successfully !!');

        } catch (\Illuminate\Database\QueryException $e) {

            DB::rollBack();

            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'info',message:'This record is associated with another data. You cannot delete it !!');
            }else
            {
                $this->dispatch('alert',type:'error',message:'Failed To Delete Assigned Subject Data !!');
            }
        }
    }

    public function softdelete(Subjectbucket $assignsubject)
    {
        DB::beginTransaction();

        try
        {
            $assignsubject->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Assigned Subject Soft Deleted Successfully !!');

        }
        catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Assigned Subject !!');
        }
    }

    public function restore($assign_subject_id)
    {
        DB::beginTransaction();

        try
        {
            $assignsubject = Subjectbucket::withTrashed()->findOrFail($assign_subject_id);

            $assignsubject->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Assigned Subject Restored Successfully !!');

        }
        catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Assigned Subject Not Found !!');
        }
    }

    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }

    public function view(Subjectbucket $assignsubject)
    {
        if ($assignsubject){
            $this->subjectvertical_id= isset($assignsubject->subjectvertical->subject_vertical) ? $assignsubject->subjectvertical->subject_vertical : '';
            $this->department_id= isset( $assignsubject->department->dept_name) ?  $assignsubject->department->dept_name : '';
            $this->course_id= isset($assignsubject->patternclass->courseclass->course->course_name) ? $assignsubject->patternclass->courseclass->course->course_name : '';
            $classyear = isset($assignsubject->patternclass->courseclass->classyear->classyear_name) ? $assignsubject->patternclass->courseclass->classyear->classyear_name : '';
            $course = isset($assignsubject->patternclass->courseclass->course->course_name) ? $assignsubject->patternclass->courseclass->course->course_name : '';
            $pattern = isset($assignsubject->pattern->pattern_name) ? $assignsubject->pattern->pattern_name : '';
            $this->patternclass_id = $classyear.''.$course.''.$pattern;
            $this->subject_sem = isset($assignsubject->subject->subject_sem) ? $assignsubject->subject->subject_sem : '';
            $this->academicyear_id = isset($assignsubject->academicyear->year_name) ? $assignsubject->academicyear->year_name : '';
            $this->subject_id = isset($assignsubject->subject->subject_name) ? $assignsubject->subject->subject_name : '';
        }else{
            $this->dispatch('alert',type:'error',message:'Assigned Subject Details Not Found');
        }
        $this->setmode('view');
    }

    public function changestatus(Subjectbucket $assignsubject)
    {
        DB::beginTransaction();

        try {

            $assignsubject->status = $assignsubject->status == 0 ? 1 : 0;

            $assignsubject->update();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Assigned Subject Status Updated Successfully !!');

        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Update Assigned Subject Status !!');

        }
    }

    public function render()
    {
        $assignsubjects=collect([]);

        if($this->mode !== 'view'){
            $this->subject_verticals = Subjectvertical::select('id', 'subject_vertical')
                ->whereNotIn('subjectbuckettype_id', [1])
                ->where('is_active', 1)
                ->get();

            $this->semesters=Semester::where('status',1)->pluck('semester','id');
            $this->departments = Department::where('status',1)->pluck('dept_name','id');
            $this->courses = Course::pluck('course_name','id');

            $course_classes = Courseclass::where('course_id', $this->course_id)->pluck('id');

            $this->pattern_classes =  Classview::select('id','classyear_name', 'course_name', 'pattern_name')->where('status',1)->whereIn('class_id', $course_classes)->get();


            if($this->subjectvertical_id && $this->subject_sem){
                $this->subjects = Subject::select('id', 'subject_name')
                ->with(['subjectvertical:id,subject_vertical',])
                ->where('subjectvertical_id', $this->subjectvertical_id)
                ->where('subject_sem', $this->subject_sem)
                ->where('status', 1)
                ->get();
            }else{
                $this->subjects=[];
            }
            $assignsubjects = Subjectbucket::with(['department:id,dept_name', 'subjectvertical:id,subject_vertical', 'subject:id,subject_name', 'academicyear:id,year_name'])->whereNotIn('subjectvertical_id', [1])->when($this->search, function($query, $search){
                $query->search($search);
            })->orderBy($this->sortColumn, $this->sortColumnBy)->withTrashed()->paginate($this->perPage);
        }
        return view('livewire.faculty.assign-subject.all-assign-subject',compact('assignsubjects'))->extends('layouts.faculty')->section('faculty');
    }
}
