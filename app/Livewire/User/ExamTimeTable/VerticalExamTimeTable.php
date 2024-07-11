<?php

namespace App\Livewire\User\ExamTimeTable;

use App\Models\Exam;
use App\Models\Subject;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Examtimetable;
use App\Models\Timetableslot;
use App\Models\Subjectvertical;
use App\Models\Exampatternclass;
use Illuminate\Support\Facades\DB;

class VerticalExamTimeTable extends Component
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
    public $subject_verticals=[];
    public $subjectvertical_id;

    #[Locked]
    public $exampatternclasses=[];
    public $exampatternclass_ids=[];    

    #[Locked]
    public $timeslots=[];
    public $timeslot_id;

    public $examdate;
    #[Locked]
    public $subject_name;
    #[Locked]
    public $exam_patternclass;
    #[Locked]
    public $exam_time_table_id;
    #[Locked]
    public $delete_id;

    protected function rules()
    {
        return [
            'subjectvertical_id' => ['required'],
            'exampatternclass_ids' => ['required', 'array'],    
            'timeslot_id' =>  ['required', 'integer'],    
            'examdate' =>  ['required', 'date'],    
        ];
    }

    public function messages()
    {   
        $messages = [
            'subjectvertical_id.required' => 'Subject Vertical is required.',
            'exampatternclass_ids.required' => 'Exam Pattern Class is required.',
            'timeslot_id.required' => 'Time Slot is required.',
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
            'examdate',
            'timeslot_id',
            'exampatternclass_ids',
            'exampatternclasses',

        ]);
    }

    public function setmode($mode)
    {
        $this->resetinput();
        $this->mode=$mode;
        $this->subjectvertical_id=null;
    }

    public function updatedSubjectverticalId()
    {   
        $this->resetinput();

        $patternclass_ids = Subject::where('status', 1)->where('subjectvertical_id', $this->subjectvertical_id)->pluck('patternclass_id');
        $exam_ids = Exam::where('status',1)->pluck('id');

        $this->exampatternclasses=Exampatternclass::select('id','patternclass_id')
        ->with([ 'patternclass.courseclass.course:id,course_name', 'patternclass.courseclass.classyear:id,classyear_name',])
        ->whereIn('patternclass_id',$patternclass_ids)
        ->whereIn('exam_id',$exam_ids)
        ->get();


        if($this->mode=="bulkedit")
        {
            $exam_time_tables=Examtimetable::whereIn('exam_patternclasses_id', $this->exampatternclasses->pluck('id'))->pluck('exam_patternclasses_id')->unique()->toArray();
            $this->exampatternclass_ids=$exam_time_tables;
        }

    }

    public function add()
    {   
        $this->validate();
        DB::beginTransaction();
        try 
        {   
            $exampatternclasses= Exampatternclass::whereIn('id',array_values($this->exampatternclass_ids))->pluck('patternclass_id','id');

            $exam_time_tables=[];
            foreach ($exampatternclasses as $exampatternclass_id => $patternclass_id) 
            {
               $subjects = Subject::where('status', 1)->where('subjectvertical_id', $this->subjectvertical_id)->where('patternclass_id',$patternclass_id)->pluck('id');
    
                foreach ( $subjects as $key => $subject_id) 
                {
                    $exam_time_tables[] = [
                        'exam_patternclasses_id' =>$exampatternclass_id ,  
                        'subject_id' => $subject_id,
                        'examdate' => $this->examdate,
                        'timeslot_id'=>$this->timeslot_id,
                        'status'=>1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
    
            }
    
            if(!empty($exam_time_tables))
            {
                Examtimetable::insert($exam_time_tables);
            }

            DB::commit();
            $this->resetinput();
            $this->dispatch('alert',type:'success',message:'Exam Time Table Created Successfully !!'  );
            $this->mode='all';
        }
        catch (\Exception $e) 
        {
            DB::rollback();
    
            $this->dispatch('alert',type:'error',message:'Failed To Create Exam Time Table !!'  );
        }
    }

    public function edit(Examtimetable $examtimetable)
    { 
        $this->resetinput();
        $this->exam_time_table_id=$examtimetable->id;
        $this->subject_name= $examtimetable->subject->subject_code." ".$examtimetable->subject->subject_name;
        $this->exam_patternclass= get_pattern_class_name($examtimetable->exampatternclass->patternclass_id); 
        $this->examdate=$examtimetable->examdate;
        $this->timeslot_id=$examtimetable->timeslot_id;
        $this->mode='edit';
    }

    public function update(Examtimetable $examtimetable)
    {   
        $this->validate([
            'timeslot_id' =>  ['required', 'integer'],    
            'examdate' =>  ['required', 'date'],  
        ]);

        DB::beginTransaction();
        try 
        {
            $examtimetable->examdate= $this->examdate;
            $examtimetable->timeslot_id= $this->timeslot_id;
            $examtimetable->update();

            DB::commit();

            $this->exam_time_table_id=null;
            $this->subject_name=null;
            $this->exam_patternclass=null;
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
        $this->validate();
        DB::beginTransaction();
        try 
        {  
            $exampatternclasses= Exampatternclass::whereIn('id',array_values($this->exampatternclass_ids))->pluck('patternclass_id','id');
            foreach ($exampatternclasses as $exampatternclass_id => $patternclass_id) 
            {
               $subjects = Subject::where('status', 1)->where('subjectvertical_id', $this->subjectvertical_id)->where('patternclass_id',$patternclass_id)->pluck('id');
    
                foreach ( $subjects as $key => $subject_id) 
                {
                    Examtimetable::updateOrCreate(
                        [
                            'exam_patternclasses_id' => $exampatternclass_id,
                            'subject_id' => $subject_id,
                        ],
                        [
                            'examdate' => $this->examdate,
                            'timeslot_id' => $this->timeslot_id,
                            'status' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
    
            }

            DB::commit();
            $this->resetinput();
            $this->dispatch('alert',type:'success',message:'Exam Time Table Updated Successfully !!'  );
            $this->mode='all';
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();
            dd($e);
            $this->dispatch('alert',type:'error',message:'Failed to Update Exam Time Table Subject !!');
        }

    }

    public function render()
    {   
        if($this->mode!=='all')
        {   
            $this->subject_verticals = Subjectvertical::whereIn('is_active', [1, 2])->pluck('subject_vertical', 'id');
            $this->timeslots=Timetableslot::where('isactive',1)->pluck('timeslot','id');
        }

        $exam=Exam::where('status',1)->first();
        $examtimetables=Examtimetable::select('id','subject_id','exam_patternclasses_id','examdate','timeslot_id','deleted_at')
        ->whereIn('exam_patternclasses_id',$exam->exampatternclasses->pluck('id'))
        ->with(['subject:subject_code,subject_name,id','exampatternclass.patternclass.pattern:pattern_name,id','exampatternclass.patternclass.courseclass.classyear:classyear_name,id','exampatternclass.patternclass.courseclass.course:course_name,id','timetableslot:timeslot,id'])
        ->withTrashed()-> when($this->search, function ($query, $search) {
            $query->search($search);
        })->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.exam-time-table.vertical-exam-time-table',compact('examtimetables'))->extends('layouts.user')->section('user');
    }
}
