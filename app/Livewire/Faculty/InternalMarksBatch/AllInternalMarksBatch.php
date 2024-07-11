<?php

namespace App\Livewire\Faculty\InternalMarksBatch;

use App\Models\Exam;
use App\Models\Subject;
use Livewire\Component;
use App\Models\Classview;
use App\Models\Subjecttype;
use App\Models\Patternclass;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use App\Models\Studentexamform;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use App\Models\Exampatternclass;
use App\Models\Examstudentseatno;
use App\Models\Hodappointsubject;
use App\Models\Internalmarksbatch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Intbatchseatnoallocation;

class AllInternalMarksBatch extends Component
{
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'delete'];

    public $search='';
    public $perPage=10;
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;

    public $batch_option;
    public $fromseatno;
    public $toseatno;
    public $no_of_batch;
    public $patternclass_id;
    public $subject_type;
    public $subject_id;
    public $currentdate;
    public $seatnocount=0;
    public $seatnoallocationcount;

    #[Locked]
    public $delete_id;

    public $seatno;
    public $hod_subject_ids=[];
    public $checked_seatno=[];
    public $hod_subject_patternclass_ids=[];
    public $subjects=[];
    public $patternclasses;
    public $subject_types=[];

    public function updated($propertyName, $value)
    {
        if($propertyName == 'patternclass_id'){
            $this->loadSubjects($value);
        }elseif($propertyName == 'subject_id'){
            $this->loadSubjectTypes($value);
        }elseif($propertyName == 'subject_type'){
            $this->loadBatchCreateType($value);
        }
    }

    protected function rules()
    {
        $rules = [
            'patternclass_id' => ['required', Rule::exists(Patternclass::class, 'id')],
            'subject_id' => ['required', Rule::exists(Subject::class, 'id')],
            'subject_type' => ['required', Rule::exists(Subjecttype::class, 'type_name')],
            'batch_option' => ['required', 'in:1,2,3,4'],
        ];

        switch ($this->batch_option) {
            case '2':
                $rules['no_of_batch'] = ['required', 'integer'];
                break;
            case '3':
                $rules['fromseatno'] = ['required', Rule::exists(Examstudentseatno::class, 'seatno')];
                $rules['toseatno'] = ['required', Rule::exists(Examstudentseatno::class, 'seatno')];
                break;
        }

        return $rules;
    }


    public function messages()
    {
        return [
            'patternclass_id.required' => 'The class field is required.',
            'patternclass_id.exists' => 'The selected class does not exist.',
            'subject_id.required' => 'The subject field is required.',
            'subject_id.exists' => 'The selected subject does not exist.',
            'subject_type.required' => 'The subject type field is required.',
            'subject_type.exists' => 'The selected subject type does not exist.',
            'batch_option.required' => 'The batch option field is required.',
            'batch_option.in' => 'The selected batch option is invalid.',
            'no_of_batch.required' => 'The no of batch field is required.',
            'no_of_batch.integer' => 'The no of batch should be an integer.',
            'fromseatno.required' => 'The from seat no field is required.',
            'fromseatno.exists' => 'The selected seat no does not exist.',
            'toseatno.required' => 'The to seat no field is required.',
            'toseatno.exists' => 'The selected seat no does not exist.',
        ];
    }

    public function resetinput()
    {
        $this->reset([
            'patternclass_id',
            'subject_id',
            'subject_type',
            'batch_option',
            'no_of_batch',
            'fromseatno',
            'toseatno',
            'checked_seatno',
        ]);

    }

    public function loadSubjects($patternclass_id)
    {
        $this->subjects = Subject::select('id', 'subject_code', 'subject_name')->where('patternclass_id', $patternclass_id)
            ->whereHas('hodappointsubjects', function ($query) {
                $query->where('faculty_id', Auth::guard('faculty')->user()->id);
            })->get();
    }

    public function loadSubjectTypes($subject_id)
    {
        $exam=Exam::where('status','1')->first();

        if($subject_id!="")
        {
            $str=array();

            $subject=Subject::find($subject_id);

            $exam_patternclasses_id=Exampatternclass::where('patternclass_id', $subject->patternclass_id)->where('exam_id',$exam->id)->get();

            //dd($exam_patternclasses_id->first());

            if($subject->subject_type=="IG" && $exam_patternclasses_id->first()->patternclass->courseclass->course->course_type=="UG")
            {
                array_push($str,"IG");
                //array_push($str,"I");
                //array_push($str,"IGE");
            }
            if($subject->subject_type=="IEG" && $exam_patternclasses_id->first()->patternclass->courseclass->course->course_type=="UG")
            {
                //array_push($str,"IEG");
                array_push($str,"I");
                array_push($str,"IEG");
            }
            if($subject->subject_type=="IEG" && $exam_patternclasses_id->first()->patternclass->courseclass->course->course_type=="PG")
            {
                //array_push($str,"IEG");
                array_push($str,"I");
                array_push($str,"IEG");
            }
            if($subject->subject_type=="IG" && $exam_patternclasses_id->first()->patternclass->courseclass->course->course_type=="PG")
            {
                array_push($str,"IG");
            }
            if($subject->subject_type=="G")

                array_push($str,"G");

            if($subject->subject_type=="I"||$subject->subject_type=="IE")

                array_push($str,"I");

            if($subject->subject_type=="IP" || $subject->subject_type=="IEP")
            {
                array_push($str,"I");
                array_push($str,"P");
            }
            $this->subject_types=$str;
            // dd($this->subject_types);
        }

    }

    public function loadBatchCreateType($type)
    {
        $exam = Exam::where('status','1')->first();
        $reindex=collect();
        $subject=Subject::find($this->subject_id);
        $colsubject = $this->determine_col_subject($type, $subject->subject_type);

        if (isset($colsubject))
        {
            $student_exam_forms = $subject->studentexamforms()->where('exam_id', $exam->id)->where($colsubject, '1')->whereHas('examformmaster', function ($query) {
                $query->where('inwardstatus', 1);
            })->with('student.examstudentseatnos')->get();

            foreach ($student_exam_forms as $student_exam_form)
            {
                $last_seat_no = $student_exam_form->student->examstudentseatnos->last();
                if ($last_seat_no !== null) {
                    $reindex->push($last_seat_no->seatno);
                }
            }
        }

        $reindex=$reindex->sort();
        $exam_patternclasses_id=Exampatternclass::where('patternclass_id', $subject->patternclass_id)->where('exam_id',$exam->id)->get();
        $alloseatno=collect();
        $this->seatnoallocationcount=0;

        foreach($subject->internalmarksbatches->where('exam_patternclasses_id',$exam_patternclasses_id->first()->id)->where('subject_type',$type) as $data)
        {
            $this->seatnoallocationcount=$this->seatnoallocationcount+$data->intbatchseatnoallocations->count();
            $alloseatno->push($data->intbatchseatnoallocations->pluck('seatno')->toArray());
        }

        $alloseatno=$alloseatno->collapse();
        $diff=$reindex->diff($alloseatno);
        $this->seatno=array_values($diff->toArray());
        $this->seatnocount=$diff->count();
    }

    public function determine_col_subject($subtype, $subject_type)
    {
        if (($subtype == "G" || $subtype == "IG"))
        {
            return 'grade_status';
        } else if ($subtype == "I" && $subject_type == "IEG")
        {
            return 'int_status';
        } else if ($subtype == "IEG" && $subject_type == "IEG")
        {
            return 'ext_status';
        } else if ($subtype == "I") {
            return 'int_status';
        } else if ($subtype == "P" && $subject_type == "IEP")
        {
            return 'int_practical_status';
        } else if ($subtype == "P" && $subject_type == "IP")
        {
            return 'ext_status';
        } else
        {
            return null;
        }
    }

    public function save()
    {
        $validatedData = $this->validate();

        DB::beginTransaction();

        try
        {

            $colsubject = null;

            $exam = Exam::where('status', '1')->first();

            $faculty = Auth::user();

            $patternclass = $faculty->hodappointsubjects->unique('patternclass_id');

            $exam_patternclasses_id = Exampatternclass::where('patternclass_id', $validatedData['patternclass_id'])->where('exam_id', $exam->id)->get();

            $subject = Subject::find($validatedData['subject_id']);

            $subtype = $validatedData['subject_type'];

            $colsubject = $this->determine_col_subject($subtype, $subject->subject_type);

            $option = $validatedData['batch_option'];

            if ($option == "1")
            {
                $reindex = collect();

                $student_exam_forms = $subject->studentexamforms->where('exam_id', $exam->id)->where($colsubject, '1')->load(['examformmaster', 'student.examstudentseatnos.exampatternclass']);

                foreach ($student_exam_forms as $data)
                {
                    if ($data->examformmaster->inwardstatus == 1)
                    {
                        $last_exam_student_seatno = $data->student->examstudentseatnos->where('exampatternclass.exam_id', $exam->id)->last();
                        if (!is_null($last_exam_student_seatno))
                        {
                            $reindex->push($last_exam_student_seatno->seatno);
                        }
                    }
                }

                $reindex = $reindex->sort();

                $seatnoallocationcount = 0;

                $alloseatno = collect();

                $internal_marks_batches = $subject->internalmarksbatches->where('exam_patternclasses_id', $exam_patternclasses_id->first()->id)->where('subject_type', $subtype)->load('intbatchseatnoallocations');

                foreach ($internal_marks_batches as $data)
                {
                    $seatnoallocationcount += $data->intbatchseatnoallocations->count();
                    $alloseatno->push($data->intbatchseatnoallocations->pluck('seatno')->toArray());
                }

                $alloseatno = $alloseatno->collapse();
                $diff = $reindex->diff($alloseatno);
                $seatno = array_values($diff->toArray());

                if (count($seatno) > 0)
                {
                    $values = [
                        'exam_patternclasses_id' => $exam_patternclasses_id->first()->id,
                        'subject_id' => $validatedData['subject_id'],
                        'subject_type' => $subtype,
                        'status' => '1'
                    ];

                    $internalmarksbatch = Internalmarksbatch::create($values);

                    $int_batch_alloc = [];

                    $exam_student_seatnos = Examstudentseatno::whereIn('seatno', $seatno)
                        ->latest()
                        ->with('student')
                        ->get()
                        ->groupBy('seatno');

                    foreach ($seatno as $sn)
                    {
                        $examstudseatno = $exam_student_seatnos[$sn]->first();
                        $student_id = $examstudseatno->student->id;
                        $int_batch_alloc[] = [
                            'intbatch_id' => $internalmarksbatch->id,
                            'student_id' => $student_id,
                            'seatno' => $sn,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }

                    Intbatchseatnoallocation::insert($int_batch_alloc);

                    DB::commit();

                    $this->resetinput();

                    $this->dispatch('alert', type: 'success', message: 'Batch Created Successfully !!');
                }
                else
                {
                    $this->dispatch('alert', type: 'info', message: 'Batch Already Created !!');
                }
            }
            elseif ($option == "2")
            {
                $noofbatch = $validatedData['no_of_batch'];
                $reindex = collect();

                $student_exam_forms = $subject->studentexamforms()
                ->where('exam_id', $exam->id)
                ->where($colsubject, '1')
                ->with(['examformmaster', 'student.examstudentseatnos.exampatternclass'])
                ->get();

                foreach ($student_exam_forms as $data)
                {
                    if ($data->examformmaster->inwardstatus == 1)
                    {
                        $last_exam_student_seatno = $data->student->examstudentseatnos->where('exampatternclass.exam_id', $exam->id)->last();
                        if ($last_exam_student_seatno)
                        {
                            $reindex->push($last_exam_student_seatno->seatno);
                        }
                    }
                }

                $reindex = $reindex->sort();
                $seatnoallocationcount = 0;
                $alloseatno = collect();

                $internal_marks_batches = $subject->internalmarksbatches()
                ->where('exam_patternclasses_id', $exam_patternclasses_id->first()->id)
                ->where('subject_type', $subtype)
                ->with('intbatchseatnoallocations')
                ->get();

                foreach ($internal_marks_batches as $data)
                {
                    $seatnoallocationcount += $data->intbatchseatnoallocations->count();
                    $alloseatno->push($data->intbatchseatnoallocations->pluck('seatno')->toArray());
                }

                $alloseatno = $alloseatno->collapse();
                $diff = $reindex->diff($alloseatno);
                $seatno = array_values($diff->toArray());

                $seatno2 = array_chunk($seatno, ceil(count($seatno) / $noofbatch));

                if (count($seatno) > 0)
                {
                    $all_batches = [];
                    $all_int_batch_alloc = [];

                    foreach ($seatno2 as $sn2)
                    {
                        $values = [
                            'exam_patternclasses_id' => $exam_patternclasses_id->first()->id,
                            'subject_id' => $validatedData['subject_id'],
                            'subject_type' => $subtype,
                            'status' => '1',
                            'created_at' => now(),
                            'updated_at' => now()
                        ];

                        $all_batches[] = $values;
                    }

                    Internalmarksbatch::insert($all_batches);

                    $inserted_batches = Internalmarksbatch::latest()->take(count($seatno2))->get();

                    $all_seatnos = array_merge(...$seatno2);

                    $exam_student_seatnos = Examstudentseatno::whereHas('exampatternclass', function ($query) use ($exam)
                    {
                        $query->where('exam_id', $exam->id);
                    })->whereIn('seatno', $all_seatnos)->with('student')->orderBy('id', 'desc')->get()->groupBy('seatno');

                    foreach ($inserted_batches as $index => $internalmarksbatch)
                    {
                        $sn2 = $seatno2[$index];
                        foreach ($sn2 as $sn)
                        {
                            if ($exam_student_seatnos->has($sn))
                            {
                                $examstudseatno = $exam_student_seatnos->get($sn)->first();
                                $student_id = $examstudseatno->student->id;

                                $all_int_batch_alloc[] = [
                                    'intbatch_id' => $internalmarksbatch->id,
                                    'student_id' => $student_id,
                                    'seatno' => $sn,
                                    'created_at' => now(),
                                    'updated_at' => now()
                                ];
                            }
                        }
                    }
                    Intbatchseatnoallocation::insert($all_int_batch_alloc);

                    DB::commit();
                    $this->resetinput();
                    $this->dispatch('alert', type: 'success', message: 'Batch Created Successfully !!');
                } else {
                    $this->dispatch('alert', type: 'info', message: 'Batch Already Created !!');
                }
            }
            elseif ($option == "3")
            {
                $fromseatno = $validatedData['fromseatno'];
                $toseatno = $validatedData['toseatno'];

                if ($fromseatno < $toseatno)
                {
                    $reindex = collect();

                    $student_exam_forms = $subject->studentexamforms()
                        ->where('exam_id', $exam->id)
                        ->where($colsubject, '1')
                        ->with(['examformmaster', 'student.examstudentseatnos.exampatternclass'])
                        ->get();

                    foreach ($student_exam_forms as $data)
                    {
                        if ($data->examformmaster->inwardstatus == 1)
                        {
                            $last_exam_student_seatno = $data->student->examstudentseatnos->where('exampatternclass.exam_id', $exam->id)->last();
                            if ($last_exam_student_seatno)
                            {
                                $reindex->push($last_exam_student_seatno->seatno);
                            }
                        }
                    }

                    $reindex = $reindex->sort();
                    $seatnoallocationcount = 0;
                    $alloseatno = collect();

                    $internal_marks_batches = $subject->internalmarksbatches()->where('exam_patternclasses_id', $exam_patternclasses_id->first()->id)->where('subject_type', $subtype)->with('intbatchseatnoallocations')->get();

                    foreach ($internal_marks_batches as $data)
                    {
                        $seatnoallocationcount += $data->intbatchseatnoallocations->count();
                        $alloseatno->push($data->intbatchseatnoallocations->pluck('seatno')->toArray());
                    }

                    $alloseatno = $alloseatno->collapse();
                    $diff = $reindex->diff($alloseatno);
                    $seatno = array_values($diff->toArray());

                    if (count($seatno) > 0)
                    {
                        $values = [
                            'exam_patternclasses_id' => $exam_patternclasses_id->first()->id,
                            'subject_id' => $validatedData['subject_id'],
                            'subject_type' => $subtype,
                            'status' => '1'
                        ];

                        $internalmarksbatch = Internalmarksbatch::create($values);

                        $int_batch_alloc = [];
                        $exam_student_seatnos = Examstudentseatno::whereIn('seatno', $seatno)->whereBetween('seatno', [$fromseatno, $toseatno])->with('student')->get()->groupBy('seatno');

                        foreach ($exam_student_seatnos as $sn => $examstudseatno_group)
                        {
                            $examstudseatno = $examstudseatno_group->first();
                            $student_id = $examstudseatno->student->id;

                            $int_batch_alloc[] = [
                                'intbatch_id' => $internalmarksbatch->id,
                                'student_id' => $student_id,
                                'seatno' => $sn,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                        }

                        Intbatchseatnoallocation::insert($int_batch_alloc);

                        DB::commit();
                        $this->resetinput();
                        $this->dispatch('alert', type: 'success', message: 'Batch Created Successfully !!');
                    }
                    else
                    {
                        $this->dispatch('alert', type: 'info', message: 'Batch Already Created !!');
                    }
                }
            }
            elseif ($option == "4")
            {
                if (isset($this->checked_seatno))
                {
                    $exam_patternclass = Exampatternclass::where('patternclass_id', $subject->patternclass_id)->where('exam_id', $exam->id)->first();

                    $values = [
                        'exam_patternclasses_id' => $exam_patternclass->id,
                        'subject_id' => $validatedData['subject_id'],
                        'subject_type' => $subtype,
                        'status' => '1'
                    ];

                    $internalmarksbatch = Internalmarksbatch::create($values);

                    $exam_student_seatnos = Examstudentseatno::whereIn('seatno', array_keys($this->checked_seatno))->with('student')->get()->groupBy('seatno');

                    $int_batch_alloc = [];

                    foreach ($this->checked_seatno as $sn => $value)
                    {
                        $examstudseatno = $exam_student_seatnos[$sn]->first();
                        $student_id = $examstudseatno->student->id;

                        $int_batch_alloc[] = [
                            'intbatch_id' => $internalmarksbatch->id,
                            'student_id' => $student_id,
                            'seatno' => $sn,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }

                    Intbatchseatnoallocation::insert($int_batch_alloc);

                    DB::commit();

                    $this->resetinput();

                    $this->dispatch('alert', type: 'success', message: 'Batch Created Successfully !!');
                } else {
                    $this->dispatch('alert', type: 'info', message: 'Batch Already Created !!');
                }
            }
        }
        catch(\Exception $e)
        {
            DB::rollBack();

            Log::error($e);

            $this->dispatch('alert',type:'error',message :'Failed to Create Batch. Please try again.');
        }
    }

    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }

    public function delete()
    {
        DB::beginTransaction();

        try
        {
            $int_batch = Internalmarksbatch::find($this->delete_id);

            if ($int_batch->status === 5)
            {
                $this->dispatch('alert', type: 'info', message: 'Batch cannot be removed as it is already in status 5 !!');
                return;
            }

            Intbatchseatnoallocation::where('intbatch_id', $int_batch->id)->delete();

            $int_batch->delete();

            $this->delete_id = null;

            DB::commit();

            $this->dispatch('alert', type: 'success', message: 'Batch Deleted Successfully !!');
        } catch (\Illuminate\Database\QueryException $e)
        {
            DB::rollBack();

            $this->dispatch('alert', type: 'error', message: 'Failed To Delete Batch Data !!');
        }
    }

    public function mount()
    {
        $this->currentdate=Carbon::now();

        $active_exam = Exam::select('id')->where('status',1)->first();

        $this->hod_subject_ids = Subject::whereHas('hodappointsubjects',function( $query){
            $query->where('faculty_id',Auth::guard('faculty')->user()->id);
        })->pluck('id');

        $this->hod_subject_patternclass_ids = Hodappointsubject::where('faculty_id',Auth::guard('faculty')->user()->id)
        ->where('status',1)
        ->pluck('patternclass_id');

        $active_exam_patternclass_pc_ids = Exampatternclass::where('exam_id',$active_exam->id)
        ->whereIn('patternclass_id',$this->hod_subject_patternclass_ids)
        ->where('launch_status',1)
        ->pluck('patternclass_id');

        $this->patternclasses = Classview::select('id','classyear_name', 'course_name', 'pattern_name')
        ->with(['exampatternclasses:id,intmarksstart_date,intmarksend_date,patternclass_id'])
        ->whereIn('id',$active_exam_patternclass_pc_ids)
        ->where('status',1)
        ->get();

    }

    public function render()
    {
        $int_batches = Internalmarksbatch::select('id','exam_patternclasses_id','subject_id','subject_type','created_at')
        ->with([
            'faculty:id,faculty_name',
            'subject:id,subject_name,subject_code',
            'exam_patternclass:id,intmarksstart_date,intmarksend_date,patternclass_id',
            'intbatchseatnoallocations'
            ])
        ->whereIn('subject_id',$this->hod_subject_ids)
        ->when($this->search, function($query, $search){
            $query->search($search);
        })->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);
        return view('livewire.faculty.internal-marks-batch.all-internal-marks-batch',compact('int_batches'))->extends('layouts.faculty')->section('faculty');
    }
}
