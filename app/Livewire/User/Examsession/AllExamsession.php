<?php

namespace App\Livewire\User\Examsession;

use Excel;
use App\Models\Exam;
use Livewire\Component;
use App\Models\Examsession;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Exports\User\Examsession\Exportexamsession;

class AllExamsession extends Component
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

    public $from_date;
    public $to_date;
    public $from_time;
    public $to_time;
    public $session_type;
    public $exam_id;

    #[Locked]
    public $session_id;

    protected function rules()
    {
        return [
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date'],
            'from_time' => ['required',],
            'to_time' => ['required', ],
            'session_type' => ['required', 'string' , 'max:1'],

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
                'from_date',
                'to_date',
                'from_time',
                'to_time',
                'session_type',
                'session_id',
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

            $filename="Exam_Session_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new Exportexamsession($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new Exportexamsession($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new Exportexamsession($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Exam Session Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Exam Session !!');
        }
    }

    public function add()
    {   
        $this->validate();
        $exam=Exam::where('status',1)->first();

        DB::beginTransaction();

        try 
        {   
            $fromtime =\DateTime::createFromFormat('H:i',  $this->from_time);
            $totime =\DateTime::createFromFormat('H:i',  $this->to_time);
            $examsession =  new Examsession;
            $examsession->create([
                'exam_id'=>$exam->id,
                'from_date' => $this->from_date,
                'to_date' => $this->to_date,
                'from_time' => $fromtime,
                'to_time' => $totime,
                'session_type' => $this->session_type,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Exam Session Created Successfully !!');

        } catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Exam Session !!');
        }
    }

    
    public function edit(Examsession $examsession)
    {   
        $this->resetinput();
        $this->session_id=$examsession->id;
        $this->from_date = date('Y-m-d', strtotime($examsession->from_date));
        $this->to_date = date('Y-m-d', strtotime($examsession->to_date));
        $this->from_time= $examsession->from_time;
        $this->to_time=$examsession->to_time;
        $this->session_type=$examsession->session_type;
        $this->mode='edit';
    }

    public function update(Examsession $examsession)
    {
        $this->validate();
        
        DB::beginTransaction();

        try 
        {   
            $examsession->update([
                'from_date' => $this->from_date,
                'to_date' => $this->to_date,
                'from_time' => $this->from_time,
                'to_time' => $this->to_time,
                'session_type' => $this->session_type,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Exam Session Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Exam Session !!');
        }
    }


    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }


    public function delete(Examsession  $examsession)
    {  
        DB::beginTransaction();

        try
        {   
            $examsession->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Exam Session Soft Deleted Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Exam Session !!');
        }
    }

    public function restore($id)
    {   
        DB::beginTransaction();

        try
        {   
            $examsession = Examsession::withTrashed()->find($id);
            $examsession->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Exam Session Restored Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Exam Session !!');
        }
    }

  

    public function forcedelete()
    {   
        DB::beginTransaction();
        try 
        {
            $examsession = Examsession::withTrashed()->find($this->delete_id);
            $examsession->forceDelete();
            $this->delete_id=null;
            DB::commit();
            $this->dispatch('alert',type:'success',message:'Exam Session Deleted Successfully !!');
            
        } catch (\Illuminate\Database\QueryException $e) {

            DB::rollBack();
            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'error',message:'This record is associated with another data. You cannot delete it !!');
            } 
            else
            {   
                $this->dispatch('alert',type:'error',message:'Failed To Delete Exam Session !!');
            }
        }
    }
    
    public function render()
    {
        $sessions=Examsession::select('id','from_date','to_date','session_type','from_time','to_time','deleted_at')
        ->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);


        return view('livewire.user.examsession.all-examsession',compact('sessions'))->extends('layouts.user')->section('user');
    }
}
