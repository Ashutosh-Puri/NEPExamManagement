<?php

namespace App\Livewire\User\RoleType;

use Livewire\Component;
use App\Models\Roletype;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\User\RoleType\RoleTypeExport;

class AllRoleType extends Component
{   
    ### By Ashutosh
    use WithPagination;
    protected $listeners = ['delete-confirmed'=>'delete'];

    public $roletype_name;

    #[Locked]
    public $roletype_id;
    #[Locked]
    public $delete_id;

    public $perPage=10;
    public $search='';
    public $sortColumn="roletype_name";
    public $sortColumnBy="ASC";
    #[Locked]
    public $mode='all';
    public $ext;

    protected function rules()
    {
        return [
            'roletype_name' => ['required', 'string', 'max:255',],
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function resetinput()
    {
        $this->reset([
            'roletype_name',
            'roletype_id',
        ]);

    }

    public function messages()
    {
        return [
        'roletype_name.required' => 'The role type name field is required.',
        'roletype_name.string' => 'The role type name must be a string.',
        'roletype_name.max' => 'The role type name must not exceed 255 characters.',
        ];
    }

    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
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

    public function save()
    {   
        $validatedData = $this->validate();

        DB::beginTransaction();

        try 
        {
            $roletype = Roletype::create($validatedData);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Role Type Added Successfully');

        } catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Add Role Type. Please try again.');
        }
    }

    public function edit(Roletype $roletype)
    {
        if ($roletype){
            $this->roletype_id = $roletype->id;
            $this->roletype_name= $roletype->roletype_name;
        }else{
            $this->dispatch('alert',type:'error',message:'Role Type Details Not Found');
        }
        $this->mode='edit';
    }

    public function update(Roletype $roletype)
    {
        $validatedData = $this->validate();

        DB::beginTransaction();
        try 
        {
            $roletype->update($validatedData);

            DB::commit();

            $this->resetinput();

            $this->mode='all';

            $this->dispatch('alert',type:'success',message:'Role Type Updated Successfully');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Error To Update Role Type');
        }
    }


    public function delete()
    {   
        DB::beginTransaction();
        try
        {
            $roletype = Roletype::withTrashed()->find($this->delete_id);
            $roletype->forceDelete();
            $this->delete_id = null;
            DB::commit();
            $this->dispatch('alert',type:'success',message:'Role Type Deleted Successfully !!');
        } catch (\Illuminate\Database\QueryException $e) {

            DB::rollBack();

            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'error',message:'This record is associated with another data. You cannot delete it !!');
            }else
            {   
                $this->dispatch('alert',type:'error',message:'Failed To Delete Role Type !!');
            }
        }
    }

    public function softdelete(Roletype $roletype)
    {   
       
        DB::beginTransaction();

        try 
        {
            $roletype->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Role Type Soft Deleted Successfully !!');

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Role Type !!');
        }
    }
 

    public function restore($id)
    {   
       
        DB::beginTransaction();

        try
        {
            $roletype = Roletype::withTrashed()->find($id);
            if($roletype)
            {
                $roletype->restore();
            }

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Role Type Restored Successfully');

        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Role Type Not Found');
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

            $filename="Role_Type_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new RoleTypeExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new RoleTypeExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new RoleTypeExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Role Type Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            \Log::error($e);

            $this->dispatch('alert',type:'error',message:'Failed To Export Role Type !!');
        }

    }


    public function update_status(Roletype $roletype)
    {
        DB::beginTransaction();

        try 
        {   
            if($roletype->status)
            {
                $roletype->status=0;
            }
            else
            {
                $roletype->status=1;
            }
            $roletype->update();

            DB::commit();
            $this->dispatch('alert',type:'success',message:'Status Updated Successfully !!'  );
        }catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Status  !!');
        }
    }

    public function view(Roletype $roletype)
    {
        if ($roletype){
            $this->roletype_name= $roletype->roletype_name;
        }else{
            $this->dispatch('alert',type:'error',message:'Role Type Details Not Found');
        }
        $this->setmode('view');
    }


    public function render()
    {
        $roletypes = Roletype::when($this->search, function($query, $search){
            $query->search($search);
        })->orderBy($this->sortColumn, $this->sortColumnBy)->withTrashed()->paginate($this->perPage);
        return view('livewire.user.roletype.all-roletype',compact('roletypes'))->extends('layouts.user')->section('user');
    }
}
