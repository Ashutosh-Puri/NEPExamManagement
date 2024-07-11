<?php

use App\Models\Exam;
use App\Models\Course;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Classview;
use App\Models\Courseclass;
use App\Models\Examfeeview;
use App\Models\Patternclass;
use App\Models\Examfeecourse;
use App\Models\Departmentprefix;
use App\Models\Exampatternclass;
use Illuminate\Support\Collection;
use App\Models\Currentclassstudent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

// Getting Pattern Class Name
if (!function_exists('get_pattern_class_name')) {
    function get_pattern_class_name($pattern_class_id)
    {
        $pattern_class = Classview::find($pattern_class_id);

        if ($pattern_class) {
            return  (isset($pattern_class->classyear_name) ? $pattern_class->classyear_name : '-') . " " .
                    (isset($pattern_class->course_name) ? $pattern_class->course_name : '-') . " " .
                    (isset($pattern_class->pattern_name) ? $pattern_class->pattern_name : '-');
        }
        return null;
    }
}

// Getting Subject Name With Code
if (!function_exists('get_subject_name')) {
    function get_subject_name($subject_id)
    {
        $subject = Subject::find($subject_id);
        if ($subject) {
            return $subject->subject_code ."-".$subject->subject_name;
        }
        return null;
    }
}

// Generate Subject Code
if (!function_exists('generate_subject_code')) {
    function generate_subject_code($patternClassId, $subjectVerticalId, $subjectSem, $classYear)
    {
        // dd($patternClassId, $subjectVerticalId, $subjectSem, $classYear);
        $authFaculty = Auth::guard('faculty')->user()->department_id;

        $departmentPrefix = Departmentprefix::where('dept_id', $authFaculty)->first();
        $courseClass = Courseclass::select('id', 'classyear_id')->find($patternClassId);
        $courseType = Course::where('id', optional($courseClass)->id)->value('course_type');
        $courseTypeFirstChar = strtoupper(substr($courseType, 0, 1));
        $subject_count = Subject::where('patternclass_id', $patternClassId)
                        ->where('subjectvertical_id', $subjectVerticalId)
                        ->where('department_id', $authFaculty)
                        ->where('subject_sem', $subjectSem)
                        ->where('classyear_id', $classYear)
                        ->count();
        $subjectCode = '';
        if ($departmentPrefix) {
            $subjectCode .= $departmentPrefix->prefix;
        }
        $subjectCode .= $courseTypeFirstChar;
        if ($courseClass) {
            $subjectCode .= $courseClass->classyear_id;
        }
        $subjectCode .= $subjectSem;

        $subjectCode .= $subject_count;
        if ($departmentPrefix) {
            $subjectCode .= $departmentPrefix->postfix;
        }
        return $subjectCode;
    }
}



// Getting Current Sem of Student
if (!function_exists('get_student_current_sem'))
{
    function get_student_current_sem($student_id)
    {
        $student=Student::find($student_id);
        if($student)
        {
            $sem=null;
            $current_active_exam_session=null;
            $current_active_exam = Exam::Where('status',1)->get();
            if(isset($current_active_exam->first()->exam_sessions))
            {
                $current_active_exam_session=$current_active_exam->first()->exam_sessions;
            }
            $current_class_student_entry = $student->currentclassstudents->last();
            if(!isset($current_class_student_entry))
            {
                if($current_active_exam_session==1)
                {
                    $sem=1;
                }elseif($current_active_exam_session==2)
                {
                    $sem='not_regular';
                }
            }
            else
            {
                $sem=$current_class_student_entry->sem;
            }
        }
      return $sem;
    }
}


// Checking Is Next Class Or Not
if (!function_exists('get_is_next_class'))
{
    function get_is_next_class($pattern_class_id)
    {
        $is_next_class=false;
        $pattern_class = Patternclass::with(['courseclass:nextyearclass_id,id'])->find($pattern_class_id);
        if($pattern_class)
        {
            if($pattern_class->courseclass->nextyearclass_id!=null)
            {
                $is_next_class = true;
            }
        }

        return  $is_next_class;
    }
}

// Getting Indian Rupees Currency
if (!function_exists('INR'))
{
    function INR($amount)
    {
        $formatter = new \NumberFormatter('en_IN', \NumberFormatter::CURRENCY);
        $formatter->setTextAttribute(\NumberFormatter::PATTERN_RULEBASED, "#,##,##0.00");
        return $formatter->formatCurrency($amount, 'INR');
    }
}

// Getting Course Type , UG , PG ,PHD
if (!function_exists('get_course_type'))
{
    function get_course_type($pattern_class_id)
    {
        $pattern_class = Patternclass::with(['courseclass.course:course_type,id'])->find($pattern_class_id);

        return  (isset($pattern_class->courseclass->course->course_type) ? $pattern_class->courseclass->course->course_type : '-');
    }
}


// Getting Backlog Fee
if (!function_exists('get_backlog_fee')) 
{
    function get_backlog_fee($sem,$pattern_class_id)
    {   
        $backlog_fee=0;
        $fee_course=Examfeeview::where('form_name','Backlog Form')->where('patternclass_id',$pattern_class_id)->where('sem',$sem)->where('active_status',1)->first();

        if($fee_course)
        {
            $backlog_fee=$fee_course->fee;
        }

        return $backlog_fee;
    }
}


if (!function_exists('get_exam_form_fees')) 
{
    function get_exam_form_fees($patternclass_id,$regular_subject_data, $backlog_subject_data,$is_fail=false)
    {     
        $backlog_subject_data= collect($backlog_subject_data);
        $total_backlog_fee=0;
        $internal_count = 0;
        $external_count = 0;
        $project_count = 0;
        $evs_count = 0;
        $sem_count = 0;
        $sems = [];
        $setting = Setting::first();
        $student=Auth::guard('student')->user();
        $statement_of_marks_is_year_wise = isset($setting->statement_of_marks_is_year_wise) ? $setting->statement_of_marks_is_year_wise : 0;
        $pattern_class_count = Currentclassstudent::whereIn('pfstatus',[0,2])->where('student_id',$student->id)->distinct('patternclass_id')->pluck('patternclass_id')->count();


        $sem_wise_backlog_fee_total=[];
        foreach( $backlog_subject_data->groupBy(['subject_sem','patternclass_id']) as $semester => $backlog_subject)
        {   
            foreach($backlog_subject as $subject_patternclass_id => $subject)
            { 
                $sem_wise_backlog_fee_total[$semester]=(get_backlog_fee($semester,$subject_patternclass_id) * count($subject));
            }
        }

        $total_backlog_fee = array_sum($sem_wise_backlog_fee_total);

        $regular_and_backlog_subjects = collect($regular_subject_data)->merge($backlog_subject_data);

        foreach ($regular_and_backlog_subjects as $subject) {

            if ($subject->subjectcategory->subjectcategory == "Project") {
                $project_count++;
            }

            if ($subject->subjectcategory->subjectcategory == "EVS") {
                $evs_count++;
            }

            if ($subject->subject_sem) {
                $temp[] = $subject->subject_sem;
                $sems = array_values(array_unique($temp));
            }

            if ($subject->subject_type == "I" || $subject->subject_type == "IP" || $subject->subject_type == "IE" || $subject->subject_type == "IG" || $subject->subject_type == "IEP" || $subject->subject_type == "IEG") {
                $internal_count++;
            }

            if ($subject->subject_type == "E" || $subject->subject_type == "IE" || $subject->subject_type == "IEP" || $subject->subject_type == "EP"  || $subject->subject_type == "EINTP" ) {
                $external_count++;
            }

        }
        $sem_count = count($sems);

        $exam = Exam::where('status', 1)->first();
        if ($exam) {

            $exam_pattern_class = Exampatternclass::where('patternclass_id', $patternclass_id)->where('exam_id', $exam->id)->first();

            $student_course_fees = Examfeeview::where('patternclass_id', $patternclass_id)
            ->where('active_status', 1)
            ->distinct('examfees_id')
            // ->groupBy('examfees_id')
            ->orderBy('examfees_id','ASC')
            ->latest()->get();
            if ($student_course_fees) {
                $fees_array = [];
                foreach ($student_course_fees as $course_fee) {
                    switch ($course_fee->fee_name) {
                        case "Form Fee":
                            // Calculate Form Fee
                            $fees_array[] = [
                                "form_name" => $course_fee->form_name,
                                "fee_name" => $course_fee->fee_name,
                                "id" => $course_fee->id,
                                "fee" =>$course_fee->fee,
                                "sem" => $course_fee->sem,
                                "patternclass_id" => $course_fee->patternclass_id,
                                "examfees_id" => $course_fee->examfees_id,
                                "active_status" =>$course_fee->active_status,
                                "approve_status" =>$course_fee->approve_status,
                                "remark" =>$course_fee->remark,
                            ];
                        break;
                        case "Exam Fee":

                            if($is_fail)
                            {
                                // Calculate Exam Fee
                                $fees_array[] = [
                                    "form_name" => $course_fee->form_name,
                                    "fee_name" => $course_fee->fee_name,
                                    "id" => $course_fee->id,
                                    "fee" =>$total_backlog_fee,
                                    "sem" => $course_fee->sem,
                                    "patternclass_id" => $course_fee->patternclass_id,
                                    "examfees_id" => $course_fee->examfees_id,
                                    "active_status" =>$course_fee->active_status,
                                    "approve_status" =>$course_fee->approve_status,
                                    "remark" =>$course_fee->remark,
                                ];
                            }else
                            {
                                 // Calculate Exam Fee
                                 $fees_array[] = [
                                    "form_name" => $course_fee->form_name,
                                    "fee_name" => $course_fee->fee_name,
                                    "id" => $course_fee->id,
                                    "fee" =>($course_fee->fee +$total_backlog_fee),
                                    "sem" => $course_fee->sem,
                                    "patternclass_id" => $course_fee->patternclass_id,
                                    "examfees_id" => $course_fee->examfees_id,
                                    "active_status" =>$course_fee->active_status,
                                    "approve_status" =>$course_fee->approve_status,
                                    "remark" =>$course_fee->remark,
                                ];
                            }
                        break;
                        case "CAP Fee":
                            // Apply CAP Fee SEM Wise
                            if ($external_count) {
                                if ($sem_count) {
                                    // SEM Count * Fee
                                    $fees_array[] = [
                                        "form_name" => $course_fee->form_name,
                                        "fee_name" => $course_fee->fee_name,
                                        "id" => $course_fee->id,
                                        "fee" => ($course_fee->fee * $sem_count),
                                        "sem" => $course_fee->sem,
                                        "patternclass_id" => $course_fee->patternclass_id,
                                        "examfees_id" => $course_fee->examfees_id,
                                        "active_status" =>$course_fee->active_status,
                                        "approve_status" =>$course_fee->approve_status,
                                        "remark" =>$course_fee->remark,
                                    ];
                                }
                            }else
                            {
                                // Zero Fee
                                $fees_array[] = [
                                    "form_name" => $course_fee->form_name,
                                    "fee_name" => $course_fee->fee_name,
                                    "id" => $course_fee->id,
                                    "fee" => 0,
                                    "sem" => $course_fee->sem,
                                    "patternclass_id" => $course_fee->patternclass_id,
                                    "examfees_id" => $course_fee->examfees_id,
                                    "active_status" =>$course_fee->active_status,
                                    "approve_status" =>$course_fee->approve_status,
                                    "remark" =>$course_fee->remark,
                                ];
                            }
                        break;
                        case "Statement of Marks Fee":
                            // Calculate Statement of Marks Fee
                            if ($statement_of_marks_is_year_wise) {
                                    // Apply Statement of Marks Fee Patternclass Wise Or Year Wise
                                    if ($pattern_class_count) {
                                        // Patternclass Count In Current Class
                                        $fees_array[] = [
                                            "form_name" => $course_fee->form_name,
                                            "fee_name" => $course_fee->fee_name,
                                            "id" => $course_fee->id,
                                            "fee" => ($course_fee->fee * $pattern_class_count),
                                            "sem" => $course_fee->sem,
                                            "patternclass_id" => $course_fee->patternclass_id,
                                            "examfees_id" => $course_fee->examfees_id,
                                            "active_status" =>$course_fee->active_status,
                                            "approve_status" =>$course_fee->approve_status,
                                            "remark" =>$course_fee->remark,
                                        ];
                                    }else{
                                        // FY SEM-I or/and Direct SEM-II
                                        $fees_array[] = [
                                            "form_name" => $course_fee->form_name,
                                            "fee_name" => $course_fee->fee_name,
                                            "id" => $course_fee->id,
                                            "fee" => $course_fee->fee,
                                            "sem" => $course_fee->sem,
                                            "patternclass_id" => $course_fee->patternclass_id,
                                            "examfees_id" => $course_fee->examfees_id,
                                            "active_status" =>$course_fee->active_status,
                                            "approve_status" =>$course_fee->approve_status,
                                            "remark" =>$course_fee->remark,
                                        ];
                                    }
                            } else {
                                // Apply Statement of Marks Fee SEM Wise
                                if ($sem_count) {
                                    // SEM Count * Fee
                                    $fees_array[] = [
                                        "form_name" => $course_fee->form_name,
                                        "fee_name" => $course_fee->fee_name,
                                        "id" => $course_fee->id,
                                        "fee" => ($course_fee->fee * $sem_count),
                                        "sem" => $course_fee->sem,
                                        "patternclass_id" => $course_fee->patternclass_id,
                                        "examfees_id" => $course_fee->examfees_id,
                                        "active_status" =>$course_fee->active_status,
                                        "approve_status" =>$course_fee->approve_status,
                                        "remark" =>$course_fee->remark,
                                    ];
                                }
                            }
                        break;
                        case "Passing Certificate Fee":
                            // Calculate Passing Certificate Fee
                            $type=get_course_type($patternclass_id);
                            if(($type=="PG" && in_array(4, $sems)) || ($type=="UG"  && in_array(6, $sems)) || !(get_is_next_class($patternclass_id)))
                            {
                                $fees_array[] = [
                                    "form_name" => $course_fee->form_name,
                                    "fee_name" => $course_fee->fee_name,
                                    "id" => $course_fee->id,
                                    "fee" => $course_fee->fee,
                                    "sem" => $course_fee->sem,
                                    "patternclass_id" => $course_fee->patternclass_id,
                                    "examfees_id" => $course_fee->examfees_id,
                                    "active_status" =>$course_fee->active_status,
                                    "approve_status" =>$course_fee->approve_status,
                                    "remark" =>$course_fee->remark,
                                ];
                            }else
                            {
                                // Zero Fee
                                $fees_array[] = [
                                    "form_name" => $course_fee->form_name,
                                    "fee_name" => $course_fee->fee_name,
                                    "id" => $course_fee->id,
                                    "fee" => 0,
                                    "sem" => $course_fee->sem,
                                    "patternclass_id" => $course_fee->patternclass_id,
                                    "examfees_id" => $course_fee->examfees_id,
                                    "active_status" =>$course_fee->active_status,
                                    "approve_status" =>$course_fee->approve_status,
                                    "remark" =>$course_fee->remark,
                                ];
                            }
                        break;
                        case "Project Fee/Dissertation":
                            // Calculate Project Fee / Dissertation Fee
                            if ($project_count) {
                                // Counting Subject has Subject Category is Project
                                $fees_array[] = [
                                    "form_name" => $course_fee->form_name,
                                    "fee_name" => $course_fee->fee_name,
                                    "id" => $course_fee->id,
                                    "fee" => ($course_fee->fee  * $project_count),
                                    "sem" => $course_fee->sem,
                                    "patternclass_id" => $course_fee->patternclass_id,
                                    "examfees_id" => $course_fee->examfees_id,
                                    "active_status" =>$course_fee->active_status,
                                    "approve_status" =>$course_fee->approve_status,
                                    "remark" =>$course_fee->remark,
                                ];
                            } else {
                                // Zero Fee
                                $fees_array[] = [
                                    "form_name" => $course_fee->form_name,
                                    "fee_name" => $course_fee->fee_name,
                                    "id" => $course_fee->id,
                                    "fee" => 0,
                                    "sem" => $course_fee->sem,
                                    "patternclass_id" => $course_fee->patternclass_id,
                                    "examfees_id" => $course_fee->examfees_id,
                                    "active_status" =>$course_fee->active_status,
                                    "approve_status" =>$course_fee->approve_status,
                                    "remark" =>$course_fee->remark,
                                ];
                            }
                        break;
                        case "EVS Fee":
                            // Calculate EVS Fee
                            if ($evs_count) {
                                // Counting Subject has Subject Category is EVS
                                $fees_array[] = [
                                    "form_name" => $course_fee->form_name,
                                    "fee_name" => $course_fee->fee_name,
                                    "id" => $course_fee->id,
                                    "fee" =>($course_fee->fee * $evs_count),
                                    "sem" => $course_fee->sem,
                                    "patternclass_id" => $course_fee->patternclass_id,
                                    "examfees_id" => $course_fee->examfees_id,
                                    "active_status" =>$course_fee->active_status,
                                    "approve_status" =>$course_fee->approve_status,
                                    "remark" =>$course_fee->remark,
                                ];
                            } else {
                                // Zero Fee
                                $fees_array[] = [
                                    "form_name" => $course_fee->form_name,
                                    "fee_name" => $course_fee->fee_name,
                                    "id" => $course_fee->id,
                                    "fee" =>0,
                                    "sem" => $course_fee->sem,
                                    "patternclass_id" => $course_fee->patternclass_id,
                                    "examfees_id" => $course_fee->examfees_id,
                                    "active_status" =>$course_fee->active_status,
                                    "approve_status" =>$course_fee->approve_status,
                                    "remark" =>$course_fee->remark,
                                ];
                            }
                        break;
                        case "Internal Marks Fee":
                            // Calculate Internal Marks Fee
                            if ($internal_count) {
                                // Count Inernal Subject According Subject Type ,I,IE,IP .etc
                                $fees_array[] = [
                                    "form_name" => $course_fee->form_name,
                                    "fee_name" => $course_fee->fee_name,
                                    "id" => $course_fee->id,
                                    "fee" =>($course_fee->fee * $internal_count),
                                    "sem" => $course_fee->sem,
                                    "patternclass_id" => $course_fee->patternclass_id,
                                    "examfees_id" => $course_fee->examfees_id,
                                    "active_status" =>$course_fee->active_status,
                                    "approve_status" =>$course_fee->approve_status,
                                    "remark" =>$course_fee->remark,
                                ];
                            } else {
                                // Zero Fee
                                $fees_array[] = [
                                    "form_name" => $course_fee->form_name,
                                    "fee_name" => $course_fee->fee_name,
                                    "id" => $course_fee->id,
                                    "fee" =>0,
                                    "sem" => $course_fee->sem,
                                    "patternclass_id" => $course_fee->patternclass_id,
                                    "examfees_id" => $course_fee->examfees_id,
                                    "active_status" =>$course_fee->active_status,
                                    "approve_status" =>$course_fee->approve_status,
                                    "remark" =>$course_fee->remark,
                                ];
                            }
                        break;
                        case "Departmental Fee":
                            // Calculate Departmental Fee
                            $fees_array[] = [
                                "form_name" => $course_fee->form_name,
                                "fee_name" => $course_fee->fee_name,
                                "id" => $course_fee->id,
                                "fee" =>$course_fee->fee,
                                "sem" => $course_fee->sem,
                                "patternclass_id" => $course_fee->patternclass_id,
                                "examfees_id" => $course_fee->examfees_id,
                                "active_status" =>$course_fee->active_status,
                                "approve_status" =>$course_fee->approve_status,
                                "remark" =>$course_fee->remark,
                            ];
                        break;
                        case "Transcript Fee":
                            // Calculate Transcript Fee
                            $fees_array[] = [
                                "form_name" => $course_fee->form_name,
                                "fee_name" => $course_fee->fee_name,
                                "id" => $course_fee->id,
                                "fee" =>$course_fee->fee,
                                "sem" => $course_fee->sem,
                                "patternclass_id" => $course_fee->patternclass_id,
                                "examfees_id" => $course_fee->examfees_id,
                                "active_status" =>$course_fee->active_status,
                                "approve_status" =>$course_fee->approve_status,
                                "remark" =>$course_fee->remark,
                            ];
                        break;
                        case "Late Fee":
                            // Calculate Late Fee
                            if ($exam_pattern_class) {
                                // Late Fee Apply If In Exam Pattern Class Late Fee Date Is Exceeds Todays Date
                                if (isset($exam_pattern_class->latefee_date)) {
                                    $late_fee_date = date('Y-m-d', strtotime($exam_pattern_class->latefee_date));
                                    $today_date = date('Y-m-d');
                                    if ($today_date > $late_fee_date) {
                                        $fees_array[] = [
                                            "form_name" => $course_fee->form_name,
                                            "fee_name" => $course_fee->fee_name,
                                            "id" => $course_fee->id,
                                            "fee" =>$course_fee->fee,
                                            "sem" => $course_fee->sem,
                                            "patternclass_id" => $course_fee->patternclass_id,
                                            "examfees_id" => $course_fee->examfees_id,
                                            "active_status" =>$course_fee->active_status,
                                            "approve_status" =>$course_fee->approve_status,
                                            "remark" =>$course_fee->remark,
                                        ];
                                    } else {
                                        // Zero Fee
                                        $fees_array[] = [
                                            "form_name" => $course_fee->form_name,
                                            "fee_name" => $course_fee->fee_name,
                                            "id" => $course_fee->id,
                                            "fee" =>0,
                                            "sem" => $course_fee->sem,
                                            "patternclass_id" => $course_fee->patternclass_id,
                                            "examfees_id" => $course_fee->examfees_id,
                                            "active_status" =>$course_fee->active_status,
                                            "approve_status" =>$course_fee->approve_status,
                                            "remark" =>$course_fee->remark,
                                        ];
                                    }
                                }
                            }
                        break;
                        case "Fine Fee":
                            // Calculate Fine Fee
                            if ($exam_pattern_class) {
                                // Fine Fee Apply If In Exam Pattern Class Fine Fee Date Is Exceeds Todays Date
                                if (isset($exam_pattern_class->finefee_date)) {
                                    $fine_fee_date = date('Y-m-d', strtotime($exam_pattern_class->finefee_date));
                                    $today_date = date('Y-m-d');
                                    if ($today_date > $fine_fee_date) {
                                        $fees_array[] = [
                                            "form_name" => $course_fee->form_name,
                                            "fee_name" => $course_fee->fee_name,
                                            "id" => $course_fee->id,
                                            "fee" =>$course_fee->fee,
                                            "sem" => $course_fee->sem,
                                            "patternclass_id" => $course_fee->patternclass_id,
                                            "examfees_id" => $course_fee->examfees_id,
                                            "active_status" =>$course_fee->active_status,
                                            "approve_status" =>$course_fee->approve_status,
                                            "remark" =>$course_fee->remark,
                                        ];
                                    } else {
                                        $fees_array[] = [
                                            "form_name" => $course_fee->form_name,
                                            "fee_name" => $course_fee->fee_name,
                                            "id" => $course_fee->id,
                                            "fee" =>0,
                                            "sem" => $course_fee->sem,
                                            "patternclass_id" => $course_fee->patternclass_id,
                                            "examfees_id" => $course_fee->examfees_id,
                                            "active_status" =>$course_fee->active_status,
                                            "approve_status" =>$course_fee->approve_status,
                                            "remark" =>$course_fee->remark,
                                        ];
                                    }
                                }
                            }
                        break;
                        // case "Backlog Fee":
                        //     // Calculate Backlog Subject Exam Fee add in exam fee
                        //     if ($total_backlog_fee) {
                        //         // According Backlog Subject Count
                        //         $fees_array[] = [
                        //             "form_name" => $course_fee->form_name,
                        //             "fee_name" => $course_fee->fee_name,
                        //             "id" => $course_fee->id,
                        //             "fee" =>$total_backlog_fee,
                        //             "sem" => $course_fee->sem,
                        //             "patternclass_id" => $course_fee->patternclass_id,
                        //             "examfees_id" => $course_fee->examfees_id,
                        //             "active_status" =>$course_fee->active_status,
                        //             "approve_status" =>$course_fee->approve_status,
                        //             "remark" =>$course_fee->remark,
                        //         ];
                        //     }
                        // break;
                    }
                }
            }
        }
        return collect($fees_array);
    }
}


if (!function_exists('amount_to_word')) {
    function amount_to_word($amount)
    {
        $units = array("Zero", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen");
        $tens = array("", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety");

        try {
            $amount_int = (int)$amount;
            $amount_dec = (int)round(($amount - $amount_int) * 100);
            if ($amount_dec == 0) {
                return convert($amount_int) . " Rupees Only.";
            } else {
                return convert($amount_int) . " Rupees And " . convert($amount_dec) . " Paisa Only.";
            }
        } catch (Exception $e) {
            // Handle exception
            return "Error: " . $e->getMessage();
        }
    }
}

if (!function_exists('convert')) {
    function convert($i)
    {
        $units = array("Zero", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen");
        $tens = array("", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety");

        if ($i < 20) {
            return $units[$i];
        }
        if ($i < 100) {
            return $tens[(int)($i / 10)] . (($i % 10 > 0) ? " " . convert($i % 10) : "");
        }
        if ($i < 1000) {
            return $units[(int)($i / 100)] . " Hundred" . (($i % 100 > 0) ? " And " . convert($i % 100) : "");
        }
        if ($i < 100000) {
            return convert((int)($i / 1000)) . " Thousand " . (($i % 1000 > 0) ? " " . convert($i % 1000) : "");
        }
        if ($i < 10000000) {
            return convert((int)($i / 100000)) . " Lakh " . (($i % 100000 > 0) ? " " . convert($i % 100000) : "");
        }
        if ($i < 1000000000) {
            return convert((int)($i / 10000000)) . " Crore " . (($i % 10000000 > 0) ? " " . convert($i % 10000000) : "");
        }
        return convert((int)($i / 1000000000)) . " Arab " . (($i % 1000000000 > 0) ? " " . convert($i % 1000000000) : "");
    }
}


// generate_passord
if (!function_exists('generate_password'))
{
    function generate_password($digits=6)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $password = '';
    
        for ($i = 0; $i < $digits; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $password .= $characters[$index];
        }

        return $password;
    }
}

// calculate CGPA
if (!function_exists('calculate_CGPA'))
{
    function calculate_CGPA($sem_total_credit_points,$sem_total_credits,$is_oridnance_one_four=0)
    {
    
        $cgpa=  round($sem_total_credit_points/$sem_total_credits,2);
 
        $final_grade="";
        $difference;

        if($cgpa>=9.50)
        {
            $final_grade="O";
        }
        else  if($cgpa>=8.25 && $cgpa<9.50)
        {
            $short_fall=$sem_total_credits*9.50 ;
            
            $difference=$short_fall-$sem_total_credit_points;
                                                      
            if($difference<=10 && $is_oridnance_one_four==0)
            {
                $final_grade="O $ 2";  $cgpa=9.50;
            }
            else  
            {
                $final_grade="A+";
            }
        }
        else  if($cgpa>=6.75 && $cgpa<8.25)
        {
            $short_fall=$sem_total_credits*8.25 ;
            $difference=$short_fall-$sem_total_credit_points;
                    
            if($difference<=10 && $is_oridnance_one_four==0)
            {
                $final_grade="A+ $ 2";  $cgpa=8.25;
            }
            else 
            {
                $final_grade="A";
            } 
        }  
        else  if($cgpa>=5.75 && $cgpa<6.75)
        {
            $short_fall=$sem_total_credits*6.75 ;
            $difference=$short_fall-$sem_total_credit_points;

            if($difference<=10 && $is_oridnance_one_four==0)
            {
                $final_grade="A $ 2";  $cgpa=6.75;
            }
            else  
            {
                $final_grade="B+";
            }
        }  
        else  if($cgpa>=5.25 && $cgpa<5.75)
        {
            $short_fall=$sem_total_credits*5.75 ;
            $difference=$short_fall-$sem_total_credit_points;
            if($difference<=10 && $is_oridnance_one_four==0)
            {
                $final_grade="B+ $ 2";  $cgpa=5.75;
            }
            else 
            {
                $final_grade="B";
            } 
        }
        else  if($cgpa>=4.75 && $cgpa<5.25)
        {
            $short_fall=$sem_total_credits*5.25 ;
            $difference=$short_fall-$sem_total_credit_points;
            
            if($difference<=10 && $is_oridnance_one_four==0)
            {
                $final_grade="B $ 2";  $cgpa=5.25;
            }
            else  
            {
                $final_grade="C";
            }
        }
        else  if($cgpa>=4. && $cgpa<4.75)
        {           
            $final_grade="D";
        }
        
        return $final_grade;
    }
}


    // if (! function_exists('encode')) 
    // {
    //     function encode($data)
    //     {
    //         $encryptionKey = env('DATA_ENCRYPTION_KEY','abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+[]{}|;:,.<>?');
    //         $encryptionKey = hash('sha256', $encryptionKey, true);

    //         $iv = '1234567890123456';
    
    //         $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryptionKey, OPENSSL_RAW_DATA, $iv);
    
    //         if ($encrypted === false) {
    //             return false;
    //         }
    
    //         return base64_encode($encrypted);
    //     }
    // }

    // if (! function_exists('decode')) 
    // {
    //     function decode($encryptedData)
    //     {   
    //         if(!isset($encryptedData))
    //         {
    //             return '';
    //         }
            
    //         $encryptionKey =env('DATA_ENCRYPTION_KEY','abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+[]{}|;:,.<>?');

    //         $encryptionKey = hash('sha256', $encryptionKey, true);

    //         $iv = '1234567890123456';

    //         $decodedData = base64_decode($encryptedData);

    //         if ($decodedData === false) {
    //             return false;
    //         }

    //         $decrypted = openssl_decrypt($decodedData, 'aes-256-cbc', $encryptionKey, OPENSSL_RAW_DATA, $iv);

    //         if ($decrypted === false) {
    //             return '';
    //         }

    //         return $decrypted;
    //     }
    // }
