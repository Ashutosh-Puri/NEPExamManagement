<?php

namespace App\Livewire\User\Instruction;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Instructiontype;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Exports\User\InstructionType\ExportInstructiontype;

class AllInstructionType extends Component
{   
    ## By Ashutosh
    use WithPagination;
    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    #[Locked]
    public $mode='all';
    public $per_page = 10;
    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;

    public $instruction_type;
    public $is_active;

    #[Locked]
    public $instruction_id;
    #[Locked]
    public $delete_id;



    protected function rules()
    {
        return [
           
            'instruction_type' => ['required'],
        ];
    }

    public function resetInput()
    {
        $this->reset(
            [              
                'instruction_type',          
                'is_active',
            ]
        );
    }

    #[Renderless]
    public function export()
    {   
        try 
        {
            set_time_limit(600);
            ini_set('memory_limit', '1024M');

            $filename="Instruction_Type_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new ExportInstructiontype($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new ExportInstructiontype($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new ExportInstructiontype($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Instruction Type Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Instruction !!');
        }
    }

    
    public function add()
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {   
            $instructions =  new Instructiontype;
            $instructions->create([
                'instruction_type' => $this->instruction_type,
                'is_active' => $this->is_active,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Instruction Type Created Successfully !!');

        } catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Instruction Type !!');
        }
    }

    public function edit(Instructiontype $inst)
    {   
        $this->resetinput();
        $this->instruction_id=$inst->id;
        $this->instruction_type=$inst->instruction_type;
        $this->is_active=$inst->is_active;
        $this->mode='edit';
    }

    public function update(Instructiontype $inst)
    {
        $this->validate();
        
        DB::beginTransaction();

        try 
        {   
            $inst->update([
                'instruction_type' => $this->instruction_type,
                'is_active' => $this->is_active,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Instruction Type Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Instruction Type !!');
        }
    }

    public function deleteconfirmation($instruction_id)
    {
        $this->delete_id=$instruction_id;
        $this->dispatch('delete-confirmation');
    }

    public function delete(Instructiontype  $inst)
    {  
        DB::beginTransaction();

        try 
        {
            $inst->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Instruction Type Soft Deleted Successfully !!');

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Instruction Type !!');
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

    
    public function restore($instruction_id)
    {   
       
        DB::beginTransaction();

        try
        {
            $instruction = Instructiontype::withTrashed()->findOrFail($instruction_id);

            $instruction->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Instruction Type Restored Successfully !!');

        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Instruction Type !!');
        }
    }

    public function forcedelete()
    {   

        DB::beginTransaction();

        try 
        { 
            $instruction = Instructiontype::withTrashed()->find($this->delete_id);
            $instruction->forceDelete();

            $this->delete_id=null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Instruction Type Deleted Successfully !!');

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
                $this->dispatch('alert',type:'error',message:'Failed To Delete Instruction Type !!');
            }
        }
    }

    public function update_status(Instructiontype $instruction)
    {
        DB::beginTransaction();

        try 
        {   
            if($instruction->is_active)
            {
                $instruction->is_active=0;
            }
            else
            {
                $instruction->is_active=1;
            }
            $instruction->update();

            DB::commit();
            $this->dispatch('alert',type:'success',message:'Status Updated Successfully !!'  );
        }catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Status  !!');
        }
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



    public function render()
    {
        $instructions=Instructiontype::select('id','instruction_type','is_active','deleted_at')
        ->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.instruction.all-instruction-type',compact('instructions'))->extends('layouts.user')->section('user');
    }
}
