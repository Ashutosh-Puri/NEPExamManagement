<?php

namespace App\Livewire\User\Exambody;

use Excel;
use App\Models\Role;
use App\Models\College;
use App\Models\Faculty;
use Livewire\Component;
use App\Models\Exambody;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Exports\User\Exambody\ExportExambody;

class AllExamBody extends Component
{   
    ## By Ashutosh
    use WithPagination;
    use WithFileUploads;
    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    #[Locked]
    public $mode='all';
    public $per_page = 10;
    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;

    public $faculty_id;
    #[Locked]
    public $faculties;
    public $college_id;
    #[Locked]
    public $colleges;
    public $role_id;
    #[Locked]
    public $roles;
    public $is_active;
    #[Locked]
    public $exambody_id;
    #[Locked]
    public $delete_id;

    public $profile_photo_path;
    public $profile_photo_path_old;
    public $sign_photo_path;
    public $sign_photo_path_old;
    public $can_update=0;

    protected function rules()
    {
        return [
            'college_id' => ['required', 'integer', Rule::exists('colleges', 'id')],
            'faculty_id' => ['required', 'integer', Rule::exists('faculties', 'id')],
            'role_id' => ['required', 'integer', Rule::exists('roles', 'id')],
            'profile_photo_path' =>[$this->can_update==1?'nullable':'required','max:250','mimes:png,jpg,jpeg'],
            'sign_photo_path' => [$this->can_update==1?'nullable':'required','max:50','mimes:png,jpg,jpeg'],
        ];
    }

    public function messages()
    {   
        $messages = [
            'college_id.required' => 'Please select a college.',
            'college_id.integer' => 'The college ID must be an integer.',
            'college_id.exists' => 'The selected college does not exist.',
            
            'faculty_id.required' => 'Please select a faculty.',
            'faculty_id.integer' => 'The faculty ID must be an integer.',
            'faculty_id.exists' => 'The selected faculty does not exist.',
            
            'role_id.required' => 'Please select a role.',
            'role_id.integer' => 'The role ID must be an integer.',
            'role_id.exists' => 'The selected role does not exist.',
            
            'profile_photo_path.required' => 'Please upload a profile photo.',
            'profile_photo_path.max' => 'The profile photo may not be greater than :max kilobytes.',
            'profile_photo_path.mimes' => 'The profile photo must be a file of type: png, jpg, jpeg.',
            
            'sign_photo_path.required' => 'Please upload a signature photo.',
            'sign_photo_path.max' => 'The signature photo may not be greater than :max kilobytes.',
            'sign_photo_path.mimes' => 'The signature photo must be a file of type: png, jpg, jpeg.',
            
        ];
        
        return $messages;
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

    public function resetInput()
    {
        $this->reset(
            [
                'faculty_id',
                'college_id',
                'role_id',
                'is_active',
                'profile_photo_path',
                'sign_photo_path'
            ]
        );
    }

    private function deactivateRecords($role_id)
    {
        Exambody::where('role_id', $role_id)->where('is_active', 1)->update(['is_active' => 0]);
    }

    
    public function add()
    {   
     
        $this->validate();

        DB::beginTransaction();
        
        try 
        {  
            $exambody = new Exambody;
            $exambody->college_id= $this->college_id;
            $exambody->user_id= Auth::guard('user')->user()->id;
            $exambody->faculty_id= $this->faculty_id;
            $exambody->role_id= $this->role_id;
            $exambody->is_active= $this->is_active;

            if ($this->profile_photo_path) {
                if ($exambody->profile_photo_path) {
                    File::delete($exambody->profile_photo_path);
                }
                $path = 'user/exam_body/photo/';
                $fileName = 'exam_body_'.$this->faculty_id.'_' . time(). '.' . $this->profile_photo_path->getClientOriginalExtension();
                $this->profile_photo_path->storeAs($path, $fileName, 'public');
                $exambody->profile_photo_path = 'storage/' . $path . $fileName;
                $this->reset('profile_photo_path');
            }

            if ($this->sign_photo_path) {
                if ($exambody->sign_photo_path) {
                    File::delete($exambody->sign_photo_path);
                }
                $path = 'user/exam_body/sign/';
                $fileName = 'exam_body_'.$this->faculty_id.'_'.time(). '.' . $this->sign_photo_path->getClientOriginalExtension();
                $this->sign_photo_path->storeAs($path, $fileName, 'public');
                $exambody->sign_photo_path = 'storage/' . $path . $fileName;
                $this->reset('sign_photo_path');
            }

            $this->deactivateRecords($exambody->role_id);

            $exambody->save();

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Exam Body Created Successfully !!');

        } catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Exam Body !!');
        }
    }

    public function edit(Exambody $exambody)
    {
        if(isset($exambody->sign_photo_path) && isset($exambody->profile_photo_path))
        {   
            if(file_exists($exambody->sign_photo_path) && file_exists($exambody->profile_photo_path)){

                $this->can_update=1;
            }
        }
        $this->resetinput();
        $this->exambody_id=$exambody->id;
        $this->college_id = $exambody->college_id;
        $this->faculty_id = $exambody->faculty_id;
        $this->role_id = $exambody->role_id;
        $this->is_active = $exambody->is_active;
        $this->profile_photo_path_old=$exambody->profile_photo_path;
        $this->sign_photo_path_old=$exambody->sign_photo_path;
        $this->mode='edit';
    }

    public function update(Exambody $exambody)
    {   
        $this->validate();
    
        DB::beginTransaction();
    
        try 
        {
            $currentIsActive = $exambody->is_active;
    
            $otherAttributes = [
                'college_id' => $exambody->college_id,
                'faculty_id' => $exambody->faculty_id,
                'role_id' => $exambody->role_id,
                'is_active' => $currentIsActive,
            ];
    
            if ($this->profile_photo_path) {
                if ($exambody->profile_photo_path) {
                    File::delete($exambody->profile_photo_path);
                }
                $path = 'user/exam_body/photo/';
                $fileName = 'exam_body_'.$this->faculty_id.'_' . time(). '.' . $this->profile_photo_path->getClientOriginalExtension();
                $this->profile_photo_path->storeAs($path, $fileName, 'public');
                $exambody->profile_photo_path = 'storage/' . $path . $fileName;
                $this->reset('profile_photo_path');
            }

            if ($this->sign_photo_path) {
                if ($exambody->sign_photo_path) {
                    File::delete($exambody->sign_photo_path);
                }
                $path = 'user/exam_body/sign/';
                $fileName = 'exam_body_'.$this->faculty_id.'_'.time(). '.' . $this->sign_photo_path->getClientOriginalExtension();
                $this->sign_photo_path->storeAs($path, $fileName, 'public');
                $exambody->sign_photo_path = 'storage/' . $path . $fileName;
                $this->reset('sign_photo_path');
            }
    
            $exambody->update($otherAttributes);
    
            DB::commit();
    
            $this->resetinput();
            $this->mode='all';
            $this->dispatch('alert', type:'success', message:'Exam Body Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();
            $this->dispatch('alert', type:'error', message:'Failed to Update Exam Body !!');
        }
    }
    
    public function status(Exambody $exambody)
    {
        
        $this->deactivateRecords($exambody->role_id);

        if($exambody->is_active)
        {
            $exambody->is_active=0;
        }
        else
        {
            $exambody->is_active=1;
        }
        $exambody->update();
        $this->dispatch('alert',type:'success',message:'Status Updated Successfully !!'  );
    }

    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }

    public function delete(Exambody  $exambody)
    {  
        DB::beginTransaction();

        try
        {   
            $exambody->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Exam Body  Soft Deleted Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Exam Body  !!');
        }
    }

    public function restore($id)
    {   
        DB::beginTransaction();

        try
        {   
            $exambody = Exambody::withTrashed()->find($id);
            $exambody->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Exam Body  Restored Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Exam Body  !!');
        }
    }

    public function forcedelete()
    {   
        DB::beginTransaction();
        try 
        {
            $exambody = Exambody::withTrashed()->find($this->delete_id);

            if ($exambody->profile_photo_path) {
                File::delete($exambody->profile_photo_path);
            }

            if ($exambody->sign_photo_path) {
                File::delete($exambody->sign_photo_path);
            }

            $exambody->forceDelete();
            $this->delete_id=null;
            DB::commit();
            $this->dispatch('alert',type:'success',message:'Exam Body  Deleted Successfully !!');
            
        } catch (\Illuminate\Database\QueryException $e) 
        {
            DB::rollBack();

            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'error',message:'This record is associated with another data. You cannot delete it !!');
            } 
            else
            {   
                $this->dispatch('alert',type:'error',message:'Failed To Delete Exam Body  !!');
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

            $filename="Exambody_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                     $response = Excel::download(new ExportExambody($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new ExportExambody($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new ExportExambody($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Exam Body  Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Exam Body  !!');
        }
    }


    public function render()
    {
        $this->colleges=College::where('status',1)->pluck('college_name','id');
        $this->faculties=Faculty::where('active',1)->pluck('faculty_name','id');
        $this->roles=Role::pluck('role_name','id');

        $exambody=Exambody::select('id','faculty_id','role_id','user_id','college_id','profile_photo_path','sign_photo_path','is_active','deleted_at')
        ->with(['user:name,id','college:college_name,id','faculty:faculty_name,id','role:role_name,id'])
        ->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);
        return view('livewire.user.exambody.all-exam-body',compact('exambody'))->extends('layouts.user')->section('user');
    
    }
}
