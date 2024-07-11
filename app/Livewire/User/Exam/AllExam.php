<?php

namespace App\Livewire\User\Exam;
use Excel;
use App\Models\Exam;
use App\Models\Month;
use Livewire\Component;
use App\Models\Academicyear;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Exports\User\Exam\ExportExam;


class AllExam extends Component
{
    # By Ashutosh
    use WithPagination;
    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="DESC";
    public $ext;
    public $exam_name;
    public $status;
    public $exam_sessions;
    public $academicyear_id;
    public $month;
 
    #[Locked] 
    public $months=[];
    #[Locked] 
    public $academics=[];
    #[Locked] 
    public $mode='all';
    #[Locked] 
    public $exam_id;
    #[Locked] 
    public $delete_id;
 

    protected function rules()
    {
        return [
        'exam_name' => ['required','string','max:100'],
        'academicyear_id' => ['required',Rule::exists('academicyears', 'id')],
        'month' => ['required','string','max:100'],
        'status' => ['required'],
        'exam_sessions' => ['required'],
        ];
    }

    public function messages()
    {   
        $messages = [
        'exam_name.required' => 'The exam name is required.',
        'exam_name.string' => 'The exam name must be a string.',
        'exam_name.max' => 'The exam name may not be greater than 100 characters.',
        'academicyear_id.required' => 'The academic year ID is required.',
        'academicyear_id.exists' => 'The selected academic year ID is invalid.',
        'month.required' => 'The Month is required.',
        'month.exists' => 'The selected Month is invalid.',
        'status.required' => 'The status is required.',
        'exam_sessions.required' => 'At least one exam session is required.',
        ];
        return $messages;
    }

    public function resetinput()
    {
        $this->reset([
            'exam_name',
            'academicyear_id',
            'status',
            'exam_sessions',
            'exam_id',
            'month'
        ]);
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

    public function add()
    { 
        $this->validate();

        DB::beginTransaction();

        try 
        {   
            $exam=new Exam;
            $exam->exam_name= $this->exam_name;         
            $exam->academicyear_id= $this->academicyear_id;         
            $exam->month= $this->month;         
            $exam->status= $this->status;
            $exam->exam_sessions= $this->exam_sessions;
            $exam->save();

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Exam Created Successfully !!');

        } catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Exam !!');
        }
    }

    public function edit(Exam $exam)
    {
        if ($exam) 
        {
            $this->resetinput();
            $this->exam_id=$exam->id;
            $this->exam_name = $exam->exam_name;
            $this->academicyear_id = $exam->academicyear_id;
            $this->month = $exam->month;
            $this->status = $exam->status;          
            $this->exam_sessions = $exam->exam_sessions;          
            $this->mode='edit';
        }
    }

    public function update(Exam $exam)
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {
                    
            $exam->update([                         
                'exam_name' => $this->exam_name,              
                'academicyear_id' => $this->academicyear_id,              
                'month' => $this->month,              
                'status' => $this->status,  
                'exam_sessions' => $this->exam_sessions,                    
            ]);   

            DB::commit();

            $this->resetinput();

            $this->mode='all';

            $this->dispatch('alert',type:'success',message:'Exam Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Exam !!');
        }
    }
    

    public function deleteconfirmation($exam_id)
    {
        $this->delete_id=$exam_id;
        $this->dispatch('delete-confirmation');
    }
       
    public function delete(Exam  $exam)
    {  
        DB::beginTransaction();

        try 
        {
            $exam->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Exam Soft Deleted Successfully !!');

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Exam !!');
        }
    }
    
    public function restore($exam_id)
    {   
       
        DB::beginTransaction();

        try
        {
            $exam = Exam::withTrashed()->findOrFail($exam_id);

            $exam->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Exam Restored Successfully !!');

        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Exam !!');
        }
    }

    public function forcedelete()
    {   

        DB::beginTransaction();

        try 
        {  
            $exam = Exam::withTrashed()->find($this->delete_id);
            $exam->forceDelete();

            $this->delete_id=null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Exam Deleted Successfully !!');

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
                $this->dispatch('alert',type:'error',message:'Failed To Delete Exam !!');
            }
        }
    }


    public function status_update(Exam $exam)
    {
        DB::beginTransaction();

        try 
        {   
            if($exam->status)
            {
                $exam->status=0;
            }
            else
            {
                $exam->status=1;
            }
            $exam->update();

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

            $filename="Exam_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new ExportExam($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new ExportExam($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new ExportExam($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }     

            $this->dispatch('alert',type:'success',message:'Exam Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Exam !!');
        }

    }

    public function render()
    {
        if($this->mode!=='all')
        {   
            $this->academics=Academicyear::where('active',1)->pluck('year_name','id');
            $this->months=Month::where('is_active',1)->pluck('month_name','id');
        }

        $exams=Exam::select('id','exam_name','month','exam_sessions','academicyear_id','status','deleted_at')
        ->when($this->search, function ($query, $search) {  $query->search($search); })
        ->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.exam.all-exam',compact('exams'))->extends('layouts.user')->section('user');
    }
}
