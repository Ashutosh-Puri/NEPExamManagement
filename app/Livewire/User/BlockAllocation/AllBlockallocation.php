<?php

namespace App\Livewire\User\BlockAllocation;

use Excel;
use App\Models\Exam;
use App\Models\College;
use App\Models\Faculty;
use App\Models\Subject;
use Livewire\Component;
use App\Models\Classroom;
use App\Models\Blockmaster;
use Livewire\WithPagination;
use App\Models\Blockallocation;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use App\Models\Exampatternclass;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use Illuminate\Support\Facades\Auth;
use App\Exports\User\Blockallocation\ExportBlockallocation;

class AllBlockallocation extends Component
{
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

    #[Locked]
    public $blocks;
    public $block_id;
    #[Locked]
    public $exampatternclasses;
    public $exampatternclass_id;
    #[Locked]
    public $classrooms;
    public $classroom_id;
    #[Locked]
    public $subjects=[];
    public $subject_id;
    #[Locked]
    public $faculties;
    public $faculty_id;
    public $user_id;
    public $noofabsent;

    public $college_id;
    #[Locked]
    public $colleges;
    public $status;
    #[Locked] 
    public $edit_id;

    protected function rules()
    {
        return [
            'block_id' => ['required', 'integer', Rule::exists('blockmasters', 'id')],
            'classroom_id' => ['required', 'integer', Rule::exists('classrooms', 'id')],
            'exampatternclass_id' => ['required', 'integer', Rule::exists('exam_patternclasses', 'id')],
            'subject_id' => ['required', 'integer', Rule::exists('subjects', 'id')],
            'faculty_id' => ['required', 'integer', Rule::exists('faculties', 'id')],
            'college_id' => ['required', 'integer', Rule::exists('colleges', 'id')],
          
        ];
    }

    public function messages()
    {   
        $messages = [
            
            'block_id.required' => 'Please select a valid block.',
            'block_id.integer' => 'Block ID must be an integer.',
            'block_id.exists' => 'Selected block does not exist in the database.',
        
            'classroom_id.required' => 'Please select a valid classroom.',
            'classroom_id.integer' => 'Classroom ID must be an integer.',
            'classroom_id.exists' => 'Selected classroom does not exist in the database.',
        
            'exampatternclass_id.required' => 'Please select a valid exam pattern class.',
            'exampatternclass_id.integer' => 'Exam pattern class ID must be an integer.',
            'exampatternclass_id.exists' => 'Selected exam pattern class does not exist in the database.',
        
            'subject_id.required' => 'Please select a valid subject.',
            'subject_id.integer' => 'Subject ID must be an integer.',
            'subject_id.exists' => 'Selected subject does not exist in the database.',
        
            'faculty_id.required' => 'Please select a valid faculty.',
            'faculty_id.integer' => 'Faculty ID must be an integer.',
            'faculty_id.exists' => 'Selected faculty does not exist in the database.',
        
            'college_id.required' => 'Please select a valid college.',
            'college_id.integer' => 'College ID must be an integer.',
            'college_id.exists' => 'Selected college does not exist in the database.',
        
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
                'block_id',
                'classroom_id',
                'exampatternclass_id',
                'subject_id',
                'faculty_id',
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
    }

    #[Renderless]
    public function export()
    {   
        try 
        {
            set_time_limit(600);
            ini_set('memory_limit', '1024M');

            $filename="Block_Allocation_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    return Excel::download(new ExportBlockallocation($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    return Excel::download(new ExportBlockallocation($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    return Excel::download(new ExportBlockallocation($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Block Allocation Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Block Allocation !!');
        }
    }


    public function add()
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {   
            $blockallocation =  new Blockallocation;
            $blockallocation->create([
                'block_id' => $this->block_id,
                'classroom_id' => $this->classroom_id,
                'exampatternclass_id' => $this->exampatternclass_id,
                'user_id' =>Auth::guard('user')->user()->id,
                'subject_id' => $this->subject_id,
                'faculty_id' => $this->faculty_id,
                'status' => $this->status,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Blockallocation Created Successfully !!');

        } catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Blockallocation !!');
        }
    }


    public function edit(Blockallocation $blockallocation)
    {   
        $this->resetinput();
        $this->edit_id=$blockallocation->id;
        $this->block_id= $blockallocation->block_id;
        $this->classroom_id=$blockallocation->classroom_id;
        $this->exampatternclass_id=$blockallocation->exampatternclass_id;
        $this->subject_id=$blockallocation->subject_id;
        $this->faculty_id=$blockallocation->faculty_id;
        $this->status=$blockallocation->status;
  
        $this->mode='edit';
    }

    public function update(Blockallocation $blockallocation)
    {
        $this->validate();
        
        DB::beginTransaction();

        try 
        {   
            $blockallocation->update([
                'block_id' => $this->block_id,
                'classroom_id' => $this->classroom_id,
                'exampatternclass_id' => $this->exampatternclass_id,
                'subject_id' => $this->subject_id,
                'faculty_id' => $this->faculty_id,
                'status' => $this->status,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Blockallocation Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Blockallocation !!');
        }
    }


    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }


    public function delete(Blockallocation  $blockallocation)
    {  
        DB::beginTransaction();

        try
        {   
            $blockallocation->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Blockallocation Soft Deleted Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Blockallocation !!');
        }
    }
 
    
    public function restore($id)
    {   
        DB::beginTransaction();

        try
        {   
            $blockallocation = Blockallocation::withTrashed()->find($id);
            $blockallocation->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Blockallocation Restored Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Blockallocation !!');
        }
    }

  

    public function forcedelete()
    {   
        try 
        {
            $blockallocation = Blockallocation::withTrashed()->find($this->delete_id);
            $blockallocation->forceDelete();
            $this->delete_id=null;
            DB::commit();
            $this->dispatch('alert',type:'success',message:'Blockallocation Deleted Successfully !!');
            
        } catch (\Illuminate\Database\QueryException $e) {

            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'error',message:'This record is associated with another data. You cannot delete it !!');
            } 
            else
            {   
                $this->dispatch('alert',type:'error',message:'Failed To Delete Blockallocation !!');
            }
        }
    }


    public function updatestatus(Blockallocation $blockallocation)
    {
        DB::beginTransaction();

        try 
        {   
            if($blockallocation->status)
            {
                $blockallocation->status=0;
            }
            else
            {
                $blockallocation->status=1;
            }
            $blockallocation->update();

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
        $exam = Exam::where('status',1)->first();
        $this->blocks=Blockmaster::where('status',1)->pluck('block_name','id');
        $this->classrooms=Classroom::where('status',1)->pluck('class_name','id');
        $this->faculties=Faculty::where('active',1)->pluck('faculty_name','id');
        $this->exampatternclasses=Exampatternclass::where('exam_id',$exam->id)->select('patternclass_id','id')->with(['patternclass.pattern:id,pattern_name','patternclass.courseclass.course:id,course_name','patternclass.courseclass.classyear:id,classyear_name',])->get();
        $this->colleges=College::where('status',1)->pluck('college_name','id');
        
        if($this->exampatternclass_id)
        {
           $exampatternclass=Exampatternclass::find($this->exampatternclass_id);
           if($exampatternclass)
           {
            $this->subjects= Subject::where('patternclass_id',$exampatternclass->patternclass_id)->where('subject_type','IE')->pluck('subject_name','id');
           }
        }

        $blockallocations=Blockallocation::select('id','block_id','classroom_id','exampatternclass_id','subject_id','faculty_id','user_id','college_id','status','deleted_at')
        ->with(['block:block_name,id','classroom:class_name,id','subject:subject_name,id','faculty:faculty_name,id','user:name,id','college:college_name,id'])
         ->when($this->search, function ($query, $search) {
             $query->search($search);
         })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.blockallocation.all-blockallocation',compact('blockallocations'))->extends('layouts.user')->section('user');
    }
}
