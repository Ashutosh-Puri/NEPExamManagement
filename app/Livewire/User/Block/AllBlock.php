<?php

namespace App\Livewire\User\Block;

use Excel;
use App\Models\Block;
use Livewire\Component;
use App\Models\Building;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Exports\User\Block\ExportBlock;

class AllBlock extends Component
{   
    ## By Ashutosh
    use WithPagination;
    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;
    public $building_id;
    public $classname;
    public $block;
    public $capacity;
    public $noofblocks;
    public $status;

    #[Locked] 
    public $buildings;
    #[Locked] 
    public $block_id;
    #[Locked] 
    public $mode='all';
    #[Locked] 
    public $delete_id;
  

    protected function rules()
    {
       return [
        'building_id'=>['required',Rule::exists('buildings', 'id')],
        'classname'=>['required','string','max:80'],
        'block'=>['required','string','max:4'],
        'capacity'=>['required','digits_between:1,10'],
        'noofblocks'=>['required','digits_between:1,10'],
        ];
    }

    public function messages()
    {   
        $messages = [
            'building_id.required' => 'The building ID is required.',
            'building_id.exists' => 'The selected building ID is invalid.',
            'classname.required' => 'The class name is required.',
            'classname.string' => 'The class name must be a string.',
            'classname.max' => 'The class name may not be greater than 80 characters.',
            'block.required' => 'The block is required.',
            'block.string' => 'The block must be a string.',
            'block.max' => 'The block may not be greater than 4 characters.',
            'capacity.required' => 'The capacity is required.',
            'capacity.digits_between' => 'The capacity must be between 1 and 10 digits.',
            'noofblocks.required' => 'The number of blocks is required.',
            'noofblocks.digits_between' => 'The number of blocks must be between 1 and 10 digits.',
        ];
        return $messages;
    }

    public function resetinput()
    {
        $this->reset([
            'building_id',
            'classname',
            'block',
            'capacity',
            'noofblocks',
            'status',
        ]);
    }

    public function add(Block $blok)
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {
            $blok->building_id= $this->building_id;
            $blok->classname= $this->classname;
            $blok->block=  $this->block;
            $blok->capacity=  $this->capacity;
            $blok->noofblocks= $this->noofblocks;
            $blok->status= $this->status;
            $blok->save();

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Block Created Successfully !!');

        } catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Block !!');
        }
    }

    public function edit(Block $blok ){

        if ($blok) {
            $this->resetinput();
            $this->block_id=$blok->id;
            $this->building_id = $blok->building_id;
            $this->classname = $blok->classname;
            $this->block = $blok->block;
            $this->capacity = $blok->capacity;
            $this->noofblocks = $blok->noofblocks;
            $this->status = $blok->status;          
            $this->mode='edit';
        }
    }

    public function update(Block  $blok)
    {   

        $this->validate();

        DB::beginTransaction();

        try 
        {

            $blok->update([
                              
                'building_id' => $this->building_id,
                'classname' => $this->classname,               
                'block' => $this->block,
                'capacity' => $this->capacity,
                'noofblocks' => $this->noofblocks,                
                'status' => $this->status,                   
            ]);
            DB::commit();

            $this->resetinput();

            $this->mode='all';

            $this->dispatch('alert',type:'success',message:'Block Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Block !!');
        }
    }
     
    public function deleteconfirmation($block_id)
    {
        $this->delete_id=$block_id;
        $this->dispatch('delete-confirmation');
    }

    public function delete(Block  $block)
    {   
        DB::beginTransaction();

        try 
        {
            $block->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Block Soft Deleted Successfully !!');

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Block !!');
        }
    }

    public function restore($block_id)
    {   
       
        DB::beginTransaction();

        try
        {
            $block = Block::withTrashed()->findOrFail($block_id);

            $block->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Block Restored Successfully !!');

        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Block !!');
        }
    }

    public function forcedelete()
    {   

        DB::beginTransaction();

        try 
        {
            $block = Block::withTrashed()->find($this->delete_id);

            $block->forceDelete();
            $this->delete_id=null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Block Deleted Successfully !!');

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
                $this->dispatch('alert',type:'error',message:'Failed To Delete Block !!');
            }
        }
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


    public function update_status(Block $block)
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

            $filename="Block_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new ExportBlock($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new ExportBlock($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new ExportBlock($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Block Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Block !!');
        }

    }

    public function render()
    { 
        if($this->mode!=='all')
        {
            $this->buildings = Building::where('status',1)->pluck('building_name','id');
        }

        $blocks=Block::select('id','building_id','classname','block','capacity','noofblocks','status','deleted_at')
        ->with('building:building_name,id')
        ->when($this->search, function ($query, $search) {$query->search($search);})
        ->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.block.all-block',compact('blocks'))->extends('layouts.user')->section('user');    
    }
}
