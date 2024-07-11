<?php

namespace App\Livewire\User\Instruction;

use Excel;
use App\Models\College;
use Livewire\Component;
use App\Models\Instruction;
use Livewire\WithPagination;
use App\Models\Instructiontype;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use Illuminate\Support\Facades\Auth;
use App\Exports\User\Instruction\ExportInstruction;

class AllInstruction extends Component
{   
    # By Ashutosh
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

    public $instruction_name;
    public $is_active;

    public $user_id;
    #[Locked]
    public $colleges;
    #[Locked]
    public $inst;
    public $college_id;
    public $instructiontype_id;
    #[Locked]
    public $instruction_id;
    #[Locked]
    public $delete_id;

    protected function rules()
    {
        return [
            'college_id' => ['required', 'integer', Rule::exists('colleges', 'id')],
            'instructiontype_id' => ['required', 'integer', Rule::exists('instructiontypes', 'id')],
            'instruction_name' => ['required'],
        ];
    }

    public function resetInput()
    {
        $this->reset(
            [
                'instruction_id',
                'instructiontype_id',
                'instruction_name',
                'college_id',
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

            $filename="Instruction_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new ExportInstruction($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new ExportInstruction($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new ExportInstruction($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Instruction Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Instruction !!');
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

    public function add()
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {   
            $instructions =  new Instruction;
            $instructions->create([
                'user_id' =>Auth::guard('user')->user()->id,
                'instructiontype_id' => $this->instructiontype_id,
                'instruction_name' => $this->instruction_name,
                'college_id' => $this->college_id,
                'is_active' => $this->is_active,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Instruction Created Successfully !!');

        } catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Instruction !!');
        }
    }

    public function edit(Instruction $inst)
    {   
        $this->resetinput();
        $this->instruction_id=$inst->id;
        $this->instructiontype_id=$inst->instructiontype_id;
        $this->college_id=$inst->college_id;
        $this->instruction_name=$inst->instruction_name;
        $this->is_active=$inst->is_active;
        $this->mode='edit';
    }

    public function update(Instruction $inst)
    {
        $this->validate();
        
        DB::beginTransaction();

        try 
        {   
            $inst->update([
                'college_id' => $this->college_id,
                'instructiontype_id' => $this->instructiontype_id,
                'instruction_name' => $this->instruction_name,
                'is_active' => $this->is_active,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Instruction Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Instruction !!');
        }
    }

    public function deleteconfirmation($instruction_id)
    {
        $this->delete_id=$instruction_id;
        $this->dispatch('delete-confirmation');
    }

    public function delete(Instruction  $inst)
    {  
        DB::beginTransaction();

        try 
        {
            $inst->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Instruction Soft Deleted Successfully !!');

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Instruction !!');
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
            $instruction = Instruction::withTrashed()->findOrFail($instruction_id);

            $instruction->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Instruction Restored Successfully !!');

        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Instruction !!');
        }
    }

    public function forcedelete()
    {   

        DB::beginTransaction();

        try 
        { 
            $instruction = Instruction::withTrashed()->find($this->delete_id);
            $instruction->forceDelete();

            $this->delete_id=null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Instruction Deleted Successfully !!');

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
                $this->dispatch('alert',type:'error',message:'Failed To Delete Instruction !!');
            }
        }
    }

    public function status(Instruction $instruction)
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

    public function render()
    {
        if($this->mode!=='all')
        {   
            $this->colleges=College::where('status',1)->pluck('college_name','id');
            $this->inst=Instructiontype::where('is_active',1)->pluck('instruction_type','id');
        }
        
        $instructions=Instruction::select('id','instruction_name','user_id','instructiontype_id','college_id','is_active','deleted_at')
        ->with(['user:name,id','college:college_name,id','instructiontype:instruction_type,id'])
        ->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.instruction.all-instruction',compact('instructions'))->extends('layouts.user')->section('user');
    }
}
