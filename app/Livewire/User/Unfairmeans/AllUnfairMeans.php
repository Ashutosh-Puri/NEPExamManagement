<?php

namespace App\Livewire\User\Unfairmeans;

use Excel;
use App\Models\Exam;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use App\Models\Unfairmeansmaster;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Exports\User\Unfairmeans\ExportUnfairmeans;
use App\Exports\User\Unfairmeans\ExportUnfairmenas;

class AllUnfairMeans extends Component
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

    public $location;
    public $date;
    public $time;
    public $exam_id;
    public $status;
    public $exams;

    #[Locked]
    public $unfairmeans_id;
    #[Locked]
    public $delete_id;

    protected function rules()
    {
        return [
            'location' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date',],
            'time'=>['required'],
        ];
    }

    public function messages()
    {   
        $messages = [
            'location.required' => 'The location field is required.',
            'location.string' => 'The location must be a string.',
            'location.max' => 'The location may not be greater than :max characters.',
            
            'date.required' => 'The date field is required.',
            'date.date' => 'The date must be a valid date format.',
            
            'time.date_format' => 'The time must be a valid time format (HH:MM:SS).',    
        ];
        return $messages;
    }

    public function resetInput()
    {
        $this->location = null;
        $this->date = null;
        $this->time = null;
        $this->exam_id = null;
        $this->status = null;
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

    public function save(){

        $this->validate();
            $exam=Exam::where('status',1)->first();
            if( $exam)
            {
                $startTime =\DateTime::createFromFormat('H:i',  $this->time);
                $unfairmeans = Unfairmeansmaster::create([
                    'exam_id' => $exam->id,
                    'location' => $this->location,
                    'date' => $this->date,
                    'time' => $startTime,
                    'status' => 1
                ]);
            }

            $this->dispatch('alert',type:'success',message:'Unfairmeans Added Successfully !!'  );
            $this->resetinput();
            $this->setmode('all');
    }

    public function deleteconfirmation($unfairmeans_id)
    {
        $this->delete_id=$unfairmeans_id;
        $this->dispatch('delete-confirmation');
    }

   
    public function delete(Unfairmeansmaster  $unfairmeans)
    {  
        DB::beginTransaction();

        try 
        {
            $unfairmeans->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Unfairmeans Soft Deleted Successfully !!');

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Unfairmeans !!');
        }
    }

    public function restore($unfairmeans_id)
    {   
       
        DB::beginTransaction();

        try
        {
            $unfairmeans = Unfairmeansmaster::withTrashed()->findOrFail($unfairmeans_id);

            $unfairmeans->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Unfairmeans Restored Successfully !!');

        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Unfairmeans !!');
        }
    }

    public function forcedelete()
    {   

        DB::beginTransaction();

        try 
        { 
            $unfairmeans = Unfairmeansmaster::withTrashed()->find($this->delete_id);
            $unfairmeans->forceDelete();

            $this->delete_id=null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Unfairmeans Deleted Successfully !!');

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
                $this->dispatch('alert',type:'error',message:'Failed To Delete Unfairmeans !!');
            }
        }
    }

    public function Status(Unfairmeansmaster $unfairmeans)
    {
        DB::beginTransaction();

        try 
        {   
            if($unfairmeans->status)
            {
                $unfairmeans->status=0;
            }
            else
            {
                $unfairmeans->status=1;
            }
            $unfairmeans->update();

            DB::commit();
            $this->dispatch('alert',type:'success',message:'Status Updated Successfully !!'  );
        }catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Status  !!');
        }
    }

    public function edit(Unfairmeansmaster $unfairmean){
        $this->resetinput();
        $this->unfairmeans_id=$unfairmean->id;
        $this->location = $unfairmean->location;
        $this->date = $unfairmean->date;
        $this->time = $unfairmean->time;
        $this->mode='edit';
        
    }

    public function update(Unfairmeansmaster  $unfairmean)
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {
            $unfairmean->location= $this->location;
            $unfairmean->date= $this->date;
            $unfairmean->time= $this->time;
            $unfairmean->update();  

            DB::commit();

            $this->resetinput();

            $this->mode='all';

            $this->dispatch('alert',type:'success',message:'Unfairmeans Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Unfairmeans !!');
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

            $filename="Unfairmean_Master_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new ExportUnfairmeans($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new ExportUnfairmeans($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new ExportUnfairmeans($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }  

            $this->dispatch('alert',type:'success',message:'Unfairmean Master Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Unfairmean Master !!');
        }

    }

    public function render()
    {   

        $unfairmeans=Unfairmeansmaster::select('id','location','date','time','exam_id','status','deleted_at')
        ->with(['exam:exam_name,id'])
        ->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.unfairmeans.all-unfair-means',compact('unfairmeans'))->extends('layouts.user')->section('user');
    }
}
