<?php

namespace App\Livewire\User\Building;

use Excel;
use Livewire\Component;
use App\Models\Building;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Exports\User\Building\ExportBuilding;

class AllBuilding extends Component
{   
    ## By Ashutosh
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    public $building_name;
    public $Priority;
    public $status;
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
    public $building_id;

    protected function rules()
    {
        return [
        'building_name' => ['required','string','max:255',Rule::unique('buildings', 'building_name')->ignore($this->building_id,)],
        'Priority' => ['required'],
        'status' => ['required'],    
        ];
    }

    public function messages()
    {   
        $messages = [
            'building_name.required' => 'The Building Name field is required.',
            'building_name.string' => 'The Building Name must be a string.',
            'building_name.max' => 'The  Building Name must not exceed :max characters.',
            'building_name.unique' => 'The Building Name has already been taken.',
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
            'building_id',
            'building_name',
            'Priority',
            'status'
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

    public function update_status(Building $building)
    {
        DB::beginTransaction();

        try 
        {   
            if($building->status)
            {
                $building->status=0;
            }
            else
            {
                $building->status=1;
            }
            $building->update();

            DB::commit();
            $this->dispatch('alert',type:'success',message:'Status Updated Successfully !!'  );
        }catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Status  !!');
        }
    }

    
    public function add(Building  $building)
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {
            $building->create([
                'building_name' => $this->building_name,
                'Priority' => $this->Priority,
                'status' => $this->status,          
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Building Created Successfully !!');

        } catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Building !!');
        }
    }

    public function edit(Building $building){
        if ($building) {
            $this->resetinput();
            $this->building_id=$building->id;
            $this->building_name = $building->building_name;
            $this->Priority = $building->Priority;
            $this->status = $building->status;                    
            $this->mode='edit';
        }
    }

    public function update(Building $building)
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {

            $building->update([
                'building_name' => $this->building_name,
                'Priority' => $this->Priority,
                'status' => $this->status,
                
            ]); 

            DB::commit();

            $this->resetinput();

            $this->mode='all';

            $this->dispatch('alert',type:'success',message:'Building Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Building !!');
        }
    }


    public function deleteconfirmation($building_id)
    {
        $this->delete_id=$building_id;
        $this->dispatch('delete-confirmation');
    }


    public function delete(Building  $building)
    {   
        DB::beginTransaction();

        try 
        {
            $building->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Building Soft Deleted Successfully !!');

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Building !!');
        }
    }

    
    public function restore($building_id)
    {         
        DB::beginTransaction();

        try
        {
            $building = Building::withTrashed()->findOrFail($building_id);
            $building->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Building Restored Successfully !!');

        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Building !!');
        }
    }

    public function forcedelete()
    {   

        DB::beginTransaction();

        try 
        {
            $building = Building::withTrashed()->find($this->delete_id);
            $building->forceDelete();

            $this->delete_id=null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Building Deleted Successfully !!');

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
                $this->dispatch('alert',type:'error',message:'Failed To Delete Building !!');
            }
        }
    }

    #[Renderless]
    public function export()
    {   
        try 
        {
            set_time_limit(600);
            ini_set('memory_limit', '1024M');

            $filename="Building_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new ExportBuilding($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new ExportBuilding($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new ExportBuilding($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Building Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Building !!');
        }

    }

    public function render()
    {       
        $buildings=Building::select('id','building_name','Priority','status','deleted_at')
        ->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.building.all-building',compact('buildings'))->extends('layouts.user')->section('user');
    }
}
