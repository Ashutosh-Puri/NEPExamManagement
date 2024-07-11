<?php

namespace App\Livewire\User\Role;

use App\Models\Role;
use App\Models\College;
use Livewire\Component;
use App\Models\Roletype;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\User\Role\RoleExport;

class AllRole extends Component
{   
    ### By Ashutosh
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'delete'];
    public $role_name;
    public $roletype_id;
    public $college_id;
    public $roletypes;
    #[Locked] 
    public $colleges;


    #[Locked]
    public $role_id;
    #[Locked]
    public $delete_id;

    public $perPage=10;
    public $search='';
    #[Locked] 
    public $mode='all';
    public $sortColumn="role_name";
    public $sortColumnBy="ASC";
    public $ext;

    protected function rules()
    {
        return [
            'role_name' => ['required', 'string', 'max:255',],
            'roletype_id' => ['required',Rule::exists(Roletype::class,'id')],
            'college_id' => ['required',Rule::exists(College::class,'id')],
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function resetinput()
    {
        $this->reset(
            [
                'role_id',
                'role_name',
                'roletype_id',
                'college_id',
                
            ]
        );
    }

    public function messages()
    {
        return [
            'role_name.required' => 'The role name field is required.',
            'role_name.string' => 'The role name must be a string.',
            'role_name.max' => 'The role name must not exceed 255 characters.',
            'roletype_id.required' => 'The role type field is required.',
            'roletype_id.exists' => 'The selected role type is invalid.',
            'college_id.required' => 'The college field is required.',
            'college_id.exists' => 'The selected college is invalid.',
        ];
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

    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }

    public function save()
    {  
        $validatedData = $this->validate();
    
        DB::beginTransaction();
    
        try 
        {   
            $role = Role::create($validatedData);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Role Created Successfully !!');

        } catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Role !!');
        }
    }

    public function edit(Role $role)
    {
        if ($role){
            $this->role_id = $role->id;
            $this->role_name= $role->role_name;
            $this->roletype_id= $role->roletype_id;
            $this->college_id= $role->college_id;
        }else{
            $this->dispatch('alert',type:'error',message:'Role Details Not Found');
        }
        $this->mode='edit';
    }

    public function update(Role $role)
    {
        $validatedData = $this->validate();

        DB::beginTransaction();

        try 
        {   
            $role->update($validatedData);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Role Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Role !!');
        }
    }

    public function delete()
    {   
        
        DB::beginTransaction();
        try
        {
            $role = Role::withTrashed()->find($this->delete_id);
            $role->forceDelete();
            $this->delete_id = null;
            DB::commit();
            $this->dispatch('alert',type:'success',message:'Role Deleted Successfully !!');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'error',message:'This record is associated with another data. You cannot delete it !!');
            }else
            {   
                $this->dispatch('alert',type:'error',message:'Failed To Delete Role !!');
            }
        }
    }

    public function softdelete(Role $role)
    {  
        DB::beginTransaction();

        try
        {   
            $role->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Role Soft Deleted Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Role !!');
        }
    }

    public function restore($id)
    {   
        DB::beginTransaction();

        try
        {   
            $role = Role::withTrashed()->find($id);
            $role->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Role Restored Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Role !!');
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

    public function updatedSearch()
    {
        $this->resetPage();
    }

    
    #[Renderless]
    public function export()
    {   
        try 
        {
            set_time_limit(600);
            ini_set('memory_limit', '1024M');

            $filename="Role_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                     $response = Excel::download(new RoleExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                     $response = Excel::download(new RoleExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                     $response = Excel::download(new RoleExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Role Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Role !!');
        }
    }

    public function view(Role $role)
    {
        if ($role)
        {
            $this->role_name= $role->role_name;
            $this->roletype_id = isset($role->roletype->roletype_name) ? $role->roletype->roletype_name : '';
            $this->college_id = isset($role->college->college_name) ? $role->college->college_name : '';
            $this->setmode('view');
        }else{
            $this->dispatch('alert',type:'error',message:'Something Went Wrong !!');
        }
    }

    public function render()
    {
        if($this->mode !== 'all')
        {
            $this->roletypes = Roletype::where('status',1)->pluck('roletype_name','id');
            $this->colleges= College::where('status',1)->pluck('college_name','id');
        }

        $roles = Role::with('roletype','college')->when($this->search, function($query, $search){
            $query->search($search);
        })->orderBy($this->sortColumn, $this->sortColumnBy)->withTrashed()->paginate($this->perPage);
        return view('livewire.user.role.all-role' ,compact('roles'))->extends('layouts.user')->section('user');
    }
}
