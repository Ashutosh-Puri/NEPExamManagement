<?php

namespace App\Livewire\User\AdmissionData;

use Excel;
use App\Models\User;
use App\Models\College;
use App\Models\Subject;
use Livewire\Component;
use App\Models\Classview;
use App\Models\Department;
use App\Models\Academicyear;
use App\Models\Patternclass;
use Livewire\WithPagination;
use App\Models\Admissiondata;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;
use App\Exports\User\AdmissionData\AdmissionDataExport;
use App\Imports\User\AdmissionData\AdmissionDataImport;
use App\Policies\User\AdmissionData\AdmissionDataPolicy;

class AllAdmissionData extends Component
{  
    # By Ashutosh
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'forcedelete','refreshAllAdmissionData' => '$refresh'];
    
    public $search='';
    public $perPage=10;
    public $sortColumn="stud_name";
    public $sortColumnBy="ASC";
    public $ext;
    
    #[Locked] 
    public  $delete_id;
    #[Locked] 
    public $edit_id;
    #[Locked] 
    public $mode='all';
    #[Locked] 
    public $pattern_classes=[];
    #[Locked] 
    public $subjects=[];

    public $patternclass_id;
    public $subject_id;
    public $stud_name;
    public $memid;


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
        $this->mode=$mode;

        $this->resetValidation();
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
            'patternclass_id' => ['required', 'integer', Rule::exists('pattern_classes', 'id')],
            'subject_id' => ['required', 'integer', Rule::exists('subjects', 'id')],
            'stud_name' => ['required', 'string','max:100'],
            'memid' => ['required', 'integer'],
            // 'memid' => ['required', 'integer','digits_between:1,11',Rule::unique('admissiondatas', 'memid')->ignore($this->edit_id, 'id')],
        ];
    }

    public function messages()
    {   
        return [
            'patternclass_id.required' => 'The Pattern Class field is required.',
            'patternclass_id.integer' => 'The Pattern Class must be a number.',
            'patternclass_id.exists' => 'The selected Pattern Class is invalid.',
            'subject_id.required' => 'The Subject field is required.',
            'subject_id.integer' => 'The Subject must be a number.',
            'subject_id.exists' => 'The selected Subject is invalid.',
            'stud_name.required' => 'The Student Name field is required.',
            'stud_name.string' => 'The Student Name must be a string.',
            'stud_name.max' => 'The Student Name may not be greater than :max characters.',
            'memid.required' => 'The Member ID field is required.',
            'memid.integer' => 'The  Member ID must be an integer.',
            'memid.digits_between' => 'The Member ID must be between :min and :max digits.',
        ];
    }

    protected function resetinput()
    {
        $this->reset([
            'patternclass_id',
            'subject_id',
            'stud_name',
            'memid',
            'edit_id',
        ]);
    }


    #[Renderless]
    public function export()
    {   
        try 
        {
            set_time_limit(600);
            ini_set('memory_limit', '1024M');

            $filename="Admission_Data_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new AdmissionDataExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new AdmissionDataExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new AdmissionDataExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Admission Data Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Admission Data !!');
        }

    }


    public function add()
    {   
        if (!app(AdmissionDataPolicy::class)->create(auth()->user())) {
            return abort(403);
        }

        $this->validate();

        $academicYear = Academicyear::where('active', 1)->first();
        if (!$academicYear) {
            $this->dispatch('alert',type:'error',message:'Academic Year Not Found !!');
            return;
        }

        DB::beginTransaction();

        try 
        {
            Admissiondata::create([
                'user_id' => Auth::guard('user')->user()->id,
                'college_id' => Auth::guard('user')->user()->college_id,
                'academicyear_id' => $academicYear->id,
                'patternclass_id' => $this->patternclass_id,
                'subject_id' => $this->subject_id,
                'stud_name' => $this->stud_name,
                'memid' => $this->memid,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Admission Data Entry Created Successfully !!');

        } catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Admission Data Entry !!');
        }
    }


    public function edit(Admissiondata $admission_data)
    {   
        if (!app(AdmissionDataPolicy::class)->edit(auth()->user(),$admission_data)) {
            return abort(403);
        }

        $this->resetinput();
        $this->edit_id=$admission_data->id;
        $this->patternclass_id=$admission_data->patternclass_id;
        $this->subject_id=$admission_data->subject_id;
        $this->stud_name=$admission_data->stud_name;
        $this->memid=$admission_data->memid;
        $this->mode='edit';
    }

    public function update(Admissiondata $admission_data)
    {   
        if (!app(AdmissionDataPolicy::class)->update(auth()->user(),$admission_data)) {
            return abort(403);
        }
        $this->validate();

        DB::beginTransaction();

        try 
        {

            $academic_year = Academicyear::where('active', 1)->first();

            if (!$academic_year) 
            {
                $this->dispatch('alert',type:'error',message:'Academic Year Not Found !!');
            }

            $admission_data->update([
                'user_id' => Auth::guard('user')->user()->id,
                'college_id' => Auth::guard('user')->user()->college_id,
                'academicyear_id' => $academic_year->id,
                'patternclass_id' => $this->patternclass_id,
                'subject_id' => $this->subject_id,
                'stud_name' => $this->stud_name,
                'memid' => $this->memid,
            ]);

            DB::commit();

            $this->resetinput();

            $this->mode='all';

            $this->dispatch('alert',type:'success',message:'Admission Data Entry Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Admission Data Entry !!');
        }
    }


    public function delete(Admissiondata $admission_data)
    {   
        if (!app(AdmissionDataPolicy::class)->delete(auth()->user(),$admission_data)) {
            return abort(403);
        }
        
        DB::beginTransaction();

        try 
        {
            $admission_data->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Admission Data Entry Soft Deleted Successfully !!');

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Admission Data Entry !!');
        }
    }
    
    public function restore($admission_data_id)
    {   
       
        DB::beginTransaction();

        try
        {
            $admission_data = Admissiondata::withTrashed()->findOrFail($admission_data_id);

            if (!app(AdmissionDataPolicy::class)->restore(auth()->user(),$admission_data)) {
                return abort(403);
            }

            $admission_data->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Admission Data Entry Restored Successfully !!');

        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Admission Data Entry !!');
        }
    }


    public function deleteconfirmation($admission_data_id)
    {   
        $this->delete_id=$admission_data_id;

        $this->dispatch('delete-confirmation');
    }
    
    
    public function forcedelete()
    {   

        DB::beginTransaction();

        try 
        {
            $admission_data = Admissiondata::withTrashed()->find($this->delete_id);

            if (!app(AdmissionDataPolicy::class)->forcedelete(auth()->user(),$admission_data)) {
                return abort(403);
            }

            $admission_data->forceDelete();
            $this->delete_id=null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Admission Data Entry Deleted Successfully !!');

        } 
        catch (\Illuminate\Database\QueryException $e) 
        {
            DB::rollBack();

            if ($e->errorInfo[1] == 1451) 
            {
                $this->dispatch('alert',type:'info',message:'This Record Is Associated With Another Data. You Cannot Delete It !!');
            } 
            else
            {   
                $this->dispatch('alert',type:'error',message:'Failed To Delete Admission Data Entry !!');
            }
        }
    }

    public function render()
    {   
        $admission_datas = collect([]);

        if ($this->mode == 'all' || $this->mode == 'import') 
        {   

            $admission_datas = Admissiondata::where('college_id', Auth::guard('user')->user()->college_id)
            ->with(['academicyear:year_name,id', 'patternclass.courseclass.course:course_name,id', 'patternclass.pattern:pattern_name,id','patternclass.courseclass.classyear:classyear_name,id', 'subject:subject_name,id'])
            ->when($this->search, function ($query, $search) { $query->search($search); })
            ->withTrashed()
            ->orderBy($this->sortColumn, $this->sortColumnBy)
            ->paginate($this->perPage);

        }elseif($this->mode !== 'import')
        {   
            $this->pattern_classes = Classview::select('id','classyear_name', 'course_name', 'pattern_name')->where('status',1)->get();
            
            if($this->patternclass_id)
            {
                $this->subjects = Subject::where('status', 1)->where('patternclass_id', $this->patternclass_id)->pluck('subject_name', 'id');
            }
        }

        return view('livewire.user.admission-data.all-admission-data', compact('admission_datas'))->extends('layouts.user')->section('user');
    }
}
