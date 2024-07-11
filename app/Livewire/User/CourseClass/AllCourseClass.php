<?php

namespace App\Livewire\User\CourseClass;

use Excel;
use App\Models\Course;
use App\Models\College;
use Livewire\Component;
use App\Models\Classyear;
use App\Models\Courseclass;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use Illuminate\Support\Facades\Auth;
use App\Exports\User\CourseClass\CourseClassExport;

class AllCourseClass extends Component
{   
    # By Ashutosh
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    #[Locked] 
    public $delete_id;
    #[Locked] 
    public $mode='all';
    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;

    public $nextyearclass_id;
    public $classyear_id;
    public $college_id;
    public $course_id;
    #[Locked] 
    public $colleges;
    #[Locked] 
    public $class_years;
    #[Locked] 
    public $courses;
    #[Locked] 
    public $next_classess=[];
    #[Locked] 
    public $edit_id;


    protected function rules()
    {
        return [
            'college_id' => ['required', 'integer', Rule::exists('colleges', 'id')],
            'course_id' => ['required', 'integer', Rule::exists('courses', 'id')],
            'classyear_id' => ['required', 'integer', Rule::exists('classyears', 'id')],
            'nextyearclass_id' => ['nullable', 'integer', Rule::exists('course_classes', 'id')],
        ];
    }

    public function messages()
    {   
        $messages = [
            'college_id.required' => 'The College is required.',
            'college_id.integer' => 'The College must be a number.',
            'college_id.exists' => 'The selected College is invalid.',

            'course_id.required' => 'The Course is required.',
            'course_id.integer' => 'The Course must be a number.',
            'course_id.exists' => 'The Selected Course is invalid.',
            'course_id.unique' => 'The Combination of College and Course must be unique.',

            'classyear_id.required' => 'The Class Year is required.',
            'classyear_id.integer' => 'The Class Year must be a number.',
            'classyear_id.exists' => 'The selected Class Year is invalid.',

            'nextyearclass_id.required' => 'The Next Year Class is required.',
            'nextyearclass_id.integer' => 'The Next Year Class must be a number.',
            'nextyearclass_id.exists' => 'The selected Next Year Class is'
        ];
        
        return $messages;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function resetinput()
    {
        $this->reset(
            [
                'edit_id',
                'nextyearclass_id',
                'classyear_id',
                'college_id',
                'course_id'
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

    #[Renderless]
    public function export()
    {   
        try 
        {
            set_time_limit(600);
            ini_set('memory_limit', '1024M');

            $filename="Course_Class_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new CourseClassExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new CourseClassExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new CourseClassExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Course Class Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Course Class !!');
        }
    }

    

    public function add()
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {   
            $course_class =  new Courseclass;
            $course_class->create([
                'nextyearclass_id' => $this->nextyearclass_id,
                'classyear_id' => $this->classyear_id,
                'college_id' => $this->college_id,
                'course_id' => $this->course_id,
             ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Course Class Created Successfully !!');

        } catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Course Class !!');
        }
    }



    public function edit(Courseclass $course_class)
    {   
        $this->resetinput();
        $this->edit_id=$course_class->id;
        $this->nextyearclass_id=$course_class->nextyearclass_id;
        $this->classyear_id=$course_class->classyear_id;
        $this->college_id=$course_class->college_id;
        $this->course_id=$course_class->course_id;
        $this->mode='edit';
    }

    public function update(Courseclass $course_class)
    {
        $this->validate();
        
        DB::beginTransaction();

        try 
        {   
            $course_class->update([
                'nextyearclass_id' => $this->nextyearclass_id,
                'classyear_id' => $this->classyear_id,
                'college_id' => $this->college_id,
                'course_id' => $this->course_id,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Course Class Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Course Class !!');
        }
    }

    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }


    public function delete(Courseclass $course_class)
    {  
        DB::beginTransaction();

        try
        {   
            $course_class->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Course Class Soft Deleted Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Course Class !!');
        }
    }
    
    public function restore($id)
    {   
        DB::beginTransaction();

        try
        {   
            $course_class = Courseclass::withTrashed()->find($id);
            $course_class->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Course Class Restored Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Course Class !!');
        }
    }
   

    public function forcedelete()
    {    DB::beginTransaction();
        try 
        {
            $course_class = Courseclass::withTrashed()->find($this->delete_id);
            $course_class->forceDelete();
            $this->delete_id=null;
            DB::commit();
            $this->dispatch('alert',type:'success',message:'Course Class Deleted Successfully !!');
               
        } catch (\Illuminate\Database\QueryException $e) {

            DB::rollBack();
            
            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'error',message:'This record is associated with another data. You cannot delete it !!');
            } 
            else
            {   
                $this->dispatch('alert',type:'error',message:'Failed To Delete Course Class !!');
            }
        }
        
    }

    public function render()
    {   
        $this->next_classess=[];

        if($this->mode!=='all')
        {   
            if( $this->course_id)
            {
                $this->next_classess = Courseclass::with([ 'classyear:id,classyear_name', 'courseclass.classyear:id,classyear_name', 'courseclass.course:id,course_name',])->select('classyear_id', 'course_id', 'nextyearclass_id', 'id')->where('course_id', $this->course_id)->get();
            }
            $this->class_years=Classyear::where('status',1)->pluck('classyear_name','id');
            $this->courses =Course::pluck('course_name','id');
            $this->colleges =College::where('status',1)->pluck('college_name','id');
        }

       $course_classes=Courseclass::where('college_id',Auth::guard('user')->user()->college_id)->with(['classyear:classyear_name,id', 'course:course_name,id', 'courseclass.classyear:classyear_name,id', 'courseclass.course:course_name,id', 'college:college_name,id'])->select('id','course_id','classyear_id','nextyearclass_id', 'college_id','deleted_at')->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.course-class.all-course-calss',compact('course_classes'))->extends('layouts.user')->section('user');
    }
}
