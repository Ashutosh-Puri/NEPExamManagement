<?php

namespace App\Livewire\Faculty\Faculty;

use App\Models\Role;
use App\Models\College;
use App\Models\Faculty;
use Livewire\Component;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Prefixmaster;
use Livewire\WithPagination;
use App\Models\Banknamemaster;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Renderless;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Faculty\Faculty\FacultyExport;


class AllFaculty extends Component
{
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'delete'];

    public $search='';
    public $perPage=10;
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;

    #[Locked]
    public $faculty_id;
    #[Locked]
    public $delete_id;
    #[Locked]
    public $mode='all';
    #[Locked]
    public $prefixes;
    #[Locked]
    public $designations;
    #[Locked]
    public $departments;
    #[Locked]
    public $colleges;
    #[Locked]
    public $banknames;

    public $designation_id;
    public $facultybank_id;
    public $department_id;
    public $college_id;
    public $prefix;
    public $faculty_name;
    public $email;
    public $mobile_no;
    public $active;
    public $bank_name;
    public $account_no;
    public $bank_address;
    public $branch_name;
    public $branch_code;
    public $ifsc_code;
    public $micr_code;
    public $account_type;


    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function setmode($mode)
    {
        if($mode=='add')
        {
            $this->resetinput();
        }
        if($mode=='edit')
        {
            $this->resetValidation();
        }
        $this->mode=$mode;
    }

    public function sort_column($column)
    {
        if( $this->sortColumn === $column)
        {
            $this->sortColumnBy=($this->sortColumnBy=="ASC")?"DESC":"ASC";
            return;
        }
        $this->sortColumn=$column;
        $this->sortColumnBy=="ASC";
    }

    protected function rules()
    {
        return [
            'prefix' => ['required',],
            'faculty_name' => ['required', 'string', 'max:255',],
            'email' => ['required', 'email', 'string','unique:faculties,email,'.($this->mode=='edit'? $this->faculty_id :'')],
            'mobile_no' => ['required', 'numeric','digits:10'],
            'designation_id' => ['required',Rule::exists(Designation::class,'id')],
            'department_id' => ['required',Rule::exists(Department::class,'id')],
            'college_id' => ['required',Rule::exists(College::class,'id')],
            'account_no' => ['required', 'numeric','digits_between:8,16','unique:facultybankaccounts,account_no,'.($this->mode=='edit'? $this->facultybank_id :'')],
            'bank_address' => ['required', 'string', 'max:255',],
            'bank_name' => ['required', 'string', 'max:255',],
            'branch_name' => ['required', 'string', 'max:255',],
            'branch_code' => ['required', 'numeric', 'digits:4',],
            'ifsc_code' => ['required', 'string', 'size:11',],
            'micr_code' => ['required', 'numeric', 'digits:9',],
            'account_type' => ['required', 'in:CURRENT,SAVING',],

        ];
    }

    public function messages()
    {
        return [
        'prefix.required' => 'The prefix field is required.',
        'faculty_name.required' => 'The faculty name field is required.',
        'faculty_name.string' => 'The faculty name must be a string.',
        'faculty_name.max' => 'The faculty name must not exceed 255 characters.',
        'email.required' => 'The email field is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'The email address is already taken.',
        'mobile_no.required' => 'The mobile number field is required.',
        'mobile_no.numeric' => 'The mobile number must be numeric.',
        'mobile_no.digits' => 'The mobile number must be 10 digits.',
        'designation_id.required' => 'The role field is required.',
        'designation_id.exists' => 'The selected role is invalid.',
        'department_id.required' => 'The department field is required.',
        'department_id.exists' => 'The selected department is invalid.',
        'college_id.required' => 'The college field is required.',
        'college_id.exists' => 'The selected college is invalid.',
        'account_no.required' => 'The account number field is required.',
        'account_no.numeric' => 'The account number must be numeric.',
        'account_no.digits_between' => 'The account number must be between 8 and 16 digits.',
        'account_no.unique' => 'The account number is already taken.',
        'bank_address.required' => 'The bank address field is required.',
        'bank_address.string' => 'The bank address must be a string.',
        'bank_name.required' => 'The bank name field is required.',
        'bank_name.string' => 'The bank name must be a string.',
        'branch_name.required' => 'The branch name field is required.',
        'branch_name.string' => 'The branch name must be a string.',
        'branch_code.required' => 'The branch code field is required.',
        'branch_code.numeric' => 'The branch code must be numeric.',
        'branch_code.digits' => 'The branch code must be 4 digits.',
        'ifsc_code.required' => 'The IFSC code field is required.',
        'ifsc_code.string' => 'The IFSC code must be a string.',
        'ifsc_code.size' => 'The IFSC code must be 11 characters.',
        'micr_code.required' => 'The MICR code field is required.',
        'micr_code.numeric' => 'The MICR code must be numeric.',
        'micr_code.digits' => 'The MICR code must be 9 digits.',
        'account_type.required' => 'The account type field is required.',
        'account_type.in' => 'The account type must be either "CURRENT" or "SAVINGS".',
        ];
    }

    public function resetinput()
    {
        $this->reset([
            "prefix",
            "faculty_name",
            "email",
            "mobile_no",
            "designation_id",
            "department_id",
            "college_id",
            "bank_name",
            "account_no",
            "bank_address",
            "branch_name",
            "branch_code",
            "ifsc_code",
            "micr_code",
            "account_type",
        ]);

    }

    #[Renderless]
    public function export()
    {
        try
        {
            $filename="Faculty_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new FacultyExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new FacultyExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new FacultyExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Faculties Data Exported Successfully !!');

            return $response;
        }
        catch (Exception $e)
        {
            Log::error($e);

            $this->dispatch('alert',type:'error',message:'Failed To Export Faculties Data !!');
        }
    }

    public function save()
    {
        DB::beginTransaction();

        try {
            $this->validate();

            $faculty = Faculty::create([
                'prefix' => $this->prefix,
                'faculty_name' => $this->faculty_name,
                'email' => $this->email,
                'mobile_no' => $this->mobile_no,
                'designation_id' => $this->designation_id,
                'department_id' => $this->department_id,
                'college_id' => $this->college_id,
            ]);

            $faculty->facultybankaccount()->create([
                'account_no' => $this->account_no,
                'bank_address' => $this->bank_address,
                'bank_name' => $this->bank_name,
                'branch_name' => $this->branch_name,
                'branch_code' => $this->branch_code,
                'ifsc_code' => $this->ifsc_code,
                'micr_code' => $this->micr_code,
                'account_type' => $this->account_type,
            ]);

            DB::commit();

            $this->dispatch('alert', type: 'success', message: 'Faculty Registered Successfully !!');
            $this->setmode('all');
            $this->resetinput();
        } catch (\Exception $e) {
            DB::rollback();

            $this->dispatch('alert',type:'error',message:'Failed To Register Faculty. Please try again !!');

        }
    }

    public function edit(Faculty $faculty)
    {
        if ($faculty)
        {
            $this->faculty_id = $faculty->id;
            $this->prefix= $faculty->prefix;
            $this->faculty_name= $faculty->faculty_name;
            $this->email= $faculty->email;
            $this->mobile_no= $faculty->mobile_no;
            $this->designation_id= $faculty->designation_id;
            $this->department_id= $faculty->department_id;
            $this->college_id= $faculty->college_id;

            $bankdetails = $faculty->facultybankaccount()->first();

            if($bankdetails){
                $this->facultybank_id= $bankdetails->id;
                $this->bank_name= $bankdetails->bank_name;
                $this->account_no=$bankdetails->account_no;
                $this->bank_address= $bankdetails->bank_address;
                $this->branch_name= $bankdetails->branch_name;
                $this->branch_code= $bankdetails->branch_code;
                $this->ifsc_code= $bankdetails->ifsc_code;
                $this->micr_code= $bankdetails->micr_code;
                $this->account_type= $bankdetails->account_type;
            }else{
                $this->dispatch('alert',type:'error',message:'Bank Details Not Found');
            }
            $this->setmode('edit');
        }else{
            $this->dispatch('alert',type:'error',message:'Something Went Wrong !!');
        }
    }

    public function update(Faculty $faculty)
    {
        DB::beginTransaction();

        try {
            $this->validate();

            if ($faculty) {
                $faculty->prefix = $this->prefix;
                $faculty->faculty_name = $this->faculty_name;
                $faculty->email = $this->email;
                $faculty->mobile_no = $this->mobile_no;
                $faculty->designation_id = $this->designation_id;
                $faculty->department_id = $this->department_id;
                $faculty->college_id = $this->college_id;
                $faculty->save();

                $faculty->facultybankaccount()->updateOrCreate([], [
                    'account_no' => $this->account_no,
                    'bank_address' => $this->bank_address,
                    'bank_name' => $this->bank_name,
                    'branch_name' => $this->branch_name,
                    'branch_code' => $this->branch_code,
                    'ifsc_code' => $this->ifsc_code,
                    'micr_code' => $this->micr_code,
                    'account_type' => $this->account_type,
                ]);

                DB::commit();

                $this->dispatch('alert', type: 'success', message: 'Faculty Updated Successfully !!');
                $this->setmode('all');
                $this->resetinput();
            }
        } catch (\Exception $e) {
            DB::rollback();

            $this->dispatch('alert', type: 'error', message: 'Failed To Update Faculty Please try again !!');

        }
    }

    public function delete()
    {
        try {
            $faculty = Faculty::withTrashed()->find($this->delete_id);

            if ($faculty) {
                if ($faculty->profile_photo_path && File::exists($faculty->profile_photo_path)) {
                    File::delete($faculty->profile_photo_path);
                }

                $faculty->forceDelete();

                $faculty->facultybankaccount()->delete();

                $this->delete_id = null;
                $this->dispatch('alert',type:'success',message:'Faculty Deleted Successfully !!');
            } else {
                $this->dispatch('alert',type:'error',message:'Faculty not found.');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1451) {
                $this->dispatch('alert',type:'error',message:'This record is associated with another data. You cannot delete it !!');
            } else {
                $this->dispatch('alert',type:'error',message:'Failed To Delete Faculty Data !!');
            }
        }
    }

    public function softdelete(Faculty $faculty)
    {
        DB::beginTransaction();

        try
        {
            $faculty->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Faculty Deleted Successfully !!');

        }
        catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Faculty !!');
        }
    }

    public function restore($faculty_id)
    {
        DB::beginTransaction();

        try
        {
            $faculty = Faculty::withTrashed()->findOrFail($faculty_id);

            $faculty->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Faculty Restored Successfully !!');

        }
        catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Faculty Not Found !!');
        }
    }

    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }

    public function view(Faculty $faculty)
    {
        if ($faculty)
        {
            $this->prefix= $faculty->prefix;
            $this->faculty_name= $faculty->faculty_name;
            $this->email= $faculty->email;
            $this->mobile_no= $faculty->mobile_no;
            $this->department_id = isset($faculty->department->dept_name) ? $faculty->department->dept_name : '';
            $this->designation_id = isset($faculty->designation->designation_name) ? $faculty->designation->designation_name : '';
            $this->college_id = isset($faculty->college->college_name) ? $faculty->college->college_name : '';

            $bankdetails = $faculty->facultybankaccount()->first();

            if($bankdetails){
                $this->facultybank_id= $bankdetails->id;
                $this->bank_name= $bankdetails->bank_name;
                $this->account_no=$bankdetails->account_no;
                $this->bank_address= $bankdetails->bank_address;
                $this->branch_name= $bankdetails->branch_name;
                $this->branch_code= $bankdetails->branch_code;
                $this->ifsc_code=$bankdetails->ifsc_code;
                $this->micr_code= $bankdetails->micr_code;
                $this->account_type = $bankdetails->account_type;
            }else{
                $this->dispatch('alert',type:'error',message:'Bank Details Not Found');
            }
            $this->setmode('view');
        }else{
            $this->dispatch('alert',type:'error',message:'Something Went Wrong !!');
        }
    }

    public function changestatus(Faculty $faculty)
    {
        DB::beginTransaction();

        try {

            $faculty->active = $faculty->active == 0 ? 1 : 0;

            $faculty->update();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Faculty Status Updated Successfully !!');

        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Update Faculty Status !!');

        }
    }

    public function render()
    {
        $faculties=collect([]);

        if($this->mode !== 'all'){
            $this->prefixes = Prefixmaster::select('id','prefix','prefix_shortform')->where('is_active',1)->get();
            $this->banknames = Banknamemaster::select('id','bank_name','bank_shortform')->where('is_active',1)->get();
            $this->designations= Designation::select('id','designation_name',)->get();
            $this->departments= Department::select('id','dept_name',)->where('status',1)->get();
            $this->colleges= College::select('id','college_name',)->where('status',1)->get();
        }
        $authFaculty = auth('faculty')->user();
        $faculties = Faculty::with(['designation', 'department', 'college'])->when($this->search, function($query, $search){
            $query->search($search);
        })->orderBy($this->sortColumn, $this->sortColumnBy)->withTrashed()->paginate($this->perPage);
        return view('livewire.faculty.faculty.all-faculty',compact(['faculties','authFaculty']))->extends('layouts.faculty')->section('faculty');
    }
}
