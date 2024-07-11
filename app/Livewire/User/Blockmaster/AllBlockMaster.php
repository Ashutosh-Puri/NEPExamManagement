<?php

namespace App\Livewire\User\Blockmaster;

use Excel;
use Livewire\Component;
use App\Models\Blockmaster;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Exports\User\Blockmaster\ExportBlockMaster;

class AllBlockMaster extends Component
{   
    ## By Ashutosh
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    #[Locked] 
    public $delete_id;
    #[Locked]
    public $mode='all';
    public $perPage=10;
    public $search='';
    public $sortColumn="block_name";
    public $sortColumnBy="ASC";
    public $ext;

    public $block_name;
    public $block_size;
    public $status;

    #[Locked] 
    public $edit_id;

    protected function rules()
    {
        return [
            'block_name' => ['required', 'string','max:4',Rule::unique('blockmasters', 'block_name')->ignore($this->edit_id, 'id')],
            'block_size' => ['required', 'integer'],

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
                'edit_id',
                'block_name',
                'block_size',
                'status',
            ]
        );
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

            $filename="Block_Master_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new ExportBlockMaster($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new ExportBlockMaster($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new ExportBlockMaster($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Block Master Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Block Master !!');
        }
    }

    public function add()
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {   
            $block =  new Blockmaster;
            $block->create([
                'block_name' => $this->block_name,
                'block_size' => $this->block_size,
                'status' => $this->status == true ? 0 : 1,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Block Master Created Successfully !!');

        } catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Block Master !!');
        }
    }

    
    public function edit(Blockmaster $block)
    {   
        $this->resetinput();
        $this->edit_id=$block->id;
        $this->block_name= $block->block_name;
        $this->block_size= $block->block_size;
        $this->status=$block->status;
        $this->mode='edit';
    }

    public function update(Blockmaster $block)
    {
        $this->validate();
        
        DB::beginTransaction();

        try 
        {   
            $block->update([
                'block_name' => $this->block_name,
                'block_size' => $this->block_size,
                'status' => $this->status,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Block Master Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Block Master !!');
        }
    }


    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }


    public function delete(Blockmaster  $block)
    {  
        DB::beginTransaction();

        try
        {   
            $block->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Block Master Soft Deleted Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Block Master !!');
        }
    }

    public function restore($id)
    {   
        DB::beginTransaction();

        try
        {   
            $block = Blockmaster::withTrashed()->find($id);
            $block->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Block Master Restored Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Block Master!!');
        }
    }

  

    public function forcedelete()
    {   
        DB::beginTransaction();
        try 
        {
            $block = Blockmaster::withTrashed()->find($this->delete_id);
            $block->forceDelete();
            $this->delete_id=null;
            DB::commit();
            $this->dispatch('alert',type:'success',message:'Block Master Deleted Successfully !!');
            
        } catch (\Illuminate\Database\QueryException $e) {

            DB::rollBack();

            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'error',message:'This record is associated with another data. You cannot delete it !!');
            } 
            else
            {   
                $this->dispatch('alert',type:'error',message:'Failed To Delete Block Master !!');
            }
        }
    }

    public function updatestatus(Blockmaster $block)
    {
        DB::beginTransaction();

        try 
        {   
            if($block->status)
            {
                $block->status=0;
            }
            else
            {
                $block->status=1;
            }
            $block->update();

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
        $blocks=Blockmaster::select('id','block_name','block_size','status','deleted_at')
        ->when($this->search, function ($query, $search) {  $query->search($search); })
        ->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);
 
        return view('livewire.user.blockmaster.all-block-master',compact('blocks'))->extends('layouts.user')->section('user');
    }
}
