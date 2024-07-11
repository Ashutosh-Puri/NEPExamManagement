<?php

namespace App\Livewire\User\Classroom;

use Excel;
use Livewire\Component;
use App\Models\Building;
use App\Models\Classroom;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Exports\User\Classroom\ExportClassroom;

class AllClassroom extends Component
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
    public $sortColumn="class_name";
    public $sortColumnBy="ASC";
    public $ext;

    public $building_id;
    #[Locked]
    public $building;
    public $noofbenches;
    public $status;
    public $class_name;
    #[Locked]
    public $edit_id;

    protected function rules()
    {
        return [
            'building_id' => ['required',Rule::exists('buildings', 'id')],
            'class_name' => ['required', 'string','max:20'],
            'noofbenches' => ['required', 'integer'],

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
                'building_id',
                'class_name',
                'noofbenches',
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

            $filename="Classroom_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new ExportClassroom($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new ExportClassroom($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new ExportClassroom($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Classroom Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Classroom !!');
        }
    }

    public function add()
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {   
            $classroom =  new Classroom;
            $classroom->create([
                'building_id' => $this->building_id,
                'class_name' => $this->class_name,
                'noofbenches' => $this->noofbenches,
                'status' => $this->status,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Classroom Created Successfully !!');

        } catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Classroom !!');
        }
    }

    
    public function edit(Classroom $classroom)
    {   
        $this->resetinput();
        $this->edit_id=$classroom->id;
        $this->building_id= $classroom->building_id;
        $this->class_name= $classroom->class_name;
        $this->noofbenches= $classroom->noofbenches;
        $this->status=$classroom->status;
        $this->mode='edit';
    }

    public function update(Classroom $classroom)
    {
        $this->validate();
        
        DB::beginTransaction();

        try 
        {   
            $classroom->update([
                'building_id' => $this->building_id,
                'class_name' => $this->class_name,
                'noofbenches' => $this->noofbenches,
                'status' => $this->status,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Classroom Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Classroom !!');
        }
    }


    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }


    public function delete(Classroom  $classroom)
    {  
        DB::beginTransaction();

        try
        {   
            $classroom->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Classroom Soft Deleted Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Classroom !!');
        }
    }

    public function restore($id)
    {   
        DB::beginTransaction();

        try
        {   
            $classroom = Classroom::withTrashed()->find($id);
            $classroom->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Classroom Restored Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Classroom !!');
        }
    }

  

    public function forcedelete()
    {   
        DB::beginTransaction();
        try 
        {
            $classroom = Classroom::withTrashed()->find($this->delete_id);
            $classroom->forceDelete();
            $this->delete_id=null;
            DB::commit();
            $this->dispatch('alert',type:'success',message:'Classroom Deleted Successfully !!');
            
        } catch (\Illuminate\Database\QueryException $e) {

            DB::rollBack();

            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'error',message:'This record is associated with another data. You cannot delete it !!');
            } 
            else
            {   
                $this->dispatch('alert',type:'error',message:'Failed To Delete Classroom !!');
            }
        }
    }

    public function updatestatus(Classroom $classroom)
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
            $this->building=Building::where('status',1)->pluck('building_name','id');

        }

        $classrooms=Classroom::select('id','building_id','class_name','noofbenches','status','deleted_at')
        ->with(['building:building_name,id'])
        ->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.classroom.all-classroom',compact('classrooms'))->extends('layouts.user')->section('user');
    }
}
