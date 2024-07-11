<?php

namespace App\Livewire\User\AdmissionData;

use Excel;
use App\Models\Subject;
use Livewire\Component;
use App\Models\Classview;
use App\Models\Patternclass;
use Livewire\WithFileUploads;
use App\Models\Subjectvertical;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Imports\User\AdmissionData\AdmissionDataImport;

class ImportAdmissionData extends Component
{   
    # By Ashutosh
    use WithFileUploads;

    protected $listeners = ['subjectCategoryChanged'];
    protected $subject_ids=[];
    public $importfile;
    public $patternclass_id;
    public $subject_id;
    public $subjectvertical_id;

    #[Locked]
    public $subjects=[];
    #[Locked]
    public $patternclasses=[];
    #[Locked]
    public $subject_verticals=[];
    
    public function mount()
    {   
        $this->patternclasses = Classview::select('id','classyear_name', 'course_name', 'pattern_name')->where('status',1)->get();
        $this->subject_verticals = Subjectvertical::whereIn('is_active', [1, 2])->pluck('subject_vertical', 'id');
    }
    
    protected function rules()
    {
        return [
            'importfile' =>['required','file','mimes:csv,xlsx'],
        ];
    }
    
    public function messages()
    {   
        return [
            'importfile.required'=>"Please Select csv, xlsx File To Import",
            'importfile.file'=>"Please Select Valid File",
            'importfile.mimes'=>"Only csv, xlsx File Are Allowed",
        ];
    }
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function inputreset()
    {   
        $this->reset(['patternclass_id','subject_id','subjectvertical_id','importfile']);
    }

    public function import()
    {    
        $this->validate();

        set_time_limit(600); // 600 sec  // 10 min
        ini_set('memory_limit', '1024M'); //  1GB

        $filters = [];

        if ($this->patternclass_id && $this->subjectvertical_id && $this->subject_id) 
        {
            $filters = [];
            $filters['patternclass_id'] = $this->patternclass_id;
        
            $patternClass = Patternclass::find($this->patternclass_id);
        
            if ($patternClass) {
                if ($this->subjectvertical_id && $this->subject_id) {
                    $filters['subject_ids'] = $patternClass->subjects()->where('subjectvertical_id', $this->subjectvertical_id)->where('id', $this->subject_id)->pluck('id')->toArray();
                }
            }

            if(empty($filters['subject_ids']))
            {
                $this->dispatch('alert',type:'info',message:'Admission Data Not Found for Current Filters !!');
                return false;
            }
        }
        elseif ($this->patternclass_id && $this->subjectvertical_id) 
        {
            $filters = [];
            $filters['patternclass_id'] = $this->patternclass_id;
        
            $patternClass = Patternclass::find($this->patternclass_id);
        
            if ($patternClass) {
                if ($this->subjectvertical_id) {
                    $filters['subject_ids'] = $patternClass->subjects()->where('subjectvertical_id', $this->subjectvertical_id)->pluck('id')->toArray();
                }
            }

            if(empty($filters['subject_ids']))
            {
                $this->dispatch('alert',type:'info',message:'Admission Data Not Found for Current Filters !!');
                return false;
            }
        }
        elseif ($this->patternclass_id) 
        {
            $filters = [];
            $filters['patternclass_id'] = $this->patternclass_id;

            $patternClass = Patternclass::find($this->patternclass_id);

            if ($patternClass) {
                $filters['subject_ids'] = $patternClass->subjects()->pluck('id')->toArray();
            }
            
            if(empty($filters['subject_ids']))
            {
                $this->dispatch('alert',type:'info',message:'Admission Data Not Found for Current Filters !!');
                return false;
            }
        }

        if ($this->importfile) 
        {   
            DB::beginTransaction();
            try 
            {
                $import = new AdmissionDataImport($filters);
                Excel::import($import, $this->importfile->getRealPath());

                DB::commit();
                $this->dispatch('refreshAllAdmissionData');
                $this->dispatch('alert',type:'success',message:'Admission Data Imported Successfully !!');
            } 
            catch (\Exception $e) 
            {
                DB::rollBack();
                \Log::error($e);
                $this->dispatch('alert',type:'error',message:'Failed To Import Admission Data !!');
            }
        }
        else 
        {
            $this->dispatch('alert',type:'info',message:'Please choose a file to Import !!');
        }
    }

    public function render()
    {   
        $subjects = Subject::where('status', 1)
        ->when($this->patternclass_id, function ($query) { return $query->where('patternclass_id', $this->patternclass_id); })
        ->when($this->subjectvertical_id, function ($query) {  return $query->where('subjectvertical_id', $this->subjectvertical_id); })
        ->pluck('subject_name', 'id');

        return view('livewire.user.admission-data.import-admission-data',compact('subjects'));
    }
}
