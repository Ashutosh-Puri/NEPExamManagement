<?php

namespace App\Livewire\User\ClassroomBlock;

use Excel;
use Livewire\Component;
use App\Models\Classroom;
use App\Models\Blockmaster;
use Livewire\WithPagination;
use App\Models\Classroomblock;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Exports\User\ClassroomBlock\ExportClassroomblock;

class AllClassroomBlock extends Component
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
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;

    public $classroom_id;
    #[Locked]
    public $classroom;
    public $blockmaster_id;
    #[Locked]
    public $blocks;
    public $status;

    #[Locked]
    public $edit_id;

    protected function rules()
    {
        return [
            'classroom_id' => ['required',Rule::exists('classrooms', 'id')],
            'blockmaster_id' => ['required',Rule::exists('blockmasters', 'id')],
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
                'classroom_id',
                'blockmaster_id',
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

            $filename="Classroom_Block_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    return Excel::download(new ExportClassroomblock($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    return Excel::download(new ExportClassroomblock($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    return Excel::download(new ExportClassroomblock($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Classroom Block Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Classroom Block !!');
        }
    }

    public function add()
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {   
            $classroom =  new Classroomblock;
            $classroom->create([
                'classroom_id' => $this->classroom_id,
                'blockmaster_id' => $this->blockmaster_id,
                'status' => $this->status,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Classroom Block Created Successfully !!');

        } catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Classroom Block !!');
        }
    }

    
    public function edit(Classroomblock $classroom)
    {   
        $this->resetinput();
        $this->edit_id=$classroom->id;
        $this->classroom_id= $classroom->classroom_id;
        $this->blockmaster_id= $classroom->blockmaster_id;
        $this->status=$classroom->status;
        $this->mode='edit';
    }

    public function update(Classroomblock $classroom)
    {
        $this->validate();
        
        DB::beginTransaction();

        try 
        {   
            $classroom->update([
                'classroom_id' => $this->classroom_id,
                'blockmaster_id' => $this->blockmaster_id,
                'status' => $this->status,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Classroom Block Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Classroom Block !!');
        }
    }


    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }


    public function delete(Classroomblock  $classroom)
    {  
        DB::beginTransaction();

        try
        {   
            $classroom->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Classroom Block Soft Deleted Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Classroom Block !!');
        }
    }

    public function restore($id)
    {   
        DB::beginTransaction();

        try
        {   
            $classroom = Classroomblock::withTrashed()->find($id);
            $classroom->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Classroom Block Restored Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Classroom Block !!');
        }
    }

  

    public function forcedelete()
    {   
        DB::beginTransaction();
        try 
        {
            $classroom = Classroomblock::withTrashed()->find($this->delete_id);
            $classroom->forceDelete();
            $this->delete_id=null;
            DB::commit();
            $this->dispatch('alert',type:'success',message:'Classroom Block Deleted Successfully !!');
            
        } catch (\Illuminate\Database\QueryException $e) {

            DB::rollBack();

            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'error',message:'This record is associated with another data. You cannot delete it !!');
            } 
            else
            {   
                $this->dispatch('alert',type:'error',message:'Failed To Delete Classroom Block !!');
            }
        }
    }

    public function updatestatus(Classroomblock $classroom)
    {
        DB::beginTransaction();

        try 
        {   
            if($classroom->status)
            {
                $classroom->status=0;
            }
            else
            {
                $classroom->status=1;
            }
            $classroom->update();

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
            $this->classroom=Classroom::where('status',1)->pluck('class_name','id');
            $this->blocks=Blockmaster::where('status',1)->pluck('block_name','id');
        }

        $classrooms=Classroomblock::select('id','classroom_id','blockmaster_id','status','deleted_at')
        ->with(['classroom:class_name,id','blockmaster:block_name,id'])
        ->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.classroom-block.all-classroom-block',compact('classrooms'))->extends('layouts.user')->section('user');
    }
}
