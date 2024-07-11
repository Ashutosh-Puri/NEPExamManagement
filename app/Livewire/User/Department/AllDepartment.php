<?php

namespace App\Livewire\User\Department;

use Excel;
use App\Models\College;
use Livewire\Component;
use App\Models\Department;
use Livewire\WithPagination;
use App\Models\Departmenttype;
use App\Models\Departmenttypes;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use App\Exports\User\Department\ExportDepartment;

class AllDepartment extends Component
{   
    ## By Ashutosh
    use WithPagination;
    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;
    public $dept_name;
    public $short_name;
    public $departmenttype_ids=[];
    public $college_id;
    public $status;
    
    #[Locked] 
    public $departmenttypes=[];
    #[Locked] 
    public $mode='all';
    #[Locked] 
    public $colleges;
    #[Locked] 
    public $dept_id;
    #[Locked] 
    public $delete_id;

    
    protected function rules()
    {
        return [
        'dept_name' => ['required','string','max:50'],
        'short_name' => ['required','string','max:50'],
        'departmenttype_ids' => ['required'],
        'college_id' => ['required',Rule::exists('colleges', 'id')],
        'status' => ['required'],
        ];
    }

    public function messages()
    {   
        $messages = [
            'dept_name.required' => 'The department name is required.',
            'dept_name.string' => 'The department name must be a string.',
            'dept_name.max' => 'The department name may not be greater than 50 characters.',
            'short_name.required' => 'The short name is required.',
            'short_name.string' => 'The short name must be a string.',
            'short_name.max' => 'The short name may not be greater than 50 characters.',
            'departmenttype_ids.required' => 'The department type is required.',
            'departmenttype_ids.string' => 'The department type must be a string.',
            'departmenttype_ids.max' => 'The department type may not be greater than 255 characters.',
            'college_id.required' => 'The college ID is required.',
            'college_id.exists' => 'The selected college ID is invalid.',
            'status.required' => 'The status is required.',
        ];
        return $messages;
    }

    public function resetinput()
    {
        $this->reset([
            'dept_name',
            'short_name',
            'departmenttype_ids',
            'college_id',
            'status',
            'dept_id',
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
    
    public function add()
    {      
        $this->validate();

        DB::beginTransaction();

        try 
        {   
            $dept = new Department;
            $dept->dept_name = $this->dept_name;
            $dept->short_name = $this->short_name;
            $dept->college_id = $this->college_id;
            $dept->status = $this->status;
            $dept->save();
            $dept->departmenttypes()->sync($this->departmenttype_ids);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Department Created Successfully !!');

        } catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Department !!');
        }
    }

    public function edit(Department $dept)
    {   
        if ($dept) 
        {
            $this->resetinput();
            $this->dept_id=$dept->id;
            $this->dept_name = $dept->dept_name;     
            $this->short_name = $dept->short_name;
            $this->college_id = $dept->college_id ;
            $this->status = $dept->status ;
            $departmenttype_ids = $dept->departmenttypes()->pluck('departmenttype_id')->toArray();
            $this->departmenttype_ids = array_map('strval', $departmenttype_ids);
            $this->mode='edit';
        }
    }

    public function update(Department  $dept)
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {

            $dept->dept_name= $this->dept_name;
            $dept->short_name= $this->short_name;
            $dept->college_id= $this->college_id;
            $dept->status= $this->status;
            $dept->update();

            $dept->departmenttypes()->sync($this->departmenttype_ids);

            DB::commit();

            $this->resetinput();

            $this->mode='all';

            $this->dispatch('alert',type:'success',message:'Department Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to UpdateDepartment !!');
        }
    }
    
    public function deleteconfirmation($dept_id)
    {
        $this->delete_id=$dept_id;
        $this->dispatch('delete-confirmation');
    }
      
    public function delete(Department  $dept)
    {   
        DB::beginTransaction();

        try 
        {
            $dept->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Department Soft Deleted Successfully !!');

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Department !!');
        }
    }
    
    public function restore($dept_id)
    {   
       
        DB::beginTransaction();

        try
        {
            $dept = Department::withTrashed()->findOrFail($dept_id);
            $dept->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Department Restored Successfully !!');

        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Department !!');
        }
    }

    public function forcedelete()
    {   
        DB::beginTransaction();

        try 
        {   $dept = Department::withTrashed()->find($this->delete_id);
            $dept->forceDelete();

            $this->delete_id=null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Department Deleted Successfully !!');

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
                $this->dispatch('alert',type:'error',message:'Failed To Delete Department !!');
            }
        }
    }
 

    public function update_status(Department $dept)
    {
        DB::beginTransaction();

        try 
        {   
            if($dept->status)
            {
                $dept->status=0;
            }
            else
            {
                $dept->status=1;
            }
            $dept->update();

            DB::commit();
            $this->dispatch('alert',type:'success',message:'Status Updated Successfully !!'  );
        }catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Status  !!');
        }
    }
    
    #[Renderless]
    public function export()
    {   
        try 
        {
            set_time_limit(600);
            ini_set('memory_limit', '1024M');

            $filename="Department_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new ExportDepartment($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new ExportDepartment($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new ExportDepartment($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }     
            $this->dispatch('alert',type:'success',message:'Admission Data Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Admission Data !!');
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
        if($this->mode!=='all')
        {   
            $this->colleges=College::where('status',1)->pluck('college_name','id');
            $this->departmenttypes=Departmenttype::where('status',1)->pluck('departmenttype','id');
        }
        
        $departments=Department::select('id','dept_name','short_name','college_id','status','deleted_at')
       ->with('college:college_name,id')
        ->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.department.all-department',compact('departments'))->extends('layouts.user')->section('user');
    }
}
