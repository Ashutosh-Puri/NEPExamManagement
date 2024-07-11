<?php

namespace App\Livewire\User\User;

use Excel;
use App\Models\Role;
use App\Models\User;
use App\Models\College;
use Livewire\Component;
use App\Models\Department;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Exports\User\User\ExportUser;


class AllUser extends Component
{
    ## By Ashutosh
    use WithPagination;
    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    public $perPage=10;
    public $search='';
    public $sortColumn="name";
    public $sortColumnBy="ASC";
    public $ext;
    public $name;
    public $email;
    public $college_id;
    public $department_id;
    public $password;
    public $user_contact_no;
    public $remember_token;
    public $is_active;
    public $role_id;

    #[Locked] 
    public $user_id;
    #[Locked] 
    public $roles;
    #[Locked] 
    public $departments;
    #[Locked] 
    public $colleges;
    #[Locked] 
    public $mode='all';
    #[Locked] 
    public $delete_id;

    protected function rules()
    {
        return [
        'name' => ['required','string','max:255'],
        'email' => ['required','email'],
        'password'=>['required','min:8'],
        'college_id' => ['required',Rule::exists(College::class,'id')],
        'role_id' => ['required',Rule::exists(Role::class,'id')],
        'department_id' => ['required',Rule::exists(Department::class,'id')],
        'is_active'=>['required'],
        'user_contact_no'=>['required'],
        ];
    }

    public function messages()
    {
        return [
        'name.required' => 'The name field is required.',
        'name.string' => 'The name must be a string.',
        'name.max' => 'The name may not be greater than 255 characters.',
        'email.required' => 'The email field is required.',
        'email.email' => 'Please enter a valid email address.',
        'password.required' => 'The password field is required.',
        'password.min' => 'The password must be at least 8 characters.',
        'college_id.required' => 'The college field is required.',
        'college_id.exists' => 'Please select a valid college.',
        'role_id.required' => 'The role field is required.',
        'role_id.exists' => 'Please select a valid role.',
        'department_id.required' => 'The department field is required.',
        'department_id.exists' => 'Please select a valid department.',
        'is_active.required' => 'The is active field is required.',
        'user_contact_no.required' => 'The contact number field is required.',
        ];
        return $messages;
    }

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function resetinput()
    {
        $this->reset([
            'name',
            'email',
            'password',
            'college_id',
            'department_id',
            'remember_token',
            'user_contact_no',
            'role_id',
            'is_active',
            'user_id'
        ]);
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
            $user = new User;
            $user->name= $this->name;
            $user->email= $this->email;
            $user->password= $this->password;
            $user->remember_token= $this->remember_token;
            $user->college_id= $this->college_id;
            $user->department_id= $this->department_id;
            $user->role_id= $this->role_id;
            $user->is_active= $this->is_active;
            $user->user_contact_no= $this->user_contact_no;
            $user->save();
            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'User Created Successfully !!');

        } catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create User !!');
        }
    }
    
    public function edit(User $user ){
        
        if ($user) {
            $this->resetinput();
            $this->user_id=$user->id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->user_contact_no =$user->user_contact_no;
            $this->college_id = $user->college_id;
            $this->department_id = $user->department_id;          
            $this->role_id=$user->role_id;        
            $this->is_active = $user->is_active;          
            $this->mode='edit';
        }
    }
    
    public function update(User  $user)
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {
                    
            $user->update([
                
                'name' => $this->name,
                'email' => $this->email,               
                'password' => $this->password,
                'user_contact_no' => $this->user_contact_no,
                'college_id' => $this->college_id,                
                'department_id' => $this->department_id,
                'role_id'=>$this->role_id,
                'is_active' => $this->is_active,
                
            ]);  

            DB::commit();

            $this->resetinput();

            $this->mode='all';

            $this->dispatch('alert',type:'success',message:'User Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update User !!');
        }
    }
  
    
    
    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }
    
    
    public function delete(User  $user)
    {  
        DB::beginTransaction();

        try 
        {
            $user->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'User Soft Deleted Successfully !!');

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete User !!');
        }
    }

    public function restore($user_id)
    {   
       
        DB::beginTransaction();

        try
        {
            $user = User::withTrashed()->findOrFail($user_id);

            $user->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'User Restored Successfully !!');

        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore User !!');
        }
    }

    public function forcedelete()
    {   

        DB::beginTransaction();

        try 
        { 
            $user = User::withTrashed()->find($this->delete_id);
            $user->forceDelete();

            $this->delete_id=null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'User Deleted Successfully !!');

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
                $this->dispatch('alert',type:'error',message:'Failed To Delete User !!');
            }
        }
    }
 

    public function update_status(User $user)
    {
        DB::beginTransaction();

        try 
        {   
            if($user->is_active)
            {
                $user->is_active=0;
            }
            else
            {
                $user->is_active=1;
            }
            $user->update();

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

            $filename="User_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    return Excel::download(new ExportUser($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                    break;
                    case 'csv':
                        return Excel::download(new ExportUser($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                        break;
                        case 'pdf':
                    return Excel::download(new ExportUser($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }  

            $this->dispatch('alert',type:'success',message:'User Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export User !!');
        }

    }
    

    public function render()
    {    
        if($this->mode!=='all')
        {   
            $this->colleges=College::where('status',1)->pluck('college_name','id');
            $this->departments=Department::where('status',1)->pluck('dept_name','id');
            $this->roles=Role::pluck('role_name','id');
        }

        $users=User::with('college:college_name,id','role:role_name,id','department')->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.user.all-user',compact('users'))->extends('layouts.user')->section('user');
    }
}
