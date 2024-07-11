<?php

namespace App\Livewire\User\University;

use Excel;
use Livewire\Component;
use App\Models\University;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use Illuminate\Support\Facades\File;
use App\Exports\User\University\ExportUniversity;


class AllUniversity extends Component
{   
    ## By Ashutosh
    use WithFileUploads;
    use WithPagination;
    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    public $current_step=1;
    public $steps=1;
    public $university_name;
    public $university_address;
    public $university_website_url;
    public $university_email;
    public $university_contact_no;
    public $university_logo_path;
    public $university_logo_path_old;
    public $status;
    public $can_update=0;
    public $perPage=10; 
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;

    #[Locked] 
    public $mode='all';
    #[Locked] 
    public $delete_id;
    #[Locked] 
    public $university_id;

    protected function rules()
    {
        return [
        'university_name' => ['required','string','max:255'],
        'university_address' => ['required','string','max:255'],
        'university_website_url' =>['required','string','max:100'],
        'university_email' => ['required','email'],
        'university_contact_no' =>[ 'required','max:50'],
        'university_logo_path' =>[($this->can_update==1?'nullable':'required'),'mimes:png,jpg,jpeg'],
        ];
    }

    public function messages()
    {   
        $messages = [
            'university_name.required' => 'The university name is required.',
            'university_name.string' => 'The university name must be a string.',
            'university_name.max' => 'The university name may not be greater than 255 characters.',
            'university_address.required' => 'The university address is required.',
            'university_address.string' => 'The university address must be a string.',
            'university_address.max' => 'The university address may not be greater than 255 characters.',
            'university_website_url.required' => 'The university website URL is required.',
            'university_website_url.string' => 'The university website URL must be a string.',
            'university_website_url.max' => 'The university website URL may not be greater than 100 characters.',
            'university_email.required' => 'The university email is required.',
            'university_email.email' => 'The university email must be a valid email address.',
            'university_contact_no.required' => 'The university contact number is required.',
            'university_contact_no.max' => 'The university contact number may not be greater than 50 characters.',
            'university_logo_path.required' => 'The university logo is required.',
            'university_logo_path.mimes' => 'The university logo must be a file of type: png, jpg, jpeg.',
        ];
        return $messages;
    }

    public function resetinput()
    {
        $this->reset([
            'university_name',
            'university_address',
            'university_website_url',
            'university_email',
            'university_contact_no',
            'university_logo_path',
            'university_id'
        ]);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
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
            $university= new University;
            $university->university_name= $this->university_name;
            $university->university_address=  $this->university_address;
            $university->university_website_url=  $this->university_website_url;
            $university->university_email= $this->university_email;
            $university->university_contact_no= $this->university_contact_no;
            $university->status= $this->status;
        
            if ($this->university_logo_path) 
            {
            
                $path = 'user/university/logo/';     

                $fileName = 'university_' . time(). '.' . $this->university_logo_path->getClientOriginalExtension();    

                $this->university_logo_path->storeAs($path, $fileName, 'public');  

                $university->university_logo_path = 'storage/' . $path . $fileName;
                $this->reset('university_logo_path');
            }
        
            $university->save();

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'University Created Successfully !!');

        } catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create University !!');
        }
    }
    
    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }


    public function delete(University  $university)
    {  
        DB::beginTransaction();

        try 
        {
            $university->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'University Soft Deleted Successfully !!');

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete University !!');
        }
    }

    public function restore($university_id)
    {   
       
        DB::beginTransaction();

        try
        {
            $university = University::withTrashed()->findOrFail($university_id);

            $university->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'University Restored Successfully !!');

        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore University !!');
        }
    }

    public function forcedelete()
    {   

        DB::beginTransaction();

        try 
        { 
            $university = University::withTrashed()->find($this->delete_id);
            $university->forceDelete();

            $this->delete_id=null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'University Deleted Successfully !!');

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
                $this->dispatch('alert',type:'error',message:'Failed To Delete University !!');
            }
        }
    }

    public function edit(University $university){


        if ($university) 
        {
             if($university->university_logo_path)
             {
                $this->can_update=1;
                $this->resetinput();
                $this->university_id=$university->id;
                $this->university_name = $university->university_name;
                $this->university_email = $university->university_email;
                $this->university_contact_no = $university->university_contact_no;
                $this->university_website_url = $university->university_website_url;
                $this->university_logo_path_old = $university->university_logo_path;
                $this->university_address = $university->university_address;
                $this->status = $university->status;
                $this->mode='edit';      
             }
        }
    }

    public function update(University  $university)
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {
            $university->university_name= $this->university_name;
            $university->university_email=  $this->university_email;
            $university->university_contact_no=  $this->university_contact_no;
            $university->university_website_url= $this->university_website_url;
            $university->university_address= $this->university_address;
            $university->status= $this->status;
        
        if ($this->university_logo_path) 
        {
            if ($university->university_logo_path) {
                File::delete($university->university_logo_path);
            }
            $path = 'user/university/logo/';           
            $fileName = 'university_' . time(). '.' . $this->university_logo_path->getClientOriginalExtension();
            $this->university_logo_path->storeAs($path, $fileName, 'public');
            $university->university_logo_path = 'storage/' . $path . $fileName;
            $this->reset('university_logo_path');
        }
            $university->update(); 

            DB::commit();

            $this->resetinput();

            $this->mode='all';

            $this->dispatch('alert',type:'success',message:'University Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update University !!');
        }
    }

    public function update_status(University $university)
    {
        DB::beginTransaction();

        try 
        {   
            if($university->status)
            {
                $university->status=0;
            }
            else
            {
                $university->status=1;
            }
            $university->update();

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

            $filename="University_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new ExportUniversity($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new ExportUniversity($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new ExportUniversity($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }  

            $this->dispatch('alert',type:'success',message:'University Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export University !!');
        }

    }

    public function render()
    {
        $universities=University::select('id','university_name','university_email','university_address','university_website_url','university_contact_no','university_logo_path','status','deleted_at')
        ->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.university.all-university',compact('universities'))->extends('layouts.user')->section('user');
    }
}
