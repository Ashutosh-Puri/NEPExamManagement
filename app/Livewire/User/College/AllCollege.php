<?php

namespace App\Livewire\User\College;

use Excel;
use App\Models\College;
use App\Models\Sanstha;
use Livewire\Component;
use App\Models\University;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use Illuminate\Support\Facades\File;
use App\Exports\User\College\CollegeExport;
use App\Exports\User\College\ExportCollege;


class AllCollege extends Component
{   
    ## By Ashutosh
    use WithFileUploads;
    use WithPagination;
    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    
    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;
    public $steps=1;
    public $current_step=1;
    public $college_name;
    public $principal_name;
    public $college_name_marathi;
    public $college_address;
    public $college_website_url;
    public $college_email;
    public $college_contact_no;
    public $college_logo_path;
    public $college_logo_path_old;
    public $sanstha_id;
    public $university_id;
    public $status;
    public $can_update=0;
    public $is_default;
    #[Locked] 
    public $college_id;
    #[Locked] 
    public $universities;
    #[Locked] 
    public $sansthas;
    #[Locked] 
    public $mode='all';
    #[Locked] 
    public $delete_id;
  

    protected function rules()
    {
        return [
        'college_name' => ['required','string','max:255'],
        'college_name_marathi' => ['required','string','max:255'],
        'principal_name' => ['required','string'],     
        'college_address' => ['required','string','max:255'],
        'college_website_url' => ['required','string','max:100'],
        'college_email' => ['required','email'],
        'college_contact_no' => ['required','numeric'],
        'college_logo_path' =>[($this->can_update==1?'nullable':'required'),'max:250','mimes:png,jpg,jpeg'],
        'sanstha_id' => ['required',Rule::exists('sansthas', 'id')],
        'university_id' => ['required',Rule::exists('universities', 'id')],   
        ];
    }

    public function messages()
    {   
        $messages = [
        'college_name.required' => 'The college name is required.',
        'college_name.string' => 'The college name must be a string.',
        'college_name.max' => 'The college name may not be greater than 255 characters.',
        'principal_name.required' => 'The college name is required.',
        'principal_name.string' => 'The college name must be a string.',
        'college_name_marathi.required' => 'The college name in marathi is required.',
        'college_name_marathi.string' => 'The college name must be a string.',
        'college_name_marathi.max' => 'The college name may not be greater than 255 characters.',
        'college_address.required' => 'The college address is required.',
        'college_address.string' => 'The college address must be a string.',
        'college_address.max' => 'The college address may not be greater than 255 characters.',
        'college_website_url.required' => 'The college website URL is required.',
        'college_website_url.string' => 'The college website URL must be a string.',
        'college_website_url.max' => 'The college website URL may not be greater than 100 characters.',
        'college_email.required' => 'The college email is required.',
        'college_email.email' => 'The college email must be a valid email address.',
        'college_contact_no.required' => 'The college contact number is required.',
        'college_contact_no.numeric' => 'The college contact number must be a number.',
        'college_logo_path.required' => 'The college logo is required.',
        'college_logo_path.max' => 'The college logo size should be 250 KB.',
        'college_logo_path.mimes' => 'The college logo must be a file of type: png, jpg, jpeg.',
        'sanstha_id.required' => 'The Sanstha ID is required.',
        'sanstha_id.exists' => 'The selected Sanstha ID is invalid.',
        'university_id.required' => 'The University ID is required.',
        'university_id.exists' => 'The selected University ID is invalid.',
        ];
        return $messages;
    }

    public function resetinput()
    {
        $this->reset([
            'college_name',
            'college_name_marathi',
            'principal_name',
            'college_address',
            'college_website_url',
            'college_email',
            'college_contact_no',
            'college_logo_path',
            'sanstha_id',
            'university_id',
            'status',
            'is_default',
            'college_id',
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
            $college = new College;
            $college->college_name= $this->college_name;
            $college->college_name_marathi= $this->college_name_marathi;
            $college->principal_name= $this->principal_name;
            $college->college_address=  $this->college_address;
            $college->college_website_url=  $this->college_website_url;
            $college->college_email= $this->college_email;
            $college->college_contact_no= $this->college_contact_no;
            $college->sanstha_id= $this->sanstha_id;
            $college->university_id= $this->university_id;
            $college->status= $this->status;
            $college->is_default= $this->is_default==1?0:1;
            
            if ($this->college_logo_path) {
                $path = 'user/college/logo/';           
                $fileName = 'college_logo_' . time(). '.' . $this->college_logo_path->getClientOriginalExtension();
                $this->college_logo_path->storeAs($path, $fileName, 'public');
                $college->college_logo_path = 'storage/' . $path . $fileName;
                $this->reset('college_logo_path');
            }
            
            $college->save();

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'College Created Successfully !!');

        } catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create College !!');
        }
    }


    public function deleteconfirmation($college_id)
    {
        $this->delete_id=$college_id;
        $this->dispatch('delete-confirmation');
    }

   
    public function delete(College  $college)
    {   
       
        DB::beginTransaction();

        try 
        {
            $college->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'College Soft Deleted Successfully !!');

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete College !!');
        }
    }


    public function restore($college_id)
    {   
       
        DB::beginTransaction();

        try
        {
            $college = College::withTrashed()->findOrFail($college_id);

            $college->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'College Restored Successfully !!');

        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore College !!');
        }
    }

    public function forcedelete()
    {   

        DB::beginTransaction();

        try 
        {
            $college = College::withTrashed()->find($this->delete_id);
            
            if ($college->college_logo_path) {
                File::delete($college->college_logo_path);
            }

            $college->forceDelete();
            $this->delete_id=null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'College Deleted Successfully !!');

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
                $this->dispatch('alert',type:'error',message:'Failed To Delete College !!');
            }
        }
    }

    public function update_status(College $college)
    {
        DB::beginTransaction();

        try 
        {   
            if($college->status)
            {
                $college->status=0;
            }
            else
            {
                $college->status=1;
            }
            $college->update();

            DB::commit();
            $this->dispatch('alert',type:'success',message:'Status Updated Successfully !!'  );
        }catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Status  !!');
        }
    }

    public function edit(College $college){

        if ($college) {
            if($college->college_logo_path){
                $this->can_update=1;
            }
            $this->resetinput();
            $this->college_id=$college->id;
            $this->college_name = $college->college_name;
            $this->principal_name = $college->principal_name;
            $this->college_name_marathi = $college->college_name_marathi;
            $this->college_email = $college->college_email;
            $this->college_contact_no = $college->college_contact_no;
            $this->college_website_url = $college->college_website_url;
            $this->college_logo_path_old = $college->college_logo_path;
            $this->college_address = $college->college_address;
            $this->sanstha_id = $college->sanstha_id;
            $this->university_id = $college->university_id;
            $this->status = $college->status ;
            $this->is_default = $college->is_default==0?true:0 ;
            $this->mode='edit';
        }
    }

    public function updateCollege(College  $college)
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {
            $college->college_name= $this->college_name;
            $college->principal_name= $this->principal_name;
            $college->college_name_marathi= $this->college_name_marathi;
            $college->college_address=  $this->college_address;
            $college->college_website_url=  $this->college_website_url;
            $college->college_email= $this->college_email;
            $college->college_contact_no= $this->college_contact_no;
            $college->sanstha_id= $this->sanstha_id;
            $college->university_id= $this->university_id;
            $college->status= $this->status;
            $college->is_default= $this->is_default==1?0:1;
        
            if ($this->college_logo_path) 
            {   
                if ($college->college_logo_path) {
                    File::delete($college->college_logo_path);
                }

                $path = 'user/college/logo/';           
                $fileName = 'college_logo_' . time(). '.' . $this->college_logo_path->getClientOriginalExtension();
                $this->college_logo_path->storeAs($path, $fileName, 'public');
                $college->college_logo_path = 'storage/' . $path . $fileName;
                $this->reset('college_logo_path');
            }
            $college->update();


            DB::commit();

            $this->resetinput();

            $this->mode='all';

            $this->dispatch('alert',type:'success',message:'College Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update College !!');
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

            $filename="College_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response =  Excel::download(new CollegeExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response =  Excel::download(new CollegeExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response =  Excel::download(new CollegeExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'College Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export College !!');
        }

    }
       
    public function render()
    {   
        if($this->mode!=='all')
        {   
            $this->sansthas=Sanstha::where('status',1)->pluck('sanstha_name','id');
            $this->universities=University::where('status',1)->pluck('university_name','id');
        }
       
        $colleges=College::select('id','college_name','college_email','sanstha_id','university_id','status','deleted_at')
        ->with(['sanstha:sanstha_name,id','university:university_name,id'])
        ->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.college.all-college',compact('colleges'))->extends('layouts.user')->section('user');
    }
}
