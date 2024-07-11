<?php

namespace App\Livewire\User\Ordinace;

use Excel;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use App\Models\Ordinace163master;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Exports\User\Ordinace\Ordinace163Export;

class AllOrdinace163 extends Component
{   
    # By Ashutosh
    use WithPagination;
    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    #[Locked] 
    public $delete_id;
    #[Locked] 
    public $mode='all';
    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;

    public $ordinace_name;
    public $activity_name;
    public $status;
    #[Locked] 
    public $edit_id;


    protected function rules()
    {
        return [
            'activity_name' => ['required', 'string','max:100','unique:ordinace163masters,activity_name,' . ($this->mode == 'edit' ? $this->edit_id : ''),],
            'ordinace_name' => ['required', 'string','max:100']
        ];
    }

    public function messages()
    {   
        $messages = [
            'ordinace_name.required' => 'The Ordinace Name field is required.',
            'ordinace_name.string' => 'The Ordinace Name must be a string.',
            'ordinace_name.max' => 'The  Ordinace Name must not exceed :max characters.',
            'activity_name.required' => 'The Activity Name field is required.',
            'activity_name.string' => 'The Activity Name must be a string.',
            'activity_name.max' => 'The Activity Name must not exceed :max characters.',
            'activity_name.unique' => 'The Activity Name has already been taken.',
        ];
        
        return $messages;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function resetinput()
    {
        $this->reset([
            'edit_id',
            'ordinace_name',
            'activity_name',
            'status',
        ]);
      
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

    #[Renderless]
    public function export()
    {   
        try 
        {
            set_time_limit(600);
            ini_set('memory_limit', '1024M');

            $filename="Ordinace_163_".now();

            $response = null;
            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new Ordinace163Export($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new Ordinace163Export($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new Ordinace163Export($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Ordinace 163 Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Ordinace 163 !!');
        }
    }

    public function add()
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {   
            $ordinace163 =  new Ordinace163master;
            $ordinace163->create([
                'ordinace_name' => $this->ordinace_name,
                'activity_name' => $this->activity_name,
                'status' => $this->status==true?0:1,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Ordinace 163 Created Successfully !!');

        } catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Ordinace 163 !!');
        }
    }


    public function edit(Ordinace163master $ordinace163)
    {   
        $this->resetinput();
        $this->edit_id=$ordinace163->id;
        $this->ordinace_name= $ordinace163->ordinace_name;
        $this->activity_name= $ordinace163->activity_name;
        $this->status=$ordinace163->status==1?0:true;
        $this->mode='edit';
    }

    public function update(Ordinace163master $ordinace163)
    {
        $this->validate();
        
        DB::beginTransaction();

        try 
        {   
            $ordinace163->update([
                'ordinace_name' => $this->ordinace_name,
                'activity_name' => $this->activity_name,
                'status' => $this->status == true ? 0 : 1,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Ordinace 163 Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Ordinace 163 !!');
        }
    }

    public function changestatus(Ordinace163master $ordinace163)
    {
        DB::beginTransaction();

        try 
        {   
            if($ordinace163->status)
            {
                $ordinace163->status=0;
            }
            else
            {
                $ordinace163->status=1;
            }
            $ordinace163->update();

            DB::commit();
            $this->dispatch('alert',type:'success',message:'Status Updated Successfully !!'  );
        }catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Status  !!');
        }
    }


    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }


    // public function delete(Ordinace163master  $ordinace163)
    // {  
    //     DB::beginTransaction();

    //     try
    //     {   
    //         $ordinace163->delete();

    //         DB::commit();

    //         $this->dispatch('alert',type:'success',message:'Ordinace 163 Soft Deleted Successfully !!');
    //     } 
    //     catch (\Exception $e) 
    //     {
    //         DB::rollBack();

    //         $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Ordinace 163 !!');
    //     }
    // }
    
    // public function restore($id)
    // {   
    //     DB::beginTransaction();

    //     try
    //     {   
    //         $ordinace163 = Ordinace163master::withTrashed()->find($id);
    //         $ordinace163->restore();

    //         DB::commit();

    //         $this->dispatch('alert',type:'success',message:'Ordinace 163 Restored Successfully !!');
    //     } 
    //     catch (\Exception $e) 
    //     {
    //         DB::rollBack();

    //         $this->dispatch('alert',type:'error',message:'Failed To Restore Ordinace 163 !!');
    //     }
    // }


    public function forcedelete()
    {   
        DB::beginTransaction();
        try 
        {
            $ordinace163 = Ordinace163master::find($this->delete_id);
            $ordinace163->delete();
            $this->delete_id=null;
            DB::commit();
            $this->dispatch('alert',type:'success',message:'Ordinace 163 Deleted Successfully !!');
            
        } catch (\Illuminate\Database\QueryException $e) {

            DB::rollBack();
            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'error',message:'This record is associated with another data. You cannot delete it !!');
            } 
            else
            {   
                $this->dispatch('alert',type:'error',message:'Failed To Delete Ordinace 163 !!');
            }
        }
    }

    public function render()
    {   
        $ordinace163s=Ordinace163master::select('id','ordinace_name','activity_name','status')->when($this->search, function ($query, $search) {
            $query->search($search);
        })->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.ordinace.all-ordinace163',compact('ordinace163s'))->extends('layouts.user')->section('user');
    }
}