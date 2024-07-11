<?php

namespace App\Livewire\User\HodAppointSubject;

use App\Models\Course;
use App\Models\Faculty;
use App\Models\Pattern;
use App\Models\Subject;
use Livewire\Component;
use App\Models\Classview;
use App\Models\Courseclass;
use App\Models\Patternclass;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use App\Models\Hodappointsubject;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\User\HodAppointSubject\HodAppointSubjectExport;

class AllHodAppointSubject extends Component
{   
    ### By Ashutosh
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'delete'];

    public $faculty_id;
    public $patternclass_id;
    public $appointby_id;
    public $pattern_id;
    public $course_id;
    public $course_class_id;
    public $courseclass_subject_id;
    public $pattern_class;
    public $course_name;

    #[Locked]
    public $faculties;
    #[Locked]
    public $patterns;
    #[Locked]
    public $courses;
    #[Locked]
    public $course_classes;
    #[Locked]
    public $pattern_classes;
    #[Locked]
    public $courseclass_subjects;

    #[Locked]
    public $mode='all';

    #[Locked]
    public $hodappointsubject_id;
    #[Locked]
    public $delete_id;


    public $perPage=10;
    public $search='';
    public $sortColumn="faculty_id";
    public $sortColumnBy="ASC";
    public $ext;
    public $isDisabled = true;

    protected function rules()
    {
        return [
            'faculty_id' => ['required', Rule::exists(Faculty::class,'id')],
            'course_id' => ['required',Rule::exists(Course::class,'id')],
            'patternclass_id' => ['required',Rule::exists(Patternclass::class,'id')],
            'courseclass_subject_id' => ['required',Rule::exists(Subject::class,'id')],
        ];
    }

    public function messages()
    {
        return [
            'faculty_id.required' => 'The faculty name field is required.',
            'faculty_id.exists' => 'The selected faculty is invalid.',
            'course_id.required' => 'The course name field is required.',
            'course_id.exists' => 'The selected course is invalid.',
            'patternclass_id.required' => 'The course patternclass name field is required.',
            'patternclass_id.exists' => 'The selected patternclass class is invalid.',
            'courseclass_subject_id.required' => 'The subject name field is required.',
            'courseclass_subject_id.exists' => 'The selected subject is invalid.',
        ];
    }

    public function resetinput()
    {
        $this->reset(
            [
                'hodappointsubject_id',
                'faculty_id',
                'course_id',
                'patternclass_id',
                'courseclass_subject_id'
            ]
        );
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

        $this->resetValidation();

        $this->mode=$mode;
    }

    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $validatedData = $this->validate();

        DB::beginTransaction();
        try
        {   

            $existing_record = Hodappointsubject::where('patternclass_id', $this->course_class_id)->where('subject_id', $this->courseclass_subject_id)->where('status', 1)->latest('updated_at')->first();

            if ($existing_record) {
                $this->dispatch('alert', type: 'error', message: 'HOD is active and already assigned for that subject');
                return;
            }

            $validatedData['subject_id'] = $this->courseclass_subject_id;
            $validatedData['appointby_id'] = Auth::gurad('user')->id;
            $hodappointsubject = Hodappointsubject::create($validatedData);

            DB::commit();

            $this->dispatch('alert', type: 'success', message: 'HOD Appointed Successfully !!');

        }catch (\Exception $e)
        {
            DB::rollBack();
            $this->dispatch('alert',type:'error',message:'Failed to Appoint HOD  !!');
        }


        $this->mode='all';
    }



    public function edit(Hodappointsubject $hodappointsubject)
    {
        if ($hodappointsubject)
        {
            $this->hodappointsubject_id = $hodappointsubject->id;
            $this->faculty_id= $hodappointsubject->faculty_id;
            $this->course_id = $hodappointsubject->patternclass->courseclass->course->id;
            $this->patternclass_id= $hodappointsubject->patternclass_id;
            $this->courseclass_subject_id= $hodappointsubject->subject_id;
            $this->mode='edit';
        }
        else
        {
            $this->dispatch('alert',type:'error',message:'Something Went Wrong !!');
        }
    }

    public function update(Hodappointsubject $hodappointsubject)
    {
        $validatedData = $this->validate();

        DB::beginTransaction();
        try
        {   

            $existing_record = Hodappointsubject::where('patternclass_id', $this->patternclass_id)->where('subject_id', $this->courseclass_subject_id)->where('status', 1)->where('id', '!=', $hodappointsubject->id)->latest('updated_at')->first();

            if ($existing_record) {
                $this->dispatch('alert', type: 'error', message: 'Hod is already assigned for that subject');
                return;
            }

            $validatedData['subject_id'] = $this->courseclass_subject_id;
            $validatedData['appointby_id'] = Auth::gurad('user')->id;
            $hodappointsubject->update($validatedData);
            DB::commit();
            $this->dispatch('alert',type:'success',message:'Appointed HOD Updated Successfully !!');

        }catch (\Exception $e)
        {
            DB::rollBack();
            $this->dispatch('alert',type:'error',message:'Failed to Update Appointed HOD  !!');
        }

        $this->mode='all';
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
            set_time_limit(600);
            ini_set('memory_limit', '1024M');

            $filename="HOD_Appoint_Subject_".time();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new HodAppointSubjectExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new HodAppointSubjectExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new HodAppointSubjectExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Appointed HOD Exported Successfully !!');

            return $response;
        }
        catch (\Exception $e)
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Appointed HOD !!');
        }
    }


    public function changestatus(Hodappointsubject $hodappointsubject)
    {
        DB::beginTransaction();

        try
        {
            if($hodappointsubject->status)
            {
                $hodappointsubject->status=0;
            }
            else
            {
                $hodappointsubject->status=1;
            }
            $hodappointsubject->update();

            DB::commit();
            $this->dispatch('alert',type:'success',message:'Status Updated Successfully !!'  );
        }catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Status  !!');
        }
    }

    public function delete()
    {   
        DB::beginTransaction();
        try
        {   
            $hodappointsubject = Hodappointsubject::withTrashed()->find($this->delete_id);
            $hodappointsubject->forceDelete();
            $this->delete_id = null;
            DB::commit();
            $this->dispatch('alert',type:'success',message:'Appointed HOD Deleted Successfully !!');

        }catch (\Exception $e)
        {
            DB::rollBack();
            $this->dispatch('alert',type:'error',message:'Failed to Delete Appointed HOD  !!');
        }
        
    }

    public function softdelete($id)
    {   
        DB::beginTransaction();
        try
        {       
            $hodappointsubject = Hodappointsubject::withTrashed()->findOrFail($id);
            $hodappointsubject->delete();
            DB::commit();
            $this->dispatch('alert',type:'success',message:'Appointed HOD Soft Deleted Successfully !!');

        }catch (\Exception $e)
        {
            DB::rollBack();
            $this->dispatch('alert',type:'error',message:'Failed to Soft Delete Appointed HOD  !!');
        }

    }

    public function restore($id)
    {   
        DB::beginTransaction();
        try
        {   
            $hodappointsubject = Hodappointsubject::withTrashed()->find($id);
            $hodappointsubject->restore();
            DB::commit();
            $this->dispatch('alert',type:'success',message:'Appointed HOD Restored Successfully !!');

        }catch (\Exception $e)
        {
            DB::rollBack();
            $this->dispatch('alert',type:'error',message:'Failed to Restore Appointed HOD  !!');
        }
    }

    public function view(Hodappointsubject $hodappointsubject)
    {
        if ($hodappointsubject)
        {
            $this->faculty_id = isset($hodappointsubject->faculty->faculty_name) ? $hodappointsubject->faculty->faculty_name : '';
            $this->pattern_id = isset($hodappointsubject->patternclass->pattern->pattern_name) ? $hodappointsubject->patternclass->pattern->pattern_name : '';
            $this->course_id = isset($hodappointsubject->patternclass->courseclass->course->course_name) ? $hodappointsubject->patternclass->courseclass->course->course_name : '';
            $this->patternclass_id =get_pattern_class_name($hodappointsubject->patternclass_id);
            $this->courseclass_subject_id = isset($hodappointsubject->subject->subject_name) ? $hodappointsubject->subject->subject_name : '';

            $this->setmode('view');
        }else{
            $this->dispatch('alert',type:'error',message:'Something Went Wrong !!');
        }
    }

    public function render()
    {
        if ($this->mode !== 'all') {
            $this->faculties = Faculty::where('active',1)->pluck('faculty_name','id');
            $this->courses = Course::pluck('course_name','id');
            $course_classes = Courseclass::where('course_id', $this->course_id)->pluck('id');
            $this->pattern_classes = Classview::select('id','classyear_name', 'course_name', 'pattern_name')->where('status',1)->whereIn('class_id', $course_classes)->get();

            if ($this->mode === 'edit') 
            {
                $this->courseclass_subjects = Subject::select('id', 'subject_name')->where('patternclass_id', $this->patternclass_id)->get();
            } else 
            {
                $existing_subject_ids = Hodappointsubject::where('patternclass_id', $this->patternclass_id)->where('status', 1)->pluck('subject_id');
                $this->courseclass_subjects = Subject::select('id', 'subject_name')->where('patternclass_id', $this->patternclass_id)->whereNotIn('id', $existing_subject_ids)->get();
            }
        }

        $hodappointsubjects = Hodappointsubject::with('faculty','subject', 'patternclass')->when($this->search, function($query, $search){
            $query->search($search);
        })->orderBy($this->sortColumn, $this->sortColumnBy)->withTrashed()->paginate($this->perPage);
        return view('livewire.user.hodappointsubject.all-hodappointsubject',compact('hodappointsubjects'))->extends('layouts.user')->section('user');
    }
}
