<?php

namespace App\Livewire\Faculty\UpdateProfile;

use App\Models\College;
use App\Models\Faculty;
use Livewire\Component;
use App\Models\Roletype;
use App\Models\Department;
use App\Models\Gendermaster;
use App\Models\Castecategory;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use App\Models\Facultybankaccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class UpdateProfile extends Component
{
    use WithFileUploads;

    public $faculty_id;
    public $prefix;
    public $faculty_name;
    public $email;
    public $mobile_no;
    public $college_id;
    public $department_id;
    public $colleges;
    public $designation_id;

    public $bank_name;
    public $account_no;
    public $bank_address;
    public $branch_name;
    public $branch_code;
    public $ifsc_code;
    public $micr_code;
    public $account_type;
    public $profile_photo_path_old;

    public $faculty;
    public $date_of_birth;
    public $gender;
    public $category;
    public $pan;
    public $current_address;
    public $permanant_address;
    public $profile_photo_path;
    public $unipune_id;
    public $qualification;

    public $cast_categories;
    public $roles;
    public $genders;


    protected function rules()
    {
        $rules = [
            'date_of_birth' => ['required', 'date',],
            // 'gender' => ['required', Rule::in(Gendermaster::pluck('gender_shortform'))],
            'gender' => ['required',  Rule::exists(Gendermaster::class,'id')],
            'pan' => ['required', 'string', 'size:10', Rule::unique('faculties', 'pan')->ignore($this->faculty_id, 'id')],
            'unipune_id' => ['required', 'integer', Rule::unique('faculties', 'unipune_id')->ignore($this->faculty_id, 'id')],
            // 'category' => ['required', Rule::in(Castecategory::pluck('caste_category'))],
            'category' => ['required', Rule::exists(Castecategory::class,'id')],
            'permanant_address' => ['required'],
            'current_address' => ['required'],
        ];

        if ($this->faculty_id) {
            $faculty = Faculty::find($this->faculty_id);
            if (!$faculty || !file_exists($faculty->profile_photo_path)) {
                $rules['profile_photo_path'] = ['required', 'file', 'max:150', 'mimes:png,jpg,jpeg,pdf'];
            }
        }
        return $rules;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function messages()
    {
        return [
            'date_of_birth.required' => 'Please provide your date of birth.',
            'date_of_birth.date' => 'Invalid date format for date of birth.',
            'gender.required' => 'Please select your gender.',
            'pan.required' => 'Please provide your PAN number.',
            'pan.string' => 'Invalid PAN format.',
            'pan.size' => 'The PAN number must be exactly 10 characters long.',
            'pan.unique' => 'The PAN number has already been taken.',
            'unipune_id.required' => 'Please provide your university ID.',
            'unipune_id.integer' => 'The university ID must be an integer.',
            'unipune_id.unique' => 'The university ID has already been taken.',
            'category.required' => 'Please select your category.',
            'permanant_address.required' => 'Please provide your permanent address.',
            'current_address.required' => 'Please provide your current address.',
            'profile_photo_path.required' => 'The profile photo is required.',
            'profile_photo_path.file' => 'The profile photo must be a file.',
            'profile_photo_path.max' => 'The profile photo must be less than :max kilobytes.',
            'profile_photo_path.mimes' => 'The profile photo must be a PNG, JPG, JPEG, or PDF file.',
        ];
    }

    public function show(Faculty $faculty)
    {
        if($faculty){
            $this->faculty_id = $faculty->id;
            $this->prefix = $faculty->prefix;
            $this->faculty_name = $faculty->faculty_name;
            $this->email = $faculty->email;
            $this->mobile_no = $faculty->mobile_no;
            $this->date_of_birth = $faculty->date_of_birth;
            $this->gender = $faculty->gender;
            $this->pan = $faculty->pan;
            $this->category = $faculty->category;
            $this->current_address = $faculty->current_address;
            $this->permanant_address = $faculty->permanant_address;
            $this->college_id = isset($faculty->college->college_name) ? $faculty->college->college_name : '';
            $this->qualification = $faculty->qualification;
            $this->unipune_id = $faculty->unipune_id;
            $this->profile_photo_path_old = $faculty->profile_photo_path;
            $this->department_id = isset($faculty->department->dept_name) ? $faculty->department->dept_name : '';
            $this->designation_id = isset($faculty->designation->designation_name) ? $faculty->designation->designation_name : '';

            $bankdetails = $faculty->facultybankaccount()->first();
            if($bankdetails){
                $this->bank_name= $bankdetails->bank_name;
                $this->account_no= $bankdetails->account_no;
                $this->bank_address= $bankdetails->bank_address;
                $this->branch_name= $bankdetails->branch_name;
                $this->branch_code= $bankdetails->branch_code;
                $this->ifsc_code= $bankdetails->ifsc_code;
                $this->micr_code= $bankdetails->micr_code;
                $this->account_type= $bankdetails->account_type;
            }else{
                $this->dispatch('alert',type:'info',message:'Bank Details Not Found');
            }
        }
    }

    public function updateProfile(Faculty $faculty)
    {
        $validatedData = $this->validate();

        $gender = Gendermaster::find($this->gender);
        $category = Castecategory::find($this->category);

        $validatedData['gender'] = $gender->gender_shortform;

        $validatedData['category'] = $category->caste_category;

        DB::beginTransaction();

        try {

            $dataToUpdate = [
                'date_of_birth' => $validatedData['date_of_birth'],
                'gender' => $validatedData['gender'],
                'pan' => $validatedData['pan'],
                'unipune_id' => $validatedData['unipune_id'],
                'category' => $validatedData['category'],
                'current_address' => $validatedData['current_address'],
                'permanant_address' => $validatedData['permanant_address'],
                // 'qualification' => $validatedData['qualification'],
            ];

            if ($this->profile_photo_path) {
                if ($faculty->profile_photo_path) {
                    File::delete(public_path($faculty->profile_photo_path));
                }

                $path = 'faculty/profile/photo/';
                $fileName = 'faculty-' . time() . '.' . $this->profile_photo_path->getClientOriginalExtension();
                $this->profile_photo_path->storeAs($path, $fileName, 'public');
                $dataToUpdate['profile_photo_path'] = 'storage/' . $path . $fileName;
            }

            $faculty->update($dataToUpdate);

            DB::commit();

            $this->dispatch('alert', type: 'success', message: 'Faculty Profile Updated Successfully');
            $this->render();

        } catch (\Exception $e) {

            DB::rollback();

            Log::error($e);

            $this->dispatch('alert', type: 'error', message: 'Failed To Update Profile Please Try Again !!');
        }
    }

    public function mount()
    {
        $faculty = Auth::guard('faculty')->user();
        $this->show($faculty);
        $this->genders = Gendermaster::where('is_active',1)->pluck('gender','id');
        $this->cast_categories = Castecategory::where('is_active',1)->pluck('caste_category','id');
    }

    public function render()
    {
        return view('livewire.faculty.update-profile.update-profile')->extends('layouts.faculty')->section('faculty');
    }
}
