<?php

namespace App\Livewire\Faculty\InternalMarksEntry;

use Exception;
use App\Models\Exam;
use App\Models\Subject;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use App\Models\Internalmarksbatch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Renderless;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Intbatchseatnoallocation;
use App\Exports\Faculty\InternalMarks\InternalMarksFormat\InternalMarksFormatExport;

class AllInternalMarksEntry extends Component
{
    use WithPagination;

    // public $perPage=10;

    public $appointed_role_id;
    public $batch_data;
    public $exam;
    public $show_student_list;
    public $selectedOption;
    public $flag;

    #[Locked]
    public $mode='all';
    #[Locked]
    public $appointed_roles;
    #[Locked]
    public $facultyhead_id;
    #[Locked]
    public $edit_batch_id;

    public function load_appointed_roles()
    {
        $this->appointed_roles = [
            ['id' => 1, 'value'=> 1, 'display' => 'Internal Examiner'],
        ];
    }

    public function setmode($mode)
    {
        if($mode=='edit')
        {
            $this->resetValidation();
        }
        $this->mode=$mode;
    }

    public function mount()
    {
        $this->load_appointed_roles();
        $this->exam = Exam::where('status', '1')->first();
        $this->flag = 0;
    }

    #[Renderless]
    public function download_format($batch_id)
    {
        try
        {
            $filename = "Internal_Marks_Format_" . now()->format('Y-m-d_H-i-s') . '.' . 'xlsx';

            return Excel::download(new InternalMarksFormatExport($batch_id), $filename);
        }
        catch (Exception $e)
        {
            Log::error($e);

            $this->dispatchBrowserEvent('alert', type: 'error', message: 'Failed To Export Internal Marks Format !!');
        }
    }

    public function edit($batch_id)
    {
        $this->batch_data = Internalmarksbatch::select('id','subject_id','subject_type','exam_patternclasses_id','created_at')
        ->with(['subject','exam_patternclass','intbatchseatnoallocations'])
        ->where('id',$batch_id)
        ->first();

        $this->setmode('edit');

    }

    public function confirm_marks(Internalmarksbatch $intbatch)
    {
        DB::beginTransaction();

        try {

            $intbatch->update(['status' => '5']);

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Internal Marks Confirmed Successfully !!');

        }
        catch (\Exception $e)
        {
            DB::rollBack();
            Log::error($e);
            $this->dispatch('alert',type:'error',message:'Failed To Confirm Internal Marks !!');

        }
    }


    public function render()
    {
        $appointed_batches = Internalmarksbatch::select('id','exam_patternclasses_id','subject_id','subject_type', 'totalBatchsize', 'totalAbsent', 'totalMarksentry', 'status', 'created_at')
        ->where('faculty_id',Auth::guard('faculty')->user()->id)
        ->whereNotNull('faculty_id')
        ->with([
            'subject:id,subject_name,subject_code',
            'exam_patternclass:id,intmarksstart_date,intmarksend_date,patternclass_id',
            'exam_patternclass.patternclass.pattern:id,pattern_name',
            'exam_patternclass.patternclass.courseclass.course:id,course_name',
            'exam_patternclass.patternclass.courseclass.classyear:id,classyear_name',
            'intbatchseatnoallocations'
            ])->get();

        return view('livewire.faculty.internal-marks-entry.all-internal-marks-entry',compact('appointed_batches'))->extends('layouts.faculty')->section('faculty');
    }
}
