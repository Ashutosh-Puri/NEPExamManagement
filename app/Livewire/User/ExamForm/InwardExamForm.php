<?php

namespace App\Livewire\User\ExamForm;

use Carbon\Carbon;
use App\Models\Exam;
use App\Models\Course;
use App\Models\Pattern;
use Livewire\Component;
use App\Models\Classview;
use App\Models\Courseclass;
use App\Models\Patternclass;
use Livewire\WithPagination;
use App\Models\Admissiondata;
use App\Models\Examformmaster;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use App\Models\Currentclassstudent;
use Illuminate\Support\Facades\Auth;

class InwardExamForm extends Component
{   
    # By Ashutosh
    use WithPagination;

    public $page = 1;
    public $perPage = 10;
    public $search = '';
    public $sortColumn = "id";
    public $sortColumnBy = "ASC";
    public $course_id;
    public $list_by='m';
    public $application_id;
    public $patternclass_id;
    public $exam;

    #[Locked]
    public $student_name;
    #[Locked]
    public $mother_name;
    #[Locked]
    public $course_name;
    #[Locked]
    public $memid;

    #[Locked]
    public $inward_id;

    #[Locked]
    public $courses = [];
    #[Locked]
    public $pattern_classes = [];
    #[Locked]
    public $student_exam_form_fees = [];
    #[Locked]
    public $student_exam_form_subjects = [];
    #[Locked]
    public $student_exam_form_extrcredit_subjects = [];


    protected function rules()
    {
        $rules = [
            'course_id' => ['required', 'integer', Rule::exists('courses', 'id')],
            'patternclass_id' => ['required', 'integer', Rule::exists('pattern_classes', 'id')],
            'list_by' => ['required', 'in:o,m'],
        ];

        if ($this->list_by == 'o') {
            $rules['application_id'] = [
                'required',
                'integer',
                Rule::exists('examformmasters', 'id')->where(function ($query) {
                    return $query->where('patternclass_id', $this->patternclass_id);
                })
            ];
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'application_id.required' => 'The Application ID is required.',
            'application_id.integer' => 'The Application ID must be an integer.',
            'application_id.exists' => 'The Application ID does not exist For This Pattern Class.',

            'course_id.required' => 'The Course is required.',
            'course_id.integer' => 'The Course must be an integer.',
            'course_id.exists' => 'The selected Course does not exist.',

            'patternclass_id.required' => 'The Pattern Class is required.',
            'patternclass_id.integer' => 'The Pattern Class must be an integer.',
            'patternclass_id.exists' => 'The selected Pattern Class does not exist.',

            'list_by.required' => 'The List By field is required.',
            'list_by.in' => 'The List By field must be valid',
        ];

        return $messages;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function resetinput()
    {
        $this->reset([
            'page',
            'inward_id',
            'search',
            'course_id',
            'application_id',
            'list_by',
            'patternclass_id',
            'courses',
            'pattern_classes',
            'student_name',
            'mother_name',
            'course_name',
            'memid',
            'student_exam_form_fees',
            'student_exam_form_subjects',
            'student_exam_form_extrcredit_subjects',
        ]);
    }

    public function sort_column($column)
    {
        if ($this->sortColumn === $column) {
            $this->sortColumnBy = ($this->sortColumnBy == "ASC") ? "DESC" : "ASC";
            return;
        }
        $this->sortColumn = $column;
        $this->sortColumnBy == "ASC";
    }

    public function setpage($page)
    {
        $this->page = $page;
    }


    public function inward_class_form()
    {
        $this->validate();
        $this->page = 2;

    }


    public function procced_to_approve($id)
    {   
        $this->student_exam_form_extrcredit_subjects=collect([]);
        $this->application_id = null;
        $exam_form_master = Examformmaster::find($id);
        $this->inward_id = $exam_form_master->id;
        $this->mother_name = $exam_form_master->student->mother_name;
        $this->course_name = get_pattern_class_name($exam_form_master->patternclass_id);
        $this->memid = $exam_form_master->student->memid;
        $this->student_name = $exam_form_master->student->student_name;
        $this->student_exam_form_fees = $exam_form_master->studentexamformfees()->get();
        $this->student_exam_form_subjects = $exam_form_master->studentexamforms()->get();
        $this->student_exam_form_extrcredit_subjects = $exam_form_master->studentextracreditexamforms()->get();
        $this->page = 3;
    }

    public function inward_form(Examformmaster $exam_form_master)
    {
        DB::beginTransaction();

        try 
        {
            $exam_form_master->update(['inwardstatus' => 1, 'feepaidstatus' => 1, 'inwarddate' => now(),'payment_date'=>now(),'user_id'=>Auth::guard('user')->user()->id]);
            $student = $exam_form_master->student;
            $active_exam = $student->patternclass->exams()->where('launch_status', 1)->first();
            $student_current_class_entry = $student->currentclassstudents->last();
            $marksheet_printed_current_class_students = $student->currentclassstudents->where('markssheetprint_status', '!=', '-1');

            if (is_null($student_current_class_entry)) {
                if (is_null($student->prn)) {
                    
                    $admission_data = Admissiondata::where('memid', $student->memid)->where('patternclass_id', $student->patternclass_id)->get();
                    if ($admission_data->last()->academicyear_id == $exam_form_master->exam->academicyear_id) {
                        if (!$admission_data->isEmpty()) {
                            $student->studentadmissions()->create(['student_id' => $student->id, 'patternclass_id' => $exam_form_master->patternclass_id, 'academicyear_id' => $exam_form_master->exam->academicyear_id, 'college_id' => $exam_form_master->college_id], ['student_id', 'patternclass_id', 'academicyear_id']);
                        }
                    }
                    
                    //3 digit coursecode  2 digit year 5 digit studentid
                    $course_code = $student->patternclass->courseclass->course->course_code;
                    $year = Carbon::now()->format('y');
                    str_pad($student->id, 5, '0', STR_PAD_LEFT);
                    $prn = $course_code . $year . str_pad($student->id, 5, '0', STR_PAD_LEFT);
                    $student->update(['prn' => $prn]);

                    if (get_student_current_sem($exam_form_master->student_id) === 'not_regular') 
                    {   
                        // FY Direct SEM-II Student // Done
                        $student->currentclassstudents()->create([
                            'sem' =>1,
                            'patternclass_id' =>$exam_form_master->patternclass_id,
                            'student_id' => $exam_form_master->student_id,
                            'college_id' => $exam_form_master->college_id,
                            'academicyear_id' => $exam_form_master->exam->academicyear_id,
                        ]);

                        $student->currentclassstudents()->create([
                            'sem' =>2,
                            'patternclass_id' =>$exam_form_master->patternclass_id,
                            'student_id' => $exam_form_master->student_id,
                            'college_id' => $exam_form_master->college_id,
                            'academicyear_id' => $exam_form_master->exam->academicyear_id,
                        ]);
                    } 
                    else 
                    {   
                      
                        // FY SEM-I Regular Student // Done
                        $student->currentclassstudents()->create([
                            'sem' => get_student_current_sem($exam_form_master->student_id),
                            'patternclass_id' =>$exam_form_master->patternclass_id,
                            'student_id' => $exam_form_master->student_id,
                            'college_id' => $exam_form_master->college_id,
                            'academicyear_id' => $exam_form_master->exam->academicyear_id,
                        ]);
                    }
                }
            } else 
            {   
     
                switch ($student_current_class_entry->sem) 
                {   
                    case 1:
                        // FY SEM-II Regular Student // Done
                        $student->currentclassstudents()->create([
                            'sem' => 2,
                            'patternclass_id' =>$student_current_class_entry->patternclass_id,
                            'student_id' => $exam_form_master->student_id,
                            'college_id' => $exam_form_master->college_id,
                            'academicyear_id' => $exam_form_master->exam->academicyear_id,
                        ]);
                    break;
                    case 2:
                        $sem_1_data = $student->studentresults->where('sem', $student_current_class_entry->sem - 1)->last();
                        $sem_2_data = $student->studentresults->where('sem', $student_current_class_entry->sem)->last();

                        if (is_null($sem_1_data) && is_null($sem_2_data))
                        {   
                            //  Direct SY SEM-III // Done
                            if ($student_current_class_entry->markssheetprint_status = -1) 
                            {   
                                $student->currentclassstudents()->create([
                                    'sem' => $student_current_class_entry->sem + 1,
                                    'patternclass_id' =>$student_current_class_entry->patternclass_id+1,
                                    'student_id' => $exam_form_master->student_id,
                                    'college_id' => $exam_form_master->college_id,
                                    'academicyear_id' => $exam_form_master->exam->academicyear_id,
                                ]);

                                $student->studentadmissions()->create([
                                    'student_id' => $student->id, 
                                    'patternclass_id' => $student_current_class_entry->patternclass_id+1, 
                                    'academicyear_id' => $active_exam->academicyear_id
                                    ],['student_id','patternclass_id','academicyear_id']
                                );
                            }
                        } else if (!(is_null($sem_1_data) && is_null($sem_2_data))) 
                        {   
                            $this->year_result = $student->get_year_result_exam_form($sem_1_data,$sem_2_data, $student_current_class_entry);
                            if ($this->year_result == 0)
                            {
                                // FY SEM-I-II Fail Student // Done
                                $student->currentclassstudents()->create([
                                    'sem' => $student_current_class_entry->sem,
                                    'patternclass_id' =>$student_current_class_entry->patternclass_id,
                                    'student_id' => $exam_form_master->student_id,
                                    'college_id' => $exam_form_master->college_id,
                                    'academicyear_id' => $exam_form_master->exam->academicyear_id,
                                ]);
                                
                            } else 
                            {   
                                // SY SEM-III Regular Student // Done
                                if ($active_exam->exam_sessions == 2) 
                                {   
                                    $student->currentclassstudents()->create([
                                        'sem' =>3,
                                        'patternclass_id' =>$student_current_class_entry->patternclass_id,
                                        'student_id' => $exam_form_master->student_id,
                                        'college_id' => $exam_form_master->college_id,
                                        'academicyear_id' => $exam_form_master->exam->academicyear_id,
                                    ]);

                                    $student->currentclassstudents()->create([
                                        'sem' =>4,
                                        'patternclass_id' =>$student_current_class_entry->patternclass_id,
                                        'student_id' => $exam_form_master->student_id,
                                        'college_id' => $exam_form_master->college_id,
                                        'academicyear_id' => $exam_form_master->exam->academicyear_id,
                                    ]);
                                } else 
                                {   
                                    // SY SEM-III Regular Student // Done
                                    $student->currentclassstudents()->create([
                                        'sem' => $student_current_class_entry->sem + 1,
                                        'patternclass_id' =>$student_current_class_entry->patternclass_id + 1,
                                        'student_id' => $exam_form_master->student_id,
                                        'college_id' => $exam_form_master->college_id,
                                        'academicyear_id' => $exam_form_master->exam->academicyear_id,
                                    ]);

                                    $student->studentadmissions()->create([
                                        'student_id' => $student->id, 
                                        'patternclass_id' => $student_current_class_entry->patternclass_id + 1, 
                                        'academicyear_id' => $active_exam->academicyear_id
                                        ],['student_id','patternclass_id','academicyear_id']
                                    );
                                }
                            }
                        }
                        break;
                    case 3:
                       // SY SEM-IV Regular Student // Done
                        $student->currentclassstudents()->create([
                            'sem' => 4,
                            'patternclass_id' =>$student_current_class_entry->patternclass_id,
                            'student_id' => $exam_form_master->student_id,
                            'college_id' => $exam_form_master->college_id,
                            'academicyear_id' => $exam_form_master->exam->academicyear_id,
                        ]);
                        break;
                    case 4:

                        if ($marksheet_printed_current_class_students->isEmpty()) 
                        {
                          
                            $student->currentclassstudents()->create([
                                'sem' => $student_current_class_entry->sem + 1,
                                'patternclass_id' =>$student_current_class_entry->patternclass_id + 1,
                                'student_id' => $exam_form_master->student_id,
                                'college_id' => $exam_form_master->college_id,
                                'academicyear_id' => $exam_form_master->exam->academicyear_id,
                            ]);

                            $student->studentadmissions()->create([
                                'student_id' => $student->id, 
                                'patternclass_id' => $student_current_class_entry->patternclass_id + 1, 
                                'academicyear_id' => $active_exam->academicyear_id
                                ],['student_id','patternclass_id','academicyear_id']
                            );
                        }

                        if ($marksheet_printed_current_class_students->count() == 4) 
                        {     
                            $sem_1_data = $student->studentresults->where('sem', 1)->last();
                            $sem_2_data = $student->studentresults->where('sem', 2)->last();
                            if (!(is_null($sem_1_data) && is_null($sem_2_data))) 
                            {
                                $this->year_result = $student->get_year_result_exam_form($sem_1_data,$sem_2_data, $student_current_class_entry);
                               
                                if ($this->year_result != 1 )
                                {   
                                    $student->currentclassstudents()->create([
                                        'sem' => $student_current_class_entry->sem,
                                        'patternclass_id' =>$student_current_class_entry->patternclass_id,
                                        'student_id' => $exam_form_master->student_id,
                                        'college_id' => $exam_form_master->college_id,
                                        'academicyear_id' => $exam_form_master->exam->academicyear_id,
                                    ]);
                                } 
                                else 
                                {   
                                    $sem_3_data = $student->studentresults->where('sem', 3)->last();
                                    $sem_4_data = $student->studentresults->where('sem', 4)->last();
                                    
                                    if (!(is_null($sem_3_data) && is_null($sem_4_data))) 
                                    {
                                        $this->year_result = $student->get_year_result_exam_form($sem_3_data, $sem_4_data, $student_current_class_entry);
                                        if ($this->year_result == 0)
                                        {   
                                            $student->currentclassstudents()->create([
                                                'sem' => $student_current_class_entry->sem,
                                                'patternclass_id' =>$student_current_class_entry->patternclass_id,
                                                'student_id' => $exam_form_master->student_id,
                                                'college_id' => $exam_form_master->college_id,
                                                'academicyear_id' => $exam_form_master->exam->academicyear_id,
                                            ]);
                                            
                                        } 
                                        else 
                                        {
                                            if ($active_exam->exam_sessions == 2)
                                            {
                                                $student->currentclassstudents()->create([
                                                    'sem' => 5,
                                                    'patternclass_id' =>$student_current_class_entry->patternclass_id,
                                                    'student_id' => $exam_form_master->student_id,
                                                    'college_id' => $exam_form_master->college_id,
                                                    'academicyear_id' => $exam_form_master->exam->academicyear_id,
                                                ]);

                                                $student->currentclassstudents()->create([
                                                    'sem' => 6,
                                                    'patternclass_id' =>$student_current_class_entry->patternclass_id,
                                                    'student_id' => $exam_form_master->student_id,
                                                    'college_id' => $exam_form_master->college_id,
                                                    'academicyear_id' => $exam_form_master->exam->academicyear_id,
                                                ]);

                                            } else 
                                            {   
                                                $student->currentclassstudents()->create([
                                                    'sem' => $student_current_class_entry->sem + 1,
                                                    'patternclass_id' =>$student_current_class_entry->patternclass_id + 1,
                                                    'student_id' => $exam_form_master->student_id,
                                                    'college_id' => $exam_form_master->college_id,
                                                    'academicyear_id' => $exam_form_master->exam->academicyear_id,
                                                ]);
                                          
                                                $student->studentadmissions()->create([
                                                    'student_id' => $student->id, 
                                                    'patternclass_id' => $student_current_class_entry->patternclass_id + 1, 
                                                    'academicyear_id' => $active_exam->academicyear_id
                                                    ],['student_id','patternclass_id','academicyear_id']
                                                );
                                            }
                                        }
                                    }
                                }
                            }
                        } else if ($marksheet_printed_current_class_students->count() == 2) 
                        {
                            $sem_1_data = $student->studentresults->where('sem', $student_current_class_entry->sem - 1)->last();
                            $sem_2_data = $student->studentresults->where('sem', $student_current_class_entry->sem)->last();

                            if (!(is_null($sem_1_data) && is_null($sem_2_data))) 
                            {
                                $this->year_result = $student->get_year_result_exam_form($sem_1_data,$sem_2_data, $student_current_class_entry);

                                if ($this->year_result == 0)
                                {   
                                    $student->currentclassstudents()->create([
                                        'sem' => $student_current_class_entry->sem,
                                        'patternclass_id' =>$student_current_class_entry->patternclass_id,
                                        'student_id' => $exam_form_master->student_id,
                                        'college_id' => $exam_form_master->college_id,
                                        'academicyear_id' => $exam_form_master->exam->academicyear_id,
                                    ]);

                                } else
                                {
                                    if ($active_exam->exam_sessions == 2)
                                    {   
                                        $student->currentclassstudents()->create([
                                            'sem' => 5,
                                            'patternclass_id' =>$student_current_class_entry->patternclass_id,
                                            'student_id' => $exam_form_master->student_id,
                                            'college_id' => $exam_form_master->college_id,
                                            'academicyear_id' => $exam_form_master->exam->academicyear_id,
                                        ]);

                                        $student->currentclassstudents()->create([
                                            'sem' => 6,
                                            'patternclass_id' =>$student_current_class_entry->patternclass_id,
                                            'student_id' => $exam_form_master->student_id,
                                            'college_id' => $exam_form_master->college_id,
                                            'academicyear_id' => $exam_form_master->exam->academicyear_id,
                                        ]);

                                    } else 
                                    {   
                                        $student->currentclassstudents()->create([
                                            'sem' => $student_current_class_entry->sem + 1,
                                            'patternclass_id' =>$student_current_class_entry->patternclass_id + 1,
                                            'student_id' => $exam_form_master->student_id,
                                            'college_id' => $exam_form_master->college_id,
                                            'academicyear_id' => $exam_form_master->exam->academicyear_id,
                                        ]);

                                        $student->studentadmissions()->create([
                                            'student_id' => $student->id, 
                                            'patternclass_id' => $student_current_class_entry->patternclass_id + 1, 
                                            'academicyear_id' => $active_exam->academicyear_id
                                            ],['student_id','patternclass_id','academicyear_id']
                                        );
                                    }
                                }
                            }
                        }
                        break;
                    case 5:
                        // TY SEM-VI // Done
                        $student->currentclassstudents()->create([
                            'sem' =>6,
                            'patternclass_id' =>$student_current_class_entry->patternclass_id,
                            'student_id' => $exam_form_master->student_id,
                            'college_id' => $exam_form_master->college_id,
                            'academicyear_id' => $exam_form_master->exam->academicyear_id,
                        ]);
                        break;
                }
            }

            DB::commit();

            $this->application_id = null;
            $this->inward_id = null;

            $this->dispatch('alert', type: 'success', message: 'Exam Form Inward Successfully !!');

            if ($this->list_by == 'o') {
                $this->page = 1;
            } else {
                $this->page = 2;
            }

        } catch (\Exception $e) {

            DB::rollBack();

            \Log::error($e);

            $this->dispatch('alert', type: 'error', message: 'Failed To Inward Exam Form !!');
        }
    }

    public function verify(Examformmaster $exam_form_master)
    {
        DB::beginTransaction();

        try {
            $exam_form_master->verified_at = now();
            $exam_form_master->update();

            DB::commit();

            $this->application_id = null;
            $this->inward_id = null;
            if ($this->list_by == 'o') {
                $this->page = 1;
            } else {
                $this->page = 2;
            }
            $this->dispatch('alert', type: 'success', message: 'Exam Form Verified Successfully !!');

        } catch (\Exception $e) {

            DB::rollBack();

            \Log::error($e);

            $this->dispatch('alert', type: 'error', message: 'Failed To Verify Exam Form !!');
        }
    }

    public function mount()
    {

        $this->exam = Exam::where('status', 1)->first();
    }

    public function render()
    {
        $exam_form_master_inwards = collect([]);
        $exam_form_masters = collect([]);
        if ($this->page == 1) {
            $this->courses = Course::select('course_name', 'id')->get();
            if ($this->course_id) {
                $courseclassids = Courseclass::select('id')->where('course_id', $this->course_id)->pluck('id')->toArray();
                $this->pattern_classes = Classview::select('id','classyear_name', 'course_name', 'pattern_name')->where('status',1)->whereIn('class_id', $courseclassids)->get();
            }
        } elseif ($this->page == 2) {
            if ($this->patternclass_id && $this->list_by == "m") {
                $exam_form_masters = Examformmaster::where('exam_id', $this->exam->id)->where('printstatus', 1)->where('inwardstatus', 0)->where('patternclass_id', $this->patternclass_id)->when($this->search, function ($query, $search) {
                    $query->search($search);
                })->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

                $exam_form_master_inwards = Examformmaster::where('exam_id', $this->exam->id)->where('printstatus', 1)->where('inwardstatus', 1)->where('patternclass_id', $this->patternclass_id)->when($this->search, function ($query, $search) {
                    $query->search($search);
                })->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

            } elseif ($this->patternclass_id && $this->list_by == "o" && $this->application_id) {
                $exam_form_masters = Examformmaster::where('exam_id', $this->exam->id)->where('printstatus', 1)->where('inwardstatus', 0)->where('patternclass_id', $this->patternclass_id)->where('id', $this->application_id)->first();
                if ($exam_form_masters) {
                    $this->page = 3;
                    $this->procced_to_approve($exam_form_masters->id);
                }
            }
        }

        return view('livewire.user.exam-form.inward-exam-form', compact('exam_form_masters', 'exam_form_master_inwards'))->extends('layouts.user')->section('user');
    }
}
