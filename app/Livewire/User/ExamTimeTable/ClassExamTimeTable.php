<?php

namespace App\Livewire\User\ExamTimeTable;

use PDF;
use Excel;
use App\Models\Exam;
use App\Models\Subject;
use Livewire\Component;
use App\Models\Semester;
use Livewire\WithPagination;
use App\Models\Examtimetable;
use App\Models\Subjectbucket;
use App\Models\Timetableslot;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use App\Models\Exampatternclass;
use Illuminate\Support\Facades\DB;
use App\Exports\User\ExamTimeTable\ExportExamTimeTable;

class ClassExamTimeTable extends Component
{   
    ## By Ashutosh
    use WithPagination;
    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;

    public $sem;
    public $exam_pattern_class_id;
    public $pattern_class_id;
    public $timeslot_id;
    public $subject_id;

    #[Locked] 
    public $exam;
    #[Locked] 
    public $semesters=[];
    #[Locked] 
    public $mode='all';
    #[Locked] 
    public $subjects=[];
    #[Locked] 
    public $timeslots=[];
    public $examdates=[];
    public $timeslot_ids=[];
    public $deletetable=[];

   
    protected function rules()
    {
        return [
            'exam_pattern_class_id' => ['required',Rule::exists('exam_patternclasses', 'id')],
            'examdates.*' => ['nullable', 'date'],    
            'timeslot_ids.*' =>  ['nullable', 'integer'],    
        ];
    }

    public function messages()
    {   
        $messages = [
            'exam_pattern_class_id.required' => 'The exam pattern class ID is required.',
            'exam_pattern_class_id.exists' => 'The selected exam pattern class ID is invalid.',
            'timeslot_id.required' => 'The timeslot ID is required.',
            'timeslot_id.exists' => 'The selected timeslot ID is invalid.',
            'examdates.*.date' => 'One or more of the exam dates is not a valid date.',
        ];
        return $messages;
    }
 
    public function resetinput()
    {
        $this->reset([
            'sem',
            'subjects',
            'subject_id',
            'timeslot_id',
            'timeslot_ids',
            'examdates',
        ]); 
    }

    public function updated($property)
    {
        $this->validateOnly($property);
        
    }

    public function setmode($mode)
    {
        $this->mode=$mode;
        $this->exam_pattern_class_id=null;
        $this->pattern_class_id=null;
        $this->deletetable=[];
        $this->resetValidation();
    }
    
    public function create(Exampatternclass  $exampatternclass)
    {
        $this->pattern_class_id=$exampatternclass->patternclass_id;
        $this->exam_pattern_class_id=$exampatternclass->id;
        $this->semesters=Semester::where('status',1)->get();
        $this->timeslots=Timetableslot::where('isactive',1)->pluck('timeslot','id');
        $this->subjects = Subject::where('is_panel',1)->where('patternclass_id',$this->pattern_class_id)->where('status', 1)->orderBy('subject_sem','ASC')->get();
        $this->mode='add';
    }

    public function updatedTimeslotId()
    {
        $this->timeslot_ids=[];
        $this->examdates=[];

        foreach ($this->subjects as $value) {
            $this->timeslot_ids[$value->id]=$this->timeslot_id;
        }
    }   

    public function updatedSem()
    {   
        $this->timeslot_ids=[];
        $this->examdates=[];

        $this->subjects = Subject::where('is_panel',1)->where('patternclass_id',$this->pattern_class_id)->where('status', 1)->where('subject_sem', $this->sem)->orderBy('subject_sem','ASC')->get();
    }

    public function store(Exampatternclass  $exampatternclass)
    {   
        $this->validate();

        if(count($this->examdates) >0 && count($this->timeslot_ids)>0)
        {
            DB::beginTransaction();
            try 
            {
                $exam_time_table =[];
    
                foreach($this->examdates as $subject_id => $examdate)
                {
                    $exam_time_table[]=[
                        'subject_id'=>$subject_id,
                        'exam_patternclasses_id'=>$exampatternclass->id,
                        'examdate'=>$examdate,
                        'timeslot_id'=>$this->timeslot_ids[$subject_id],
                        'status'=>1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
    
                Examtimetable::insert($exam_time_table);

                DB::commit();

                $this->dispatch('alert',type:'success',message:'Exam Time Table Created Successfully !!'  );
                $this->mode='all';
            }
            catch (\Exception $e) 
            {
                DB::rollback();
        
                $this->dispatch('alert',type:'error',message:'Failed To Create Exam Time Table !!'  );
            }
        }else
        {   
            $this->dispatch('alert',type:'info',message:'At Least One Subject Date & Time Select !!'  );
            return false ;
        }
        
        $this->resetinput();
        $this->exam_pattern_class_id=null;
        $this->pattern_class_id=null;
    }

    public function edit(Exampatternclass $exampatternclass)
    {
        $this->resetinput();
        $this->exam_pattern_class_id=$exampatternclass->id;
        $exam_time_tables=Examtimetable::where('exam_patternclasses_id',$exampatternclass->id)->get();
        $this->deletetable = $exam_time_tables->pluck('subject_id','id')->toArray();
        $this->semesters=Semester::where('status',1)->get();
        $this->timeslots=Timetableslot::where('isactive',1)->pluck('timeslot','id');
        $this->subjects = Subject::where('is_panel',1)->where('patternclass_id', $exampatternclass->patternclass_id)->where('status', 1)->orderBy('subject_sem','ASC')->get();
         
        foreach($exam_time_tables as $examtimetable)
        {
            $this->timeslot_ids[$examtimetable->subject_id]=$examtimetable->timeslot_id;
            $this->examdates[$examtimetable->subject_id]=$examtimetable->examdate;
        }

        $this->mode='edit';
    }

    public function delete_time_table_entry( $examtimetable_id)
    {       
        DB::beginTransaction();
    
        try 
        {   
            $examtimetable = Examtimetable::withTrashed()->find($examtimetable_id);
            $exam_pattern_class= Exampatternclass::withTrashed()->find($examtimetable->exam_patternclasses_id); ;
            $examtimetable->forceDelete();
            DB::commit();
            
            $this->edit($exam_pattern_class);
                
            $this->dispatch('alert',type:'success',message:'Exam Time Table Subject Deleted Successfully !!'  );

        } catch (\Exception $e) 
        {
                    
            DB::rollback();
    
            $this->dispatch('alert',type:'error',message:'Failed To Delete  Exam Time Table Subject !!'  );
        }
       
     
    }

    public function update(Exampatternclass $exampatternclass)
    {
        DB::beginTransaction();
    
        try 
        {
            $exam_time_table =[];
    
            foreach($this->examdates as $subject_id => $examdate)
            {
                $exam_time_table[]=[
                    'subject_id'=>$subject_id,
                    'exam_patternclasses_id'=>$exampatternclass->id,
                    'examdate'=>$examdate,
                    'timeslot_id'=>$this->timeslot_ids[$subject_id],
                    'status'=>1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            foreach ($exam_time_table as $record) {
                Examtimetable::updateOrCreate(
                    [
                        'subject_id' => $record['subject_id'],
                        'exam_patternclasses_id' => $record['exam_patternclasses_id']
                    ],
                    $record
                );
            }

            DB::commit();
    
            $this->dispatch('alert',type:'success',message:'Exam Time Table Updated Successfully !!'  );
            $this->resetinput();
            $this->setmode('all');
            $this->isEditing = false;
    
        } catch (\Exception $e) 
        {

            DB::rollback();
    
            $this->dispatch('alert',type:'error',message:'Failed To Update Exam Time Table !!'  );
        }

        $this->deletetable=[];
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

    public function mount()
    {
        $this->exam = Exam::where('status',1)->first();
    }

    public function render()
    {
        $exampatternclasses=Exampatternclass::select('id','exam_id','patternclass_id','deleted_at')
        ->with([
            'patternclass.pattern:id,pattern_name',
            'patternclass.courseclass.course:id,course_name',
            'patternclass.courseclass.classyear:id,classyear_name',
            ])->where('exam_id',$this->exam->id)    
        ->when($this->search, function ($query, $search) {
          $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.exam-time-table.all-exam-time-table',compact('exampatternclasses'))->extends('layouts.user')->section('user');
    }
}
