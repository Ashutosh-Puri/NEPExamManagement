<?php

namespace App\Livewire\User\ExamTimeTable;

use Carbon\Carbon;
use App\Models\Exam;
use App\Models\Subject;
use Livewire\Component;
use App\Models\Patternclass;
use Livewire\WithPagination;
use App\Models\Examtimetable;
use App\Models\Subjectbucket;
use App\Models\Timetableslot;
use App\Models\Subjectcategory;
use Illuminate\Validation\Rule;
use App\Models\Exampatternclass;
use Illuminate\Support\Facades\DB;


class SubjectExamTimeTable extends Component
{   
    ## By Ashutosh
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    #[Locked]
    public $mode='all';
    public $search='';
    public $perPage=10;
    public $sortColumn="id";
    public $sortColumnBy="ASC";

    #[Locked]
    public $subjects=[];
    public $subject_id;
    #[Locked]
    public $subject_categories=[];
    public $subjectcategory_id;
    #[Locked]
    public $exampatternclasses=[];
    public $exam_patternclasses_id;

    #[Locked]
    public $timeslots=[];
    #[Locked]
    public $delete_id;
    public $timeslot_id;
    public $timeslot_ids = []; 
    
    #[Locked]
    public $exam_time_table_id;

    public $examdates = [];
    public $examdate;

    protected function rules()
    {
        return [
            'examdate' => ['required', 'date'],    
            'timeslot_id' =>  ['required', 'integer'],    
        ];
    }

    public function messages()
    {   
        $messages = [
            'timeslot_id.required' => 'Time Slot is required.',
            'timeslot_id.integer' => 'Time Slot is not a valid.',
            'examdate.required' => 'Exam Date is required.',
            'examdate.date' => 'Exam Date is not a valid date.',
        ];
        return $messages;
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

    public function resetinput()
    {
          $this->reset([
            'subject_id',
            'subjectcategory_id',
            'exampatternclasses',
            'timeslot_id',
            'examdate',
            'timeslot_ids',
            'examdates',
        ]);
    }

    public function setmode($mode)
    {
        if($mode=='add')
        {
            $this->resetinput();
        }
        $this->mode=$mode;
    }

    public function UpdatedSubjectcategoryId()
    {   
        $this->exampatternclasses=[];
        $this->examdates=[];
        $this->timeslot_ids=[];
        $this->examdate=null;
        $this->timeslot_id=null;
        $this->subjects = Subject::where('status', 1)->where('subjectcategory_id', $this->subjectcategory_id)->orderBy('subject_sem','ASC')->pluck('subject_name', 'id');
    }

    public function UpdatedSubjectId()
    { 
        $this->exampatternclasses=[];
        $this->examdates=[];
        $this->timeslot_ids=[];
        $this->examdate=null;
        $this->timeslot_id=null;

        $exam=Exam::where('status',1)->first();

        $patternclass_ids = Subjectbucket::where('subject_id',$this->subject_id)->where('academicyear_id',$exam->academicyear_id)->pluck('patternclass_id');
             
        if ($patternclass_ids->isNotEmpty()) 
        {  
            $this->exampatternclasses = Exampatternclass::with(['patternclass.pattern:id,pattern_name','patternclass.courseclass.course:id,course_name','patternclass.courseclass.classyear:id,classyear_name',])
            ->where('exam_id',$exam->id)->whereIn('patternclass_id', $patternclass_ids)->get();

            if($this->mode=="bulkedit")
            {
                foreach ($this->exampatternclasses as $exampatternclass) 
                {
                    $exam_time_tables=Examtimetable::where('exam_patternclasses_id',$exampatternclass->id)->get();

                    foreach($exam_time_tables as $examtimetable)
                    {
                        $this->timeslot_ids[$exampatternclass->id]=$examtimetable->timeslot_id;
                        $this->examdates[$exampatternclass->id]=$examtimetable->examdate;
                    }
                } 
            }
        }
    }

    public function UpdatedExamdate()
    {  
        foreach ($this->exampatternclasses as $value) 
        {
            $this->examdates[$value->id]=$this->examdate;
        } 
    }

    public function UpdatedTimeslotId()
    {   
        foreach ($this->exampatternclasses as $value) 
        {
            $this->timeslot_ids[$value->id]=$this->timeslot_id;
        }
    }

    public function add()
    {
        DB::beginTransaction();

        try 
        {
            $exam_time_tables = [];
        
            foreach ($this->examdates as $exam_pattern_class_id => $examdate)
            {
                $exam_time_tables[] = [
                    'exam_patternclasses_id' =>$exam_pattern_class_id,  
                    'subject_id' => $this->subject_id,
                    'examdate' => $examdate,
                    'timeslot_id' => $this->timeslot_ids[$exam_pattern_class_id],
                    'status' => 1,
                    'created_at' => now(), 
                    'updated_at' => now(),
                ];
            }
        
            Examtimetable::insert($exam_time_tables);

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Exam Time Table Created Successfully !!'  );
            $this->resetinput();
            $this->mode='all';     

        } catch (\Exception $e) 
        {
            DB::rollback();
        
            $this->dispatch('alert',type:'error',message:'Failed To Create Exam Time Table  !!'  );
        }
    }

    public function edit(Examtimetable $examtimetable)
    { 
        $this->resetinput();
        $this->exam_time_table_id=$examtimetable->id;
        $this->subject_id= $examtimetable->subject->subject_code." ".$examtimetable->subject->subject_name;
        $this->exam_patternclasses_id= get_pattern_class_name($examtimetable->exampatternclass->patternclass_id); 
        $this->examdate=$examtimetable->examdate;
        $this->timeslot_id=$examtimetable->timeslot_id;
        $this->mode='edit';
    }

    public function update(Examtimetable $examtimetable)
    {   
        $this->validate();

        DB::beginTransaction();
        try 
        {
            $examtimetable->examdate= $this->examdate;
            $examtimetable->timeslot_id= $this->timeslot_id;
            $examtimetable->update();

            DB::commit();

            $this->exam_time_table_id=null;
            $this->subject_id=null;
            $this->exam_patternclasses_id=null;
            $this->examdate=null;
            $this->timeslot_id=null;
            $this->mode='all';

            $this->dispatch('alert',type:'success',message:'Exam Time Table Subject Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Exam Time Table Subject !!');
        }
    }

    public function delete(Examtimetable  $examtimetable)
    {   
        DB::beginTransaction();
        try 
        {   
            $examtimetable->delete();
            DB::commit();
            $this->dispatch('alert',type:'success',message:'Exam Time Table Subject Soft Delete Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Soft Delete Exam Time Table Subject !!');
        }
    }

    public function restore($id)
    {   
        DB::beginTransaction();
        try 
        {   
            $examtimetable = Examtimetable::withTrashed()->find($id);
            $examtimetable->restore();
            DB::commit();
            $this->dispatch('alert',type:'success',message:'Exam Time Table Subject Restore Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Restore Exam Time Table Subject !!');
        }
    }

    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }

    public function forcedelete()
    {  
        DB::beginTransaction();
        try 
        {   
            $examtimetable = Examtimetable::withTrashed()->find($this->delete_id);
            $examtimetable->forceDelete();
            DB::commit();
            $this->delete_id=null;
            $this->dispatch('alert',type:'success',message:'Exam Time Table Subject Deleted Successfully !!');
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
                $this->dispatch('alert',type:'error',message:'Failed to Delete Exam Time Table Subject !!');
            }
        }
    }

    public function bulk_update()
    {   
        DB::beginTransaction();
        try 
        {   
            foreach ($this->examdates as $exam_pattern_class_id => $examdate) 
            {
                $examtimetable=Examtimetable::where('subject_id',$this->subject_id)
                ->where('exam_patternclasses_id', $exam_pattern_class_id)
                ->update(['examdate' => $examdate,'timeslot_id' => $this->timeslot_ids[$exam_pattern_class_id], 'status' => 1, ]);
            }
    
            DB::commit();

            $this->exam_time_table_id=null;
            $this->subject_id=null;
            $this->exam_patternclasses_id=null;
            $this->examdate=null;
            $this->timeslot_id=null;
            $this->examdates=[];
            $this->timeslot_ids=[];

            $this->dispatch('alert',type:'success',message:'Exam Time Table Subject Updated Successfully !!');
            $this->mode='all';
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Exam Time Table Subject !!');
        }

    }

    public function render()
    {
        if($this->mode!=='all')
        {   
            $this->timeslots=Timetableslot::where('isactive',1)->pluck('timeslot','id');
        }

        if($this->mode!=='all' && $this->mode!=='edit')
        { 
            $this->subject_categories = Subjectcategory::whereIn('active', [1, 2])->pluck('subjectcategory', 'id');
        }

        $exam=Exam::where('status',1)->first();

        $examtimetables=Examtimetable::select('id','subject_id','exam_patternclasses_id','examdate','timeslot_id','deleted_at')
        ->whereIn('exam_patternclasses_id',$exam->exampatternclasses->pluck('id'))
        ->with(['timetableslot','subjectbucket.subject:subject_code,subject_name,id','exampatternclass.patternclass.pattern:pattern_name,id','exampatternclass.patternclass.courseclass.classyear:classyear_name,id','exampatternclass.patternclass.courseclass.course:course_name,id','Timetableslot:timeslot,id'])
        ->withTrashed()->when($this->search, function ($query, $search) { $query->search($search);})
        ->orderBy($this->sortColumn, $this->sortColumnBy)->get();

            $grouptimetable = $examtimetables->groupBy('exam_patternclasses_id');

        return view('livewire.user.exam-time-table.subject-exam-time-table',compact('examtimetables','grouptimetable'))->extends('layouts.user')->section('user');
    }
}
