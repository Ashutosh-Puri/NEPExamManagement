<?php

namespace App\Livewire\User\ExamPanel;

use Excel;
use App\Models\Faculty;
use App\Models\Subject;
use Livewire\Component;
use App\Models\Classview;
use App\Models\Exampanel;
use App\Models\Department;
use App\Models\Patternclass;
use Livewire\WithPagination;
use App\Models\Examorderpost;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Exports\User\ExamPanel\ExportExamPanel;

class AllExamPanel extends Component
{   
    ## By Ashutosh
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;
    public $faculty_id;
    public $examorderpost_id;
    public $subject_id;
    public $department_id;
    public $active_status;
    public $patternclass_id;
    public $description;
    
    #[Locked] 
    public $departments;
    #[Locked] 
    public $patternclasses;
    #[Locked] 
    public $examorderposts;
    #[Locked] 
    public $faculties=[];
    #[Locked] 
    public $subjects=[];
    #[Locked] 
    public $delete_id;
    #[Locked] 
    public $mode='all';
    #[Locked] 
    public $edit_id;

    protected function rules()
    {
        return  [
            'department_id' => ['required',Rule::exists('departments', 'id')],
            'faculty_id' => ['required',Rule::exists('faculties', 'id')],
            'examorderpost_id' => ['required',Rule::exists('exam_order_posts', 'id')],
            'subject_id' => ['required',Rule::exists('subjects', 'id')],
            'description' => ['nullable','string','max:50'],
        ];
    }

    public function messages()
    {   
        $messages = [
            'faculty_id.required' => 'The faculty ID is required.',
            'faculty_id.exists' => 'The selected faculty ID is invalid.',
            'department_id.required' => 'The faculty ID is required.',
            'department_id.exists' => 'The selected faculty ID is invalid.',
            'examorderpost_id.required' => 'The exam order post ID is required.',
            'examorderpost_id.exists' => 'The selected exam order post ID is invalid.',
            'subject_id.required' => 'The subject ID is required.',
            'subject_id.exists' => 'The selected subject ID is invalid.',
            'description.string' => 'The description must be a string.',
            'description.max' => 'The description may not be greater than 50 characters.',
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
            'department_id',
            'faculty_id',
            'examorderpost_id',
            'subject_id',
            'description',
            'active_status',
            'patternclass_id',
            'edit_id',
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

            $filename="Exam_Panel_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new ExportExamPanel($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new ExportExamPanel($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new ExportExamPanel($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Exam Panel Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Exam Panel !!');
        }

    }


    public function add()
    {   
        $this->validate();

       
        $existingRecord = Exampanel::where('examorderpost_id', 1)
        ->where('subject_id', $this->subject_id)
        ->where('active_status', 1)
        ->first();

        if ($existingRecord)
        {
            $existingRecord->update(['active_status' => 0]);
        }

        DB::beginTransaction();

        try 
        {
            
            $exampanel = new Exampanel;
            $exampanel->faculty_id = $this->faculty_id;
            $exampanel->examorderpost_id = $this->examorderpost_id;
            $exampanel->subject_id = $this->subject_id;
            $exampanel->description = $this->description;
            $exampanel->active_status = 1;
            $exampanel->save();

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Exam Panel Created Successfully !!');

        } catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Exam Panel !!');
        }
    }


    public function edit(Exampanel $exampanel)
    {   
        $this->resetinput();
        $this->edit_id=$exampanel->id;
        $this->faculty_id= $exampanel->faculty_id;
        $this->examorderpost_id= $exampanel->examorderpost_id;
        $this->subject_id= $exampanel->subject_id;
        $this->description= $exampanel->description;
        $this->active_status= $exampanel->active_status;    
        $this->mode='edit';
    }

    public function update(Exampanel $exampanel)
    {   
      
        $this->validate();

        DB::beginTransaction();

        try 
        {
            $exampanel->update([
                'faculty_id' => $this->faculty_id,
                'examorderpost_id'=>$this->examorderpost_id,
                'subject_id'=>$this->subject_id,
                'description'=>$this->description,
                'active_status'=>$this->active_status,
               
            ]);

            DB::commit();

            $this->resetinput();

            $this->mode='all';

            $this->dispatch('alert',type:'success',message:'Exam Panel Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Exam Panel !!');
        }
    }

    public function status(Exampanel $exampanel)
    {
        DB::beginTransaction();

        try 
        {   
            if($exampanel->active_status)
            {
                $exampanel->active_status=0;
            }
            else
            {
                $exampanel->active_status=1;
            }
            $exampanel->update();

            DB::commit();
            $this->dispatch('alert',type:'success',message:'Status Updated Successfully !!'  );
        }catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Status  !!');
        }
    }

    public function deleteconfirmation($panel_id)
    {
        $this->delete_id=$panel_id;
        $this->dispatch('delete-confirmation');
    }

    public function delete(Exampanel  $exampanel)
    {   
        
        DB::beginTransaction();

        try 
        {
            $exampanel->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Exam Panel Soft Deleted Successfully !!');

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Exam Panel !!');
        }
    }

    public function restore($panel_id)
    {   
       
        DB::beginTransaction();

        try
        {
            $exam_panel = Exampanel::withTrashed()->findOrFail($panel_id);

            $exam_panel->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Exam Panel Restored Successfully !!');

        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Exam Panel !!');
        }
    }

    public function forcedelete()
    {   

        DB::beginTransaction();

        try 
        { 
            $exampanel = Exampanel::withTrashed()->find($this->delete_id);
            $exampanel->forceDelete();

            $this->delete_id=null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Exam Panel Deleted Successfully !!');

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
                $this->dispatch('alert',type:'error',message:'Failed To Delete Exam Panel !!');
            }
        }
    }

   
    public function render()
    {
        
        if($this->mode!=='all')
        {
            $this->departments = Department::where('status', 1)->pluck('dept_name', 'id');
            $this->examorderposts = Examorderpost::where('status', 1)->pluck('post_name', 'id');
            $this->patternclasses=  Classview::select('id','classyear_name', 'course_name', 'pattern_name')->where('status',1)->get();
        }

        if ($this->department_id)
        {
            $this->faculties = Faculty::where('active', 1)->where('department_id', $this->department_id)->pluck('faculty_name', 'id');
        }

        if($this->patternclass_id)
        {
            $this->subjects = Subject::where('status', 1)->where('patternclass_id', $this->patternclass_id)->pluck('subject_name', 'id');
        }

        $panels=Exampanel::select('id','faculty_id','subject_id','examorderpost_id','description','active_status','deleted_at')
        ->where('active_status',1)
        ->with(['faculty:faculty_name,id','subject:subject_name,id','examorderpost:post_name,id'])
        ->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.exam-panel.all-exam-panel',compact('panels'))->extends('layouts.user')->section('user');
    }
}
