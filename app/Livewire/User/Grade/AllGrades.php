<?php

namespace App\Livewire\User\Grade;

use Excel;
use Livewire\Component;
use App\Models\Gradepoint;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Exports\User\Grade\ExportGrade;

class AllGrades extends Component
{   
    ## By Ashutosh
    use WithPagination;
    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;
    public $max_percentage;
    public $min_percentage;
    public $grade_point;
    public $grade_name;
    public $description;
    public $is_active;
    #[Locked] 
    public $grade_id;
    #[Locked]
    public $mode='all';
    #[Locked] 
    public $delete_id;

    protected function rules()
    {
        return [
        'max_percentage' => ['required',],
        'min_percentage' => ['required'],
        'grade_point' => ['required'],
        'grade_name' => ['required','max:5'],
        'description' => ['nullable','max:50'],
        'is_active' => ['required'],
        ];
    }

    public function messages()
    {   
        $messages = [
            'max_percentage.required' => 'The Maximum Percentage field is required.',
            'min_percentage.required' => 'The Minimum Percentage field is required.',
            'grade_point.required' => 'The Grade Point field is required.',
            'grade_name.required' => 'The Grade Name Field is required',
            'grade_name.max' => 'The Grade must not exceed :max characters.',
            'description.max' => 'The Description must not exceed :max characters.',
        ];
        return $messages;
    }

    public function resetinput()
    {
        $this->reset([
            'max_percentage',
            'min_percentage',
            'grade_point',
            'grade_name',
            'description',
            'is_active',
        ]);  
    }

    public function add()
    { 
        $this->validate();

        DB::beginTransaction();

        try 
        {
            $grade= new Gradepoint;
            $grade->max_percentage= $this->max_percentage;
            $grade->min_percentage= $this->min_percentage;
            $grade->grade_point=  $this->grade_point;
            $grade->grade_name=  $this->grade_name;
            $grade->description= $this->description;
            $grade->is_active= $this->is_active==1?0:1;
            $grade->save();

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Grade Created Successfully !!');

        } catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Grade !!');
        }
    }

    public function edit(Gradepoint $grade ){

        if ($grade) {
            $this->resetinput();
            $this->grade_id=$grade->id;
            $this->max_percentage = $grade->max_percentage;
            $this->min_percentage = $grade->min_percentage;
            $this->grade_point = $grade->grade_point;
            $this->grade_name = $grade->grade_name;
            $this->description = $grade->description;
            $this->is_active = $grade->is_active;          
            $this->mode='edit';
        }
    }

    public function update(Gradepoint  $grade)
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {
                    
            $grade->update([
                              
                'max_percentage' => $this->max_percentage,
                'min_percentage' => $this->min_percentage,               
                'grade_point' => $this->grade_point,
                'grade_name' => $this->grade_name,
                'description' => $this->description,                
                'is_active' => $this->is_active,
                     
            ]);
            DB::commit();

            $this->resetinput();

            $this->mode='all';

            $this->dispatch('alert',type:'success',message:'Grade Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Grade !!');
        }
    }

    
    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }

    public function delete(Gradepoint  $grade)
    {  
        DB::beginTransaction();

        try 
        {
            $grade->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Grade Soft Deleted Successfully !!');

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Grade !!');
        }
    }
    

    public function restore($grade_id)
    {   
       
        DB::beginTransaction();

        try
        {
            $grade = Gradepoint::withTrashed()->findOrFail($grade_id);

            $grade->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Grade Restored Successfully !!');

        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Grade !!');
        }
    }

    public function forcedelete()
    {   

        DB::beginTransaction();

        try 
        { 
            $grade = Gradepoint::withTrashed()->find($this->delete_id);
            $grade->forceDelete();

            $this->delete_id=null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Grade Deleted Successfully !!');

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
                $this->dispatch('alert',type:'error',message:'Failed To Delete Grade !!');
            }
        }
    }

    #[Renderless]
    public function export()
    {   
        try 
        {
            set_time_limit(600);
            ini_set('memory_limit', '1024M');

            $filename="Grade_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new ExportGrade($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new ExportGrade($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new ExportGrade($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }    

            $this->dispatch('alert',type:'success',message:'Grade Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Grade !!');
        }

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

    public function update_status(Gradepoint $grade)
    {
        DB::beginTransaction();

        try 
        {   
            if($grade->is_active)
            {
                $grade->is_active=0;
            }
            else
            {
                $grade->is_active=1;
            }
            $grade->update();

            DB::commit();
            $this->dispatch('alert',type:'success',message:'Status Updated Successfully !!'  );
        }catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Status  !!');
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


    public function render()
    {
        $grades=Gradepoint::select('id','max_percentage','min_percentage','grade_point','grade_name','description','is_active','deleted_at')
        ->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.grade.all-grades',compact('grades'))->extends('layouts.user')->section('user');
    }
}
