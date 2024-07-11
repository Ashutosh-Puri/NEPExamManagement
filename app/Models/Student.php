<?php

namespace App\Models;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\Studentmark;
use App\Models\Patternclass;
use App\Models\Studentresult;
use App\Models\Examformmaster;
use App\Models\Studentaddress;
use App\Models\Studentprofile;
use App\Models\Studentexamform;
use App\Models\Studenthelpline;
use App\Models\Studentadmission;
use App\Models\Examstudentseatno;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Studentordinace163;
use Illuminate\Support\Facades\DB;
use App\Models\Currentclassstudent;
use App\Models\Studentpreviousexam;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Notifications\StudentRegisteMailNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Jobs\Student\SendEmailVerificationNotificationJob;
use App\Notifications\Student\StudentRegisterMailNotification;
use App\Notifications\Student\StudentResetPasswordNotification;

class Student extends  Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable ,SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guard="student";
    protected $broker = 'students';
    protected $fillable = [
        'student_name',
        'email',
        'password',
        'last_login',
        'prn',
        'university_prn',
        'memid',
        'eligibilityno',
        'abcid',
        'aadhar_card_no',
        'mother_name',
        'mobile_no',
        'email_verified_at',
        'mobile_no_verified_at',
        'patternclass_id',
        'department_id',
        'total_steps',
        'current_step',
        'is_profile_complete',
        'college_id',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $encodedColumns = [
        'abcid',
        'aadhar_card_no',
        'mother_name',
        'mobile_no',
        'eligibilityno'
    ];

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encodedColumns)) {
            return $this->setEncodedAttribute($key, $value);
        } else {
            return parent::setAttribute($key, $value);
        }
    }

    protected function setEncodedAttribute($key, $value)
    {
        return parent::setAttribute($key, base64_encode($value));
    }

    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (in_array($key, $this->encodedColumns) && $value !== null) {
            return base64_decode($value);
        }

        return $value;
    }



    public function sendPasswordResetNotification($token)
    {
        $this->notify(new StudentResetPasswordNotification($token));
    }

    public function sendEmailVerificationNotification()
    {   
        SendEmailVerificationNotificationJob::dispatch($this);

    }

    public function studentprofile(): HasOne
    {
        return $this->hasOne(Studentprofile::class, 'student_id', 'id');
    }

    public function studentaddress(): HasMany
    {
        return $this->HasMany(Studentaddress::class, 'student_id', 'id');
    }

    public function educationalcourses(): HasMany
    {
        return $this->HasMany(Studentpreviousexam::class, 'student_id', 'id');
    }

    public function patternclass()
    {
        return $this->belongsTo(Patternclass::class,'patternclass_id','id')->withTrashed();
    }

    public function studenthelplines(): HasMany
    {
        return $this->HasMany(Studenthelpline::class, 'student_id', 'id')->withTrashed();
    }

    public function currentclassstudents()
    {
        return $this->hasMany(Currentclassstudent::class,'student_id','id')->withTrashed();
    }


    // public function intextracreditbatchseatnoallocations()
    // {
    //     return $this->hasMany(Intextracreditbatchseatnoallocation::class,'student_id','id');
    // }


    public function examformmasters()
    { 
        return $this->hasMany(Examformmaster::class,'student_id','id');
    }


    public function getpermanentaddress()
    {
        return $this->hasOne(Studentaddress::class)->whereHas('addresstype', function ($query) {
            $query->where('type', 'Permanent Address');
        })->first();
    }
    
    public function getcorrespondenceaddress()
    {
        return $this->hasOne(Studentaddress::class)->whereHas('addresstype', function ($query) {
            $query->where('type', 'Correspondence Address');
        })->first();
    }

    public function studentadmissions()
    {
        return $this->hasMany(Studentadmission::class,'student_id','id');
    } 


    public function studentmarks()
    {
        return $this->hasMany(Studentmark::class,'student_id','id');
    }

    public function studentresults()
    { 
        return $this->hasMany(Studentresult::class,'student_id','id');
    }


    public function get_year_result_exam_form($sem_1_data,$sem_2_data,$current_class_student_last_entry)
    { 
        $current_class_student_last_entry=$this->currentclassstudents->last();

        $pass_fail_absent_status=1; // Result : Pass

        $sem_1_2_credit_earned  =  $sem_1_data->semcreditearned  +   $sem_2_data->semcreditearned;
        $sem_1_2_total_credit   =  $sem_1_data->semtotalcredit   +   $sem_2_data->semtotalcredit;

        $credits                =  $current_class_student_last_entry->patternclass->credit;

        if ($sem_1_2_credit_earned >= $credits && $sem_1_2_credit_earned < $sem_1_2_total_credit) 
        {
            $pass_fail_absent_status = 2;  // Result : Fail A.T.K.T.
        }
        else if($sem_1_2_credit_earned < $credits && $sem_1_2_credit_earned >= 0)
        { 
            $pass_fail_absent_status = 0;  // Result : Fail
        }

        if($this->patternclass->courseclass->course->course_type=='PG' &&  $current_class_student_last_entry->sem==4 && $pass_fail_absent_status==2)
        {
            $pass_fail_absent_status = 0;   // Result : Fail
        }

        // if($this->patternclass->courseclass->course->course_type=='PG' && $current_class_student_last_entry->sem==4 && $pass_fail_absent_status==1)
        // {
        //     if((12 - $this->getNONCGPAtotal()) > 0)
        //     {
        //         $pass_fail_absent_status = 0; // Result : Fail
        //     }  
        // }

        return $pass_fail_absent_status;
    }
    

    public function getNONCGPAtotal()
    {
        // $intextdata=Studentmark::with(['subject'=>function($query){ $query->whereIn('subject_type',['G','IEG','IG']); }])->where('student_id',$this->id)->get() ;
        // $extdata= Intextracreditbatchseatnoallocation::with('internalmarksextracreditbatches.subjects')->whereNotIn('grade',['F','Ab','-1','NA'])->where('student_id',$this->id)->whereNotNull('grade')->get();
        // return ($extdata->sum('internalmarksextracreditbatches.subjects.subject_credit')+$intextdata->sum('subject.subject_credit'));


        return 0;
    }

    // Return Backlog Subject Collection
    public function get_backlog_subjects()
    {      
        $backlog_subjects=collect();
        $current_class_students = $this->currentclassstudents()->get();
        $current_active_exam = Exam::where('status', 1)->latest()->first();

        if(isset($current_class_students))
        {   
            foreach($current_class_students as $current_class_student_entry) 
            { 
                if ($current_class_student_entry->markssheetprint_status != -1)
                {   
                    $subjects = Subject::where('patternclass_id',$current_class_student_entry->patternclass_id)->where('subject_sem',$current_class_student_entry->sem)->orderBy('id')->get();     
                    
                    if(isset($subjects))
                    {
                        foreach ($subjects->sortBy('subject_sem') as $subject)
                        {   
                            $student_mark_last_entry=$this->studentmarks()->where('subject_id',$subject->id)->where('exam_id','<=', $current_active_exam->id )->get()->last();

                            if(isset($student_mark_last_entry) && $student_mark_last_entry->grade=="F")
                            {   
                                $subject_type="";
                                switch ($subject->subject_type) 
                                {
                                    case 'I':
                                        if($student_mark_last_entry->int_marks < $subject->subject_intpassing)
                                        {
                                            $subject_type="I";
                                        }  
                                    break;
                                    case 'IE':
                                    case 'IP':
                                        if($student_mark_last_entry->int_marks < $subject->subject_intpassing)
                                        {
                                            $subject_type="I";
                                        } 
                                        
                                        if($student_mark_last_entry->ext_marks < $subject->subject_extpassing)
                                        {
                                            $subject_type=$subject_type."E";
                                        }
                                    break;
                                    case 'IEP':
                                        if($student_mark_last_entry->int_marks < $subject->subject_intpassing)
                                        {
                                            $subject_type="I";
                                        } 

                                        if($student_mark_last_entry->ext_marks < $subject->subject_extpassing)
                                        {
                                            $subject_type=$subject_type."E";
                                        }
                                        
                                        if($student_mark_last_entry->int_practical_marks < $subject->subject_intpractpassing)
                                        {
                                            $subject_type=$subject_type."INTP";
                                        }    
                                    break;
                                    case 'E':
                                        if($student_mark_last_entry->ext_marks < $subject->subject_extpassing)
                                        {
                                            $subject_type="E";
                                        }
                                    break;
                                    case 'G':
                                            $subject_type= "G";
                                    break;
                                    case 'IG':
                                        if($student_mark_last_entry->int_marks < $subject->subject_intpassing)
                                        {
                                            $subject_type="I";
                                        } 
                                        $subject_type=$subject_type."G";
                                    break;
                                    case 'IEG':
                                        if($student_mark_last_entry->int_marks < $subject->subject_intpassing)
                                        {
                                            $subject_type="I";
                                        } 
                                        if($student_mark_last_entry->ext_marks < $subject->subject_extpassing)
                                        {
                                            $subject_type=$subject_type."EG";
                                        }
                                    break;
                                }
                                
                                if (!(($subject_type == 'I' or $subject_type == 'INTP' or $subject_type == 'IINTP') and $subject->subject_sem % 2 != $current_active_exam->exam_sessions % 2 and $subject->subject_type != 'IEG') or ($subject_type == 'IEG' or $subject_type == 'I' or $subject_type == 'G' or $subject_type == 'EG') and ($subject->subject_type == 'IEG' or $subject->subject_type == 'G' or $subject->subject_type == 'IG') or $subject->patternclass_id >= 1 )
                                {       
                                    $updated_subject = clone $subject;
                                    $updated_subject->subject_type = $subject_type;
                                    $backlog_subjects->push($updated_subject);
                                }
                            }
                        }
                    }
                }
            }
        }
        return  $backlog_subjects;
    }

    public function examstudentseatnos()
    {
        return $this->hasMany(Examstudentseatno::class,'student_id','id');
    }

    public function studentexamforms()
    {
        return $this->hasMany(Studentexamform::class,'student_id','id');
    } 

    public function checkstudexamform($student_id)
    {
        $sdata=Examformmaster::where('student_id',$student_id)->where('exam_id',Exam::where('status',1)->first()->id)->where('inwardstatus',1)->get();
        if($sdata->isEmpty())
        {
            return false;   // form not filled
        }
        else
        {
            return true;    // form filled
        } 
    }

    public function getsubjects($patternclass_id,$sem)
    { 
        return  Subject::where('patternclass_id',$patternclass_id)->where('subject_sem',$sem)->orderBy('id')->get();                   
    }

    public function getmarks($subject_id, $exam_id )
    {
        $studmarks=$this->studentmarks()->where('subject_id',$subject_id)->where('exam_id','<=', $exam_id )->get()->last();
        if(is_null($studmarks))
        {
            return -5;
        }
        else
        {
            return $studmarks;
        } 
    }

    public function studentordinace163s()
    {
        return $this->hasMany(Studentordinace163::class,'student_id','id');
    }

    public function checkspecial($exam)
    {
        $subject_ids= Studentmark::where('student_id',$this->id)->where('exam_id','<=',$exam->id)->pluck('subject_id');
        $subject=Subject::whereNotNull('subject_optionalgroup')->whereIn('id',$subject_ids)->first();
        if(!is_null($subject))
        {
            return 'SPECIAL SUBJECT : '.$subject->subject_optionalgroup;
        }
        else
        {
            return null;
        } 
    }

    public function getSemwiseNONCGPAtotal($sem=6)
    {
        
        $intextdata=Studentmark::with(['subject'=>function($query){ $query->whereIn('subject_type',['G','IEG','IG']); }])
        ->where('sem',$sem)
        ->where('student_id',$this->id) 
        ->whereNotIn('grade',['F','Ab','-1'])
        ->whereNotNull('grade')
        ->get() ;
      
        
        $extdata= Intextracreditbatchseatnoallocation::with('internalmarksextracreditbatches.subjects')
        ->whereNotIn('grade',['F','Ab','-1','NA'])
        ->where('student_id',$this->id) 
        ->where('sem',$sem)
        ->whereNotNull('grade')
        ->get();

        return($extdata->sum('internalmarksextracreditbatches.subjects.subject_credit')+$intextdata->sum('subject.subject_credit'));
    }


    // public function getStudentName($value)
    // {
        //     return ucfirst($value);
        // }
        
        // public function checkprn()
        // {
    //     $prnstatus=$this->prn!=NULL?1:0;
    //     if($prnstatus==1)
    //     {
    //         $values = array('medium_instruction' => "English",'student_id'=>$this->id,'patternclass_id' =>$this->patternclass_id);
    //     }
    //     else
    //     {
    //         return $prnstatus;
    //     }
    // }
   

    // public function patternclassstudent()
    // {
    //     return $this->hasMany(PatternclassStudent::class,'student_id','id');
    // }



    // public function studentextracreditexamforms()
    // {
    //     return $this->hasMany(Extracreditsubjectexamform::class,'student_id','id');
    // }

    // public function patternclass()
    // {
    //     return $this->belongsTo(PatternClass::class, 'patternclass_id', 'id');
    // }





    // public function checkexamform($student_id,$exam_id)
    // {
    //     $sdata=Examformmaster::where('student_id',$student_id)->where('exam_id',$exam_id)->get();
    //     if($sdata->isEmpty())
    //     {
    //         return 0; // form not filled
    //     }
    //     else 
    //     {
    //         return 1; //    form filled
    //     }
    // }




    //for previous result
    // public function getmarksforexamform($sub, $currentexam )
    // {
    //     $studmarks=$this->studentmarks()->where('subject_id',$sub)->where('exam_id','<', $currentexam )->get()->last();
    //     if(is_null($studmarks))
    //     {
    //         return -5;
    //     }
    //     else
    //     {
    //         return $studmarks;
    //     } 
    // }

    // public function getextmarks($sub)
    // { 
    //     return $studmarks=$this->studentmarks()->where('subject_id',$sub)->select('ext_marks')->max('ext_marks');
    // }
   


    // public function previousexamstudent()
    // {
    //     return $this->hasOne(PreviousexamStudent::class,'student_id','id');
    // }

    // public function student_blockallocations()
    // {
    //     return $this->hasMany(StudentBlockallocation::class,'student_id','id');
    // }

    // public function intbatchseatnoallocations()
    // {
    //     return $this->hasMany(Intbatchseatnoallocation::class,'student_id','id');
    // }



  

    // public function getyearresult($sem_1_data,$sem_2_data,$data2)
    // { 
    //     $pfAbStatus=1;  
    //     $x= $sem_1_data->semcreditearned+$sem_2_data->semcreditearned;
    //     $sem_1_2_total_credit= $sem_1_data->semtotalcredit+$sem_2_data->semtotalcredit;
    //     $z=$data2->patternclass->credit;
    //     if ($x>=$z and $x<$sem_1_2_total_credit) 
    //     {
    //         $pfAbStatus=2;  // Result : Fail A.T.K.T.
    //     }    
    //     else if($x<$z and $x>=0)
    //     { 
    //         $pfAbStatus=0;  // Result : Fail
    //     }
    //     if ($pfAbStatus==1 and ($sem_1_data->extracreditsstatus==0 or $sem_2_data->extracreditsstatus==0))
    //     {
    //         $pfAbStatus=2;  // Result : Fail A.T.K.T.
    //     }
    //     return $pfAbStatus;
    // }






    // public function isRegular($exam)
    // {
    //     if(Studentadmission::where('student_id',$this->id)->where('academicyear_id',$exam->academicyear_id)->get()->count()>0)
    //     {
    //         return "Regular";
    //     }
    //     else
    //     {
    //         return "Repeater";
    //     } 
    // }

    // public function getfinalnoncgpa()
    // {
    //    $extdata= Intextracreditbatchseatnoallocation::with('internalmarksextracreditbatches.subjects')->where('student_id',$this->id)->get();
    //     dd($extdata->sum('internalmarksextracreditbatches.subjects.subject_credit'));
    // }



    // public function gettyclass()
    // {
    //     $memberid= $this->memid;
    //     $adsubjectdata= Admissiondata::where('memid',$memberid)->get();
    //     if(!$adsubjectdata->isEmpty())
    //     {
    //        return ($adsubjectdata->last()->patternclass_id);
    //     }        
    // }

    // public function checkprofile()
    // {
    //     if($this->studentprofile)
    //     {
    //         if ((file_exists(public_path('storage/images/photo/'.$this->studentprofile->profile_photo_path)))&&(file_exists(public_path('storage/images/photo/'.$this->studentprofile->sign_photo_path)))) 
    //         {
    //             return false;
    //         }
    //         else 
    //         {
    //             return true;
    //         }
    //     }
    //     else
    //     {
    //         return true;
    //     } 
    // }

    // public function getExtraCreditSubject()
    // {
    //     $intbatch=$this->intextracreditbatchseatnoallocations->where('grade','!=','NA')->where('grade','!=','F')->whereNotNull('grade');
    //     $allintbatch=Internalmarksextracreditbatch::whereIn('id',$intbatch->pluck('intbatch_id'))->get();
    //     $extracrsub=$allintbatch->pluck('subject_id');
    //     return $extracrsub;
    // }

    // public function studentspecializations()
    // {
    //     return $this->hasMany(Studentspecialization::class,'student_id','id');
    // }

    // public function unfairmeans()
    // {
    //     return $this->hasMany(Unfairmean::class,'student_id','id');
    // }
    
    // public function checkpc()
    // {
    //     return $this->unfairmeans->where('status','0')->sum('punishment');
    // }

    // public function photocopyexammaster()
    // { 
    //     return $this->hasMany(Photocopyexammaster::class,'student_id','id');
    // }

    // public function checkphotocopy()
    // {
    //     $exam = Exam::where('id', '9')->first();
    //     if(($this->photocopyexammaster->where('exam_id',$exam->id))->isEmpty())
    //     {
    //         return null;
    //     }
    //     else
    //     {
    //         return $this->photocopyexammaster->where('exam_id',$exam->id);
    //     }
    // }

    // public function isregularorbacklog( $epc,$subjects)
    // {
    //     $oldstudent_id=$this->studentmarks->where('exam_id','!=', $epc->first()->exam_id)->where('subject_id', $subjects->id);
    //     if($oldstudent_id->count()>=0)
    //     {
    //         return " (Backlog)"; 
    //     } 
    //     else
    //     {
    //         return "";
    //     } 
    // }

    // public function checkforreval()
    // {
    //     $exam = Exam::where('id', '9')->first();
    //     $photocopymaster=$this->photocopyexammaster->where('exam_id',$exam->id);
    //     if($photocopymaster->isEmpty())
    //     {
    //         return false;
    //     }
    //     else if($photocopymaster->first()->inwardstatus=='1')
    //     {
    //         return true;
    //     }
    //     else if($photocopymaster->first()->inwardstatus=='2')
    //     {
    //         return false;
    //     }
    // }

    // public function checkrevaluationstatus()
    // {
    //     $exam = Exam::where('id', '9')->first();
    //     if(($this->photocopyexammaster->where('exam_id',$exam->id)->where('revaltotalfee','0'))->isEmpty())
    //     {
    //         return null;
    //     }
    //     else
    //     {
    //         return $this->photocopyexammaster->where('exam_id',$exam->id);
    //     }
    // }

    // public function checkspecialfromexamform($exam,$patternclass_id)
    // {
    //     $studentmark= studentexamform::where('student_id',$this->id)->where('exam_id','<=',$exam->id)->pluck('subject_id');
    //     $sub=Subject::whereNotNull('subject_optionalgroup')->where ('patternclass_id',$patternclass_id)->whereIn('id',$studentmark)->first();
    //     if(!is_null($sub))
    //     {
    //         return $sub->subject_optionalgroup;
    //     }
    //     else 
    //     {
    //         return "-";
    //     }
    // }

    //   public function currentexamstudentseatno()
    //   {
    //       return $this->hasOne(Examstudentseatno::class,'student_id','id')->latestOfMany();
    //   }

    //   public function studentspecialsubjects()
    //   {
    //       return $this->hasMany(Studentspecialsubject::class,'student_id','id');
    //   }

    // public function studdocumentpendings()
    // { 
    //     return $this->hasMany(Studdocumentpending::class,'student_id','id');
    // }


}

