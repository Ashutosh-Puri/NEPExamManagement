<?php

namespace App\Livewire\User\HelplineQuery;

use Excel;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Models\Studenthelplinequery;
use App\Models\Studenthelplinequeryquery;
use App\Exports\User\HelplineQuery\HelplineQueryExport;

class AllHelplineQuery extends Component
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
    public $sortColumn="query_name";
    public $sortColumnBy="ASC";
    public $ext;

    public $query_name;
    public $is_active;
    #[Locked] 
    public $edit_id;


    protected function rules()
    {
        return [
            'query_name' => ['required', 'string','max:255','unique:student_helpline_queries,query_name,' . ($this->mode == 'edit' ? $this->edit_id : ''),],
        ];
    }

    public function messages()
    {   
        $messages = [
            'query_name.required' => 'The Query Name field is required.',
            'query_name.string' => 'The Query Name must be a string.',
            'query_name.max' => 'The  Query Name must not exceed :max characters.',
            'query_name.unique' => 'The Query Name has already been taken.',
        ];
        
        return $messages;
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
                'query_name',
                'is_active',
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

            $filename="Helpline_Query_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new HelplineQueryExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new HelplineQueryExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new HelplineQueryExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Helpline Query Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Helpline Query !!');
        }
    }

    public function add()
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {   
            $helpline_query =  new Studenthelplinequery;
            $helpline_query->create([
                'query_name' => $this->query_name,
                'is_active' => $this->is_active==true?0:1,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Helpline Query Created Successfully !!');

        } catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Helpline Query !!');
        }
    }


    public function edit(Studenthelplinequery $helpline_query)
    {   
        $this->resetinput();
        $this->edit_id=$helpline_query->id;
        $this->query_name= $helpline_query->query_name;
        $this->is_active=$helpline_query->is_active==1?0:true;
        $this->mode='edit';
    }

    public function update(Studenthelplinequery $helpline_query)
    {
        $this->validate();
        
        DB::beginTransaction();

        try 
        {   
            $helpline_query->update([
                'query_name' => $this->query_name,
                'is_active' => $this->is_active == true ? 0 : 1,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Helpline Query Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Helpline Query !!');
        }
    }

    public function status(Studenthelplinequery $helpline_query)
    {
        DB::beginTransaction();

        try 
        {   
            if($helpline_query->is_active)
            {
                $helpline_query->is_active=0;
            }
            else
            {
                $helpline_query->is_active=1;
            }
            $helpline_query->update();

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


    public function delete(Studenthelplinequery  $helpline_query)
    {  
        DB::beginTransaction();

        try
        {   
            $helpline_query->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Helpline Query Soft Deleted Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Helpline Query !!');
        }
    }
    
    public function restore($id)
    {   
        DB::beginTransaction();

        try
        {   
            $helpline_query = Studenthelplinequery::withTrashed()->find($id);
            $helpline_query->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Helpline Query Restored Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Helpline Query !!');
        }
    }
 
    public function forcedelete()
    {   
        DB::beginTransaction();
        try 
        {
            $helpline_query = Studenthelplinequery::withTrashed()->find($this->delete_id);
            $helpline_query->forceDelete();
            $this->delete_id=null;
            DB::commit();
            $this->dispatch('alert',type:'success',message:'Helpline Query Deleted Successfully !!');
            
        } catch (\Illuminate\Database\QueryException $e) {

            DB::rollBack();
            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'error',message:'This record is associated with another data. You cannot delete it !!');
            } 
            else
            {   
                $this->dispatch('alert',type:'error',message:'Failed To Delete Helpline Query !!');
            }
        }
    }

    public function render()
    {   
        $student_helpline_queries=Studenthelplinequery::select('id','query_name','is_active','deleted_at')
        ->when($this->search, function ($query, $search) { $query->search($search); })
        ->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.helpline-query.all-helpline-query',compact('student_helpline_queries'))->extends('layouts.user')->section('user');
    }
}
