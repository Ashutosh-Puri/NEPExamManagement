<?php

namespace App\Livewire\User\ExamBuilding;

use Excel;
use App\Models\Exam;
use Livewire\Component;
use App\Models\Building;
use App\Models\Exambuilding;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Exports\User\ExamBuilding\ExportExamBuilding;

class AllExamBuilding extends Component
{   
    ## By Ashutosh
    use WithPagination;
    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    public $perPage=10;
    public $search='';
    public $sortColumn="exam_id";
    public $sortColumnBy="ASC";
    public $ext;
    public $exam_id;
    public $building_id;
    public $status;

    #[Locked] 
    public $exam;
    #[Locked] 
    public $building;
    #[Locked] 
    public $mode='all';
    #[Locked] 
    public $delete_id;
    #[Locked] 
    public $edit_id;

    protected function rules()
    {
       return [
            'exam_id'=>['required',Rule::exists('exams', 'id')],
            'building_id'=>['required',Rule::exists('buildings', 'id')],  
        ];
    }

    public function messages()
    {   
        $messages = [
            'exam_id.required' => 'The Exam field is required.',
            'exam_id.exists' => 'The selected Programme does not exist.',
            'building_id.required' => 'The Building field is required.',
            'building_id.exists' => 'The selected Programme does not exist.',           
        ];
        return $messages;
    }

    public function resetinput()
    {
        $this->reset([
            'exam_id',
            'building_id',
            'status',
            'edit_id',
        ]);  
    }

    public function add()
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {   
            $exambuilding = new Exambuilding;
            $exambuilding->exam_id= $this->exam_id;
            $exambuilding->building_id= $this->building_id;
            $exambuilding->status= $this->status;
            $exambuilding->save();

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Admission Data Created Successfully !!');

        } catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Exam Building !!');
        }
    }

    public function edit(Exambuilding $exambuilding ){

        if ($exambuilding) {
            $this->resetinput();
            $this->edit_id=$exambuilding->id;
            $this->exam_id = $exambuilding->exam_id;
            $this->building_id = $exambuilding->building_id;
            $this->status = $exambuilding->status;          
            $this->mode='edit';
        }
    }

    public function update(Exambuilding  $exambuilding)
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {

            $exambuilding->update([                            
                'exam_id' => $this->exam_id,               
                'building_id' => $this->building_id,               
                'status' => $this->status,                   
            ]);


            DB::commit();

            $this->resetinput();

            $this->mode='all';

            $this->dispatch('alert',type:'success',message:'Exam Building Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Exam Building !!');
        }
    }

    public function deleteconfirmation($building_id)
    {
        $this->delete_id=$building_id;
        $this->dispatch('delete-confirmation');
    }

    public function delete(Exambuilding  $exambuilding)
    {   
        DB::beginTransaction();

        try 
        {
            $exambuilding->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Exam Building Soft Deleted Successfully !!');

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Exam Building !!');
        }
    }
  

    public function restore($building_id)
    {   
       
        DB::beginTransaction();

        try
        {
            $exam_building = Exambuilding::withTrashed()->findOrFail($building_id);

            $exam_building->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Exam Building Restored Successfully !!');

        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Exam Building !!');
        }
    }

    public function forcedelete()
    {   

        DB::beginTransaction();

        try 
        { 
            $exambuilding = Exambuilding::withTrashed()->find($this->delete_id);
            $exambuilding->forceDelete();

            $this->delete_id=null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Exam Building Deleted Successfully !!');

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
                $this->dispatch('alert',type:'error',message:'Failed To Delete Exam Building !!');
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

    public function update_status(Exambuilding $exambuilding)
    {
        DB::beginTransaction();

        try 
        {   
            if($exambuilding->status)
            {
                $exambuilding->status=0;
            }
            else
            {
                $exambuilding->status=1;
            }
            $exambuilding->update();

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

            $filename="Exam_Building_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new ExportExamBuilding($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new ExportExamBuilding($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new ExportExamBuilding($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Exam Building Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Exam Building !!');
        }

    }

  
    public function render()
    {
        if($this->mode!=='all')
        {
            $this->exam = Exam::where('status',1)->pluck('exam_name','id');
            $this->building = Building::where('status',1)->pluck('building_name','id');
        }

        $exambuildings=Exambuilding::when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.exam-building.all-exam-building',compact('exambuildings'))->extends('layouts.user')->section('user');
    }
}
