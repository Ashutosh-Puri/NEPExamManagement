<?php

namespace App\Livewire\User\PatternClass;
use Excel;
use App\Models\Pattern;
use Livewire\Component;
use App\Models\Courseclass;
use App\Models\Patternclass;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Exports\User\PatternClass\PatternClassExport;

class AllPatternClass extends Component
{   
    # By Ashutosh
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    #[Locked] 
    public $delete_id;
    #[Locked] 
    public $mode='all';
    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;

    public $class_id;
    public $pattern_id;
    public $status;
    public $sem1_total_marks;
    public $sem2_total_marks;
    public $sem1_credits;
    public $sem2_credits;
    public $credit;
    public $sem1_totalnosubjects;
    public $sem2_totalnosubjects;
    #[Locked] 
    public $patterns;
    #[Locked] 
    public $course_classes;
    #[Locked] 
    public $edit_id;


    protected function rules()
    {
        return [
            'sem1_total_marks' => ['required', 'integer','digits_between:1,4'],
            'sem2_total_marks' => ['required', 'integer','digits_between:1,4'],
            'sem1_credits' => ['required', 'integer','digits_between:1,3'],
            'sem2_credits' => ['required', 'integer','digits_between:1,3'],
            'credit' => ['required', 'integer','digits_between:1,3'],
            'sem1_totalnosubjects' => ['required', 'integer','digits_between:1,3'],
            'sem2_totalnosubjects' => ['required', 'integer','digits_between:1,3'],
            'class_id' => ['required', 'integer', Rule::exists('course_classes', 'id')],
            'pattern_id' => ['required', 'integer', Rule::exists('patterns', 'id')],
        ];
    }

    public function messages()
    {   
        $messages = [
            'sem1_total_marks.required' => 'Semester 1 total marks is required.',
            'sem1_total_marks.integer' => 'Semester 1 total marks must be an integer.',
            'sem1_total_marks.digits_between' => 'Semester 1 total marks must have between :min and :max digits.',
            'sem2_total_marks.required' => 'Semester 2 total marks is required.',
            'sem2_total_marks.integer' => 'Semester 2 total marks must be an integer.',
            'sem2_total_marks.digits_between' => 'Semester 2 total marks must have between :min and :max digits.',
            'sem1_credits.required' => 'Semester 1 credits is required.',
            'sem1_credits.integer' => 'Semester 1 credits must be an integer.',
            'sem1_credits.digits_between' => 'Semester 1 credits must have between :min and :max digits.',
            'sem2_credits.required' => 'Semester 2 credits is required.',
            'sem2_credits.integer' => 'Semester 2 credits must be an integer.',
            'sem2_credits.digits_between' => 'Semester 2 credits must have between :min and :max digits.',
            'credit.required' => 'Credit is required.',
            'credit.integer' => 'Credit must be an integer.',
            'credit.digits_between' => 'Credit must have between :min and :max digits.',
            'sem1_totalnosubjects.required' => 'Semester 1 total number of subjects is required.',
            'sem1_totalnosubjects.integer' => 'Semester 1 total number of subjects must be an integer.',
            'sem1_totalnosubjects.digits_between' => 'Semester 1 total number of subjects must have between :min and :max digits.',
            'sem2_totalnosubjects.required' => 'Semester 2 total number of subjects is required.',
            'sem2_totalnosubjects.integer' => 'Semester 2 total number of subjects must be an integer.',
            'sem2_totalnosubjects.digits_between' => 'Semester 2 total number of subjects must have between :min and :max digits.',
            'class_id.required' => 'Course Class is required.',
            'class_id.integer' => 'Course Class must be a integer value.',
            'class_id.exists' => 'The selected Course Class does not exist.',
            'pattern_id.required' => 'Pattern is required.',
            'pattern_id.integer' => 'Pattern must be a integer value.',
            'pattern_id.exists' => 'The selected pattern does not exist.',
        ];
        
        return $messages;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function resetinput()
    {
        $this->reset(
            [
                'edit_id',
                'class_id',
                'pattern_id',
                'status',
                'sem1_total_marks',
                'sem2_total_marks',
                'sem1_credits',
                'sem2_credits',
                'credit',
                'sem1_totalnosubjects',
                'sem2_totalnosubjects',
            ]
        );
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

    public function updatedSearch()
    {
        $this->resetPage();
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

    #[Renderless]
    public function export()
    {   
        try 
        {
            set_time_limit(600);
            ini_set('memory_limit', '1024M');

            $filename="Pattern_Class_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new PatternClassExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new PatternClassExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new PatternClassExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Pattern Class Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Pattern Class !!');
        }
    }

    public function add()
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {   
            $pattern_class =  new Patternclass;
            $pattern_class->create([
                 'class_id' => $this->class_id,
                 'pattern_id' => $this->pattern_id,
                 'status' => $this->status==true?0:1,
                 'sem1_total_marks' => $this->sem1_total_marks,
                 'sem2_total_marks' => $this->sem2_total_marks,
                 'sem1_credits' => $this->sem1_credits,
                 'sem2_credits' => $this->sem2_credits,
                 'credit' => $this->credit,
                 'sem1_totalnosubjects' => $this->sem1_totalnosubjects,
                 'sem2_totalnosubjects' => $this->sem2_totalnosubjects,
             ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Pattern Class Created Successfully !!');

        } catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Pattern Class !!');
        }
    }


    public function edit(Patternclass $pattern_class)
    {   
        $this->resetinput();
        $this->edit_id=$pattern_class->id;
        $this->class_id=$pattern_class->class_id;
        $this->pattern_id=$pattern_class->pattern_id;
        $this->status=$pattern_class->status==1?0:true;
        $this->sem1_total_marks=$pattern_class->sem1_total_marks;
        $this->sem2_total_marks=$pattern_class->sem2_total_marks;
        $this->sem1_credits=$pattern_class->sem1_credits;
        $this->sem2_credits=$pattern_class->sem2_credits;
        $this->credit=$pattern_class->credit;
        $this->sem1_totalnosubjects=$pattern_class->sem1_totalnosubjects;
        $this->sem2_totalnosubjects=$pattern_class->sem2_totalnosubjects;
        $this->mode='edit';
    }

    public function update(Patternclass $pattern_class)
    {
        $this->validate();
        
        DB::beginTransaction();

        try 
        {   
            $pattern_class->update([
                'class_id' => $this->class_id,
                'pattern_id' => $this->pattern_id,
                'status' => $this->status==true?0:1,
                'sem1_total_marks' => $this->sem1_total_marks,
                'sem2_total_marks' => $this->sem2_total_marks,
                'sem1_credits' => $this->sem1_credits,
                'sem2_credits' => $this->sem2_credits,
                'credit' => $this->credit,
                'sem1_totalnosubjects' => $this->sem1_totalnosubjects,
                'sem2_totalnosubjects' => $this->sem2_totalnosubjects,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Pattern Class Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Pattern Class !!');
        }
    }

    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }


    public function delete(Patternclass $pattern_class)
    {  
        DB::beginTransaction();

        try
        {   
            $pattern_class->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Pattern Class Soft Deleted Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Pattern Class !!');
        }
    }
    
    public function restore($id)
    {   
        DB::beginTransaction();

        try
        {   
            $pattern_class = Patternclass::withTrashed()->find($id);
            $pattern_class->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Pattern Class Restored Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Pattern Class !!');
        }
    }

    public function forcedelete()
    {   
        DB::beginTransaction();

        try 
        {
            $pattern_class = Patternclass::withTrashed()->find($this->delete_id);
            $pattern_class->forceDelete();
            $this->delete_id=null;
            DB::commit();
            $this->dispatch('alert',type:'success',message:'Pattern Class Deleted Successfully !!');
            
        } catch (\Illuminate\Database\QueryException $e) {

            DB::rollBack();
            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'error',message:'This record is associated with another data. You cannot delete it !!');
            } 
            else
            {   
                $this->dispatch('alert',type:'error',message:'Failed To Delete Pattern Class !!');
            }
        }
    }

    public function changestatus(Patternclass $pattern_class)
    {
        DB::beginTransaction();

        try 
        {   
            if($pattern_class->status)
            {
                $pattern_class->status=0;
            }
            else
            {
                $pattern_class->status=1;
            }
            $pattern_class->update();

            DB::commit();
            $this->dispatch('alert',type:'success',message:'Status Updated Successfully !!'  );
        }catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Status  !!');
        }
    }


    public function render()
    {   
        if($this->mode!=='all')
        {
            $this->patterns=Pattern::where('status',1)->pluck('pattern_name','id');
            $this->course_classes=Courseclass::select('classyear_id','course_id','id')->with(['course:course_name,id','classyear:classyear_name,id'])->get();
        }

        $pattern_classes=Patternclass::select('id','pattern_id','class_id','sem1_total_marks','sem2_total_marks','credit','sem1_credits','sem2_credits','sem1_totalnosubjects','sem2_totalnosubjects','status','deleted_at')->with('courseclass.course:course_name,id','courseclass.classyear:classyear_name,id','pattern:pattern_name,id')->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.pattern-class.all-pattern-class',compact('pattern_classes'))->extends('layouts.user')->section('user');
    }
}
