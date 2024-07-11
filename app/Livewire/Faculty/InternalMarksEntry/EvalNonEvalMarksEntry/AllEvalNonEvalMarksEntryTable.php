<?php

namespace App\Livewire\Faculty\InternalMarksEntry\EvalNonEvalMarksEntry;

use App\Models\Exam;
use App\Models\Student;
use Livewire\Component;
use App\Models\Studentmark;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Intbatchseatnoallocation;

class AllEvalNonEvalMarksEntryTable extends Component
{
    use WithPagination;

    public $perPage=10;

    public $batchid;
    public $selectedGrade=[];
    public $selectedMarks=[];
    public $selectedCmb=[];
    public $marks;
    public $marks_type=null;
    public $exam;
    public $grade;
    public $outMarks;
    public $cmdgrade11 = ['O', 'A+', 'A', 'B+', 'B', 'C', 'P', 'F', 'Ex'];
    public $appointed_batch;
    public $a=null;
    public $student_ids=[];
    public $intbatchseatno=[];
    public $subject_type;

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    // public function rules()
    // {
    //     $rules = [];

    //     if ($this->appointed_batch->subject_type == "G")
    //     {
    //         $currentPageKeys = $this->getCurrentPageKeys('selectedGrade');

    //         foreach ($currentPageKeys as $index)
    //         {
    //             if (isset($this->selectedGrade[$index]) && $this->selectedGrade[$index] === 'Ab')
    //             {
    //                 continue;
    //             }

    //             $rules['selectedGrade.' . $index] = ['required', 'in:O,A+,A,B+,B,C,P,F,Ex'];
    //         }
    //     }
    //     elseif (in_array($this->appointed_batch->subject_type, ["I", "IG"]))
    //     {
    //         $currentPageKeys = $this->getCurrentPageKeys('selectedMarks');

    //         foreach ($currentPageKeys as $index)
    //         {
    //             if (isset($this->selectedMarks[$index]) && $this->selectedMarks[$index] === 'Ab')
    //             {
    //                 continue;
    //             }
    //             $rules['selectedMarks.' . $index] = ['required', 'numeric', 'gte:0', 'lte:' . $this->appointed_batch->subject->subject_maxmarks_int];
    //         }
    //     }
    //     elseif (in_array($this->appointed_batch->subject_type, ["IEG"]))
    //     {
    //         $currentPageKeys = $this->getCurrentPageKeys('selectedMarks');

    //         foreach ($currentPageKeys as $index)
    //         {
    //             if (isset($this->selectedMarks[$index]) && $this->selectedMarks[$index] === 'Ab')
    //             {
    //                 continue;
    //             }
    //             $rules['selectedMarks.' . $index] = ['required', 'numeric', 'gte:0', 'lte:' . $this->appointed_batch->subject->subject_maxmarks_ext];
    //         }
    //     }
    //     elseif (in_array($this->appointed_batch->subject_type, ["IEP"]))
    //     {
    //         $currentPageKeys = $this->getCurrentPageKeys('selectedMarks');

    //         foreach ($currentPageKeys as $index)
    //         {
    //             if (isset($this->selectedMarks[$index]) && $this->selectedMarks[$index] === 'Ab')
    //             {
    //                 continue;
    //             }
    //             $rules['selectedMarks.' . $index] = ['required', 'numeric', 'gte:0', 'lte:' . $this->appointed_batch->subject->subject_maxmarks_intpract];
    //         }
    //     }

    //     return $rules;
    // }

    public function rules()
    {
        $rules = [];
        $subjectType = $this->appointed_batch->subject_type;
        $currentPageKeys = [];
        $selectedField = '';
        $maxMarks = 0;

        switch ($subjectType) {
            case 'G':
                $currentPageKeys = $this->getCurrentPageKeys('selectedGrade');
                $selectedField = 'selectedGrade';
                break;
            case 'I':
            case 'IG':
                $currentPageKeys = $this->getCurrentPageKeys('selectedMarks');
                $selectedField = 'selectedMarks';
                $maxMarks = $this->appointed_batch->subject->subject_maxmarks_int;
                break;
            case 'IEG':
                $currentPageKeys = $this->getCurrentPageKeys('selectedMarks');
                $selectedField = 'selectedMarks';
                $maxMarks = $this->appointed_batch->subject->subject_maxmarks_ext;
                break;
            case 'IEP':
                $currentPageKeys = $this->getCurrentPageKeys('selectedMarks');
                $selectedField = 'selectedMarks';
                $maxMarks = $this->appointed_batch->subject->subject_maxmarks_intpract;
                break;
        }

        foreach ($currentPageKeys as $index) {
            if (isset($this->$selectedField[$index]) && $this->$selectedField[$index] === 'Ab') {
                continue;
            }

            if ($subjectType === 'G') {
                $rules[$selectedField . '.' . $index] = ['required', 'in:O,A+,A,B+,B,C,P,F,Ex'];
            } else {
                $rules[$selectedField . '.' . $index] = ['required', 'numeric', 'gte:0', 'lte:' . $maxMarks];
            }
        }

        return $rules;
    }


    public function messages()
    {
        $messages = [];

        if ($this->appointed_batch->subject_type == "G")
        {
            $currentPageKeys = $this->getCurrentPageKeys('selectedGrade');
            foreach ($currentPageKeys as $index)
            {
                $messages['selectedGrade.' . $index . '.required'] = 'Grade is required.';
                $messages['selectedGrade.' . $index . '.in'] = 'Grade must be one of O, A+, A, B+, B, C, P, F, Ex.';
            }
        } elseif (in_array($this->appointed_batch->subject_type, ["I", "IG", "IEG", "P"]))
        {
            $currentPageKeys = $this->getCurrentPageKeys('selectedMarks');
            foreach ($currentPageKeys as $index)
            {
                $messages['selectedMarks.' . $index . '.required'] = 'Marks are required.';
                $messages['selectedMarks.' . $index . '.numeric'] = 'Marks must be a number.';
                $messages['selectedMarks.' . $index . '.gte'] = 'Marks must be at least 0.';
                $messages['selectedMarks.' . $index . '.lte'] = 'Marks must not exceed ' . $this->appointed_batch->subject->subject_maxmarks_int . '.';
            }
        }

        return $messages;
    }

    public function getCurrentPageKeys($fieldName)
    {
        $collection = collect($this->$fieldName);
        $currentPageItems = $collection->slice(($this->getPage() - 1) * $this->perPage, $this->perPage, true);
        return $currentPageItems->keys()->toArray();
    }

    public function mount($batch_data)
    {
        $this->exam = Exam::where('status', '1')->first();

        $this->batchid = $batch_data->id;

        $this->intbatchseatno = Intbatchseatnoallocation::where('intbatch_id', $this->batchid)
        ->whereHas('internalmarksbatch', function($query) {
            $query->where('faculty_id', Auth::guard('faculty')->user()->id);
        })
        ->orderBy('seatno', 'asc')
        ->pluck('seatno');

        $this->appointed_batch = $batch_data;

        $this->load_batch_marks();

        if($this->appointed_batch->status != 4)
        {
            $this->load_marks_data();
        }

        $this->student_ids = $this->appointed_batch->subject->studentmarks->where('exam_id', $this->exam->id)->whereIn('seatno', $this->intbatchseatno)->pluck('student_id');
    }

    public function previousPage($pageName = 'page')
    {
        $this->a=1;
        $this->render();
        $this->setPage($this->getPage() - 1);
    }

    // public function finishPage()
    // {
    //     $cnt = 0;
    //     $abcnt = 0;

    //     if ($this->appointed_batch->subject_type == "G")
    //     {
    //         foreach ($this->selectedGrade as $key => $value)
    //         {
    //             if ($value == "")
    //             {
    //                 $this->dispatch('alert',type:'info',message:'Please Enter All Internal Marks !!');
    //             }
    //             if ($value === "Ab")
    //             {
    //                 $abcnt++;
    //             }
    //             elseif (!is_null($value) or $value!=='Ab')
    //             {
    //                 $cnt++;
    //             }

    //             $student = Student::find($key);
    //             $seatno = $student->examstudentseatnos->last()->seatno;

    //             $values = array(
    //                 'seatno' => $seatno,
    //                 'student_id' => $key,
    //                 'subject_id' => $this->appointed_batch->subject->id,
    //                 'sem' => $this->exam->exam_sessions,
    //                 'subject_grade' => $value,
    //                 'grade'=> $value,
    //                 'exam_id' => $this->exam->id,
    //                 'patternclass_id' => $this->appointed_batch->subject->patternclass_id
    //                 );

    //             $studmark = Studentmark::where('student_id' , $key)
    //             ->where('exam_id' , $this->exam->id)
    //             ->where('subject_id' , $this->appointed_batch->subject->id)
    //             ->where('seatno' , $seatno)
    //             ->first();

    //             $studmark->update($values);
    //         }
    //         $this->appointed_batch->update(['status' => '2', 'totalAbsent' => $abcnt, 'totalMarksentry' => $cnt, 'totalBatchsize' => count($this->selectedGrade)]);
    //     }
    //     else
    //     {
    //         if ($this->appointed_batch->subject_type == "I" || $this->appointed_batch->subject_type == "IG")
    //         {
    //             foreach ($this->selectedMarks as $key => $value)
    //             {
    //                 if ($value != "Ab")
    //                 {
    //                     if ($value == "")
    //                     {
    //                         $this->dispatch('alert',type:'info',message:'Please Enter All Internal Marks !!');
    //                     }
    //                     if (!is_null($value))
    //                     {
    //                         $cnt++;
    //                     }

    //                     $student = Student::find($key);
    //                     $seatno = $student->examstudentseatnos->last()->seatno;
    //                     $values = array(
    //                         'seatno' => $seatno,
    //                         'student_id' => $key,
    //                         'subject_id' => $this->appointed_batch->subject->id,
    //                         'sem' => $this->exam->exam_sessions,
    //                         'int_marks' => $value,
    //                         'exam_id' => $this->exam->id,
    //                         'patternclass_id' => $this->appointed_batch->subject->patternclass_id
    //                     );
    //                     $studmark = Studentmark::upsert($values, ['student_id', 'subject_id', 'seatno', 'exam_id']);
    //                 }
    //                 else
    //                 {
    //                     $abcnt++;
    //                 }
    //             }
    //         }
    //         else  if ($this->appointed_batch->subject_type == "IEG" )
    //         {
    //             foreach ($this->selectedMarks as $key => $value)
    //             {
    //                 if ($value != "Ab")
    //                 {
    //                     if ($value == "")
    //                     {
    //                         $this->dispatch('alert',type:'info',message:'Please Enter All Internal Marks !!');
    //                         return back();
    //                     }
    //                     if (!is_null($value))
    //                     {
    //                         $cnt++;
    //                     }
    //                     $student = Student::find($key);
    //                     $seatno = $student->examstudentseatnos->last()->seatno;
    //                     $values = array(
    //                         'seatno' => $seatno,
    //                         'student_id' => $key,
    //                         'subject_id' => $this->appointed_batch->subject->id,
    //                         'sem' => $this->exam->exam_sessions,
    //                         'ext_marks' => $value,
    //                         'exam_id' => $this->exam->id,
    //                         'patternclass_id' => $this->appointed_batch->subject->patternclass_id
    //                     );
    //                     $studmark = Studentmark::upsert($values, ['student_id', 'subject_id', 'seatno', 'exam_id']);
    //                 }
    //                 else
    //                 {
    //                     $abcnt++;
    //                 }
    //             }
    //         }
    //         else  if ($this->appointed_batch->subject_type == "P")
    //         {
    //             $subject = $this->appointed_batch->subject;
    //             switch ($subject->subject_type)
    //             {
    //                 case "IP":
    //                 $marksType = 'ext_marks';
    //                 break;

    //                 case "IEP":
    //                 $marksType = 'int_practical_marks';
    //                 break;
    //             }

    //             foreach ($this->selectedMarks as $key => $value)
    //             {
    //                 if ($value != "Ab")
    //                 {
    //                     if ($value == "")
    //                     {
    //                         $this->dispatch('alert',type:'info',message:'Please Enter All Internal Marks !!');
    //                         return back();
    //                     }
    //                     if (!is_null($value))
    //                     {
    //                         $cnt++;
    //                     }
    //                     $student = Student::find($key);
    //                     $seatno = $student->examstudentseatnos->last()->seatno;

    //                     $values = array(
    //                         'seatno' => $seatno,
    //                         'student_id' => $key,
    //                         'subject_id' => $this->appointed_batch->subject->id,
    //                         'sem' => $this->exam->exam_sessions,
    //                         $marksType => $value,
    //                         'exam_id' => $this->exam->id,
    //                         'patternclass_id' => $this->appointed_batch->subject->patternclass_id
    //                     );
    //                     $studmark = Studentmark::upsert($values, ['student_id', 'subject_id', 'seatno', 'exam_id']);
    //                 }
    //                 else
    //                 {
    //                     $abcnt++;
    //                 }
    //             }
    //         }
    //         $this->appointed_batch->update(['status' => '2', 'totalAbsent' => $abcnt, 'totalMarksentry' => $cnt, 'totalBatchsize' => count($this->selectedMarks)]);
    //     }

    //     if (($cnt + $abcnt) == $this->appointed_batch->intbatchseatnoallocations->count())
    //     {
    //         $this->dispatch('alert',type:'info',message:'Please Fill All Details !!');
    //     }
    //     else
    //     {
    //         $this->dispatch('alert',type:'info',message:'Please Enter All Marks !!');
    //     }
    // }

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
                return $intbatch_subject_type == 'I' ? 'int_marks' : ($intbatch_subject_type == 'P' ? 'ext_marks' : 'int_practical_marks');
            case "IEP":
                return $intbatch_subject_type == 'I' ? 'int_marks' : ($intbatch_subject_type == 'P' ? 'int_practical_marks' : 'ext_marks');
            default:
                return null;
        }
    }

    public function all_marks_filled()
    {
        $total_batch_size = $this->appointed_batch->intbatchseatnoallocations()->count();

        $subject = $this->appointed_batch->subject;

        $marks_type = $this->determine_marks_type($subject->subject_type, $this->appointed_batch->subject_type);

        $total_evaluated_students = $this->appointed_batch->subject->studentmarks->whereNotNull($marks_type)->where('exam_id', $this->exam->id)->whereIn('seatno', $this->intbatchseatno)->count();

        $total_students = $this->appointed_batch->subject->studentmarks
        ->where('exam_id', $this->exam->id)
        ->whereIn('seatno', $this->intbatchseatno)
        ->pluck($marks_type, 'student_id');

        $absent_entry_count = $total_students->filter(function($mark) {
            return $mark == -1 || $mark == 'Ab';
        })->count();

        $mark_entry_count = $this->appointed_batch->subject->studentmarks
        ->where($marks_type, '!=', "-1")
        ->where($marks_type, '!=', "Ab")
        ->where('exam_id', $this->exam->id)
        ->whereIn('seatno', $this->intbatchseatno)
        ->count();

        if($total_batch_size == $total_evaluated_students)
        {
            $this->appointed_batch->update([
                'status' => '2',
                'totalAbsent' => $absent_entry_count,
                'totalMarksentry' => $mark_entry_count,
                'totalBatchsize' => $total_batch_size
            ]);

            $this->dispatch('alert',type:'success',message:'All Students Marks Saved Successfully !!');
            return true;
        }
        else
        {
            $this->dispatch('alert',type:'info',message:'Please Fill All Students Internal Marks !!');
            return false;
        }
    }

    public function finishPage()
    {
        $validatedData = $this->validate();

        DB::beginTransaction();

        try {

            if ($this->appointed_batch->subject_type == "G")
            {
                $student_ids_to_update = array_keys($validatedData['selectedGrade']);

                $students = Student::with(['examstudentseatnos' => function ($query) {
                    $query->latest();
                }])->whereIn('id', $student_ids_to_update)->get();

                $upsert_values = [];
                $upsert_values_other_table = [];

                foreach ($students as $student)
                {
                    $seatno = $student->examstudentseatnos->first()->seatno;

                    $grade = $validatedData['selectedGrade'][$student->id];

                    $upsert_values[] = [
                        'seatno' => $seatno,
                        'student_id' => $student->id,
                        'subject_id' => $this->appointed_batch->subject->id,
                        'sem' => $this->appointed_batch->subject->subject_sem,
                        'subject_grade' => $grade,
                        'grade' => $grade,
                        'exam_id' => $this->exam->id,
                        'patternclass_id' => $this->appointed_batch->subject->patternclass_id
                    ];

                    $upsert_values_other_table[] = [
                        'intbatch_id' => $this->appointed_batch->id,
                        'student_id' => $student->id,
                        'seatno' => $seatno,
                        'grade' => $grade,
                    ];
                }

                Studentmark::upsert($upsert_values, ['student_id', 'subject_id', 'seatno', 'exam_id']);

                Intbatchseatnoallocation::upsert($upsert_values_other_table, ['intbatch_id', 'student_id', 'seatno']);
            }
            else
            {
                $upsert_values = [];
                $upsert_values_other_table = [];

                if (in_array($this->appointed_batch->subject_type, ["I", "IG"]))
                {
                    $student_ids_to_update = array_keys($validatedData['selectedMarks']);

                    $students = Student::with(['examstudentseatnos' => function ($query){
                        $query->latest();
                    }])->whereIn('id', $student_ids_to_update)->get();

                    foreach ($students as $student)
                    {
                        $seatno = $student->examstudentseatnos->first()->seatno;
                        $marks = $validatedData['selectedMarks'][$student->id];

                        $upsertValues[] = [
                            'seatno' => $seatno,
                            'student_id' => $student->id,
                            'subject_id' => $this->appointed_batch->subject->id,
                            'sem' => $this->appointed_batch->subject->subject_sem,
                            'int_marks' => $marks,
                            'exam_id' => $this->exam->id,
                            'patternclass_id' => $this->appointed_batch->subject->patternclass_id
                        ];

                        $upsert_values_other_table[] = [
                            'intbatch_id' => $this->appointed_batch->id,
                            'student_id' => $student->id,
                            'seatno' => $seatno,
                            'marks' => $marks,
                        ];
                    }
                }
                else if ($this->appointed_batch->subject_type == "IEG")
                {
                    $student_ids_to_update = array_keys($validatedData['selectedMarks']);

                    $students = Student::with(['examstudentseatnos' => function ($query){
                        $query->latest();
                    }])->whereIn('id', $student_ids_to_update)->get();

                    foreach ($students as $student)
                    {
                        $seatno = $student->examstudentseatnos->first()->seatno;
                        $marks = $validatedData['selectedMarks'][$student->id];

                        $upsertValues[] = [
                            'seatno' => $seatno,
                            'student_id' => $student->id,
                            'subject_id' => $this->appointed_batch->subject->id,
                            'sem' => $this->appointed_batch->subject->subject_sem,
                            'ext_marks' => $marks,
                            'exam_id' => $this->exam->id,
                            'patternclass_id' => $this->appointed_batch->subject->patternclass_id
                        ];

                        $upsert_values_other_table[] = [
                            'intbatch_id' => $this->appointed_batch->id,
                            'student_id' => $student->id,
                            'seatno' => $seatno,
                            'marks' => $marks,
                        ];
                    }
                }
                else if ($this->appointed_batch->subject_type == "P")
                {
                    $student_ids_to_update = array_keys($validatedData['selectedMarks']);
                    $marksType = $this->appointed_batch->subject->subject_type == "IP" ? 'ext_marks' : 'int_practical_marks';

                    $students = Student::with(['examstudentseatnos' => function ($query){
                        $query->latest();
                    }])->whereIn('id', $student_ids_to_update)->get();

                    foreach ($students as $student)
                    {
                        $seatno = $student->examstudentseatnos->first()->seatno;

                        $marks = $validatedData['selectedMarks'][$student->id];

                        $upsertValues[] = [
                            'seatno' => $seatno,
                            'student_id' => $student->id,
                            'subject_id' => $this->appointed_batch->subject->id,
                            'sem' => $this->appointed_batch->subject->subject_sem,
                            $marksType => $marks,
                            'exam_id' => $this->exam->id,
                            'patternclass_id' => $this->appointed_batch->subject->patternclass_id
                        ];

                        $upsert_values_other_table[] = [
                            'intbatch_id' => $this->appointed_batch->id,
                            'student_id' => $student->id,
                            'seatno' => $seatno,
                            'marks' => $marks,
                        ];
                    }
                }

                Studentmark::upsert($upsertValues, ['student_id', 'subject_id', 'seatno', 'exam_id']);

                Intbatchseatnoallocation::upsert($upsert_values_other_table, ['intbatch_id', 'student_id', 'seatno']);
            }

            if($this->all_marks_filled() == true)
            {
                DB::commit();
                $this->render();
            }
        }
        catch (\Exception $e)
        {
            DB::rollBack();

            Log::error($e);

            $this->dispatch('alert', type: 'error', message: 'An error occurred. Please try again.');
        }
    }

    // optimized
    public function nextPage($pageName = 'page')
    {
        $validatedData = $this->validate();

        DB::beginTransaction();

        try
        {
            if ($this->appointed_batch->subject_type == "G")
            {
                $student_ids_to_update = array_keys($validatedData['selectedGrade']);

                $students = Student::with(['examstudentseatnos' => function ($query) {
                    $query->latest();
                }])->whereIn('id', $student_ids_to_update)->get();

                $upsert_values = [];
                $upsert_values_other_table = [];

                foreach ($students as $student)
                {
                    $seatno = $student->examstudentseatnos->first()->seatno;

                    $grade = $validatedData['selectedGrade'][$student->id];

                    $upsert_values[] = [
                        'seatno' => $seatno,
                        'student_id' => $student->id,
                        'subject_id' => $this->appointed_batch->subject->id,
                        'sem' => $this->appointed_batch->subject->subject_sem,
                        'subject_grade' => $grade,
                        'grade' => $grade,
                        'exam_id' => $this->exam->id,
                        'patternclass_id' => $this->appointed_batch->subject->patternclass_id
                    ];

                    $upsert_values_other_table[] = [
                        'intbatch_id' => $this->appointed_batch->id,
                        'student_id' => $student->id,
                        'seatno' => $seatno,
                        'grade' => $grade,
                    ];
                }

                Studentmark::upsert($upsert_values, ['student_id', 'subject_id', 'seatno', 'exam_id']);

                Intbatchseatnoallocation::upsert($upsert_values_other_table, ['intbatch_id', 'student_id', 'seatno']);
            }
            else
            {
                $upsert_values = [];

                if (in_array($this->appointed_batch->subject_type, ["I", "IG"]))
                {
                    $student_ids_to_update = array_keys($validatedData['selectedMarks']);

                    $students = Student::with(['examstudentseatnos' => function ($query){
                        $query->latest();
                    }])->whereIn('id', $student_ids_to_update)->get();

                    foreach ($students as $student)
                    {
                        $seatno = $student->examstudentseatnos->first()->seatno;
                        $marks = $validatedData['selectedMarks'][$student->id];

                        $upsert_values[] = [
                            'seatno' => $seatno,
                            'student_id' => $student->id,
                            'subject_id' => $this->appointed_batch->subject->id,
                            'sem' => $this->appointed_batch->subject->subject_sem,
                            'int_marks' => $marks,
                            'exam_id' => $this->exam->id,
                            'patternclass_id' => $this->appointed_batch->subject->patternclass_id
                        ];

                        $upsert_values_other_table[] = [
                            'intbatch_id' => $this->appointed_batch->id,
                            'student_id' => $student->id,
                            'seatno' => $seatno,
                            'marks' => $marks,
                        ];
                    }
                }
                else if ($this->appointed_batch->subject_type == "IEG")
                {
                    $student_ids_to_update = array_keys($validatedData['selectedMarks']);

                    $students = Student::with(['examstudentseatnos' => function ($query){
                        $query->latest();
                    }])->whereIn('id', $student_ids_to_update)->get();

                    foreach ($students as $student)
                    {
                        $seatno = $student->examstudentseatnos->first()->seatno;
                        $marks = $validatedData['selectedMarks'][$student->id];

                        $upsert_values[] = [
                            'seatno' => $seatno,
                            'student_id' => $student->id,
                            'subject_id' => $this->appointed_batch->subject->id,
                            'sem' => $this->appointed_batch->subject->subject_sem,
                            'ext_marks' => $marks,
                            'exam_id' => $this->exam->id,
                            'patternclass_id' => $this->appointed_batch->subject->patternclass_id
                        ];

                        $upsert_values_other_table[] = [
                            'intbatch_id' => $this->appointed_batch->id,
                            'student_id' => $student->id,
                            'seatno' => $seatno,
                            'marks' => $marks,
                        ];

                    }

                }
                else if ($this->appointed_batch->subject_type == "P")
                {
                    $student_ids_to_update = array_keys($validatedData['selectedMarks']);
                    $marksType = $this->appointed_batch->subject->subject_type == "IP" ? 'ext_marks' : 'int_practical_marks';

                    $students = Student::with(['examstudentseatnos' => function ($query){
                        $query->latest();
                    }])->whereIn('id', $student_ids_to_update)->get();

                    foreach ($students as $student)
                    {
                        $seatno = $student->examstudentseatnos->first()->seatno;
                        $marks = $validatedData['selectedMarks'][$student->id];

                        $upsert_values[] = [
                            'seatno' => $seatno,
                            'student_id' => $student->id,
                            'subject_id' => $this->appointed_batch->subject->id,
                            'sem' => $this->appointed_batch->subject->subject_sem,
                            $marksType => $marks,
                            'exam_id' => $this->exam->id,
                            'patternclass_id' => $this->appointed_batch->subject->patternclass_id
                        ];

                        $upsert_values_other_table[] = [
                            'intbatch_id' => $this->appointed_batch->id,
                            'student_id' => $student->id,
                            'seatno' => $seatno,
                            'marks' => $marks,
                        ];

                    }

                }
            }

            Studentmark::upsert($upsert_values, ['student_id', 'subject_id', 'seatno', 'exam_id']);

            Intbatchseatnoallocation::upsert($upsert_values_other_table, ['intbatch_id', 'student_id', 'seatno']);

            DB::commit();

            $this->setPage($this->getPage() + 1);
        }
        catch (\Exception $e)
        {
            Log::error($e);

            DB::rollBack();
        }

        if (in_array($this->appointed_batch->subject_type, ["I", "P", "G", "IEG"]))
        {
            $this->a = 1;
            $this->render();
        }
    }

    // public function load_batch_marks()
    // {
    //     if ($this->appointed_batch->status == 4)
    //     {

    //         $subject = $this->appointed_batch->subject;
    //         $examId = $this->exam->id;
    //         $seatNos = $this->intbatchseatno;

    //         if ($this->appointed_batch->subject_type == "G")
    //         {
    //             $this->selectedGrade = $subject->studentmarks
    //                 ->where('exam_id', $examId)
    //                 ->whereIn('seatno', $seatNos)
    //                 ->pluck('subject_grade', 'student_id');
    //             return;
    //         }

    //         $marksType = '';
    //         switch ($this->appointed_batch->subject_type)
    //         {
    //             case "IEG":
    //                 $marksType = 'ext_marks';
    //                 $this->outMarks = $subject->subject_maxmarks_ext;
    //                 break;
    //             case "I":
    //             case "IG":
    //                 $marksType = 'int_marks';
    //                 $this->outMarks = $subject->subject_maxmarks_int;
    //                 break;
    //             case "P":
    //                 switch ($subject->subject_type)
    //                 {
    //                     case "IP":
    //                         $marksType = 'ext_marks';
    //                         $this->outMarks = $subject->subject_maxmarks_ext;
    //                         break;
    //                     case "IEP":
    //                         $marksType = 'int_practical_marks';
    //                         $this->outMarks = $subject->subject_maxmarks_intpract;
    //                         break;
    //                 }
    //                 break;
    //         }

    //         if ($marksType)
    //         {
    //             $this->selectedMarks = $subject->studentmarks
    //             ->where('exam_id', $examId)
    //             ->whereIn('seatno', $seatNos)
    //             ->pluck($marksType, 'student_id');

    //             foreach ($this->selectedMarks as $key => $value)
    //             {
    //                 $this->selectedMarks[$key] = ($value == -1) ? "Ab" : $value;
    //                 $this->selectedCmb[$key] = ($value == -1);
    //             }
    //         }
    //     }
    // }

    // optimized
    public function load_batch_marks()
    {
        if ($this->appointed_batch->status == 4)
        {
            $subject = $this->appointed_batch->subject;
            $examId = $this->exam->id;
            $seatNos = $this->intbatchseatno;
            $marksType = '';

            if ($this->appointed_batch->subject_type == "G")
            {
                $this->selectedGrade = $subject->studentmarks
                ->where('exam_id', $examId)
                ->whereIn('seatno', $seatNos)
                ->pluck('subject_grade', 'student_id');
                return;
            }

            switch ($this->appointed_batch->subject_type)
            {
                case "IEG":
                    $marksType = 'ext_marks';
                    $this->outMarks = $subject->subject_maxmarks_ext;
                    break;
                case "I":
                case "IG":
                    $marksType = 'int_marks';
                    $this->outMarks = $subject->subject_maxmarks_int;
                    break;
                case "P":
                    switch ($subject->subject_type)
                    {
                        case "IP":
                            $marksType = 'ext_marks';
                            $this->outMarks = $subject->subject_maxmarks_ext;
                            break;
                        case "IEP":
                            $marksType = 'int_practical_marks';
                            $this->outMarks = $subject->subject_maxmarks_intpract;
                            break;
                    }
                    break;
            }

            if ($marksType)
            {
                $marksCollection = $subject->studentmarks
                    ->where('exam_id', $examId)
                    ->whereIn('seatno', $seatNos)
                    ->pluck($marksType, 'student_id');

                foreach ($marksCollection as $key => $value)
                {
                    $markValue = ($value == -1) ? "Ab" : $value;
                    $this->selectedMarks[$key] = $markValue;
                    $this->selectedCmb[$key] = ($value == -1);
                }
            }
        }
    }

    // optimized
    public function load_marks_data()
    {
        $allseatno = $this->appointed_batch->intbatchseatnoallocations()->orderBy('seatno', 'asc')->get();

        if ($this->appointed_batch->status == 1)
        {
            DB::beginTransaction();

            try
            {
                $values = [];

                foreach ($allseatno as $data)
                {
                    $values[] = [
                        'seatno' => $data->seatno,
                        'student_id' => $data->student_id,
                        'subject_id' => $this->appointed_batch->subject->id,
                        'sem' => $this->appointed_batch->subject->subject_sem,
                        'exam_id' => $this->exam->id,
                        'patternclass_id' => $this->appointed_batch->subject->patternclass_id
                    ];
                }
                Studentmark::upsert($values, ['student_id', 'subject_id', 'seatno', 'exam_id']);

                $this->appointed_batch->update(['status' => '4']);

                $subject = $this->appointed_batch->subject;
                $examId = $this->exam->id;
                $seatNos = $this->intbatchseatno;
                $marksType = '';

                if ($this->appointed_batch->subject_type == "G")
                {
                    $this->selectedGrade = $subject->studentmarks
                        ->where('exam_id', $examId)
                        ->whereIn('seatno', $seatNos)
                        ->pluck('subject_grade', 'student_id');
                }
                else
                {
                    switch ($this->appointed_batch->subject_type)
                    {
                        case "I":
                        case "IG":
                            $marksType = 'int_marks';
                            $this->outMarks = $subject->subject_maxmarks_int;
                            break;
                        case "IEG":
                            $marksType = 'ext_marks';
                            $this->outMarks = $subject->subject_maxmarks_ext;
                            break;
                        case "P":
                            switch ($subject->subject_type)
                            {
                                case "IP":
                                    $marksType = 'ext_marks';
                                    $this->outMarks = $subject->subject_maxmarks_ext;
                                    break;
                                case "IEP":
                                    $marksType = 'int_practical_marks';
                                    $this->outMarks = $subject->subject_maxmarks_intpract;
                                    break;
                            }
                            break;
                    }

                    if ($marksType)
                    {
                        $marksCollection = $subject->studentmarks
                            ->where('exam_id', $examId)
                            ->whereIn('seatno', $seatNos)
                            ->pluck($marksType, 'student_id');

                        foreach ($marksCollection as $key => $value)
                        {
                            $markValue = ($value == -1) ? "Ab" : $value;
                            $this->selectedMarks[$key] = $markValue;
                            $this->selectedCmb[$key] = ($value == -1);
                        }
                    }
                }

                DB::commit();

            }
            catch (\Exception $e)
            {
                DB::rollBack();

                Log::error($e);
            }
        }
    }

    public function render()
    {
        $eval_non_eval_marks_entries=collect([]);

        if($this->a==1)
        {
            $this->load_batch_marks();
            $this->a=null;
        }

        if(isset($this->student_ids) && $this->appointed_batch->status != 2 )
        {
            $eval_non_eval_marks_entries = Intbatchseatnoallocation::select('id', 'intbatch_id', 'student_id', 'seatno')
            ->with(['internalmarksbatch', 'student'])
            ->where('intbatch_id', $this->appointed_batch->id)
            ->whereIn('student_id', $this->student_ids)
            ->whereHas('internalmarksbatch', function($query) {
                $query->where('faculty_id', Auth::guard('faculty')->user()->id)
                    ->orWhere('status', '!=', 2);
            })
            ->orderBy('seatno', 'asc')
            ->paginate($this->perPage);
        }
        return view('livewire.faculty.internal-marks-entry.eval-non-eval-marks-entry.all-eval-non-eval-marks-entry-table',compact('eval_non_eval_marks_entries'));
    }
}
