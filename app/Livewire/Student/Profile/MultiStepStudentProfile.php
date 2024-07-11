<?php

namespace App\Livewire\Student\Profile;

use App\Models\Caste;
use App\Models\Gradepoint;
use App\Models\Month;
use App\Models\State;
use App\Models\Course;
use App\Models\Taluka;
use App\Models\Country;
use App\Models\Pattern;
use App\Models\Student;
use Livewire\Component;
use App\Models\District;
use App\Models\Classyear;
use App\Models\Addresstype;
use App\Models\Courseclass;
use App\Models\Gendermaster;
use App\Models\Patternclass;
use App\Models\Previousyear;
use App\Models\Castecategory;
use Livewire\WithFileUploads;
use App\Models\Studentaddress;
use App\Models\Studentprofile;
use App\Models\Boarduniversity;
use Illuminate\Validation\Rule;
use App\Models\Educationalcourse;
use App\Models\Studentpreviousexam;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class MultiStepStudentProfile extends Component
{
    use WithFileUploads;
    public $steps = 6;
    public $current_step = 1;
    public $student_id;

    // step 1
    public $memid;
    public $abcid;
    public $aadhar_name;
    public $genders;
    public $cast_categories;
    public $student_name;
    public $email;
    public $mobile_no;
    public $father_name;
    public $mother_name;
    public $gender;
    public $date_of_birth;
    public $date_of_birth_on_adharcard;
    public $nationality;
    public $adharnumber;
    public $caste_category_id;
    public $caste_id;
    public $castes;
    public $is_noncreamylayer;
    public $is_handicap;
    public $parent_name;
    public $parent_mobile_no;
    public $student_name_devnagari;
    public $mother_name_devnagari;
    public $father_name_devnagari;
    // step 2
    public $profile_photo_path;
    public $profile_photo_path_old;
    public $sign_photo_path;
    public $sign_photo_path_old;
    public $can_update=0;
    // step 3
    public $address_types;
    public $countries;
    public $states;
    public $talukas;
    public $districts;
    public $country_id;
    public $state_id;
    public $district_id;
    public $taluka_id=null;
    public $pincode;
    public $village;
    public $address;
    public $is_same=0;
    public $countries_2;
    public $states_2;
    public $talukas_2;
    public $districts_2;
    public $country_id_2;
    public $state_id_2;
    public $district_id_2=null;
    public $taluka_id_2;
    public $pincode_2;
    public $village_2;
    public $address_2;
    // step 4
    public $patterns;
    public $courses;
    public $course_id;
    public $course_classes;
    public $course_class_id;
    public $pattern_id;
    public $pattern_class_id;
    // step 5
    public $educationalcourses;
    public $educationalcourse_id;
    public $boarduniversity_id ;
    public $boarduniversities ;
    public $obtained_marks ;
    public $total_marks ;
    public $passoutmonths;
    public $passoutyears;
    public $passout_year;
    public $passout_month;
    public $grades;
    public $grade;
    public $cgpa;
    public $percentage;
    public $seat_number;
    public $is_cgpa=0;
    public $pre_eductions;
    // step 6
    public $is_confirm;

    protected function rules()
    {
        $rules = [];

        if ($this->current_step == 1) {
            $rules = [
                // 'student_name' => ['required','string','max:255'],
                // 'email' => ['required','email','string','max:255'],
                // 'mobile_no' => ['required','numeric','digits:10'],
                // 'mother_name' => ['required','string','max:255'],
                'parent_name' => ['nullable','string','max:255'],
                'student_name_devnagari' => ['nullable','string','max:255'],
                'mother_name_devnagari' => ['nullable','string','max:255'],
                'father_name_devnagari' => ['nullable','string','max:255'],
                'parent_mobile_no' => ['nullable','numeric','digits:10'],
                'memid' => ['required','numeric','min:1','digits_between:4,10','unique:students,memid,'.Auth::guard('student')->user()->id],
                'aadhar_name' => ['required','string','max:255'],
                'abcid' => ['nullable','digits:12','unique:students,abcid,'.Auth::guard('student')->user()->id],
                'adharnumber' => ['required','numeric','digits:12'],
                'father_name' => ['required','string','max:255'],
                'gender' => ['required','string','max:1'],
                'date_of_birth' => ['required','date'],
                'date_of_birth_on_adharcard' => ['required','date'],
                'nationality' => ['required','string','max:25'],
                'caste_id' => ['required','numeric',Rule::exists('castes', 'id')],
                'caste_category_id' => ['required','numeric',Rule::exists('caste_categories', 'id')],
                'is_noncreamylayer' => ['required','numeric','min:0','max:1'],
                'is_handicap' => ['required','numeric','min:0','max:1'],
            ];
        } elseif ($this->current_step == 2) {

            $rules = [
                'profile_photo_path' =>[$this->can_update==1?'nullable':'required','max:250','mimes:png,jpg,jpeg'],
                'sign_photo_path' => [$this->can_update==1?'nullable':'required','max:50','mimes:png,jpg,jpeg'],
            ];

        }elseif ($this->current_step == 3) {
            if($this->is_same)
            {
                $rules = [
                    'pincode' =>['required','numeric','digits:6'],
                    'taluka_id' =>['required','numeric',Rule::exists('talukas', 'id')],
                    'address' =>['required','string','max:400'],
                    'village' =>['required','string','max:255'],
                    'country_id' =>['required'],
                    'district_id' =>['required'],
                    'state_id' =>['required'],
                ];

            }else
            {
                $rules = [
                    'pincode' =>['required','numeric','digits:6'],
                    'taluka_id' =>['required','numeric',Rule::exists('talukas', 'id')],
                    'address' =>['required','string','max:400'],
                    'village' =>['required','string','max:255'],
                    'pincode_2' =>['required','numeric','digits:6'],
                    'taluka_id_2' =>['required','numeric',Rule::exists('talukas', 'id')],
                    'address_2' =>['required','string','max:400'],
                    'village_2' =>['required','string','max:255'],
                    'country_id' =>['required'],
                    'district_id' =>['required'],
                    'state_id' =>['required'],
                    'country_id_2' =>['required'],
                    'district_id_2' =>['required'],
                    'state_id_2' =>['required'],
                ];
            }
        } elseif ($this->current_step == 4) {

            $rules = [
                'pattern_id' =>['required','numeric',Rule::exists('patterns', 'id')],
                'course_id' =>['required','numeric',Rule::exists('courses', 'id')],
                'course_class_id' =>['required','numeric',Rule::exists('course_classes', 'id')],
                // 'pattern_class_id' =>['required','numeric',Rule::exists('pattern_classes', 'id')],
            ];

        }elseif ($this->current_step == 5) {

            if($this->is_cgpa)
            {
                $rules = [
                    'educationalcourse_id' =>['required','numeric', Rule::unique('studentpreviousexams')->where('student_id', Auth::guard('student')->user()->id)],
                    'boarduniversity_id'=>['required','numeric',Rule::exists('boarduniversities', 'id'),],
                    'passout_year'=>['required','numeric','digits:4'],
                    'passout_month'=>['required','string'],
                    'seat_number'=>['required','string',Rule::unique('studentpreviousexams')->where('student_id', Auth::guard('student')->user()->id)],
                    'grade'=>['required','string'],
                    'cgpa'=>['required','numeric','min:0.00','max:10.00'],
                ];
            }
            else
            {
                $rules = [
                    'educationalcourse_id' =>['required','numeric', Rule::unique('studentpreviousexams')->where('student_id', Auth::guard('student')->user()->id)],
                    'boarduniversity_id'=>['required','numeric',Rule::exists('boarduniversities', 'id')],
                    'passout_year'=>['required','numeric','digits:4'],
                    'passout_month'=>['required','string'],
                    'seat_number'=>['required','numeric',Rule::unique('studentpreviousexams')->where('student_id', Auth::guard('student')->user()->id)],
                    'grade'=>['required','string'],
                    'percentage'=>['required','numeric','between:0.00,100.00'],
                ];
            }
        }elseif ($this->current_step == $this->steps) {
            
            $rules = [
                'is_confirm'=>['required'],
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            // step 1
            'memid.required' => 'Member ID is required.',
            'memid.numeric' => 'Member ID must be a number.',
            'memid.min' => 'Member ID must be greater than or equal to :min.',
            'memid.digits_between' => 'Member ID must be between :min and :max digits.',
            'student_name.required' => 'The Student Name field is required.',
            'student_name.string' => 'The Student Name must be a string.',
            'parent_name.max' => 'The Guardian Name must not exceed 255 characters.',
            'parent_name.required' => 'The Guardian Name field is required.',
            'parent_name.string' => 'The Guardian Name must be a string.',
            'student_name_devnagari.max' => 'The Student Name In Marathi must not exceed 255 characters.',
            'student_name_devnagari.required' => 'The Student Name In Marathi field is required.',
            'student_name_devnagari.string' => 'The Student Name In Marathi must be a string.',
            'mother_name_devnagari.max' => 'The Mother Name In Marathi must not exceed 255 characters.',
            'mother_name_devnagari.required' => 'The Mother Name In Marathi field is required.',
            'mother_name_devnagari.string' => 'The Mother Name In Marathi must be a string.',
            'father_name_devnagari.max' => 'The Father Name In Marathi must not exceed 255 characters.',
            'father_name_devnagari.required' => 'The Father Name In Marathi field is required.',
            'father_name_devnagari.string' => 'The Father Name In Marathi must be a string.',
            'email.required' => 'The Email field is required.',
            'email.email' => 'Please Enter a valid Email address.',
            'email.string' => 'The Email must be a string.',
            'email.max' => 'The Email must not exceed 255 characters.',
            'mobile_no.required' => 'The Mobile Number field is required.',
            'mobile_no.numeric' => 'The Mobile Number must be a number.',
            'mobile_no.digits' => 'The Mobile Number must be 10 digits.',
            'parent_mobile_no.required' => 'The Guardian  Mobile Number field is required.',
            'parent_mobile_no.numeric' => 'The  Guardian Mobile Number must be a number.',
            'parent_mobile_no.digits' => 'The  Guardian Mobile Number must be 10 digits.',
            'mother_name.required' => 'The Mother Name field is required.',
            'mother_name.string' => 'The Mother Name must be a string.',
            'mother_name.max' => 'The Mother Name must not exceed 255 characters.',
            'aadhar_name.required' => 'The Student Name As Per Aadhar field is required.',
            'aadhar_name.string' => 'The Student Name As Per Aadhar must be a string.',
            'aadhar_name.max' => 'The Student Name As Per Aadhar must not exceed 255 characters.',
            'adharnumber.required' => 'The Aadhar Number field is required.',
            'adharnumber.numeric' => 'The Aadhar Number must be a number.',
            'adharnumber.digits' => 'The Aadhar Number must be 12 digits.',
            'abcid.numeric' => 'The ABC ID must be a number.',
            'abcid.digits' => 'The ABC ID must be 12 digits.',
            'father_name.required' => 'The Father Name field is required.',
            'father_name.string' => 'The Father Name must be a string.',
            'father_name.max' => 'The Father Name must not exceed 255 characters.',
            'gender.required' => 'The Gender field is required.',
            'gender.string' => 'The Gender must be a string.',
            'gender.max' => 'The Gender must not exceed 2 characters.',
            'date_of_birth.required' => 'The Date Of Birth field is required.',
            'date_of_birth.date' => 'Please enter a valid Date Of Birth.',
            'date_of_birth_on_adharcard.required' => 'The Date Of Birth As Per Adharcard field is required.',
            'date_of_birth_on_adharcard.date' => 'Please enter a valid Date Of Birth As Per Adharcard.',
            'nationality.required' => 'The Nationality field is required.',
            'nationality.string' => 'The Nationality must be a string.',
            'nationality.max' => 'The Nationality must not exceed 25 characters.',
            'caste_category_id.required' => 'The Caste Category field is required.',
            'caste_category_id.numeric' => 'The Caste Category must be a number.',
            'caste_category_id.exists' => 'The selected Caste Category is invalid.',
            'caste_id.required' => 'The Caste field is required.',
            'caste_id.numeric' => 'The Caste must be a number.',
            'caste_id.exists' => 'The selected Caste is invalid.',
            'is_noncreamylayer.required' => 'The Non-Creamy Layer field is required.',
            'is_noncreamylayer.numeric' => 'The Non-Creamy Layer must be a number.',
            'is_noncreamylayer.min' => 'The Non-Creamy Layer must be at least 0.',
            'is_noncreamylayer.max' => 'The Non-Creamy Layer must be at most 1.',
            'is_handicap.required' => 'The Handicap field is required.',
            'is_handicap.numeric' => 'The Handicap must be a number.',
            'is_handicap.min' => 'The Handicap must be at least 0.',
            'is_handicap.max' => 'The Handicap must be at most 1.',
            // step 2
            'profile_photo_path.required' => 'The Student Photo field is required.',
            'profile_photo_path.mimes' => 'The Student Photo extension must be :mimes .',
            'profile_photo_path.max' => 'The Student Photo Size must :max KB.',
            'sign_photo_path.required' => 'The Student Photo field is required.',
            'sign_photo_path.mimes' => 'The Student Photo extension must be :mimes .',
            'sign_photo_path.max' => 'The Student Photo Size must :max KB.',
            // step 3
            'pincode.required' => 'The Pincode field is required.',
            'pincode.numeric' => 'The Pincode must be a number.',
            'pincode.digits' => 'The Pincode must be exactly 6 digits.',
            'taluka_id.required' => 'The Taluka field is required.',
            'taluka_id.numeric' => 'The Taluka must be a number.',
            'taluka_id.exists' => 'The selected Taluka is invalid.',
            'address.required' => 'The Address field is required.',
            'address.string' => 'The Address must be a string.',
            'address.max' => 'The Address may not be greater than :max characters.',
            'village.required' => 'The Village field is required.',
            'village.string' => 'The Village must be a string.',
            'village.max' => 'The Village may not be greater than :max characters.',
            'pincode_2.required' => 'The Pincode field is required.',
            'pincode_2.numeric' => 'The Pincode must be a number.',
            'pincode_2.digits' => 'The Pincode must be exactly 6 digits.',
            'taluka_id_2.required' => 'The Taluka field is required.',
            'taluka_id_2.numeric' => 'The Taluka must be a number.',
            'taluka_id_2.exists' => 'The selected Taluka is invalid.',
            'address_2.required' => 'The Address field is required.',
            'address_2.string' => 'The Address must be a string.',
            'address_2.max' => 'The Address may not be greater than :max characters.',
            'village_2.required' => 'The Village field is required.',
            'village_2.string' => 'The Village must be a string.',
            'village_2.max' => 'The Village may not be greater than :max characters.',
            'country_id.required' => 'The Country field is required.',
            'district_id.required' => 'The District field is required.',
            'state_id.required' => 'The State field is required.',
            'country_id_2.required' => 'The Country field is required.',
            'district_id_2.required' => 'The District field is required.',
            'state_id_2.required' => 'The State field is required.',
            // step 4
            'pattern_id.required'=>'The Pattern field is required..',
            'pattern_id.exists'=>'The selected Pattern is invalid.',
            'pattern_id.numeric'=>'The Pattern must be a number.',
            // 'pattern_class_id.exists'=>'The selected Class is invalid.',
            // 'pattern_class_id.required'=>'The Class field is required.',
            // 'pattern_class_id.numeric'=>'The Class must be a number.',
            // 'pattern_class_id.unique'=>'The Class has been taken.',
            'course_class_id.exists'=>'The selected Course Class is invalid.',
            'course_class_id.required'=>'The Course Class field is required.',
            'course_class_id.numeric'=>'The Course Class must be a number.',
            'course_class_id.unique'=>'The Course Class has been taken.',
            'course_id.exists'=>'The selected Course is invalid.',
            'course_id.required'=>'The Course field is required.',
            'course_id.numeric'=>'The Course must be a number.',
            'course_id.unique'=>'The Course has been taken.',
            //step 5
            'educationalcourse_id.required' => 'The Educational Course field is required.',
            'educationalcourse_id.numeric' => 'The Educational Course must be a number.',
            'educationalcourse_id.unique' => 'This Educational Course You Have Already Selected.',
            'boarduniversity_id.required' => 'The Board/University field is required.',
            'boarduniversity_id.numeric' => 'The Board/University must be a number.',
            'boarduniversity_id.exists' => 'The selected Board/University is invalid.',
            'passout_year.required' => 'The Passout Year field is required.',
            'passout_year.numeric' => 'The Passout Year must be a number.',
            'passout_year.digits' => 'The Passout Year must be four digits.',
            'passout_month.required' => 'The Passout Month field is required.',
            'seat_number.required' => 'The Seat Number field is required.',
            'seat_number.string' => 'The Seat Number must be a string.',
            'seat_number.unique' => 'The Seat Number has already been taken.',
            'grade.required' => 'The Grade field is required.',
            'grade.string' => 'The Grade must be a string.',
            'cgpa.required' => 'The CGPA field is required.',
            'cgpa.numeric' => 'The CGPA must be a number.',
            'cgpa.min' => 'The CGPA must be at least 0.00.',
            'cgpa.max' => 'The CGPA must not exceed 10.00.',
            //step 6
            'is_confirm.required' => 'The Confirmation field is required.',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function student_information_form()
    {
        $this->validate();
        Auth::guard('student')->user()->update([
            'memid'=>$this->memid,
            'abcid'=>$this->abcid,
            'aadhar_card_no'=>$this->adharnumber,
        ]);
        Auth::guard('student')->user()->studentprofile()->updateOrCreate(
            [   'student_id'=>Auth::guard('student')->user()->id ],
            [
                'student_name_on_adharcard'=>$this->aadhar_name,
                'father_name'=>$this->father_name,
                'parent_name'=>$this->parent_name,
                'parent_mobile_no'=>$this->parent_mobile_no,
                'student_name_devnagari'=>$this->student_name_devnagari,
                'mother_name_devnagari'=>$this->mother_name_devnagari,
                'father_name_devnagari'=>$this->father_name_devnagari,
                'gender'=>$this->gender,
                'date_of_birth'=>$this->date_of_birth,
                'date_of_birth_on_adharcard'=>$this->date_of_birth_on_adharcard,
                'nationality'=>$this->nationality,
                'caste_id'=>$this->caste_id,
                'caste_category_id'=>$this->caste_category_id,
                'is_noncreamylayer'=>$this->is_noncreamylayer,
                'is_handicap'=>$this->is_handicap,
            ]
        );
        Auth::guard('student')->user()->update(['current_step' =>2]);
        $this->dispatch('alert',type:'success',message:'Step 1 : Student Information Saved Successfully !!');
        $this->current_step = 2;
    }

    public function photo_upload()
    {
        $this->validate();
        $student_id =Auth::guard('student')->user()->id;
        $studentProfile = Studentprofile::where('student_id', $student_id)->first();

        if (!$studentProfile) {
            $this->dispatch('alert',type:'error',message:'Student Profile Not Found !!');
            return;
        }

        if ($this->profile_photo_path) {
            if ($studentProfile->profile_photo_path) {
                File::delete($studentProfile->profile_photo_path);
            }
            $path = 'student/profile/photo/';
            $fileName =$student_id. '.' . $this->profile_photo_path->getClientOriginalExtension();
            $this->profile_photo_path->storeAs($path, $fileName, 'public');
            $studentProfile->profile_photo_path = 'storage/' . $path . $fileName;
            $this->reset('profile_photo_path');
        }

        if ($this->sign_photo_path) {
            if ($studentProfile->sign_photo_path) {
                File::delete($studentProfile->sign_photo_path);
            }
            $path = 'student/profile/sign/';
            $fileName =$student_id. '.' . $this->sign_photo_path->getClientOriginalExtension();
            $this->sign_photo_path->storeAs($path, $fileName, 'public');
            $studentProfile->sign_photo_path = 'storage/' . $path . $fileName;
            $this->reset('sign_photo_path');
        }
        $studentProfile->update();
        Auth::guard('student')->user()->update(['current_step' =>3]);
        $this->dispatch('alert',type:'success',message:'Step 2 : Student Photo And Sign Saved Successfully !!');
        $this->current_step = 3;
    }

    public function address_form()
    {
        $this->validate();


        if($this->is_same)
        {   
            Auth::guard('student')->user()->studentaddress()->updateOrCreate(
                [
                    'student_id'=>Auth::guard('student')->user()->id,
                    'addresstype_id' => isset($this->address_types[0]->id)?$this->address_types[0]->id:'',
                ],
                [
                    'taluka_id' => $this->taluka_id,
                    'pincode' => $this->pincode,
                    'village_name' => $this->village,
                    'locality_name' => $this->village,
                    'address' => $this->address,
                    'is_same' => 1,
                    // 'address_type' => 1,
                    'addresstype_id' => isset($this->address_types[0]->id)?$this->address_types[0]->id:'',
                    'is_completed' => 1,
                ]
            );

            Auth::guard('student')->user()->studentaddress()->updateOrCreate(
                [
                    'student_id'=>Auth::guard('student')->user()->id,
                    'addresstype_id' => isset($this->address_types[1]->id)?$this->address_types[1]->id:'',
                ],
                [
                    'taluka_id' => $this->taluka_id,
                    'pincode' => $this->pincode,
                    'village_name' => $this->village,
                    'locality_name' => $this->village,
                    'address' => $this->address,
                    'is_same' => 1,
                    // 'address_type' => 2,
                    'addresstype_id' => isset($this->address_types[1]->id)?$this->address_types[1]->id:'',
                    'is_completed' => 1,
                ]
            );
            Auth::guard('student')->user()->update(['current_step' =>4]);
            $this->is_same=0;
            $this->dispatch('alert',type:'success',message:'Step 3 : Student Address Saved Successfully !!');
        }else
        {   
            Auth::guard('student')->user()->studentaddress()->updateOrCreate(
                [
                    'student_id'=>Auth::guard('student')->user()->id,
                    'addresstype_id' => $this->address_types[0]?$this->address_types[0]->id:'',
                ],
                [
                    'taluka_id' => $this->taluka_id,
                    'pincode' => $this->pincode,
                    'village_name' => $this->village,
                    'locality_name' => $this->village,
                    'address' => $this->address,
                    'is_same' => 0,
                    // 'address_type' => 1,
                    'addresstype_id' => $this->address_types[0]?$this->address_types[0]->id:'',
                    'is_completed' => 1,
                ]
            );

            Auth::guard('student')->user()->studentaddress()->updateOrCreate(
                [
                    'student_id'=>Auth::guard('student')->user()->id,
                    'addresstype_id' => $this->address_types[1]?$this->address_types[1]->id:'',
                ],
                [
                    'taluka_id' => $this->taluka_id_2,
                    'pincode' => $this->pincode_2,
                    'village_name' => $this->village_2,
                    'locality_name' => $this->village_2,
                    'address' => $this->address_2,
                    'is_same' => 0,
                    // 'address_type' => 2,
                    'addresstype_id' => $this->address_types[1]?$this->address_types[1]->id:'',
                    'is_completed' => 1,
                ]
            );
            $this->is_same=0;
            Auth::guard('student')->user()->update(['current_step' =>4]);
            $this->dispatch('alert',type:'success',message:'Step 3 : Student Current And Permanent Address Saved Successfully !!');
        }
        $this->current_step = 4;
    }

    public function choose_course_form()
    {
        $this->validate();
        $pattern_class=Patternclass::select('id')->where('class_id', $this->course_class_id)->where('pattern_id', $this->pattern_id)->first('id');
        if($pattern_class)
        {
            Auth::guard('student')->user()->update(['patternclass_id'=>$pattern_class->id ,'current_step' =>5]);
            $this->dispatch('alert',type:'success',message:'Step 4 : New Course Saved Successfully !!');
            $this->current_step = 5;
        }else
        {
            $this->dispatch('alert',type:'error',message:'Step 4 : Pattern Class Not Found!!');
        }
    }

    public function add_previous_exam_form()
    {
        $this->validate();
        $student_previous_exam= new Studentpreviousexam;
        $student_previous_exam->student_id=Auth::guard('student')->user()->id;
        $student_previous_exam->boarduniversity_id=  $this->boarduniversity_id ;
        $student_previous_exam->educationalcourse_id=  $this->educationalcourse_id;
        $student_previous_exam->passing_year=  $this->passout_year;
        $student_previous_exam->passing_month=  $this->passout_month;
        $student_previous_exam->grade=  $this->grade;
        $student_previous_exam->seat_number=  $this->seat_number;
        if( $this->is_cgpa)
        {
            $student_previous_exam->cgpa=  $this->cgpa;
        }else
        {
            $student_previous_exam-> obtained_marks=  $this->obtained_marks ;
            $student_previous_exam-> total_marks=  $this->total_marks ;
            $student_previous_exam-> percentage = $this->percentage;
        }
        $student_previous_exam->save();
        $this->dispatch('alert',type:'success',message:'Previous Education Added Successfully !!');
        $this->reset(['boarduniversity_id','educationalcourse_id','passout_year', 'passout_month','grade','seat_number','is_cgpa', 'cgpa','obtained_marks','total_marks', 'percentage']);
    }
    public function previous_exam_form()
    {
       $count= Studentpreviousexam::where('student_id',Auth::guard('student')->user()->id)->count();
        if($count >0)
        {   
            $studentId = Auth::guard('student')->user()->id;

            $isHSC = Studentpreviousexam::where('student_id', $studentId)
                ->whereHas('educationalcourse', function ($query) {
                    $query->where('course_name', 'HSC');
                })->exists();

            if ($isHSC) {
                Auth::guard('student')->user()->update(['current_step' => 6]);
                $this->dispatch('alert',type:'success',message:'Step 5 : Student Previous Education Saved Successfully !!');
                $this->current_step = 6;
            } else {
                $this->dispatch('alert',type:'info',message:'Please Add HSC As Previous Education.  !!');
                $this->add_previous_exam_form();
            }

        }else
        {   
            $this->dispatch('alert',type:'info',message:'Please Add At Least One Previous Education. !!');
            $this->add_previous_exam_form();
        }
    }

    public function delete_pre_edu(Studentpreviousexam $student_previous_exam)
    {
        $student_previous_exam->delete();
        $this->dispatch('alert',type:'success',message:'Student Previous Education Deleted Successfully !!');
    }

    public function confirm_form()
    {   
        $this->validate();
        Auth::guard('student')->user()->update(['is_profile_complete'=>1 ,'current_step' =>6]);
        $this->dispatch('alert',type:'success',message:'Student Profile Completed Successfully !!');
        $this->reset();
        $this->redirect('/student/dashboard',navigate:true);
    }

    public function back()
    {
        $this->feach();
        $this->current_step--;
    }

    public function move_to($step)
    {   
        $this->feach();
        $this->current_step=$step;
    }

    public function mount()
    {
        if(Auth::guard('student')->user()->is_profile_complete==1)
        {
            return $this->redirect('/student/dashboard',navigate:true);
        }else
        {   
            $this->current_step=Auth::guard('student')->user()->current_step;
            $this->feach();
        }
    }

    public function feach()
    {   
        $this->address_types=Addresstype::where('is_active',1)->limit(2)->get();
        $student=Auth::guard('student')->user();
        if($student)
        {
            $this->pattern_class_id=$student->patternclass_id;
            if($pattern_class=Patternclass::find($student->patternclass_id))
            {
                if($pattern_class)
                {
                    if($pattern_class->courseclass->course)
                    {
                        $this->course_id=$pattern_class->courseclass->course->id;
                    }
                    if($pattern_class->courseclass)
                    {
                        $this->course_class_id=$pattern_class->courseclass->id;
                    }
                    if($pattern_class->pattern)
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
                $this->parent_name=$student_profile->parent_name;
                $this->parent_mobile_no=$student_profile->parent_mobile_no;
                $this->student_name_devnagari=$student_profile->student_name_devnagari;
                $this->mother_name_devnagari=$student_profile->mother_name_devnagari;
                $this->father_name_devnagari=$student_profile->father_name_devnagari;
                $this->aadhar_name=$student_profile->student_name_on_adharcard;
                $this->gender=$student_profile->gender;
                $this->date_of_birth=$student_profile->date_of_birth;
                $this->date_of_birth_on_adharcard=$student_profile->date_of_birth_on_adharcard;
                $this->nationality=$student_profile->nationality;
                $this->caste_category_id=$student_profile->caste_category_id;
                $this->caste_id=$student_profile->caste_id;
                $this->is_noncreamylayer=$student_profile->is_noncreamylayer;
                $this->is_handicap=$student_profile->is_handicap;
                $this->profile_photo_path_old=$student_profile->profile_photo_path;
                $this->sign_photo_path_old=$student_profile->sign_photo_path;
                if(isset($student_profile->sign_photo_path) && isset($student_profile->profile_photo_path))
                {   
                    if(file_exists($student_profile->sign_photo_path) && file_exists($student_profile->profile_photo_path)){

                        $this->can_update=1;
                    }
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
        if($this->current_step==1)
        {
            $this->genders=Gendermaster::select('id','gender','gender_shortform')->where('is_active',1)->get();
            $this->cast_categories=Castecategory::select('id','caste_category')->where('is_active',1)->get();
            $this->castes=Caste::select('id','caste_name')->where('is_active',1)->where('caste_category_id', $this->caste_category_id)->get();
        }

        if($this->current_step==3)
        {   
            $this->address_types=Addresstype::where('is_active',1)->limit(2)->get();
            $this->countries = Country::select('id','country_name')->get();
            $this->states = State::select('id','state_name')->where('country_id', $this->country_id)->get();
            $this->districts = District::select('id','district_name')->where('state_id', $this->state_id)->get();
            $this->talukas = Taluka::select('id','taluka_name')->where('district_id', $this->district_id)->get();

            if($this->is_same===0)
            {
                $this->countries_2 = Country::select('id','country_name')->get();
                $this->states_2 = State::select('id','state_name')->where('country_id', $this->country_id_2)->get();
                $this->districts_2 = District::select('id','district_name')->where('state_id', $this->state_id_2)->get();
                $this->talukas_2 = Taluka::select('id','taluka_name')->where('district_id', $this->district_id_2)->get();
            }
        }

        if($this->current_step==4)
        {   
            $this->courses=course::select('id','course_name')->get();
            $this->patterns=Pattern::select('id','pattern_name')->where('status',1)->get();
            $this->course_classes=Courseclass::select('id','course_id','classyear_id')->where('course_id', $this->course_id)->get();
        }

        if($this->current_step==5)
        {
            $this->pre_eductions=Studentpreviousexam::where('student_id',Auth::guard('student')->user()->id)->get();
            $this->educationalcourses=Educationalcourse::select('id','course_name')->where('is_active',1)->get();
            $this->boarduniversities=Boarduniversity::select('id','boarduniversity_name')->where('is_active',1)->get();
            $this->passoutyears=Previousyear::select('id','year_name')->where('is_active',1)->get();
            $this->passoutmonths=Month::select('id','month_name')->where('is_active',1)->get();
            $this->grades=Gradepoint::select('id','grade_name')->where('is_active',1)->get();
        }
        if($this->current_step==6)
        {
            $this->pre_eductions=Studentpreviousexam::where('student_id',Auth::guard('student')->user()->id)->get();
        }


        if(is_numeric($this->obtained_marks) >0  && is_numeric($this->total_marks) >0)
        {
            $this->percentage =number_format(( $this->obtained_marks/$this->total_marks  )*100 ,2);
        }

        return view('livewire.student.profile.multi-step-student-profile')->extends('layouts.student')->section('student');
    }
}
