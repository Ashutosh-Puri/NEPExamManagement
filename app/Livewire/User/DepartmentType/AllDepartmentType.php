<?php

namespace App\Livewire\User\DepartmentType;

use Excel;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Departmenttype;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Exports\User\DepartmentType\ExportDepartmentType;

class AllDepartmentType extends Component
{   
    ## By Ashutosh
    use WithPagination;
    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;
    public $departmenttype;
    public $description;
    public $status;

    #[Locked] 
    public $mode='all';
    #[Locked] 
    public $delete_id;
    #[Locked] 
    public $dept_id;

    protected function rules()
    {
        return [
        'departmenttype' => ['required','string','max:255'],
        'description' => ['nullable','string','max:255'],
        'status' => ['required'],
        ];
    }

    public function messages()
    {   
        $messages = [
        'departmenttype.required' => 'The department type is required.',
        'departmenttype.string' => 'The department type must be a string.',
        'departmenttype.max' => 'The department type may not be greater than 255 characters.',
        'description.string' => 'The description must be a string.',
        'description.max' => 'The description may not be greater than 255 characters.',
        'status.required' => 'The status is required.',
        ];
        return $messages;
    }

    public function resetinput()
    {
        $this->reset([
            'departmenttype',
            'description',
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
            $dept = new Departmenttype;
            $dept->departmenttype = $this->departmenttype;
            $dept->description = $this->description;
            $dept->status = $this->status;
            
            $dept->save();

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Department Type Created Successfully !!');

        } catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Department Type !!');
        }
    }


    public function edit(Departmenttype $dept){

        if ($dept) {
            $this->resetinput();
            $this->dept_id=$dept->id;
            $this->departmenttype = $dept->departmenttype ;
            $this->description = $dept->description ;
            $this->status = $dept->status ;
            $this->mode='edit';
        }
    }

    public function update(Departmenttype  $dept)
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {
            $dept->departmenttype= $this->departmenttype;
            $dept->description= $this->description;
            $dept->status= $this->status;
            $dept->update();

            DB::commit();

            $this->resetinput();

            $this->mode='all';

            $this->dispatch('alert',type:'success',message:'Department Type Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Department Type!!');
        }
    }

    public function deleteconfirmation($dept_id)
    {
        $this->delete_id=$dept_id;
        $this->dispatch('delete-confirmation');
    }

    public function delete(Departmenttype  $dept)
    {   
        DB::beginTransaction();

        try 
        {
            $dept->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Department Type Soft Deleted Successfully !!');

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Department Type !!');
        }
    }

    public function restore($dept_id)
    {   
       
        DB::beginTransaction();

        try
        {
            $dept = Departmenttype::withTrashed()->findOrFail($dept_id);

            $dept->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Department Type Restored Successfully !!');

        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Department Type !!');
        }
    }

    public function forcedelete()
    {   

        DB::beginTransaction();

        try 
        {  
            $dept = Departmenttype::withTrashed()->find($this->delete_id);
            $dept->forceDelete();

            $this->delete_id=null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Department Type Deleted Successfully !!');

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
                $this->dispatch('alert',type:'error',message:'Failed To Delete Department Type !!');
            }
        }
    }



    public function update_status(Departmenttype $dept)
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

            $filename="Departmenttype_".now();

            $response = null;

            switch ($this->ext) {
            case 'xlsx':
                $response = Excel::download(new ExportDepartmentType($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
            break;
            case 'csv':
                $response = Excel::download(new ExportDepartmentType($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
            break;
            case 'pdf':
                $response = Excel::download(new ExportDepartmentType($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
            break;
        }  

            $this->dispatch('alert',type:'success',message:'Department Type Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Department Type !!');
        }

    }

    public function render()
    {
        $departmenttypes=Departmenttype::select('id','departmenttype','description','status','deleted_at')
        ->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.department-type.all-department-type',compact('departmenttypes'))->extends('layouts.user')->section('user');
    }
}
