<?php

namespace App\Livewire\Faculty\SubjectBucket;

use App\Models\Course;
use App\Models\Pattern;
use App\Models\Subject;
use Livewire\Component;
use App\Models\Classview;
use App\Models\Department;
use App\Models\Courseclass;
use App\Models\Academicyear;
use App\Models\Patternclass;
use Livewire\WithPagination;
use App\Models\Subjectbucket;
use App\Models\Subjectvertical;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Faculty\SubjectBucket\SubjectBucketExport;

class AllSubjectBucket extends Component
{
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'delete'];

    public $search='';
    public $perPage=10;
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;

    #[Locked]
    public $delete_id;
    #[Locked]
    public $subjectbucket_id;
    #[Locked]
    public $mode='all';
    #[Locked]
    public $departments;
    #[Locked]
    public $subject_verticals;
    #[Locked]
    public $patterns;
    #[Locked]
    public $courses;
    #[Locked]
    public $pattern_classes;
    #[Locked]
    public $subjects;
    #[Locked]
    public $academic_years;

    public $department_id;
    public $patternclass_id;
    public $subjectvertical_id;
    public $subject_division;
    public $subject_id;
    public $pattern_id;
    public $academicyear_id;
    public $course_id;

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
            'course_id' => ['required',Rule::exists(Course::class,'id')],
            'pattern_id' => ['required',Rule::exists(Pattern::class,'id')],
            'patternclass_id' => ['required',Rule::exists(Patternclass::class,'id')],
            'subject_id' => ['required',Rule::exists(Subject::class,'id')],
            'subjectvertical_id' => ['required',Rule::exists(Subjectvertical::class,'id')],
        ];
    }

    public function messages()
    {
        return [
            'department_id.required' => 'The department field is required.',
            'department_id.exists' => 'The selected department is invalid.',
            'pattern_id.required' => 'The pattern field is required.',
            'pattern_id.exists' => 'The pattern field is invalid.',
            'course_id.required' => 'The course field is required.',
            'course_id.exists' => 'The selected course is invalid.',
            'patternclass_id.required' => 'The patternclass class field is required.',
            'patternclass_id.exists' => 'The selected patternclass class is invalid.',
            'subject_id.required' => 'The subject field is required.',
            'subject_id.exists' => 'The selected subject is invalid.',
            'subjectvertical_id.required' => 'The subject vertical field is required.',
            'subjectvertical_id.exists' => 'The selected subject vertical is invalid.',
        ];
    }

    public function resetinput()
    {
        $this->reset([
            "department_id",
            "subjectvertical_id",
            "pattern_id",
            "course_id",
            "patternclass_id",
            "subject_id",
        ]);
    }

    #[Renderless]
    public function export()
    {
        try
        {
            $filename="Subjectbuckets_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new SubjectBucketExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new SubjectBucketExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new SubjectBucketExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Subject Buckets Data Exported Successfully !!');

            return $response;
        }
        catch (Exception $e)
        {
            Log::error($e);

            $this->dispatch('alert',type:'error',message:'Failed To Export Subject Buckets Data !!');
        }
    }

    public function save()
    {
        $validatedData = $this->validate();
        $activeAcademicYearId = Academicyear::where('active', 1)->value('id');
        $validatedData['academicyear_id'] = $activeAcademicYearId;

        DB::beginTransaction();

        try {

            $subjectbucket = Subjectbucket::create($validatedData);

            DB::commit();

            $this->resetinput();

            $this->setmode('add');

            $this->dispatch('alert',type:'success',message:'Subject Bucket Added Successfully !!');

        } catch (\Exception $e) {
            DB::rollback();

            $this->dispatch('alert',type:'error',message:'Failed To Add Subject Bucket Please try again !!');
        }
    }

    public function edit(Subjectbucket $subjectbucket)
    {
        if ($subjectbucket)
        {
            $this->subjectbucket_id = $subjectbucket->id;
            $this->department_id= $subjectbucket->department_id;
            $this->subjectvertical_id= $subjectbucket->subjectvertical_id;
            $this->pattern_id= $subjectbucket->patternclass->pattern->id;
            $this->course_id = $subjectbucket->patternclass->courseclass->course->id;
            $this->patternclass_id= $subjectbucket->patternclass_id;
            $this->subject_id= $subjectbucket->subject_id;
            $this->setmode('edit');
        }
        else{
        $this->dispatch('alert',type:'error',message:'Something Went Wrong !!');
        }
    }

    public function update(Subjectbucket $subjectbucket)
    {
        $validatedData = $this->validate();

        DB::beginTransaction();

        try {

            $subjectbucket->update($validatedData);

            DB::commit();

            $this->resetinput();

            $this->setmode('all');

            $this->dispatch('alert',type:'success',message:'Subject Bucket Updated Successfully !!');

        } catch (\Exception $e) {

            DB::rollback();

            $this->dispatch('alert', type: 'error', message: 'Failed To Update Subject Bucket Please try again !!');
        }
    }

    public function delete()
    {
        DB::beginTransaction();

        try
        {
            $subjectbucket = Subjectbucket::withTrashed()->find($this->delete_id);

            $subjectbucket->forceDelete();

            $this->delete_id = null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Subject Bucket Deleted Successfully !!');

        } catch (\Illuminate\Database\QueryException $e) {

            DB::rollBack();

            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'info',message:'This record is associated with another data. You cannot delete it !!');
            }else
            {
                $this->dispatch('alert',type:'error',message:'Failed To Delete Subject Bucket Data !!');
            }
        }
    }

    public function softdelete(Subjectbucket $subjectbucket)
    {
        DB::beginTransaction();

        try
        {
            $subjectbucket->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Subject Bucket Soft Deleted Successfully !!');

        }
        catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Subject Bucket !!');
        }
    }

    public function restore($subject_bucket_id)
    {
        DB::beginTransaction();

        try
        {
            $subjectbucket = Subjectbucket::withTrashed()->findOrFail($subject_bucket_id);

            $subjectbucket->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Subject Bucket Restored Successfully !!');

        }
        catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Subject Bucket Not Found !!');
        }
    }

    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }

    public function view(Subjectbucket $subjectbucket)
    {
        if ($subjectbucket)
        {
            $this->subject_id= isset($subjectbucket->subject->subject_name) ? $subjectbucket->subject->subject_name : '';
            $this->subject_division= $subjectbucket->subject_division;
            $this->subjectvertical_id= isset($subjectbucket->subjectvertical->subject_vertical) ? $subjectbucket->subjectvertical->subject_vertical : '';
            $this->course_id = isset($subjectbucket->patternclass->courseclass->course->course_name) ? $subjectbucket->patternclass->courseclass->course->course_name : '';
            $this->department_id= isset($subjectbucket->department->dept_name) ? $subjectbucket->department->dept_name : '';
            $this->pattern_id= isset($subjectbucket->patternclass->pattern->pattern_name) ? $subjectbucket->patternclass->pattern->pattern_name : '';
            $this->academicyear_id= isset($subjectbucket->academicyear->year_name) ? $subjectbucket->academicyear->year_name : '';
            $pattern = isset($subjectbucket->patternclass->pattern->pattern_name) ? $subjectbucket->patternclass->pattern->pattern_name : '';
            $classyear = isset($subjectbucket->patternclass->courseclass->classyear->classyear_name) ? $subjectbucket->patternclass->courseclass->classyear->classyear_name : '';
            $course = isset($subjectbucket->patternclass->courseclass->course->course_name) ? $subjectbucket->patternclass->courseclass->course->course_name : '';
            $this->patternclass_id = $classyear.' '.$course.' '.$pattern;
            $this->setmode('view');
        }else{
            $this->dispatch('alert',type:'error',message:'Something Went Wrong !!');
        }
    }

    public function changestatus(Subjectbucket $subjectbucket)
    {
        DB::beginTransaction();

        try {

            $subjectbucket->status = $subjectbucket->status == 0 ? 1 : 0;

            $subjectbucket->update();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Subject Bucket Status Updated Successfully !!');

        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Update Subject Bucket Status !!');

        }
    }

    public function render()
    {
        $subjectbuckets=collect([]);

        if($this->mode !== 'all' ){
            $this->patterns=Pattern::where('status',1)->pluck('pattern_name','id');
            $this->courses = Course::pluck('course_name','id');
            $course_classes = Courseclass::where('course_id', $this->course_id)->pluck('id');
            $this->pattern_classes = Classview::select('id','classyear_name', 'course_name', 'pattern_name')->where('status',1)->where('pattern_id', $this->pattern_id)->whereIn('class_id', $course_classes)->get();
            $this->subject_verticals = Subjectvertical::where('is_active',1)->pluck('subject_vertical','id');

            if($this->subjectvertical_id){
                $this->subjects = Subject::select('id', 'subject_name')->where('subjectvertical_id', $this->subjectvertical_id)->where('status', 1)->get();
            }else{
                $this->subjects=[];
            }

            $this->departments = Department::where('status',1)->pluck('dept_name','id');
        }

        $subjectbuckets = Subjectbucket::with('department:id,dept_name', 'patternclass', 'subjectvertical:id,subject_vertical', 'subject:id,subject_name', 'academicyear:id,year_name')
        ->when($this->search, function($query, $search){
            $query->search($search);
        })->orderBy($this->sortColumn, $this->sortColumnBy)->withTrashed()->paginate($this->perPage);
        return view('livewire.faculty.subjectbucket.all-subjectbucket',compact('subjectbuckets'))->extends('layouts.faculty')->section('faculty');
    }
}
