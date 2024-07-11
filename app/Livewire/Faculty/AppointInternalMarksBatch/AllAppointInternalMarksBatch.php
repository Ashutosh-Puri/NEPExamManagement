<?php

namespace App\Livewire\Faculty\AppointInternalMarksBatch;

use App\Models\Exam;
use App\Models\Faculty;
use App\Models\Subject;
use Livewire\Component;
use App\Models\Classview;
use App\Models\Department;
use App\Models\Patternclass;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use App\Models\Exampatternclass;
use App\Models\Hodappointsubject;
use App\Models\Internalmarksbatch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AllAppointInternalMarksBatch extends Component
{
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'delete'];

    public $search='';
    public $perPage=10;
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;

    public $patternclass_id;
    public $department_id;
    public $faculty_id;
    public $faculty_data;

    public $checked_batches=[];

    public $hod_subject_patternclass_ids;
    public $hod_subject_ids;
    public $int_batch;
    public $a;
    public $currentdate;

    #[Locked]
    public $patternclasses;
    #[Locked]
    public $delete_id;
    #[Locked]
    public $departments=[];
    #[Locked]
    public $faculties=[];

    public function updated($propertyName, $value)
    {
        if($propertyName == 'patternclass_id'){
            $this->loadDepartments();
        }elseif($propertyName == 'department_id'){
            $this->loadFaculties($value);
        }elseif($propertyName == 'faculty_id'){
            $this->loadFacultyData($value);
        }
    }

    public function loadDepartments()
    {
        $this->departments = Department::where('status',1)->pluck('dept_name','id');
    }

    public function loadFaculties($value)
    {
        $this->faculties = Faculty::where('department_id',$value)->where('active',1)->pluck('faculty_name','id');
    }

    public function loadFacultyData($value)
    {
        $this->faculty_data = Faculty::select('id','faculty_name','email','mobile_no')->where('id',$value)->first();
    }

    public function showbatch()
    {
        $this->a=1;
    }

    public function showbatchallocation()
    {
        $this->a=2;
        // $this->dispatch('alert',type:'info',message:'This Functionality Work In Progress !!');
    }

    // public function appointbatch()
    // {
    //     DB::beginTransaction();

    //     try
    //     {
    //         if(isset($this->faculty_id))
    //         {
    //             if(!empty($this->checked_batches))
    //             {
    //                 foreach($this->checked_batches as $batch_id => $value)
    //                 {
    //                     $int_batch = Internalmarksbatch::find($batch_id);
    //                     $int_batch->update(['faculty_id' => $this->faculty_id]);
    //                 }

    //                 DB::commit();

    //                 $this->dispatch('alert',type:'success',message:'Batch Appointed To Faculty Successfully !!');
    //             }
    //             else
    //             {
    //                 DB::rollBack();

    //                 $this->dispatch('alert',type:'info',message:'You Need To Select At Least One Batch To Appoint !!');
    //             }
    //         }
    //         else
    //         {
    //             $this->dispatch('alert',type:'info',message:'You Need To Select Faculty To Appoint Batch !!');
    //         }
    //     } catch (\Exception $e) {

    //         DB::rollBack();

    //         $this->dispatch('alert',type:'error',message:'Failed To Appoint Batch !!');
    //     }
    // }

    public function appointbatch()
    {
        if (!isset($this->faculty_id)) {
            $this->dispatch('alert', type: 'info', message: 'You Need To Select Faculty To Appoint Batch !!');
            return;
        }

        if (empty($this->checked_batches)) {
            $this->dispatch('alert', type: 'info', message: 'You Need To Select At Least One Batch To Appoint !!');
            return;
        }

        DB::beginTransaction();

        try {

            $batch_ids = array_keys($this->checked_batches);

            Internalmarksbatch::whereIn('id', $batch_ids)->update(['faculty_id' => $this->faculty_id]);

            DB::commit();

            $this->dispatch('alert', type: 'success', message: 'Batch Appointed To Faculty Successfully !!');
        } catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert', type: 'error', message: 'Failed To Appoint Batch !!');
        }
    }

    public function removebatch(Internalmarksbatch $batch)
    {
        DB::beginTransaction();

        try {


            if ($batch->status === 5)
            {
                $this->dispatch('alert', type: 'info', message: 'Batch cannot be removed as it is already in status 5 !!');
                return;
            }

            $batch->update(['faculty_id'=>null]);

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Appointed Batch Removed Successfully !!');

        } catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Remove Appointed Batch !!');

        }
    }

    public function mount()
    {
        $this->currentdate=Carbon::now();

        $exam = Exam::where('status', '1')->first();

        if ($exam)
        {
            $all_exam_pattern_classes = Exampatternclass::where('launch_status', 1)->where('exam_id', $exam->id)->pluck('id');

            // $this->int_batch_patternclass_ids = Internalmarksbatch::whereNull('faculty_id')
            // ->whereIn('exam_patternclasses_id', $all_exam_pattern_classes)
            // ->where('status', '!=', '0')
            // ->with('exam_patternclass')
            // ->get()
            // ->pluck('exam_patternclass.patternclass_id')
            // ->unique();

            $this->int_batch_patternclass_ids = Internalmarksbatch::whereIn('exam_patternclasses_id', $all_exam_pattern_classes)
            ->where('status', '!=', '0')
            ->with('exam_patternclass')
            ->get()
            ->pluck('exam_patternclass.patternclass_id')
            ->unique();
        }

        $this->hod_subject_patternclass_ids = Subject::whereHas('hodappointsubjects',function( $query){
            $query->where('faculty_id',Auth::guard('faculty')->user()->id);
        })->pluck('patternclass_id');

        // $this->patternclasses = Patternclass::select('id', 'class_id', 'pattern_id')
        // ->with([
        //     'pattern:id,pattern_name',
        //     'courseclass.course:id,course_name',
        //     'exampatternclasses:id,intmarksstart_date,intmarksend_date,patternclass_id'
        // ])
        // ->whereIn('id',$this->int_batch_patternclass_ids)
        // ->whereIn('id',$this->hod_subject_patternclass_ids)
        // ->where('status',1)
        // ->get();

        $this->patternclasses = Classview::select('id','classyear_name', 'course_name', 'pattern_name')
        ->with(['exampatternclasses:id,intmarksstart_date,intmarksend_date,patternclass_id'])
        ->whereIn('id',$this->int_batch_patternclass_ids)
        ->whereIn('id',$this->hod_subject_patternclass_ids)
        ->where('status',1)
        ->get();

        $this->hod_subject_ids = Hodappointsubject::where('faculty_id',Auth::guard('faculty')->user()->id)->pluck('subject_id');
    }

    public function render()
    {
        $batches = [];

        if ($this->a === 1 || $this->a === 2)
        {
            $query = Internalmarksbatch::select('id', 'exam_patternclasses_id', 'subject_id', 'subject_type', 'created_at')
                ->with([
                    'exam_patternclass:id,intmarksstart_date,intmarksend_date,patternclass_id',
                    'subject.patternclass.pattern:id,pattern_name',
                    'subject.patternclass.courseclass.course:id,course_name',
                    'subject.patternclass.courseclass.classyear:id,classyear_name'
                ])
                ->whereIn('subject_id', $this->hod_subject_ids);

            if ($this->a === 1) {
                $query->whereNull('faculty_id')
                    ->whereHas('exam_patternclass', function ($query) {
                        $query->where('patternclass_id', $this->patternclass_id);
                    });
            } elseif ($this->a === 2) {
                $query->whereNotNull('faculty_id')
                    ->whereHas('exam_patternclass', function ($query) {
                        $query->where('patternclass_id', $this->patternclass_id);
                    });
            }

            $batches = $query->paginate($this->perPage);
        }

    return view('livewire.faculty.appoint-internal-marks-batch.all-appoint-internal-marks-batch',compact('batches'))->extends('layouts.faculty')->section('faculty');
    }
}
