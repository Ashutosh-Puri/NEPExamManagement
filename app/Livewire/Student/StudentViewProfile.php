<?php

namespace App\Livewire\Student;

use App\Models\Taluka;
use Livewire\Component;
use App\Models\Addresstype;
use App\Models\Patternclass;
use App\Models\Studentaddress;
use App\Models\Studentpreviousexam;
use Illuminate\Support\Facades\Auth;

class StudentViewProfile extends Component
{   
     public $memid;
     public $abcid;
     public $aadhar_name;
     public $student_name;
     public $email;
     public $mobile_no;
     public $father_name;
     public $mother_name;
     public $gender;
     public $date_of_birth;
     public $nationality;
     public $adharnumber;
     public $caste_category_id;
     public $caste_id;
     public $is_noncreamylayer;
     public $is_handicap;
     public $profile_photo_path_old;
     public $sign_photo_path_old;
     public $taluka_id=null;
     public $pincode;
     public $village;
     public $address;
     public $taluka_id_2;
     public $pincode_2;
     public $village_2;
     public $address_2;
     public $pattern_class_id;
     public $pre_eductions;


    public function mount()
    {   
        $this->pre_eductions=Studentpreviousexam::where('student_id',Auth::guard('student')->user()->id)->get();
        $this->address_types=Addresstype::where('is_active',1)->limit(2)->get();
        $student=Auth::guard('student')->user();
        if($student)
        {
            $this->pattern_class_id=$student->patternclass_id;
            if($pattern_class=Patternclass::find($student->patternclass_id))
            {
                if(isset($pattern_class))
                {
                    if(isset($pattern_class->courseclass->course))
                    {
                        $this->course_id=$pattern_class->courseclass->course->id;
                    }
                    if(isset($pattern_class->courseclass))
                    {
                        $this->course_class_id=$pattern_class->courseclass->id;
                    }
                    if(isset($pattern_class->pattern))
                    {
                        $this->pattern_id= $pattern_class->pattern->id;
                    }
                }
            }
            $this->memid=$student->memid;
            $this->abcid=$student->abcid; 
            $this->student_name=$student->student_name;
            $this->email=$student->email;
            $this->mobile_no=$student->mobile_no;
            $this->adharnumber=$student->aadhar_card_no;
            $this->mother_name=$student->mother_name;

            $student_profile=$student->studentprofile;
            if($student_profile)
            {
                $this->father_name=$student_profile->father_name;
                $this->aadhar_name=$student_profile->student_name_on_adharcard;
                $this->gender=$student_profile->gender;
                $this->date_of_birth=$student_profile->date_of_birth;
                $this->nationality=$student_profile->nationality;
                $this->caste_category_id=$student_profile->caste_category_id;
                $this->caste_id=$student_profile->caste_id;
                $this->is_noncreamylayer=$student_profile->is_noncreamylayer;
                $this->is_handicap=$student_profile->is_handicap;
                $this->profile_photo_path_old=$student_profile->profile_photo_path;
                $this->sign_photo_path_old=$student_profile->sign_photo_path;
                if(isset($student_profile->sign_photo_path) && isset($student_profile->profile_photo_path))
                {
                    $this->can_update=1;
                }
            }

            if (isset($this->address_types[0]))
            {   
                $student_addres_1= Studentaddress::where('student_id',$student->id)->where('addresstype_id',$this->address_types[0]->id)->first();
                if($student_addres_1)
                {
                    $taluka_1=Taluka::find($student_addres_1->taluka_id);
                    if($taluka_1)
                    {
                        $this->country_id=$taluka_1->district->state->country->id;
                        $this->state_id=$taluka_1->district->state->id;
                        $this->district_id=$taluka_1->district->id;
                        $this->taluka_id=$taluka_1->id;
                    }
                    $this->pincode=$student_addres_1->pincode;
                    $this->village=$student_addres_1->village_name;
                    $this->address=$student_addres_1->address;
                }
            }
            if (isset($this->address_types[1]))
            {
                $student_addres_2= Studentaddress::where('student_id',$student->id)->where('addresstype_id',$this->address_types[1]->id)->first();
                if($student_addres_2)
                {
                    $taluka_2=Taluka::find($student_addres_2->taluka_id);
                    if($taluka_2)
                    {
                        $this->country_id_2=$taluka_2->district->state->country->id;
                        $this->state_id_2=$taluka_2->district->state->id;
                        $this->district_id_2=$taluka_2->district->id;
                        $this->taluka_id_2=$taluka_2->id;
                    }
                    $this->pincode_2=$student_addres_2->pincode;
                    $this->village_2=$student_addres_2->village_name;
                    $this->address_2=$student_addres_2->address;
                }
            }
        }
    }
    public function render()
    {
        return view('livewire.student.student-view-profile')->extends('layouts.student')->section('student');
    }
}
