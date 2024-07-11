<?php

namespace App\Livewire\User\BoardUniversity;

use Excel;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Boarduniversity;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Exports\User\BoardUniversity\BoardUniversityExport;

class AllBoardUniversity extends Component
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
    public $sortColumn="boarduniversity_name";
    public $sortColumnBy="ASC";
    public $ext;

    public $boarduniversity_name;
    public $is_active;
    #[Locked] 
    public $edit_id;


    protected function rules()
    {
        return [
            'boarduniversity_name' => ['required', 'string','max:255','unique:boarduniversities,boarduniversity_name,' . ($this->mode == 'edit' ? $this->edit_id : ''),],
        ];
    }

    public function messages()
    {   
        $messages = [
            'boarduniversity_name.required' => 'The Board University Name field is required.',
            'boarduniversity_name.string' => 'TheBoard University Name must be a string.',
            'boarduniversity_name.max' => 'The  Board University Name must not exceed :max characters.',
            'boarduniversity_name.unique' => 'The Board University Name has already been taken.',
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
            'boarduniversity_name',
            'is_active',       
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

            $filename="Board_University_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new BoardUniversityExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new BoardUniversityExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new BoardUniversityExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Board University Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Board University !!');
        }

    }

    public function add()
    {   
       
        $this->validate();

        DB::beginTransaction();

        try 
        {
           
            $board_university =  new Boarduniversity;
            $board_university->create([
                'boarduniversity_name' => $this->boarduniversity_name,
                'is_active' => $this->is_active==true?0:1,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Board University Created Successfully !!');

        } catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Board University !!');
        }
    }


    public function edit(Boarduniversity $board_university)
    {   
        $this->resetinput();
        $this->edit_id=$board_university->id;
        $this->boarduniversity_name= $board_university->boarduniversity_name;
        $this->is_active=$board_university->is_active==1?0:true;
        $this->mode='edit';
    }

    public function update(Boarduniversity $board_university)
    {   
       
        DB::beginTransaction();

        try 
        {

            $board_university->update([
                'boarduniversity_name' => $this->boarduniversity_name,
                'is_active' => $this->is_active == true ? 0 : 1,
            ]);

            DB::commit();

            $this->resetinput();

            $this->mode='all';

            $this->dispatch('alert',type:'success',message:'Board University Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Board University !!');
        }
    }


    public function status(Boarduniversity $board_university)
    {
        DB::beginTransaction();

        try 
        {   
            if($board_university->status)
            {
                $board_university->status=0;
            }
            else
            {
                $board_university->status=1;
            }
            $board_university->update();

            DB::commit();
            $this->dispatch('alert',type:'success',message:'Status Updated Successfully !!'  );
        }catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Status !!');
        }
    }

    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }

    public function delete(Boarduniversity $board_university)
    {   
      
        DB::beginTransaction();

        try 
        {
            $board_university->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Board University Soft Deleted Successfully !!');

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Board University !!');
        }
    }

    
    public function restore($id)
    {   
       
        DB::beginTransaction();

        try
        {
            $board_university = Boarduniversity::withTrashed()->findOrFail($id);

            $board_university->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Board University Restored Successfully !!');

        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Board University !!');
        }
    }
   

    public function forcedelete()
    {   
        DB::beginTransaction();

        try 
        {
            $board_university = Boarduniversity::withTrashed()->find($this->delete_id);
            $board_university->forceDelete();
            $this->delete_id=null;
            DB::commit();
            $this->dispatch('alert',type:'success',message:'Board University Deleted Successfully !!');  
        } catch (\Illuminate\Database\QueryException $e) {

            DB::rollBack();
            
            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'error',message:'This record is associated with another data. You cannot delete it !!');
            } 
            else
            {   
                $this->dispatch('alert',type:'error',message:'Failed To Delete Board University !!');
            }
        }
    }

    public function render()
    {   
        $board_universities=Boarduniversity::select('id','boarduniversity_name','is_active','deleted_at')->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.board-university.all-board-university',compact('board_universities'))->extends('layouts.user')->section('user');
    }
}
