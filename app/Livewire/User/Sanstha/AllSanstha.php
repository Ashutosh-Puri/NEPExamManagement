<?php

namespace App\Livewire\User\Sanstha;

use Excel;
use App\Models\Sanstha;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Exports\User\Sanstha\ExportSanstha;


class AllSanstha extends Component
{
    use WithPagination;
    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;
    public $steps=1;
    public $current_step=1;
    public $sanstha_name;
    public $sanstha_address;
    public $sanstha_chairman_name;
    public $sanstha_website_url;
    public $sanstha_contact_no;
    public $status;
    #[Locked] 
    public $mode='all';
    #[Locked] 
    public $delete_id;
    #[Locked] 
    public $sanstha_id;

    protected function rules()
    {
        return [
        'sanstha_name' => ['required','string','max:255'],
        'sanstha_chairman_name' => ['required','string','max:50'],
        'sanstha_address' => ['required','string','max:255'],
        'sanstha_website_url' =>[ 'required','string','max:50'],
        'sanstha_contact_no' => ['required','max:20'],   
        ];
    }

    public function messages()
    {   
        $messages = [
            'sanstha_name.required' => 'The Sanstha name is required.',
            'sanstha_name.string' => 'The Sanstha name must be a string.',
            'sanstha_name.max' => 'The Sanstha name may not be greater than 255 characters.',
            'sanstha_chairman_name.required' => 'The chairman name is required.',
            'sanstha_chairman_name.string' => 'The chairman name must be a string.',
            'sanstha_chairman_name.max' => 'The chairman name may not be greater than 50 characters.',
            'sanstha_address.required' => 'The Sanstha address is required.',
            'sanstha_address.string' => 'The Sanstha address must be a string.',
            'sanstha_address.max' => 'The Sanstha address may not be greater than 255 characters.',
            'sanstha_website_url.required' => 'The Sanstha website URL is required.',
            'sanstha_website_url.string' => 'The Sanstha website URL must be a string.',
            'sanstha_website_url.max' => 'The Sanstha website URL may not be greater than 50 characters.',
            'sanstha_contact_no.required' => 'The Sanstha contact number is required.',
            'sanstha_contact_no.max' => 'The Sanstha contact number may not be greater than 20 characters.',
        ];
        return $messages;
    }

    public function resetinput()
    {
        $this->reset([
            'sanstha_name',
            'sanstha_chairman_name',
            'sanstha_address',
            'sanstha_website_url',
            'sanstha_contact_no',
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
    }

    public function add()
    { 
        $this->validate();

        DB::beginTransaction();

        try 
        {
            $sanstha= new Sanstha;
            $sanstha->sanstha_name= $this->sanstha_name;
            $sanstha->sanstha_chairman_name= $this->sanstha_chairman_name;
            $sanstha->sanstha_address=  $this->sanstha_address;
            $sanstha->sanstha_website_url=  $this->sanstha_website_url;
            $sanstha->sanstha_contact_no= $this->sanstha_contact_no;
            $sanstha->status= $this->status;
            $sanstha->save();

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Sanstha Created Successfully !!');

        } catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Sanstha !!');
        }
    }

    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }


    public function delete(Sanstha  $sanstha)
    {  
        DB::beginTransaction();

        try 
        {
            $sanstha->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Sanstha Soft Deleted Successfully !!');

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Sanstha !!');
        }
    }

    public function restore($sanstha_id)
    {   
       
        DB::beginTransaction();

        try
        {
            $sanstha = Sanstha::withTrashed()->findOrFail($sanstha_id);

            $sanstha->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Sanstha Restored Successfully !!');

        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Sanstha !!');
        }
    }

    public function forcedelete()
    {   

        DB::beginTransaction();

        try 
        { 
            $sanstha = Sanstha::withTrashed()->find($this->delete_id);
            $sanstha->forceDelete();

            $this->delete_id=null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Sanstha Deleted Successfully !!');

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
                $this->dispatch('alert',type:'error',message:'Failed To Delete Sanstha !!');
            }
        }
    }

    public function edit(Sanstha $sanstha ){

        if ($sanstha) {
            $this->resetinput();
            $this->sanstha_id=$sanstha->id;
            $this->sanstha_name = $sanstha->sanstha_name;
            $this->sanstha_chairman_name = $sanstha->sanstha_chairman_name;
            $this->sanstha_contact_no = $sanstha->sanstha_contact_no;
            $this->sanstha_website_url = $sanstha->sanstha_website_url;
            $this->sanstha_address = $sanstha->sanstha_address;
            $this->status = $sanstha->status;          
            $this->mode='edit';
        }
    }

    public function update(Sanstha  $sanstha)
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {
            $sanstha->update([                 
                'sanstha_name' => $this->sanstha_name,
                'sanstha_chairman_name' => $this->sanstha_chairman_name,               
                'sanstha_address' => $this->sanstha_address,
                'sanstha_contact_no' => $this->sanstha_contact_no,
                'sanstha_website_url' => $this->sanstha_website_url,                
                'status' => $this->status,
                     
            ]);

            DB::commit();

            $this->resetinput();

            $this->mode='all';

            $this->dispatch('alert',type:'success',message:'Sanstha Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Sanstha !!');
        }
    }
  

    public function Status(Sanstha $sanstha)
    {
        DB::beginTransaction();

        try 
        {   
            if($sanstha->status)
            {
                $sanstha->status=0;
            }
            else
            {
                $sanstha->status=1;
            }
            $sanstha->update();

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

            $filename="Sanstha".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    return Excel::download(new ExportSanstha($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    return Excel::download(new ExportSanstha($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    return Excel::download(new ExportSanstha($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Sanstha Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Sanstha !!');
        }

    }

    public function render()
    {
        $sansthas=Sanstha::select('id','sanstha_name','sanstha_chairman_name','sanstha_address','sanstha_website_url','sanstha_contact_no','status','deleted_at')
        ->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.sanstha.all-sanstha',compact('sansthas'))->extends('layouts.user')->section('user');
    }
}
