<?php

namespace App\Livewire\Faculty\AbsentEntry;

use Carbon\Carbon;
use App\Models\Exam;
use App\Models\Subject;
use Livewire\Component;
use App\Models\Classview;
use App\Models\Studentmark;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use App\Models\Internalmarksbatch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Intbatchseatnoallocation;

class AllAbsentEntry extends Component
{
    use WithPagination;

    public $perPage=10;

    public $patternclass_id;
    public $subject_id;
    public $subject_type;
    public $batch_id;
    public $seatno;
    public $int_batch;

    public $faculty;
    public $exam;
    public $currentdate;
    public $examstud;
    public $option;
    public $marks_type;
    public $absent_students;

    #[Locked]
    public $subjects=[];
    #[Locked]
    public $batches;
    #[Locked]
    public $subject_types=[];
    #[Locked]
    public $patternclasses;

    public function updated($propertyName, $value)
    {
        if($propertyName == 'patternclass_id')
        {
            $this->load_subjects($value);
        }
        elseif($propertyName == 'subject_id')
        {
            $this->load_subject_types($value);
        }
        elseif($propertyName == 'subject_type')
        {
            $this->load_int_batches();
        }
        elseif($propertyName == 'batch_id')
        {
            $this->load_ab_students($value);
        }
    }

    public function resetinput()
    {
        $this->reset([
            'seatno',
        ]);

    }

    public function load_subjects($value)
    {
        $fac_intbatch_sub_ids = $this->faculty->internalmarksbatches->pluck('subject_id')->unique();

        $this->subjects = Subject::select('id', 'subject_code', 'subject_name')
        ->whereIn('id', $fac_intbatch_sub_ids)
        ->where('patternclass_id', $value)
        ->get();

    }

    // public function load_subject_types($value)
    // {
    //     $str = [];
    //     $subject = Subject::find($value);

    //     if (!$subject)
    //     {
    //         $this->subject_types = $str;
    //         return;
    //     }

    //     switch ($subject->subject_type)
    //     {
    //         case "IG":
    //             $str[] = "IG";
    //             break;
    //         case "IEG":
    //             $str[] = "I";
    //             $str[] = "IEG";
    //             break;
    //         case "G":
    //             $str[] = "Grade";
    //             break;
    //         case "I":
    //         case "IE":
    //             $str[] = "I";
    //             break;
    //         case "IP":
    //         case "IEP":
    //             $str[] = "I";
    //             $str[] = "P";
    //             break;
    //         default:
    //             break;
    //     }

    //     $this->subject_types = $str;
    // }

    public function load_subject_types($value)
    {
        $str = [];
        $subject = Subject::find($value);

        if (!$subject)
        {
            $this->subject_types = $str;
            return;
        }

        switch ($subject->subject_type)
        {
            case "IG":
                $str[] = "IG";
                break;
            case "IEG":
                $str[] = "I";
                $str[] = "IEG";
                break;
            case "G":
                $str[] = "G";
                break;
            case "I":
            case "IE":
                $str[] = "I";
                break;
            case "IP":
            case "IEP":
                $str[] = "I";
                $str[] = "P";
                break;
            default:
                break;
        }

        $this->subject_types = $str;
    }

    public function load_int_batches()
    {
        $this->batches = Internalmarksbatch::select('id', 'subject_id', 'created_at')->where('subject_type',$this->subject_type)->where('subject_id',$this->subject_id)->get();
    }

    public function searchseatno()
    {
        DB::beginTransaction();

        $subject = Subject::find($this->subject_id);
        $this->marks_type = $this->determine_marks_type($subject->subject_type, $this->int_batch->subject_type);

        try
        {
            $this->examstud = Intbatchseatnoallocation::whereRelation('internalmarksbatch', 'subject_id', $this->subject_id)
            ->where('seatno', $this->seatno)
            ->where('intbatch_id', $this->batch_id)
            ->get();

            if ($this->examstud->isEmpty())
            {
                    $this->examstud = null;
                    $this->dispatch('alert', type: 'info', message: 'Invalid Seat No or belongs to another batch. Please check. !!');
            }
            else
            {
                if (!$this->examstud->first()->student->studentmarks
                    ->where('seatno', $this->seatno)
                    ->where($this->marks_type, '-1')
                    ->where('subject_id', $this->subject_id)
                    ->where('exam_id', $this->exam->id)
                    ->isEmpty())
                {
                    $this->examstud = null;
                    $this->dispatch('alert', type: 'info', message: 'Student is Already Absent !!');
                }
            }
        }
        catch (\Exception $e)
        {
            DB::rollBack();

            Log::error($e);

            $this->dispatch('alert', type: 'error', message: 'Failed to Search Seat No. Please try again !!');
        }
    }

    // public function load_ab_students($batchid)
    // {
    //     $this->int_batch = Internalmarksbatch::find($batchid);

    //     $subject = Subject::find($this->subject_id);

    //     $this->marks_type = $this->determine_marks_type($subject->subject_type, $this->int_batch->subject_type);

    //     $intbatchseatno = Intbatchseatnoallocation::whereRelation('internalmarksbatch', 'status', '!=', '5')
    //     ->where('intbatch_id', $batchid)
    //     ->pluck('seatno');

    //     if ($this->int_batch->subject_type == "G")
    //     {
    //         $this->absent_students = Studentmark::where('exam_id', $this->exam->id)
    //             ->where($this->marks_type, 'Ab')
    //             ->whereIn('seatno', $intbatchseatno)
    //             ->whereHas('intbatchseatnoallocation', function($query) use ($intbatchseatno) {
    //                 $query->whereIn('seatno', $intbatchseatno)
    //                       ->where('grade', 'Ab');
    //             })
    //             ->pluck('student_id');
    //     }

    //     if ($this->int_batch->subject_type !== "G")
    //     {
    //         $this->absent_students = Studentmark::where('exam_id', $this->exam->id)
    //             ->where($this->marks_type, '-1')
    //             ->whereIn('seatno', $intbatchseatno)
    //             ->whereHas('intbatchseatnoallocation', function($query) use ($intbatchseatno){
    //                 $query->whereIn('seatno', $intbatchseatno)
    //                       ->where('marks', '-1');
    //             })
    //             ->pluck('student_id');
    //     }

    // }

    public function load_ab_students($batchid)
    {
        $this->int_batch = Internalmarksbatch::find($batchid);

        $subject = Subject::find($this->subject_id);

        $this->marks_type = $this->determine_marks_type($subject->subject_type, $this->int_batch->subject_type);

        $intbatchseatno = Intbatchseatnoallocation::whereRelation('internalmarksbatch', 'status', '!=', '5')
        ->where('intbatch_id', $batchid)
        ->pluck('seatno');

        if ($this->int_batch->subject_type == "G")
        {
            $this->absent_students = Studentmark::where('exam_id', $this->exam->id)
                ->where($this->marks_type, 'Ab')
                ->whereIn('seatno', $intbatchseatno)
                ->whereHas('intbatchseatnoallocation', function($query) use ($intbatchseatno) {
                    $query->whereIn('seatno', $intbatchseatno)
                          ->where('status', 2);
                })
                ->pluck('student_id');
        }

        if ($this->int_batch->subject_type !== "G")
        {
            $this->absent_students = Studentmark::where('exam_id', $this->exam->id)
                ->where($this->marks_type, '-1')
                ->whereIn('seatno', $intbatchseatno)
                ->whereHas('intbatchseatnoallocation', function($query) use ($intbatchseatno) {
                    $query->whereIn('seatno', $intbatchseatno)
                    ->where('status', 2);
                })
                ->pluck('student_id');
        }

    }

    public function removeseatnoab($marksid)
    {
        DB::beginTransaction();

        try
        {
            $subject = Subject::find($this->subject_id);
            $this->marks_type = $this->determine_marks_type($subject->subject_type, $this->int_batch->subject_type);

            $studmark = Studentmark::find($marksid);

            if ($studmark)
            {
                $studmark->update([$this->marks_type => null]);

                $studmark->intbatchseatnoallocation->update(['status' => 1]);

                DB::commit();

                $this->dispatch('alert', type: 'success', message: 'Student Removed From Absent Entry Successfully!');
            }
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            Log::error($e);
            $this->dispatch('alert', type: 'error', message: 'Failed To Remove Student Absent Entry !!');
        }
    }

    // public function saveseatnoab($marks_id)
    // {
    //     DB::beginTransaction();

    //     try
    //     {
    //         $subject = Subject::find($this->subject_id);
    //         $this->marks_type = $this->determine_marks_type($subject->subject_type, $this->int_batch->subject_type);

    //         $values = [
    //         'seatno' => $this->seatno,
    //         'student_id' => $this->examstud->first()->student_id,
    //         'subject_id' => $this->subject_id,
    //         'sem' => $subject->subject_sem,
    //         $this->marks_type => '-1',
    //         'exam_id' => $this->exam->id,
    //         'patternclass_id' => $subject->patternclass_id,
    //         ];

    //         $studmark = Studentmark::upsert($values, ['student_id', 'subject_id', 'seatno', 'exam_id']);

    //         $this->examstud = null;

    //         DB::commit();

    //         $this->resetinput();

    //         $this->load_ab_students($this->batch_id);

    //         $this->dispatch('alert', type: 'success', message: 'Student Absence Entry Saved Successfully !!');
    //     }
    //     catch (\Exception $e)
    //     {
    //         DB::rollBack();

    //         Log::error($e);

    //         $this->dispatch('alert', type: 'error', message: 'Failed to Save Student Absent Entry !!');
    //     }
    // }

    public function saveseatnoab($marks_id)
    {
        DB::beginTransaction();

        try
        {
            $subject = Subject::find($this->subject_id);
            $this->marks_type = $this->determine_marks_type($subject->subject_type, $this->int_batch->subject_type);
            $student_id = $this->examstud->first()->student_id;

            $upsert_values_other_table = [
                'intbatch_id' => $this->int_batch->id,
                'student_id' => $student_id,
                'seatno' => $this->seatno,
                'status' => 2,
            ];

            $student_mark_values = [
                'seatno' => $this->seatno,
                'student_id' => $student_id,
                'subject_id' => $this->subject_id,
                'sem' => $subject->subject_sem,
                'exam_id' => $this->exam->id,
                'patternclass_id' => $subject->patternclass_id,
                $this->marks_type => $this->int_batch->subject_type == "G" ? 'Ab' : -1,
            ];

            Intbatchseatnoallocation::upsert($upsert_values_other_table, ['intbatch_id', 'student_id', 'seatno']);
            Studentmark::upsert($student_mark_values, ['student_id', 'subject_id', 'seatno', 'exam_id']);

            $this->examstud = null;
            DB::commit();
            $this->resetinput();
            $this->load_ab_students($this->batch_id);
            $this->dispatch('alert', type: 'success', message: 'Student Absence Entry Saved Successfully !!');
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            Log::error($e);
            $this->dispatch('alert', type: 'error', message: 'Failed to Save Student Absent Entry !!');
        }
    }

    private function determine_marks_type($subject_type, $intbatch_subject_type)
    {
        switch ($subject_type)
        {
            case "IG":
                return $intbatch_subject_type == "IG" ? "int_marks" : null;
            case "IEG":
                if ($intbatch_subject_type == "I")
                {
                    return "int_marks";
                } elseif ($intbatch_subject_type == "IEG")
                {
                    return "ext_marks";
                }
                break;
            case "G":
                return "subject_grade";
            case "I":
                return "int_marks";
            case "IE":
            case "IP":
                return $this->subject_type == 'I' ? 'int_marks' : ($this->subject_type == 'P' ? 'ext_marks' : 'int_practical_marks');
            case "IEP":
                return $this->subject_type == 'I' ? 'int_marks' : ($this->subject_type == 'P' ? 'int_practical_marks' : 'ext_marks');
            default:
                return null;
        }
    }

    public function mount()
    {
        $this->currentdate=Carbon::now();

        $this->faculty = Auth::guard('faculty')->user();

        $this->exam=Exam::where('status','1')->first();

        $exam_patternclass_id = $this->faculty->internalmarksbatches->pluck('exam_patternclasses_id')->unique();

        $patternclass_ids = Internalmarksbatch::whereIn('exam_patternclasses_id', $exam_patternclass_id)
        ->where('status', '!=', '0')
        ->with('exam_patternclass')
        ->get()
        ->pluck('exam_patternclass.patternclass_id')
        ->unique();

        $this->patternclasses = Classview::select('id','classyear_name', 'course_name', 'pattern_name')
        ->with(['exampatternclasses:id,intmarksstart_date,intmarksend_date,patternclass_id'])
        ->whereIn('id',$patternclass_ids)
        ->where('status',1)
        ->get();
    }

    public function render()
    {
        $students = collect([]);

        if(isset($this->absent_students) && isset($this->int_batch) && isset($this->marks_type) && $this->option == 2)
        {
            $students = Studentmark::select('id','seatno','student_id','subject_id','int_marks')
            ->with(['subject','patternclass','student'])
            ->whereNotNull($this->marks_type)
            ->where('exam_id',$this->exam->id)
            ->where('subject_id',$this->int_batch->subject_id)
            ->where('patternclass_id',$this->int_batch->subject->patternclass_id)
            ->whereIn('student_id',$this->absent_students)
            ->paginate($this->perPage);
        }

        return view('livewire.faculty.absent-entry.all-absent-entry',compact('students'))->extends('layouts.faculty')->section('faculty');
    }
}
