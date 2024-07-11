<?php

namespace App\Livewire\User\FacultyRole;

use App\Models\Role;
use App\Models\Faculty;
use Livewire\Component;
use App\Models\Facultyrole;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AllFacultyRole extends Component
{   
    # By Ashutosh
    
    use WithPagination;
    #[Locked]
    public $roles=[];
    public $faculty_roles=[];
    
    #[Locked]
    public $faculty_name;
    #[Locked]
    public $faculty_id;

    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $mode='all';
    public $ext;


    public function edit(Faculty $faculty)
    {   

        $this->faculty_roles=[];
        $this->faculty_id= $faculty->id;
        $this->faculty_name=$faculty->faculty_name;
        $roles=$faculty->roles->pluck('id');
        foreach ( $roles as $key => $role_id) {
            $this->faculty_roles[$role_id]=true;
        }

        $this->setmode('edit');
    }


    public function update(Faculty $faculty)
    {   

        DB::beginTransaction();

        try 
        {
            $user_id = Auth::guard('user')->user()->id;
            $role_ids = array_keys(array_filter($this->faculty_roles));
            $valid_role_ids = array_filter($role_ids, 'is_int');
            $pivotData = [];
            foreach ($valid_role_ids as $role_id) 
            {
                $pivotData[$role_id] = [
                    'status' => 1,
                    'user_id' => $user_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $faculty->roles()->sync($pivotData);

            DB::commit();
            $this->dispatch('alert',type:'success',message:'Faculty Roles Updated Successfully !!');
            $this->faculty_roles = [];
            $this->faculty_id = null;

        } catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'success',message:'Failed To Updated Faculty Roles !!');
        }

        $this->setMode('all');
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


    public function status(Faculty $faculty)
    {
        DB::beginTransaction();

        try 
        {   
            if($faculty->active)
            {
                $faculty->active=0;
            }
            else
            {
                $faculty->active=1;
            }
            $faculty->update();

            DB::commit();
            $this->dispatch('alert',type:'success',message:'Status Updated Successfully !!'  );
        }catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Status  !!');
        }
    }

    public function render()
    {   
        if($this->mode=='edit')
        {
            $this->roles=Role::all();
        }
        
        $faculties = Faculty::when($this->search, function($query, $search){
            $query->search($search);
        })->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);
        return view('livewire.user.faculty-role.all-faculty-role',compact('faculties'))->extends('layouts.user')->section('user');
    }

}
