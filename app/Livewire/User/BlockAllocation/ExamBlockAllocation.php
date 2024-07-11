<?php

namespace App\Livewire\User\BlockAllocation;

use PDF;
use Excel;
use Mpdf\Mpdf;
use App\Models\Exam;
use Livewire\Component;
use Mpdf\MpdfException;
use App\Models\Building;
use App\Models\Classroom;
use App\Models\Blockmaster;
use Livewire\WithPagination;
use App\Models\Examtimetable;
use App\Models\Timetableslot;
use App\Models\Examformmaster;
use App\Models\Blockallocation;
use Livewire\Attributes\Locked;
use App\Models\Exampatternclass;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Models\Studentblockallocation;
use App\Exports\User\BlockAllocation\BlockAllocationExport;

class ExamBlockAllocation extends Component
{
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    #[Locked] 
    public $delete_id;
    #[Locked]
    public $mode='all';
    public $perPage=10;
    public $search='';
    public $sortColumn="block_name";
    public $sortColumnBy="ASC";
    public $ext;


    public $exam;
    public $exampatternclasses;

    #[Locked]
    public $exam_time_tables=[];
    public $buildings=[];
    public $seatnos=[];
    public $examdate;
    public $time_slot;
    public $block;
    public $timeslot_id;

    protected function rules()
    {
        return [
            'time_slot' => ['required', 'string'],
            'timeslot_id' => ['required', 'integer'],
            'examdate' => ['required', 'date'],
            'buildings' => ['required', 'array'],
        ];
    }

    public function messages()
    {   
        return [
            'time_slot.required' => 'Time Slot is required.',
            'timeslot_id.required' => 'Time Slot is required.',
            'examdate.required' => 'Exam Date is required.',
            'buildings.required' => 'Buildings are required.',
        ];

    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function setmode($mode)
    {
        $this->mode=$mode;
        $this->resetinput();
    }

    public function resetinput()
    {   
        $this->reset(
            [
                'timeslot_id',
                'time_slot',
                'examdate',
                'buildings',
                'seatnos'
            ]
        );
    }

    public function select_class_room($examdate, $timeslot_id)
    {   

        $this->examdate = $examdate;
        $this->timeslot_id = $timeslot_id;
        $this->block=Blockmaster::where('status',1)->first();
        $this->exam_time_tables=Examtimetable::with([
            'exampatternclass.patternclass.pattern:id,pattern_name',
            'exampatternclass.patternclass.courseclass.course:id,course_name',
            'exampatternclass.patternclass.courseclass.classyear:id,classyear_name',
            'subject.studentexamforms.student.examstudentseatnos'
        ])->where('examdate',$examdate)->where('timeslot_id',$timeslot_id)->where('status',1)->get();
        $timeslot=Timetableslot::find($timeslot_id);
        if($timeslot)
        {
            $this->time_slot=$timeslot->timeslot;
        }

        $this->mode='edit';
    }

    public function allocate_class_room($examdate ,$timeslot_id ,$time_slot)
    {   

        $this->validate();

        DB::beginTransaction();

        try 
        {   
            $class_room_array=array();
            $totalblock=0;
            $totalstudent=0;
            $allocatedtime=explode(" ",$time_slot);
            
            
            $exam_time_table_olds=Examtimetable::select('exam_patternclasses_id','subject_id')->where('examdate',$examdate)
            ->whereIn('timeslot_id',Timetableslot::where('timeslot','like','%'.$allocatedtime[0].'%')->where('isactive',1)->pluck( 'id'))
            ->where('status',1)->get();
    
    
            $allocated_block=collect();
    
            foreach($exam_time_table_olds as $exam_time_table_old)
            {
                $classroom_ids=Blockallocation::where('exampatternclass_id',$exam_time_table_old->exam_patternclasses_id)->where('subject_id',$exam_time_table_old->subject_id)->pluck('classroom_id');
    
                $allocated_block->add($classroom_ids);
            }
    
            $allocated_blocks = $allocated_block->flatten(1);
        
    
            $timeslot=Timetableslot::find($timeslot_id);
            $block=Blockmaster::where('status',1)->select('id','block_size')->first();
    
            $exam_time_tables=Examtimetable::where('examdate',$examdate)->where('timeslot_id',$timeslot_id)->where('status','1')->get();
            
            
            foreach($this->buildings as $building_id)
            {
                $building=Building::find($building_id);
    
                foreach($building->classrooms->whereNotIn('id',$allocated_blocks->values()->all())->where('status',1) as $class_room)
                {
                    array_push($class_room_array,$class_room->id);
                } 
            }
            
        
            $i=0;
    
            
            foreach($exam_time_tables as $exam_time_table)
            { 
     
                $seatno=collect();
                $seatno1=collect();
    
                
                $exam_forms=$exam_time_table->subject->studentexamforms->where('exam_id',$this->exam->id)->where('ext_status',1);
    
                if($exam_forms->count()!=0)                     
                {
                    foreach($exam_forms as $exam_form)
                    {
                        if($exam_form->examformmaster->inwardstatus==1)
                        {
                            $seatno->add(['seatno'=>$exam_form->student->examstudentseatnos->last()->seatno, 'student_id'=>$exam_form->student_id] );
                        }
                                
                    }
                    
                    $seatno1 = $seatno->sortBy([
                        ['seatno', 'asc'],
                    ]);
                    
                    $seatnoblocks=array_chunk($seatno1->toArray(),$block->block_size);
                    if($seatno->count() < $block->block_size)
                    {
                        $totalblock=1;
                    }
                    else
                    {
                        $totalblock=round($seatno->count()/$block->block_size);
                    }
    
                    foreach($seatnoblocks as $seatnoblock)
                    {
                        $totalstudent= $totalstudent + count($seatnoblock);
    
                        if($totalstudent > $block->block_size)
                        { 
                            $i++;  
                            $totalstudent=count($seatnoblock);
                        }
    
                        $values = array('exampatternclass_id'=> $exam_time_table->exam_patternclasses_id,'subject_id'=>$exam_time_table->subject_id ,'classroom_id'=>$class_room_array[$i],'block_id'=>$block->id,'status'=>0);
                        $blockallocation=Blockallocation::create($values);
                           
                        foreach($seatnoblock as $sn)
                        {
                            $blockallocation->studentblockallocations()->create(['seatno'=>$sn['seatno'],'student_id'=>$sn['student_id']]);
                        }
                    }
                }
            }
    
            $this->dispatch('alert',type:'success',message:'Block Allocated Successfully !!');
            
            DB::commit();
            $this->resetinput();
            $this->mode="all";

        } catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Allocate Block !!');
        }
        
    } 

    #[Renderless]
    public function download_pdf($examdate, $timeslot_id)
    {
        ini_set('max_execution_time', 3000);
        ini_set('memory_limit', '2048M');
       
        $timeslot=Timetableslot::find($timeslot_id);

        $time_slot=$timeslot->timeslot;

        $exam_time_tables=Examtimetable::where('examdate',$examdate)->where('timeslot_id',$timeslot_id)->where('status',1)->get();
        $exam=$this->exam;

        $html = view('pdf.user.block_allocation.block_allocation_pdf', compact('exam_time_tables', 'exam', 'examdate', 'time_slot'))->render();

      
        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);
            
        
        $fileName = "Block_Allocation_" . date('d-m-Y', strtotime($examdate)) . "_" . $time_slot . '.pdf';
            
        return response()->streamDownload(function () use ($mpdf) { $mpdf->Output(); },$fileName);
        
    }

    #[Renderless]
    public function download_excel($examdate, $timeslot_id)
    {
        $timeslot=Timetableslot::find($timeslot_id);
        $time_slot=$timeslot->timeslot;
        ob_end_clean();
        return Excel::download(new BlockAllocationExport($examdate,$timeslot_id),"Block_Allocation_".date('d-m-Y', strtotime($examdate))."_".$time_slot.".xlsx");
    }

    public function merge_block($examdate, $timeslot_id)
    {
        $this->examdate=$examdate;
        $this->timeslot_id=$timeslot_id;   
        $this->exam_time_tables=Examtimetable::where('examdate',$examdate)->where('timeslot_id',$timeslot_id)->where('status',1)->get();
        $timeslot=Timetableslot::find($timeslot_id);
        $this->time_slot=$timeslot->timeslot;   
        $this->mode='merge';
    }

    public function merge_seatnos_in_block($examdate, $timeslot_id,$block_allocation_id)
    {   
        if(count($this->seatnos)==0)
        {
            $this->dispatch('alert',type:'info',message:'Please Enter Seat Numbers to Merge Block !!');
            return false;
        }

        $this->validate( [
            'timeslot_id' => ['required', 'integer'],
            'examdate' => ['required', 'date'],
            'seatnos' => ['required'],
        ]);
            
        DB::beginTransaction();

        try 
        {   
            $blockallocation=Blockallocation::find($block_allocation_id);
      
            $exam_time_tables=Examtimetable::where('examdate',$examdate)->where('timeslot_id',$timeslot_id)->where('status',1)->get();
            
            $block=Blockmaster::where('status',1)->select('id')->first();
    
         
            foreach($exam_time_tables  as $data)
            {
                $v=Studentblockallocation::withTrashed()->whereHas('blockallocation',function($query) use( $data) { $query->where('exampatternclass_id', $data->exam_patternclasses_id)->where('subject_id',  $data->subject_id);  })
                ->whereIn('seatno',explode(",",$this->seatnos[$block_allocation_id]));
      
        
                if($v->count()>0)
                {
                    $blockallocation=Blockallocation::create([
                        'exampatternclass_id'=>$data->exam_patternclasses_id,
                        'subject_id'=>$data->subject_id,
                        'classroom_id'=>$blockallocation->classroom_id,
                        'block_id'=>$block->id,
                        'status'=>0,
                    ]);
                    
                    $v->update(['bloackallocation_id'=>$blockallocation->id]);
                }
            }
           
            Blockallocation::withTrashed()->doesntHave('studentblockallocations')->forceDelete();
    
            DB::commit();
            $block_allocation_id=null;
            $this->resetinput();
    
           $this->dispatch('alert',type:'success',message:'Block Merged Successfully !!');
    
           $this->mode='all';
        } catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Merge Block !!');
        }
    }

    public function mount()
    {
        $this->exam=Exam::where('status',1)->first();
        $this->exampatternclasses=Exampatternclass::where('exam_id',$this->exam->id)->pluck('id');
        Examtimetable::whereIn('exam_patternclasses_id',$this->exampatternclasses)->update(['status'=>1]);
    }

    public function render()
    {   

        $grouptimetable=Examtimetable::with(['timetableslot', 'subject', ])
        ->whereIn('exam_patternclasses_id',$this->exampatternclasses)
        ->whereHas('subject',function($query){ $query->whereIn('subject_type',['IE','IEP']); })
        ->orderby('examdate')
        ->orderby('timeslot_id')
        ->get()
        ->groupBy('examdate');
        
        
        return view('livewire.user.block-allocation.block-allocation',compact('grouptimetable'))->extends('layouts.user')->section('user');
    }
}
