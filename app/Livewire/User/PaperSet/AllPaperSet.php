<?php

namespace App\Livewire\User\PaperSet;

use Excel;
use Livewire\Component;
use App\Models\Paperset;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Exports\User\Paperset\ExportPaperSet;

class AllPaperSet extends Component
{
    use WithPagination;
    protected $listeners = ['delete-confirmed'=>'forcedelete'];

    public $mode='all';
    public $per_page = 10;
    public $perPage=10;
    public $search='';
    public $sortColumn="set_name";
    public $sortColumnBy="ASC";
    public $ext;
    public $set_name;

    #[Locked]
    public $paper_id;
    #[Locked]
    public $delete_id;

    protected function rules()
    {
        return [
        'set_name' => ['required','string','max:1'],
        ];
    }

    public function messages()
    {   
        $messages = [
            'set_name.required' => 'The Set Name field is required.',
            'set_name.string' => 'The Set Name must be a char.',
            'set_name.max' => 'The  Set Name must not exceed :max characters.',
        ];
        return $messages;
    }
    
    public function resetinput()
    {
        $this->set_name=null;  
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

    public function save(Paperset  $paperset )
    { 
        $this->validate();

        DB::beginTransaction();

        try 
        {
            $paperset->set_name= $this->set_name;
            $paperset->save();

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Paper Set Created Successfully !!');

        } catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Paper Set !!');
        }
    }

    public function edit(Paperset $paperset)
    {
        $this->resetinput();
        $this->paper_id=$paperset->id;
        $this->set_name = $paperset->set_name; 
        $this->mode='edit';
    }

    public function update(Paperset  $paperset)
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {
            $paperset->update([
                $paperset->set_name= $this->set_name
            ]);

            DB::commit();

            $this->resetinput();

            $this->mode='all';

            $this->dispatch('alert',type:'success',message:'Paper Set Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Paper Set !!');
        }
    }

    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }

   
    public function delete(Paperset  $paperset)
    {  
        DB::beginTransaction();

        try 
        {
            $paperset->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Paper Set Soft Deleted Successfully !!');

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete  Paper Set !!');
        }
    }

    public function restore($paperset_id)
    {   
       
        DB::beginTransaction();

        try
        {
            $paperset = Paperset::withTrashed()->findOrFail($paperset_id);

            $paperset->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Paper Set Restored Successfully !!');

        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Paper Set !!');
        }
    }

    public function forcedelete()
    {   

        DB::beginTransaction();

        try 
        { 
            $paperset = Paperset::withTrashed()->find($this->delete_id);
            $paperset->forceDelete();

            $this->delete_id=null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Paper Set Deleted Successfully !!');

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
                $this->dispatch('alert',type:'error',message:'Failed To Delete Paper Set !!');
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

            $filename="Paperset_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response =  Excel::download(new ExportPaperSet($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new ExportPaperSet($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response =  Excel::download(new ExportPaperSet($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }   

            $this->dispatch('alert',type:'success',message:'Paper Set Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Paper Set !!');
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
    
    public function render()
    {
        $papersets=Paperset::select('id','set_name','deleted_at')->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);
        return view('livewire.user.paper-set.all-paper-set',compact('papersets'))->extends('layouts.user')->section('user');
    }
}
