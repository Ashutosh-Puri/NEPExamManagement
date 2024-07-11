<?php

namespace App\Livewire\User\ExamForm;

use App\Models\Exam;
use App\Models\Subject;
use Livewire\Component;
use App\Models\Examfeeview;
use App\Models\Admissiondata;
use App\Models\Examformmaster;
use App\Models\Studentexamform;
use Illuminate\Validation\Rule;
use App\Models\Studentexamformfee;
use Illuminate\Support\Facades\DB;
use App\Models\Currentclassstudent;
use Illuminate\Support\Facades\Auth;

class ModifyExamForm extends Component
{   
    public $page=1;

    public $student_name;
    public $mother_name;
    public $course_name;
    public $memid;
    public $application_id;
    public $modify_id;
    #[Locked]
    public $student_exam_form_subjects=[];
    #[Locked]
    public $student_exam_form_fees=[];
    #[Locked]
    public $student_exam_form_refrance_fees=[];
    #[Locked]
    public $student_exam_form_extrcredit_subjects=[];
    #[Locked]
    public $subjects_not_selected=[];
    #[Locked]
    public $remove_subjects=[];
    #[Locked]
    public $add_subjects=[];
    #[Locked]
    public $newfees=[];
    public $newtotal=0;

    protected $student;

    
    protected function rules()
    {   
        return  [
            'application_id' => ['required', 'integer',Rule::exists('examformmasters', 'id')],
        ];
    }

    public function messages()
    {   
        $messages = [
            'application_id.required' => 'The Application ID is required.',
            'application_id.integer' => 'The Application ID must be an integer.',
            'application_id.exists' => 'The Application ID does not exist.',
        ];
        
        return $messages;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function fetch_modify()
    {   

        $this->validate();
        $exam_form_master=Examformmaster::find($this->application_id);
        $this->student=$exam_form_master->student;
        $this->modify_id=$exam_form_master->id;
        $this->mother_name= $exam_form_master->student->mother_name;
        $this->course_name=get_pattern_class_name($exam_form_master->patternclass_id);
        $this->memid=$exam_form_master->student->memid;
        $this->student_name=$exam_form_master->student->student_name;
        $this->student_exam_form_subjects= $exam_form_master->studentexamforms()->get();
        $this->student_exam_form_fees= $exam_form_master->studentexamformfees()->get();
        $this->student_exam_form_refrance_fees=  $student_course_fees = Examfeeview::where('patternclass_id', $exam_form_master->patternclass_id)->where('active_status', 1)->distinct('examfees_id')->orderBy('examfees_id','ASC')->latest()->get();
        $this->student_exam_form_extrcredit_subjects= $exam_form_master->studentextracreditexamforms()->get();
        $this->page=2;

        foreach ($this->student_exam_form_fees as $examformfee)
        {
            $this->newfees[$examformfee->examfees_id]=$examformfee->fee_amount; 
            $this->newtotal =$this->newtotal +$examformfee->fee_amount;
        }


       $current_active_exam_session=null;

       $current_active_exam = Exam::Where('status',1)->get();

       if(isset($current_active_exam->first()->exam_sessions))
       {   
           $current_active_exam_session=$current_active_exam->first()->exam_sessions;
       }

       $current_class_student_entry = $this->student->currentclassstudents->last();

    //    $not_result_status=$this->student->currentclassstudents->where('pfstatus','!=','-1')->where('markssheetprint_status','!=','-1');
       
        if(!isset($current_class_student_entry))
        {   
            if($current_active_exam_session==1)
            {   
                // Subjects Of SEM-1 
                $this->subjects_not_selected=Subject::where('patternclass_id',$exam_form_master->patternclass_id)->where('subject_sem',1)->whereNotIn('id',$exam_form_master->studentexamforms()->pluck('subject_id'))->get();        
            }
            else
            {   
                // Subjects Of SEM-2 
                $this->subjects_not_selected=Subject::where('patternclass_id',$exam_form_master->patternclass_id)->whereIn('subject_sem',[1,2])->whereNotIn('id',$exam_form_master->studentexamforms()->pluck('subject_id'))->get();

            }
        }
        else
        {
            $this->subjects_not_selected=Subject::where('patternclass_id',$exam_form_master->patternclass_id)->whereNotIn('id',$exam_form_master->studentexamforms()->pluck('subject_id'))->get();
        }

       
    }

    public function modify_form_fee(Examformmaster $exmformmaster)
    {   
        DB::beginTransaction();
        try 
        {   
            foreach ($this->newfees as $feeid => $newfee) {
                if($newfee=='')
                {
                    $newfee=0;
                }

                $existingFee = $exmformmaster->studentexamformfees()->where('examfees_id', $feeid)->first();

                if ($existingFee && $newfee != $existingFee->fee_amount) {
                    $existingFee->update(['fee_amount' => $newfee]);
                }
            }

            $total=0;
            foreach ($exmformmaster->studentexamformfees()->get() as $fee)
            {
                $total+=$fee->fee_amount; 
            }

            $exmformmaster->update(['totalfee'=>$total]);

            DB::commit();
            $this->fetch_modify();
            $this->dispatch('alert',type:'success',message:'Exam Form Fees Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollback();

            $this->dispatch('alert',type:'error',message:'Failed To Update Exam Form Fees !!');
        }
        
    }
    
    public function modify_form_subject(Examformmaster $exmformmaster)
    {   
        DB::beginTransaction();
        try 
        { 
            $subjects =Subject::whereIn('id',array_keys(array_filter($this->add_subjects)))->get();

            $student_exam_forms = [];
            $student_admission_datas = [];
            foreach ($subjects as $subject) 
            {   
                $existingRecord = Admissiondata::where('memid', $exmformmaster->student->memid)
                ->where('patternclass_id', $exmformmaster->patternclass_id)
                ->where('subject_id', $subject->id)
                ->where('academicyear_id', $exmformmaster->exam->academicyear_id)
                ->where('college_id', $exmformmaster->college_id)->first();
                $student_admission_data = [];
                if (!$existingRecord) 
                {
                    $student_admission_data = [
                        'memid' => $exmformmaster->student->memid,
                        'stud_name' => strtoupper($exmformmaster->student->student_name),
                        'user_id' => Auth::guard('user')->user()->id,
                        'patternclass_id' =>$exmformmaster->patternclass_id,
                        'subject_id' => $subject->id,
                        'academicyear_id' =>$exmformmaster->exam->academicyear_id,
                        'college_id' => $exmformmaster->college_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                $student_exam_form = [
                    'exam_id' => $exmformmaster->exam_id,
                    'student_id' => $exmformmaster->student_id,
                    'subject_id' => $subject->id,
                    'examformmaster_id' => $exmformmaster->id,
                    'college_id' => $exmformmaster->college_id,
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
                
                switch ($subject->subject_type) 
                {
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
                    
                if (!empty($student_admission_data)) {
                    $student_admission_datas[] =$student_admission_data;
                }

                if (!empty($student_exam_form)) {
                    $student_exam_forms[] = $student_exam_form;
                }
               
            }
           
            $student_exam_form_data = Studentexamform::insert($student_exam_forms);
            $student_admisstiondata_data = Admissiondata::insert($student_admission_datas);

            $this->add_subjects=[];

            $deletestudentexamformsubject = Studentexamform::where('examformmaster_id',$exmformmaster->id)->whereIn('subject_id', array_keys(array_filter($this->remove_subjects)))->delete();
            $deleteadmissiondatasubjects=Admissiondata::withTrashed()->where('academicyear_id', $exmformmaster->exam->academicyear_id)->where('memid', $exmformmaster->student->memid)->where('patternclass_id',$exmformmaster->patternclass_id)->whereIn('subject_id', array_keys(array_filter($this->remove_subjects)))->forceDelete();

            $this->remove_subjects=[];
            DB::commit();
            $this->fetch_modify();
            $this->dispatch('alert',type:'success',message:'Exam Form Subjects Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollback();
            dd($e);
            $this->dispatch('alert',type:'error',message:'Failed To Update Exam Form Subjects !!');
        }
    }

    public function deleteexamform(Examformmaster $exmformmaster)
    {   
        DB::beginTransaction();

        try 
        {
            $exmformmaster->update(['printstatus'=>0]);

            DB::commit();

           $this->dispatch('alert',type:'success',message:'Exam Form Deleted Successfully !!');

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Exam Form !!');
        }
    }

    public function render()
    {   
        $this->newtotal=0;
        foreach( $this->newfees as $nfee)
        {
            $this->newtotal =$this->newtotal + (int)$nfee;
        }

        return view('livewire.user.exam-form.modify-exam-form')->extends('layouts.user')->section('user');
    }
}
