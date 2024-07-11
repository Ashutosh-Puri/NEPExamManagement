<?php

namespace App\Livewire\User\EducationalCourse;

use Excel;
use Livewire\Component;
use App\Models\Programme;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use App\Models\Educationalcourse;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Exports\User\EducationalCourse\ExportEducationalCourse;


class AllEducationalCourse extends Component
{
    # By Ashutosh
    use WithPagination;
    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;
    public $programme_id;
    public $course_name;
    public $is_active;
    public $steps=1;
    public $current_step=1;
    
    #[Locked] 
    public $mode='all';
    #[Locked] 
    public $programs;
    #[Locked] 
    public $course_id;
    #[Locked] 
    public $delete_id;

    protected function rules()
    {
        return [
        'course_name' => ['required','string','max:255'],
        'programme_id' => ['required',Rule::exists('programmes', 'id')],    
        ];
    }

    public function messages()
    {   
        $messages = [
            'course_name.string' => 'The course name must be a string.',
            'course_name.max' => 'The course name may not be greater than 255 characters.',
            'programme_id.required' => 'The programme ID is required.',
            'programme_id.exists' => 'The selected programme ID is invalid.',
           
        ];
        return $messages;
    }

    public function resetinput()
    {
        $this->reset([
            'course_name',
            'programme_id',
            'is_active',
        ]);     
    }

    public function updated($property)
    {
        $this->validateOnly($property);
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

    public function add(Educationalcourse  $educationalCourse )
    {   
       
        $this->validate();

        DB::beginTransaction();

        try 
        {
            $educationalCourse->course_name= $this->course_name;
            $educationalCourse->programme_id=  $this->programme_id;
            $educationalCourse->is_active=  $this->is_active;
            $educationalCourse->save();

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Educational Course Created Successfully !!');

        } catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Create Educational Course !!');
        }
    }
  
    public function status(Educationalcourse $educationalCourse)
    {
        DB::beginTransaction();

        try 
        {   
            if($educationalCourse->is_active)
            {
                $educationalCourse->is_active=0;
            }
            else
            {
                $educationalCourse->is_active=1;
            }
            $educationalCourse->update();

            DB::commit();
            $this->dispatch('alert',type:'success',message:'Status Updated Successfully !!'  );
        }catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Status  !!');
        }
    }

    public function edit(Educationalcourse $educationalCourse){

        if ($educationalCourse) {
            $this->course_id=$educationalCourse->id;
            $this->course_name = $educationalCourse->course_name;     
            $this->programme_id = $educationalCourse->programme_id;
            $this->is_active = $educationalCourse->is_active ;
            $this->mode='edit';
        }
    }

    public function update(Educationalcourse  $educationalCourse)
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {
            $educationalCourse->course_name= $this->course_name;
            $educationalCourse->programme_id= $this->programme_id;
            $educationalCourse->is_active= $this->is_active;

            $educationalCourse->update();

            DB::commit();

            $this->resetinput();

            $this->mode='all';

            $this->dispatch('alert',type:'success',message:'Educational Course Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Educational Course !!');
        }
    }
  

    public function deleteconfirmation($educationalcourse_id)
    {   
        $this->delete_id=$educationalcourse_id;

        $this->dispatch('delete-confirmation');
    }

    public function delete(Educationalcourse  $educationalCourse)
    {         
        DB::beginTransaction();

        try 
        {
            $educationalCourse->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Educational Course Soft Deleted Successfully !!');

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Educational Course !!');
        }
    }

    public function restore($educationalcourse_id)
    {   
       
        DB::beginTransaction();

        try
        {
            $educationalCourse = Educationalcourse::withTrashed()->findOrFail($educationalcourse_id);

            $educationalCourse->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Educational Course Restored Successfully !!');

        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Educational Course !!');
        }
    }

    public function forcedelete()
    {   

        DB::beginTransaction();

        try 
        {  
            $educationalCourse = Educationalcourse::withTrashed()->find($this->delete_id);
            $educationalCourse->forceDelete();

            $this->delete_id=null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Educational Course Deleted Successfully !!');

        } 
        catch (\Illuminate\Database\QueryException $e) 
        {
            DB::rollBack();

            if ($e->errorInfo[1] == 1451) 
            {
                $this->dispatch('alert',type:'info',message:'This Record Is Associated With Another Data. You Cannot Delete It !!');
            } 
            else
            {   
                $this->dispatch('alert',type:'error',message:'Failed To Delete Educational Course !!');
            }
        }
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

            $filename="Educational_Courses_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new ExportEducationalCourse($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new ExportEducationalCourse($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new ExportEducationalCourse($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Educational Course Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            \Log::error($e);

            $this->dispatch('alert',type:'error',message:'Failed To Export Educational Course !!');
        }

    }


  
    public function render()
    {

        if($this->mode!=='all')
        {   
            $this->programs = Programme::where('active', 1)->pluck('programme_name', 'id');
        }

        $educationalCourses = Educationalcourse::select('id', 'course_name', 'programme_id', 'is_active', 'deleted_at')
        ->when($this->search, function ($query, $search) { $query->search($search); })
        ->with(['programme:id,programme_name'])
        ->withTrashed()
        ->orderBy($this->sortColumn, $this->sortColumnBy)
        ->paginate($this->perPage);

        return view('livewire.user.educational-course.all-educational-course',compact('educationalCourses'))->extends('layouts.user')->section('user');
    }
}
