<?php

namespace App\Livewire\User\Programme;

use Excel;
use Livewire\Component;
use App\Models\Programme;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use App\Exports\User\Progarmme\ProgrammeExport;

class AllProgramme extends Component
{   
    # By Ashutosh
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    #[Locked] 
    public $delete_id;
    #[Locked] 
    public $edit_id;
    #[Locked] 
    public $mode='all';
    public $perPage=10;
    public $search='';
    public $sortColumn="programme_name";
    public $sortColumnBy="ASC";
    public $ext;

    public $programme_name;
    public $active;


    protected function rules()
    {
        return [
            'programme_name' => ['required', 'string','max:100','unique:programmes,programme_name,' . ($this->mode == 'edit' ? $this->edit_id : ''),],
        ];
    }

    public function messages()
    {   
        $messages = [
            'programme_name.required' => 'The Programme Name field is required.',
            'programme_name.string' => 'The Programme Name must be a string.',
            'programme_name.max' => 'The  Programme Name must not exceed :max characters.',
            'programme_name.unique' => 'The Programme Name has already been taken.',
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
            'programme_name',
            'active',
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

            $filename="Programme_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new ProgrammeExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new ProgrammeExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new ProgrammeExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Programme Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            \Log::error($e);

            $this->dispatch('alert',type:'error',message:'Failed To Export Programme !!');
        }

    }


    public function add()
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {
            Programme::create([
                'programme_name' => $this->programme_name,
                'active' => $this->active==true?0:1,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Programme Created Successfully !!');

        } catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Programme !!');
        }
    }


    public function edit(Programme $programme)
    {   
        $this->resetinput();
        $this->edit_id=$programme->id;
        $this->programme_name= $programme->programme_name;
        $this->active=$programme->active==1?0:true;
        $this->mode='edit';
    }

    public function update(Programme $programme)
    {
        $this->validate();

        DB::beginTransaction();
        try 
        {
            $programme->update([
                'programme_name' => $this->programme_name,
                'active' => $this->active == true ? 0 : 1,
            ]);

            DB::commit();

            $this->resetinput();

            $this->mode='all';

            $this->dispatch('alert',type:'success',message:'Programme Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Programme !!');
        }
    }

    public function status(Programme $programme)
    {
        DB::beginTransaction();

        try 
        {   
            if($programme->active)
            {
                $programme->active=0;
            }
            else
            {
                $programme->active=1;
            }
            $programme->update();

            DB::commit();
            $this->dispatch('alert',type:'success',message:'Status Updated Successfully !!'  );
        }catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Status  !!');
        }
    }

    public function deleteconfirmation($programme_id)
    {
        $this->delete_id=$programme_id;
        $this->dispatch('delete-confirmation');
    }



    public function delete(Programme  $programme)
    {   
        DB::beginTransaction();

        try 
        {
            $programme->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Programme Soft Deleted Successfully !!');

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Programme !!');
        }
    }


    public function restore($programme_id)
    {   
       

        DB::beginTransaction();

        try
        {
            $programme = Programme::withTrashed()->find($programme_id);
            if($programme)
            {
                $programme->restore();
            }

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Programme Restored Successfully !!');

        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Programme !!');
        }
    }


    public function forcedelete()
    {   

        DB::beginTransaction();

        try 
        {
            $programme = Programme::withTrashed()->find($this->delete_id);
            if($programme)
            {
                $programme->forceDelete();
            }
            
            DB::commit();

            $this->reset('delete_id');
            
            $this->dispatch('alert',type:'success',message:'Programme Deleted Successfully !!');
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
                $this->dispatch('alert',type:'error',message:'Failed To Delete Programme !!');
            }
        }
    }


    public function render()
    {   
        $programmes=Programme::select('id','programme_name','active','deleted_at')->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.programme.all-programme',compact('programmes'))->extends('layouts.user')->section('user');
    }
}
