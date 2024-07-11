<?php

namespace App\Livewire\Student\StudentExamForm;


use App\Models\Exam;
use App\Models\College;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Subject;
use Livewire\Component;
use App\Models\Examfeeview;
use App\Models\Patternclass;
use App\Models\Admissiondata;
use App\Models\Examfeecourse;
use App\Models\Examfeemaster;
use App\Models\Examformmaster;
use App\Models\Formtypemaster;
use App\Models\Studentexamform;
use Livewire\Attributes\Locked;
use App\Models\Exampatternclass;
use App\Models\Extracreditsubject;
use App\Models\Studentexamformfee;
use Illuminate\Support\Facades\DB;
use App\Models\Currentclassstudent;
use Illuminate\Support\Facades\Auth;


class FillStudentExamForm extends Component
{

    public $medium_instruction;
    public $abcid;
    #[Locked]
    public Student $student;
    #[Locked]
    public $patternclass_id;
    #[Locked]
    public $member_id;
    #[Locked]
    public $year_result = null;
    #[Locked]
    public $page = 1;
    #[Locked]
    public $abcid_option = [];
    #[Locked]
    public $regular_subjects_data = [];
    #[Locked]
    public $extra_credit_subjects_data = [];
    #[Locked]
    public $backlog_subjects_data = [];
    #[Locked]
    public $exam_fee_courses = [];
    #[Locked]
    public $internals = [];
    #[Locked]
    public $externals = [];
    #[Locked]
    public $practicals = [];
    #[Locked]
    public $grades = [];
    #[Locked]
    public $projects = [];

    public function mount()
    {   
        $this->student = Auth::guard('student')->user();

        $college=College::where('is_default',1)->first();
        if($college)
        {
            $setting=Setting::where('college_id',$college->id)->first();
            $this->abcid_option = [
                'show_abcid' => false,
                'required_abcid' => false,
            ];
            
            if ($setting) {
               
                $this->abcid_option['show_abcid'] = (bool) $setting->show_abcid;
            
                
                $this->abcid_option['required_abcid'] = (bool) $setting->abcid_required;
            }
        }
        $this->member_id = $this->student->memid;
        $this->abcid = $this->student->abcid;
       
    }


    public function render()
    {   
        
        $this->regular_subjects_data = [];
        $this->extra_credit_subjects_data = [];
        $this->backlog_subjects_data = [];
        $this->exam_fee_courses = [];

        $current_active_exams = Exam::Where('status', 1)->get();
        $current_exam_session = null;
        $current_exam_session = $current_active_exams->first()->exam_sessions;
        $current_class_student_last_entry = $this->student->currentclassstudents->last();
        $current_class_inward_students = $this->student->currentclassstudents->where('pfstatus', '!=', -1)->where('markssheetprint_status', '!=', -1);

        if (true) 
        {
            if (is_null($current_class_student_last_entry)) 
            {
                // Fetch FY
                $admission_data = Admissiondata::where('memid', $this->member_id)->where('patternclass_id', $this->student->patternclass_id)->get();

                if (!$admission_data->isEmpty()) 
                {
                    if ($admission_data->last()->academicyear_id == $current_active_exams->first()->academicyear_id) 
                    {
                        if ($current_exam_session != 2) 
                        {   
                            // Fetch FY SEM-I Regular Student // Done
                            $this->regular_subjects_data=[];
                            $this->regular_subjects_data = Subject::whereIn('id', $admission_data->pluck('subject_id'))->where('subject_sem', 1)->get();
                            $this->check_subject_checkboxs($this->regular_subjects_data);
                            $this->backlog_subjects_data=[];
                            $this->exam_fee_courses=get_exam_form_fees($this->student->patternclass_id,$this->regular_subjects_data,$this->backlog_subjects_data);
                        } 
                        else 
                        {   
                            // Fetch Direct SEM-II Regular Student With SEM-I As Backlog // Done
                            $this->backlog_subjects_data=[];
                            $this->backlog_subjects_data = Subject::whereIn('id', $admission_data->pluck('subject_id'))->where('subject_sem', 1)->get();
                            $this->check_backlog_subject_checkboxs($this->backlog_subjects_data);
                            $this->regular_subjects_data=[];
                            $this->regular_subjects_data = Subject::whereIn('id', $admission_data->pluck('subject_id'))->where('subject_sem', 2)->get();
                            $this->check_subject_checkboxs($this->regular_subjects_data);
                            $this->exam_fee_courses=get_exam_form_fees($this->student->patternclass_id,$this->regular_subjects_data,$this->backlog_subjects_data);
                        }

                    } else 
                    {
                        $this->dispatch('alert', type: 'info', message: 'No Admission In The Current Year !!');
                    }
                } else 
                {
                    $this->dispatch('alert', type: 'info', message: 'Invalid Member ID Please Update Your Profile !!');
                }
            } else 
            {
                switch ($current_class_student_last_entry->sem) 
                {
                    case 1:
                        // Fetch FY SEM-II
                        $this->patternclass_id = $current_class_student_last_entry->patternclass_id;
                        
                        $admission_data = Admissiondata::where('memid', $this->member_id)->where('patternclass_id', $this->patternclass_id)->get();

                        if (!$admission_data->isEmpty()) 
                        {
                            if ($admission_data->last()->academicyear_id == $current_active_exams->first()->academicyear_id) 
                            {   
                                // FY SEM-II Regular Student // Done
                                $this->regular_subjects_data = [];
                                $this->regular_subjects_data = Subject::whereIn('id', $admission_data->pluck('subject_id'))->where('subject_sem', $current_class_student_last_entry->sem + 1)->get();
                                $this->check_subject_checkboxs($this->regular_subjects_data);
                                $this->backlog_subjects_data = [];
                                $this->backlog_subjects_data = $this->student->get_backlog_subjects();
                                $this->check_backlog_subject_checkboxs($this->backlog_subjects_data);
                                $this->exam_fee_courses = get_exam_form_fees($this->patternclass_id,$this->regular_subjects_data, $this->backlog_subjects_data);
                            } 
                            else 
                            {   
                                // FY SEM-II Old Year Student // Done
                                $this->regular_subjects_data = [];
                                $this->regular_subjects_data = Subject::whereIn('id', $admission_data->pluck('subject_id'))->where('subject_sem', $current_class_student_last_entry->sem + 1)->get();
                                $this->check_subject_checkboxs($this->regular_subjects_data);
                                $this->backlog_subjects_data = [];
                                $this->backlog_subjects_data = $this->student->get_backlog_subjects();
                                $this->check_backlog_subject_checkboxs($this->backlog_subjects_data);
                                $this->exam_fee_courses = get_exam_form_fees($this->patternclass_id,$this->regular_subjects_data, $this->backlog_subjects_data);
                            }
                        } 
                        else 
                        {
                            $this->dispatch('alert', type: 'info', message: 'Invalid Member ID Please Update Your Profile !!');
                        }

                        break;
                    case 2:
                        // Fetch SY SEM-III
                        $this->patternclass_id = $current_class_student_last_entry->patternclass_id+1;
                        $admission_data = Admissiondata::where('memid', $this->member_id)->where('patternclass_id', $this->patternclass_id)->get();
                        if (!$admission_data->isEmpty()) 
                        {   
                            if ($admission_data->last()->academicyear_id == $current_active_exams->first()->academicyear_id) 
                            {
                                $sem_1_data = $this->student->studentresults->where('sem', $current_class_student_last_entry->sem - 1)->last();
                                $sem_2_data = $this->student->studentresults->where('sem', $current_class_student_last_entry->sem)->last();
                                if (is_null($sem_1_data) && is_null($sem_2_data)) {
                                    if ($current_class_student_last_entry->markssheetprint_status = -1) 
                                    {   
                                        // If Marksheet Not Printed & FY Result Not Exists
                                        // Fetch Direct SY SEM-III
                                        $this->regular_subjects_data=[];
                                        $this->regular_subjects_data = Subject::whereIn('id', $admission_data->pluck('subject_id'))->where('subject_sem', $current_class_student_last_entry->sem + 1)->get();
                                        $this->check_subject_checkboxs($this->regular_subjects_data);
                                        $this->backlog_subjects_data = [];
                                        $this->backlog_subjects_data = $this->student->get_backlog_subjects();
                                        $this->check_backlog_subject_checkboxs($this->backlog_subjects_data);
                                        $this->exam_fee_courses = get_exam_form_fees($this->patternclass_id,$this->regular_subjects_data, $this->backlog_subjects_data);
                                    }
                                } else if (!(is_null($sem_1_data) && is_null($sem_2_data))) 
                                {
                                    $this->year_result = $this->student->get_year_result_exam_form($sem_1_data, $sem_2_data, $current_class_student_last_entry);
                                   
                                    if ($this->year_result == 0) 
                                    {   
                                        $is_fail=true;
                                        // Fetch FY SEM-I-II Fail Student
                                        $this->regular_subjects_data = [];
                                        $this->backlog_subjects_data = [];
                                        $this->backlog_subjects_data = $this->student->get_backlog_subjects();
                                        $this->check_backlog_subject_checkboxs($this->backlog_subjects_data);
                                        $this->exam_fee_courses = get_exam_form_fees($this->patternclass_id,$this->regular_subjects_data, $this->backlog_subjects_data,$is_fail);
                                    } else 
                                    {   
                                        
                                        if ($current_exam_session == 2) 
                                        {   
                                            // Fetch SY SEM-IV Pass OR ATKT Student
                                            $this->regular_subjects_data=[];
                                            $this->regular_subjects_data = Subject::whereIn('id', $admission_data->pluck('subject_id'))->where('subject_sem', $current_class_student_last_entry->sem + 2)->get();
                                            $this->check_subject_checkboxs($this->regular_subjects_data);
                                            $this->backlog_subjects_data = [];
                                            $this->backlog_subjects_data = $this->student->get_backlog_subjects();
                                            $this->check_backlog_subject_checkboxs($this->backlog_subjects_data);
                                        } else 
                                        {   
                                            /// Fetch SY SEM-III Pass OR ATKT Student
                                            $this->regular_subjects_data=[];
                                            $this->regular_subjects_data = Subject::whereIn('id', $admission_data->pluck('subject_id'))->where('subject_sem', $current_class_student_last_entry->sem + 1)->get();
                                            $this->check_subject_checkboxs($this->regular_subjects_data);
                                            $this->backlog_subjects_data = [];
                                            $this->backlog_subjects_data = $this->student->get_backlog_subjects();
                                            $this->check_backlog_subject_checkboxs($this->backlog_subjects_data);
                                            $this->exam_fee_courses = get_exam_form_fees($this->patternclass_id,$this->regular_subjects_data, $this->backlog_subjects_data);
                                        }
                                    }
                                }
                            } 
                            else 
                            {   
                                // Old Academic Year
                                // FY SEM-I-II Fail Student
                                $this->regular_subjects_data = [];
                                $this->backlog_subjects_data = [];
                                $this->backlog_subjects_data = $this->student->get_backlog_subjects();
                                $this->check_backlog_subject_checkboxs($this->backlog_subjects_data);
                                $this->exam_fee_courses = get_exam_form_fees($this->patternclass_id,$this->regular_subjects_data, $this->backlog_subjects_data);
                            }
                        } else 
                        {   
                            // Admission Data Not Found
                            // FY SEM-I-II Fail Student
                            $this->regular_subjects_data = [];
                            $this->backlog_subjects_data = [];
                            $this->backlog_subjects_data = $this->student->get_backlog_subjects();
                            $this->check_backlog_subject_checkboxs($this->backlog_subjects_data);
                            $this->exam_fee_courses = get_exam_form_fees($this->patternclass_id,$this->regular_subjects_data, $this->backlog_subjects_data);
                        }
                        break;
                    case 3:
                        // Fetch SY SEM-IV
                        $this->patternclass_id = $current_class_student_last_entry->patternclass_id;
                        $admission_data = Admissiondata::where('memid', $this->member_id)->where('patternclass_id', $this->patternclass_id)->get();
                        
                        if (!$admission_data->isEmpty()) 
                        {
                            if ($admission_data->last()->academicyear_id == $current_active_exams->first()->academicyear_id) 
                            {   
                                // Fetch SY SEM-IV Pass OR ATKT Student
                                $this->regular_subjects_data=[];
                                $this->regular_subjects_data = Subject::whereIn('id', $admission_data->pluck('subject_id'))->where('subject_sem', $current_class_student_last_entry->sem + 1)->get();
                                $this->check_subject_checkboxs($this->regular_subjects_data);
                                $this->backlog_subjects_data = [];
                                $this->backlog_subjects_data = $this->student->get_backlog_subjects();
                                $this->check_backlog_subject_checkboxs($this->backlog_subjects_data);
                                $this->exam_fee_courses = get_exam_form_fees($this->patternclass_id,$this->regular_subjects_data, $this->backlog_subjects_data);
                            } 
                            else 
                            {   
                                // Old Academic Year
                                // Fetch SY SEM-IV Pass OR ATKT Student
                                $this->regular_subjects_data = null;
                                $this->regular_subjects_data = Subject::whereIn('id', $admission_data->pluck('subject_id'))->where('subject_sem', $current_class_student_last_entry->sem + 1)->get();
                                $this->check_subject_checkboxs($this->regular_subjects_data);
                                $this->backlog_subjects_data = [];
                                $this->backlog_subjects_data = $this->student->get_backlog_subjects();
                                $this->check_backlog_subject_checkboxs($this->backlog_subjects_data);
                                $this->exam_fee_courses = get_exam_form_fees($this->patternclass_id,$this->regular_subjects_data, $this->backlog_subjects_data);
                            }
                        } 
                        else 
                        {
                            $this->dispatch('alert', type: 'info', message: 'Invalid Member ID Please Update Your Profile !!');
                        }

                        break;
                    case 4:;
                        // Fetch TY SEM-V
                        $this->patternclass_id = $current_class_student_last_entry->patternclass_id+1;
                        $admission_data = Admissiondata::where('memid', $this->member_id)->where('patternclass_id', $this->patternclass_id)->get();

                        if (!$admission_data->isEmpty()) 
                        {
                            if ($admission_data->last()->academicyear_id == $current_active_exams->first()->academicyear_id) 
                            {   
                                if ($current_class_inward_students->isEmpty()) 
                                {   
                                    // If Exam Form Not Inward Exam Not Given
                                    // Fetch SEM V // Done
                                    $this->regular_subjects_data = [];
                                    $this->regular_subjects_data = Subject::whereIn('id', $admission_data->pluck('subject_id'))->where('subject_sem', $current_class_student_last_entry->sem + 1)->get();
                                    $this->check_subject_checkboxs($this->regular_subjects_data);
                                    $this->backlog_subjects_data = [];
                                    $this->backlog_subjects_data = $this->student->get_backlog_subjects();
                                    $this->check_backlog_subject_checkboxs($this->backlog_subjects_data);
                                    $this->exam_fee_courses = get_exam_form_fees($this->patternclass_id,$this->regular_subjects_data, $this->backlog_subjects_data);
                                }

                                if ($current_class_inward_students->count() == 4) 
                                {   
                                    // If Exam Form Inward And Exam Given
                                    $sem_1_data = $this->student->studentresults->where('sem', 1)->last();
                                    $sem_2_data = $this->student->studentresults->where('sem', 2)->last();

                                    if (!(is_null($sem_1_data) && is_null($sem_2_data))) 
                                    {   
                                        $this->year_result = $this->student->get_year_result_exam_form($sem_1_data, $sem_2_data, $current_class_student_last_entry);
                                        // Checking FY Result Not Pass
                                        if ($this->year_result != 1) 
                                        {   
                                            // Fetch Fail Or ATKT Student
                                            $this->regular_subjects_data = [];
                                            $this->backlog_subjects_data = [];
                                            $this->backlog_subjects_data = $this->student->get_backlog_subjects();
                                            $this->check_backlog_subject_checkboxs($this->backlog_subjects_data);
                                            $this->exam_fee_courses = get_exam_form_fees($this->patternclass_id,$this->regular_subjects_data, $this->backlog_subjects_data);

                                        } 
                                        else 
                                        {   
                                            //  If FY Pass
                                            $sem_3_data = $this->student->studentresults->where('sem', 3)->last();
                                            $sem_4_data = $this->student->studentresults->where('sem', 4)->last();
                                            
                                            if (!(is_null($sem_3_data) && is_null($sem_4_data))) 
                                            {
                                                $this->year_result = $this->student->get_year_result_exam_form($sem_3_data, $sem_4_data, $current_class_student_last_entry);
                                                
                                                // Checking SY Result 
                                                if ($this->year_result == 0) 
                                                {   
                                                    // If SY Fail 
                                                    // Fetch Fail Student
                                                    $is_fail=true;
                                                    $this->regular_subjects_data = [];
                                                    $this->backlog_subjects_data = [];
                                                    $this->backlog_subjects_data = $this->student->get_backlog_subjects();
                                                    $this->check_backlog_subject_checkboxs($this->backlog_subjects_data);
                                                    $this->exam_fee_courses = get_exam_form_fees($this->patternclass_id,$this->regular_subjects_data, $this->backlog_subjects_data,$is_fail);
                                                  
                                                } 
                                                else 
                                                {  
                                                    // If SY Pass
                                                    if ($current_exam_session == 2) 
                                                    {   
                                                        // Fetch SEM-VI Pass Or ATKT Student                       
                                                        $this->regular_subjects_data = [];
                                                        $this->regular_subjects_data = Subject::whereIn('id', $admission_data->pluck('subject_id'))->where('subject_sem', $current_class_student_last_entry->sem + 2)->get();
                                                        $this->check_subject_checkboxs($this->regular_subjects_data);
                                                        $this->backlog_subjects_data = [];
                                                        $this->backlog_subjects_data = $this->student->get_backlog_subjects();
                                                        $this->check_backlog_subject_checkboxs($this->backlog_subjects_data);
                                                        $this->exam_fee_courses = get_exam_form_fees($this->patternclass_id,$this->regular_subjects_data, $this->backlog_subjects_data);
                                                    }
                                                    else 
                                                    {   
                                                        // Fetch SEM-V Pass Or ATKT Student 
                                                        $this->regular_subjects_data = [];
                                                        $this->regular_subjects_data = Subject::whereIn('id', $admission_data->pluck('subject_id'))->where('subject_sem', $current_class_student_last_entry->sem + 1)->get();
                                                        $this->check_subject_checkboxs($this->regular_subjects_data);
                                                        $this->backlog_subjects_data = [];
                                                        $this->backlog_subjects_data = $this->student->get_backlog_subjects();
                                                        $this->check_backlog_subject_checkboxs($this->backlog_subjects_data);
                                                        $this->exam_fee_courses = get_exam_form_fees($this->patternclass_id,$this->regular_subjects_data, $this->backlog_subjects_data);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }

                                if ($current_class_inward_students->count() == 2) 
                                {
                                    $sem_1_data = $this->student->studentresults->where('sem', $current_class_student_last_entry->sem - 1)->last();
                                    $sem_2_data = $this->student->studentresults->where('sem', $current_class_student_last_entry->sem)->last();

                                    if (!(is_null($sem_1_data) && is_null($sem_2_data))) 
                                    {
                                        $this->year_result = $this->student->get_year_result_exam_form($sem_1_data, $sem_2_data, $current_class_student_last_entry);
                                        // Checking FY Result
                                        if ($this->year_result == 0) 
                                        {   
                                            // If FY Fail
                                            // Fetch Fail Student
                                            $is_fail=true;
                                            $this->regular_subjects_data = [];
                                            $this->backlog_subjects_data = [];
                                            $this->backlog_subjects_data = $this->student->get_backlog_subjects();
                                            $this->check_backlog_subject_checkboxs($this->backlog_subjects_data);
                                            $this->exam_fee_courses = get_exam_form_fees($this->patternclass_id,$this->regular_subjects_data, $this->backlog_subjects_data, $is_fail);
                                        } else 
                                        {
                                            // Fetch Pass or ATKT Student
                                            $this->regular_subjects_data = Subject::whereIn('id', $admission_data->pluck('subject_id'))->get();
                                            $this->check_subject_checkboxs($this->regular_subjects_data);
                                            $this->backlog_subjects_data = [];
                                            $this->backlog_subjects_data = $this->student->get_backlog_subjects();
                                            $this->check_backlog_subject_checkboxs($this->backlog_subjects_data);
                                            $this->exam_fee_courses = get_exam_form_fees($this->patternclass_id,$this->regular_subjects_data, $this->backlog_subjects_data);
                                        }
                                    } else 
                                    {   
                                        // If FY Result Not Exists
                                        // Fetch Fail Student
                                        $this->regular_subjects_data = [];
                                        $this->backlog_subjects_data = [];
                                        $this->backlog_subjects_data = $this->student->get_backlog_subjects();
                                        $this->check_backlog_subject_checkboxs($this->backlog_subjects_data);
                                        $this->exam_fee_courses = get_exam_form_fees($this->patternclass_id,$this->regular_subjects_data, $this->backlog_subjects_data);
                                    }
                                }
                            } 
                            else
                            {   
                                // Old Academic Year
                                // Fetch Fail Students
                                $this->regular_subjects_data = [];
                                $this->backlog_subjects_data = [];
                                $this->backlog_subjects_data = $this->student->get_backlog_subjects();
                                $this->check_backlog_subject_checkboxs($this->backlog_subjects_data);
                                $this->exam_fee_courses = get_exam_form_fees($this->patternclass_id,$this->regular_subjects_data, $this->backlog_subjects_data);
                            }
                        } 
                        else 
                        {
                            $admission_data = Admissiondata::where('memid', $this->member_id)->where('patternclass_id', $current_class_student_last_entry->patternclass_id)->get();
                            if (!$admission_data->isEmpty()) 
                            {
                                $this->regular_subjects_data = [];
                                $this->backlog_subjects_data = [];
                            } 
                            else 
                            {
                                $this->dispatch('alert', type: 'info', message: 'Invalid Member ID Please Update Your Profile !!');
                            }
                        }
                        break;
                    case 5:
                        // TY SEM-VI
                        $this->patternclass_id = $current_class_student_last_entry->patternclass_id;
                        $admission_data = Admissiondata::where('memid', $this->member_id)->where('patternclass_id',$this->patternclass_id)->get();
                        if (!$admission_data->isEmpty()) 
                        {
                            if ($admission_data->last()->academicyear_id == $current_active_exams->first()->academicyear_id) 
                            {
                                // Fetch SEM-VI
                                $this->regular_subjects_data = [];
                                $this->regular_subjects_data = Subject::whereIn('id', $admission_data->pluck('subject_id'))->where('subject_sem', $current_class_student_last_entry->sem + 1)->get();
                                $this->check_subject_checkboxs($this->regular_subjects_data);
                                $this->backlog_subjects_data = [];
                                $this->backlog_subjects_data = $this->student->get_backlog_subjects();
                                $this->check_backlog_subject_checkboxs($this->backlog_subjects_data);
                                $this->exam_fee_courses = get_exam_form_fees($this->patternclass_id,$this->regular_subjects_data, $this->backlog_subjects_data);
                            } 
                            else 
                            {   
                                // Fetch SEM-VI Fail Student
                                $this->regular_subjects_data = [];
                                $this->regular_subjects_data = Subject::whereIn('id', $admission_data->pluck('subject_id'))->where('subject_sem', $current_class_student_last_entry->sem + 1)->get();
                                $this->check_subject_checkboxs($this->regular_subjects_data);
                                $this->backlog_subjects_data = [];
                                $this->backlog_subjects_data = $this->student->get_backlog_subjects();
                                $this->check_backlog_subject_checkboxs($this->backlog_subjects_data);
                                $this->exam_fee_courses = get_exam_form_fees($this->patternclass_id,$this->regular_subjects_data, $this->backlog_subjects_data);
                            }
                        } else 
                        {
                            $this->dispatch('alert', type: 'info', message: 'Invalid Member ID Please Update Your Profile !!');
                        }

                        break;
                    case 6:
                        $this->regular_subjects_data = [];
                        $this->backlog_subjects_data = [];
                        $this->backlog_subjects_data = $this->student->get_backlog_subjects();
                        $this->check_backlog_subject_checkboxs($this->backlog_subjects_data);
                        $this->exam_fee_courses = get_exam_form_fees($current_class_student_last_entry->patternclass_id,$this->regular_subjects_data, $this->backlog_subjects_data);
                        break;
                }
               
            }
        }

       
        return view('livewire.student.student-exam-form.fill-student-exam-form')->extends('layouts.student')->section('student');
    }

    protected function save_exam_form()
    {   
        if((!empty($this->regular_subjects_data) || !empty($this->backlog_subjects_data)) && isset($this->exam_fee_courses))
        {   
            $exam = Exam::where('status', 1)->first();
            if($exam)
            {
                // Getting Launched Exam Of Pattern Class
                $exampatternclass = Exampatternclass::where('exam_id', $exam->id)->where('launch_status', 1)->where('patternclass_id', $this->patternclass_id)->latest()->first();
                if ($exampatternclass) 
                {
                    // Checking Dublicate Entry
                    $existingRecord = Examformmaster::where([
                        'student_id' => $this->student->id,
                        'exam_id' => $exampatternclass->exam_id,
                        'patternclass_id' => $exampatternclass->patternclass_id,
                    ])->first();
        
        
                    // If Found Dublicte Entry Retun Error
                    if ($existingRecord) {
                        $this->dispatch('alert', type: 'error', message: 'You Are Trying To Submit The Exam Form Again.');
                        return false;
                    }
        
                    try 
                    {
                        // Init Transaction
                        DB::beginTransaction();
        
                        // Intit Total Fee
                        $total_fee = 0;
        
                        // Calculate Total Fee
                        foreach ($this->exam_fee_courses as $fee) 
                        {   
                            $total_fee += $fee['fee'];
                        }
                       
                        // Prepare Exam Form Matser Data
                        $exam_form_master = [
                            'medium_instruction' => $this->medium_instruction,
                            'totalfee' => $total_fee,
                            'student_id' => $this->student->id,
                            'college_id' => $this->student->college_id,
                            'exam_id' => $exampatternclass->exam_id,
                            'patternclass_id' => $exampatternclass->patternclass_id,
                        ];
        
                        // Save Exam Form Matser Data
                        $exam_form_master_data = Examformmaster::create($exam_form_master);
        
                        // Init Student Exam Form
                        $student_exam_forms = [];
                        
                        // Backlog Subject Ids
                        $backlog_ids=array_values(collect($this->backlog_subjects_data)->pluck('id')->toArray());

                        // Prepare Student Exam Form Data
                        $regular_and_backlog_subjects = collect($this->regular_subjects_data)->merge($this->backlog_subjects_data);
        
                        foreach ($regular_and_backlog_subjects as $subject) {
                            $student_exam_form = [
                                'exam_id' => $exam_form_master_data->exam_id,
                                'student_id' => $this->student->id,
                                'subject_id' => $subject->id,
                                'examformmaster_id' => $exam_form_master_data->id,
                                'college_id' => $this->student->college_id,
                                'is_backlog'=>in_array($subject->id, $backlog_ids)?1:0,
                                'int_status' => 0,
                                'ext_status' => 0,
                                'int_practical_status' => 0,
                                'grade_status' => 0,
                                'practical_status' => 0,
                                'project_status' => 0,
                                'oral_status' => 0,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];

        
                            // Set Checked Input
                            switch ($subject->subject_type) {
                                case 'I':
                                    $student_exam_form['int_status'] = 1;
                                    break;
                                case 'G':
                                    $student_exam_form['grade_status'] = 1;
                                    break;
                                case 'IG':
                                    $student_exam_form['int_status'] = 1;
                                    $student_exam_form['grade_status'] = 1;
                                    break;
                                case 'P':
                                case 'IP':
                                case 'IE':
                                    $student_exam_form['int_status'] = 1;
                                    $student_exam_form['ext_status'] = 1;
                                    break;
                                case 'IEG':
                                    $student_exam_form['int_status'] = 1;
                                    $student_exam_form['ext_status'] = 1;
                                    $student_exam_form['grade_status'] = 1;
                                    break;
                                case 'IEP':
                                    $student_exam_form['int_status'] = 1;
                                    $student_exam_form['ext_status'] = 1;
                                    $student_exam_form['int_practical_status'] = 1;
                                    break;
                            }
        
                            $student_exam_forms[] = $student_exam_form;
                        }

                        // Save Student Exam Form Data
                        $student_exam_form_data = Studentexamform::insert($student_exam_forms);
        
                        // Init Student Exam Form Fee
                        $student_exam_form_fees = [];
        
                        // Prepare Student Exam Form Fee
                        foreach ($this->exam_fee_courses as $fee) {
                            $student_exam_form_fees[] = [
                                'examformmaster_id' => $exam_form_master_data->id,
                                'examfees_id' => $fee['examfees_id'],
                                'fee_amount' => $fee['fee'],
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
        
                        // Save Student Exam Form Fee
                        $student_exam_form_fee_data = Studentexamformfee::insert($student_exam_form_fees);
        
                        // If All Above Work Done Then Commint It into DB
                        DB::commit();
        
                        // Notifing Success 
                        $this->dispatch('alert', type: 'success', message: 'Exam Form Saved Successfully !!');
        
                        // Redirecting To Student Dashboard
                        $this->redirect('/student/dashboard', navigate: true);
                    } catch (\Exception $e) {
                        dd($e);
                        \Log::debug($e);
                        // If Above Work Not Done Then Revert Changes
                        DB::rollback();
        
                        // Notify  Failure
                        $this->dispatch('alert', type: 'info', message: 'An Error Occurred. Transaction Rolled Back.');
                    }
                } 
                else 
                {
                    // Notify  Failure
                    $this->dispatch('alert', type: 'info', message: 'Exam Not Launched For This Pattern Class !!');
                }
    
            }
            else
            {   
                $this->dispatch('alert', type: 'error', message: 'Active Exam Not Found.');
                return false;
            }
        }
        else
        {   
            $this->dispatch('alert', type: 'error', message: 'Either Subjects Or Fees Not Found.');
            return false;
        }
    }

    // Exam Form Save
    public function student_exam_form_save()
    {   
        try 
        {
            $this->validate();
        } 
        catch (\Illuminate\Validation\ValidationException $e) 
        {
            $message = ($this->abcid_option['required_abcid']==true ? 'Please Enter Medium Of Instruction And ABC ID.' : 'Please Enter Medium Of Instruction.');
            $this->dispatch('alert', type: 'info', message: $message );
            $this->page = 1;
            return false;
        }

        if ($this->abcid_option['show_abcid']) 
        {
            Auth::guard('student')->user()->update(['abcid' => $this->abcid]);
        }

        $this->student = Auth::guard('student')->user();
        $current_class_student_last_entry = $this->student->currentclassstudents->last();
        
        if (!isset($current_class_student_last_entry)) 
        {
            $this->patternclass_id = $this->student->patternclass_id;
            $this->save_exam_form();
        } 
        else 
        {   
            function get_patternclass_id($patternclass_id)
            {   
                $patternclass = Patternclass::find($patternclass_id);
                return $patternclass->id;
            }

            switch ($current_class_student_last_entry->sem) 
            {   
                case 1:
                    $this->patternclass_id = get_patternclass_id($current_class_student_last_entry->patternclass_id);
                    $this->save_exam_form();
                break;
                case 2:
                    $this->patternclass_id = get_patternclass_id($current_class_student_last_entry->patternclass_id+1);
                    $this->save_exam_form();
                break;
                case 3:
                    $this->patternclass_id = get_patternclass_id($current_class_student_last_entry->patternclass_id);
                    $this->save_exam_form();
                break;
                case 4:
                    $this->patternclass_id = get_patternclass_id($current_class_student_last_entry->patternclass_id+1);
                    $this->save_exam_form();
                break;
                case 5:
                    $this->patternclass_id = get_patternclass_id($current_class_student_last_entry->patternclass_id);
                    $this->save_exam_form();
                break;
            }
        }
    }

    public function cancel()
    {
        $this->redirect('/student/dashboard', navigate: true);
    }

    public function next_back()
    {
        if ($this->page == 1) 
        {   
            try 
            {
                $this->validate();
                $this->page = 2;
            } 
            catch (\Illuminate\Validation\ValidationException $e) 
            {   
                $message = ($this->abcid_option['required_abcid']==true ? 'Please Enter Medium Of Instruction And ABC ID.' : 'Please Enter Medium Of Instruction.');
                $this->dispatch('alert', type: 'info', message: $message );
                $this->page = 1;
            }

        } else 
        {
            $this->page = 1;
        }
    }

    public function rules()
    {
        return [
            'abcid' => [($this->abcid_option['required_abcid']==true ? 'required' : 'nullable'), 'numeric', 'digits:12', 'unique:students,abcid,' . Auth::guard('student')->user()->id],
            'medium_instruction' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'medium_instruction.required' => 'Medium Of Instruction is required.',
            'medium_instruction.string' => 'Medium Of Instruction must be a String.',
            'abcid.unique' => 'The ABC ID has been taken.',
            'abcid.required' => 'ABC ID is required.',
            'abcid.numeric' => 'The ABC ID must be a number.',
            'abcid.digits' => 'The ABC ID must be 12 digits.',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    //Check Subject checkboxes 
    protected function check_subject_checkboxs($subjects)
    {
        foreach ($subjects as $subjects) {
            if ($subjects->subject_type == "IE") {
                $this->internals[$subjects->id] = true;
                $this->externals[$subjects->id] = true;
            }

            if ($subjects->subject_type == "IEP") {
                $this->internals[$subjects->id] = true;
                $this->externals[$subjects->id] = true;
                $this->practicals[$subjects->id] = true;
            }

            if ($subjects->subject_type == "IEG") {
                $this->internals[$subjects->id] = true;
                $this->externals[$subjects->id] = true;
                $this->grades[$subjects->id] = true;
            }

            if ($subjects->subject_type == "IEP") {
                $this->internals[$subjects->id] = true;
                $this->externals[$subjects->id] = true;
                $this->practicals[$subjects->id] = true;
            }

            if ($subjects->subject_type == "IP") {
                $this->internals[$subjects->id] = true;
                $this->practicals[$subjects->id] = true;
            }

            if ($subjects->subject_type == "IG") {
                $this->internals[$subjects->id] = true;
                $this->grades[$subjects->id] = true;
            }

            if ($subjects->subject_type == "I") {
                $this->internals[$subjects->id] = true;
            }

            if ($subjects->subject_type == "P") {
                $this->projects[$subjects->id] = true;
            }

            if ($subjects->subject_type == "G") {
                $this->grades[$subjects->id] = true;
            }
        }
    }

    //Check Backlog Subject checkboxes 
    protected function check_backlog_subject_checkboxs($subjects)
    {
        foreach ($subjects as $subject) {

            if ($subject->subject_type == "I" || $subject->subject_type == "IP" || $subject->subject_type == "IE" || $subject->subject_type == "IG" || $subject->subject_type == "IEP" || $subject->subject_type == "IEG") {
                $this->internals[$subject->id] = true;
            }

            if ($subject->subject_type == "E" || $subject->subject_type == "IE" || $subject->subject_type == "IEP" || $subject->subject_type == "EP" || $subject->subject_type == "EP" || $subject->subject_type == "EINTP" || $subject->subject_type == "IEG" || $subject->subject_type == "EG") {
                $this->externals[$subject->id] = true;
            }

            if (($subject->subjectcategory->subjectcategory != 'Project') && ($subject->subject_type == "P" || $subject->subject_type == "INTP" || $subject->subject_type == "IP" || $subject->subject_type == "IEP" || $subject->subject_type == "EP" || $subject->subject_type == "EINTP")) {
                $this->practicals[$subject->id] = true;
            }

            if ($subject->subject_type == "G" || $subject->subject_type == "IG") {
                $this->grades[$subject->id] = true;
            }

            if ($subject->subject_type == "P" || $subject->subject_type == "IP" || $subject->subject_type == "INTP") {
                $this->projects[$subject->id] = true;
            }
        }
    }
}
