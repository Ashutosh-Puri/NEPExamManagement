<?php

namespace App\Livewire\User\ExamFeeCourse;

use Excel;
use App\Models\Course;
use Livewire\Component;
use App\Models\Semester;
use App\Models\Classview;
use App\Models\Courseclass;
use App\Models\Patternclass;
use Livewire\WithPagination;
use App\Models\Examfeecourse;
use App\Models\Examfeemaster;
use App\Models\Applyfeemaster;
use App\Models\Formtypemaster;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Exports\User\ExamFeeCourse\ExamFeeCourseExport;

class AllExamFeeCourse extends Component
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
    public $sortColumn="patternclass_id";
    public $sortColumnBy="ASC";
    public $ext;
    #[Locked] 
    public $fees=[];
    #[Locked] 
    public $active_status=[];
    public $sem;
    public $patternclass_id;
    public $examfees_id;
    public $approve_status;
    #[Locked] 
    public $semesters;
    #[Locked] 
    public $patternclasses;
    #[Locked] 
    public $examfees;
    public $form_type_id;
    public $apply_fee_id;
    #[Locked] 
    public $applyfees;
    #[Locked] 
    public $formtypes;
    #[Locked] 
    public $courses;
    #[Locked] 
    public $courseclasses;
    public $course_id;
    public $course_class_id;
    public $is_sem=0;
    public $is_course=0;
    public $is_course_class=0;
    public $is_ptrn_class=0;
    #[Locked] 
    public $edit_id;


    protected function rules()
    {
        $rules = [
            'form_type_id' => ['required'],
            'apply_fee_id' => [($this->mode == 'edit' ?'nullable' : 'required')],
        ];

        if($this->is_sem)
        {
            $rules['sem'] = ['required', 'integer','digits_between:1,11'];
        }

        if($this->is_ptrn_class)
        {
            $rules['patternclass_id'] = ['required',Rule::exists('pattern_classes', 'id')];
        }

        if($this->is_course)
        {
            $rules['course_id'] = ['required',Rule::exists('courses', 'id')];
        }

        if($this->is_course_class)
        {
            $rules['course_class_id'] = ['required',Rule::exists('course_classes', 'id')];
        }

        if(!empty($this->examfees))
        {   
            foreach ($this->examfees as $fee) {
                $rules["fees.".$fee->id] = ['nullable','integer', 'digits_between:1,11'];
            }
        }

        return $rules;
    }

    public function messages()
    {   
        $messages = [
            'sem.required' => 'The SEM field is required.',
            'sem.integer' => 'The SEM must be an integer.',
            'sem.digits_between' => 'The SEM must be between 1 and 11 digits.',
            'form_type_id.required' => 'The Form Type field is required.',
            'apply_fee_id.required' => 'The Apply Fee field is required.',
            'course_id.required' => 'The Course field is required.',
            'course_id.exists' => 'The selected Course is invalid.',
            'course_class_id.required' => 'The Course Class field is required.',
            'course_class_id.exists' => 'The selected Course Class is invalid.',
        ];
        if(!empty($this->examfees))
        {
            foreach ($this->examfees as $fee) {
                $messages["fees.".$fee->id.".required"] = "The ".$fee->fee_name." Fee field is required.";
                $messages["fees.".$fee->id.".integer"] = "The ".$fee->fee_name."Fee must be an integer.";
                $messages["fees.".$fee->id.".digits_between"] = "The ".$fee->fee_name." Fee must be between :min and :max digits.";

            }   
        }    

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
                'fees',
                'active_status',
                'course_id',
                'course_class_id',
                'patternclass_id',
                'sem',
                'form_type_id',
                'apply_fee_id',
                'is_sem',
                'is_course',
                'is_course_class',
                'is_ptrn_class',
                'patternclass_id',
                'approve_status',
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

            $filename="Exam_Fee_Course_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new ExamFeeCourseExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new ExamFeeCourseExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new ExamFeeCourseExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Exam Fee Course Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Exam Fee Course !!');
        }
    }


    public function add()
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {  

            if ($this->is_course && $this->is_course_class) 
            {
            
                    $courseClass = Courseclass::find($this->course_class_id);
                    $patternClasses = $courseClass->patternclasses->where('status', 1);
                    $examFeeCourses = [];
                    foreach ($patternClasses as $patternclass) {
                        foreach ($this->fees as $key => $fee) {
                            if (isset($key) && $fee !== "" && $fee !== null) {
                                $activeStatus = isset($this->active_status[$key]) ? ($this->active_status[$key] == true ? 0 : 1) : 1;
                                $examFeeCourses[]=[
                                    'examfees_id' => $key,
                                    'fee' => $fee == "" ? 0 : $fee,
                                    'patternclass_id' => $patternclass->id,
                                    'active_status' => $activeStatus,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ];
                            }
                        }
                    }
                    Examfeecourse::insert($examFeeCourses); 
                    $examFeeCourses = [];
                
            }elseif ($this->is_course) 
            {
            
                    $course = Course::with('courseclasses.patternclasses')->find($this->course_id);
                    if ($course) {
                        $courseClasses = $course->courseclasses()->get();
                        $examFeeCourses = [];
                        foreach($courseClasses as $courseClass)
                        {
                            $patternClasses = $courseClass->patternclasses->where('status', 1);
                            foreach ($patternClasses as $patternclass) 
                            {
                                foreach ($this->fees as $key => $fee) 
                                {
                                    if (isset($key) && $fee !== "" && $fee !== null) 
                                    {
                                        $activeStatus = isset($this->active_status[$key]) ? ($this->active_status[$key] == true ? 0 : 1) : 1;
                                        $examFeeCourses[]=[
                                            'examfees_id' => $key,
                                            'fee' => $fee == "" ? 0 : $fee,
                                            'patternclass_id' => $patternclass->id,
                                            'active_status' => $activeStatus,
                                            'created_at' => now(),
                                            'updated_at' => now(),
                                        ];
                                    }
                                }
                            }
                        }
                        Examfeecourse::insert($examFeeCourses); 
                    $examFeeCourses = [];
                    }
            
            }elseif($this->is_sem)
            {
                $examFeeCourses = [];
                foreach ($this->fees as $key => $fee) {
                    if (isset($key) && $fee !== "" && $fee !== null)
                    {
                        $activeStatus = isset($this->active_status[$key]) ? ($this->active_status[$key] == true ? 0 : 1) : 1;
                        $examFeeCourses[]=[
                            'examfees_id' => $key,
                            'fee' => $fee==""?0:$fee,
                            'sem' => $this->sem,
                            'patternclass_id' => $this->patternclass_id,
                            'active_status' =>   $activeStatus,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
                Examfeecourse::insert($examFeeCourses); 
                $examFeeCourses = [];
            }


            $this->resetinput();
            $this->setmode('all');
            
            DB::commit();
            $this->dispatch('alert',type:'success',message:'Exam Fee Course Created Successfully !!');
            
           
        }catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Create Exam Fee Course  !!');
        }
    }


    public function edit(Examfeecourse $examfeecourse)
    {   
        $this->resetinput();
        $this->is_ptrn_class=1;
        $this->edit_id= $examfeecourse->id;
        $this->patternclass_id=$examfeecourse->patternclass_id;
        if(isset($examfeecourse->examfee->form_type_id))
        {
            $this->form_type_id=$examfeecourse->examfee->form_type_id;
        }

        if(isset($examfeecourse->sem) && $examfeecourse->sem!==null)
        {   
            $this->sem=$examfeecourse->sem;
            $this->is_sem=1;
        }else
        {
            $this->is_sem=0;
        }
        
        $examfeecourses=Examfeecourse::where('patternclass_id',$examfeecourse->patternclass_id)->when($examfeecourse->sem, function ($query, $sem) {
            return $query->where('sem', $sem);
        })->get();

        foreach($examfeecourses as $fee)
        {   
            $this->fees[$fee->examfees_id]=$fee->fee;
            $this->active_status[$fee->examfees_id]=$fee->active_status==1?false:true;
        }
  
        $this->mode='edit';
    }

    public function update(Examfeecourse $examfeecourse)
    {
        $this->validate();

        DB::beginTransaction();

        try 
        {  
            foreach ($this->fees as $key => $fee) 
            {
                if (isset($key) && $fee !== "" && $fee !== null)
                {   
                    $modify = Examfeecourse::when($this->sem, function ($query, $sem) { return $query->where('sem', $sem); })->where('patternclass_id',$this->patternclass_id)->where('examfees_id', $key)->latest()->first();
                    
                    if (isset($modify) && $modify->fee != $fee) 
                    {
                        Examfeecourse::create([
                            'examfees_id' => $key,
                            'fee' => $fee ?? 0,
                            'sem' => $this->sem,
                            'patternclass_id' => $this->patternclass_id,
                            'active_status' => isset($this->active_status[$key]) ? ($this->active_status[$key] == true ? 0 : 1) : 1,
                        ]);
                            
                        $modify->active_status=0;
                        $modify->update();
                        $modify=null;
                    } else 
                    {
                        Examfeecourse::updateOrCreate(
                            [
                                'examfees_id' => $key,
                                'sem' => $examfeecourse->sem,
                                'patternclass_id' => $examfeecourse->patternclass_id,
                            ],
                            [
                                'examfees_id' => $key,
                                'fee' =>$fee ?? 0,
                                'sem' => $this->sem,
                                'patternclass_id' => $this->patternclass_id,
                                'active_status' =>  isset($this->active_status[$key]) ? ($this->active_status[$key] == true ? 0 : 1) : 1,
                            ]
                        );
                    }
                }
            }
            $activeStatus=null;
            $this->resetinput();
            $this->setmode('all');
            DB::commit();
            $this->dispatch('alert',type:'success',message:'Exam Fee Course Updated Successfully !!');
           
        }catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Exam Fee Course  !!');
        }
    }

    public function status(Examfeecourse $examfeecourse)
    {
        DB::beginTransaction();

        try 
        {   
            if($examfeecourse->status)
            {
                $examfeecourse->status=0;
            }
            else
            {
                $examfeecourse->status=1;
            }
            $examfeecourse->update();

            DB::commit();
            $this->dispatch('alert',type:'success',message:'Status Updated Successfully !!'  );
        }catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Status  !!');
        }
    }

    public function approve(Examfeecourse $examfeecourse)
    {   
        
        if($examfeecourse->approve_status==1)
        {
            $examfeecourse->approve_status=0;
            $this->dispatch('alert',type:'success',message:'Exam Fee Course Not Approved Successfully !!');
        }
        else 
        {
            $examfeecourse->approve_status=1;
            $this->dispatch('alert',type:'success',message:'Exam Fee Course Approved Successfully !!');
        }
       $examfeecourse->update();
    }

    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }


    public function delete(Examfeecourse $examfeecourse)
    {  
        DB::beginTransaction();

        try
        {   
            $examfeecourse->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Exam Fee Course Soft Deleted Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Exam Fee Course !!');
        }
    }

    
    public function restore($id)
    {   
        DB::beginTransaction();

        try
        {   
            $examfeecourse = Examfeecourse::withTrashed()->find($id);
            $examfeecourse->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Exam Fee Course Restored Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Exam Fee Course !!');
        }
    }

    public function forcedelete()
    {   
        DB::beginTransaction();
        try 
        {
            $examfeecourse = Examfeecourse::withTrashed()->find($this->delete_id);
            $examfeecourse->forceDelete();
            $this->delete_id=null;
            DB::commit();
            $this->dispatch('alert',type:'success',message:'Exam Fee Course Deleted Successfully !!');

        } catch (\Illuminate\Database\QueryException $e) {

            DB::rollBack();
            
            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'error',message:'This record is associated with another data. You cannot delete it !!');
            } 
            else
            {   
                $this->dispatch('alert',type:'error',message:'Failed To Delete Exam Fee Course !!');
            }
        }
    }

    public function render()
    {   
        if($this->mode=='add')
        {   
            $this->is_ptrn_class = 0;
            if ($this->apply_fee_id) {
                $sem = Applyfeemaster::find($this->apply_fee_id);
                if ($sem && $sem->name === "Per Subject & SEM Wise") {
                    $this->is_sem = 1;
                    $this->is_ptrn_class = 1;
                    $this->is_course = 0;
                    $this->is_course_class = 0;
                }
    
                if ($sem && $sem->name === "Course Wise") {
    
                    $this->is_course = 1;
                    $this->is_course_class = 0;
                    $this->is_ptrn_class = 0;
                    $this->is_sem = 0;
                    if($this->course_id)
                    {   
                        $cour=Course::find($this->course_id);
                        if(isset($cour->coursecategory->course_category) && $cour->coursecategory->course_category=='Professional')
                        {
                            if(count($this->examfees) >0)
                            {
                                foreach ($this->examfees as $fee) {
                                    $this->fees[$fee->id]=$fee->default_professional_fee;
                                }   
                            }   
                        }
                        if(isset($cour->coursecategory->course_category) && $cour->coursecategory->course_category=='Non Professional')
                        {
                            if(count($this->examfees) >0)
                            {
                                foreach ($this->examfees as $fee) {
                                    $this->fees[$fee->id]=$fee->default_non_professional_fee;
                                }   
                            }   
                        }
                    }
                }
    
                if ($sem && $sem->name === "Class Wise") {
                    $this->is_course = 1;
                    $this->is_course_class = 1;
                    $this->is_ptrn_class = 0;
                    $this->is_sem = 0;
                    if($this->course_id)
                    {   
                        $cour=Course::find($this->course_id);
                        if(isset($cour->coursecategory->course_category) && $cour->coursecategory->course_category=='Professional')
                        {
                            if(count($this->examfees) >0)
                            {
                                foreach ($this->examfees as $fee) {
                                    $this->fees[$fee->id]=$fee->default_professional_fee;
                                }   
                            }   
                        }
                        if(isset($cour->coursecategory->course_category) && $cour->coursecategory->course_category=='Non Professional')
                        {
                            if(count($this->examfees) >0)
                            {
                                foreach ($this->examfees as $fee) {
                                    $this->fees[$fee->id]=$fee->default_non_professional_fee;
                                }   
                            }   
                        }
                    }  
                }
            }
        }


        if($this->mode!=='all')
        {   
            $this->examfees = Examfeemaster::select('id', 'fee_name')->where('form_type_id', $this->form_type_id)->where('active_status', 1)->get();
            
            if($this->mode=='add')
            {
                $this->applyfees=Applyfeemaster::pluck('name','id');
                $this->formtypes=Formtypemaster::pluck('form_name','id');

                if($this->is_course && $this->is_course_class)
                {
                    $this->courses = Course::pluck('course_name','id');
                    $this->courseclasses= Courseclass::select('id', 'course_id','classyear_id')->with(['course:course_name,id','classyear:classyear_name,id'])->where('course_id',$this->course_id)->get();
                }elseif($this->is_course )
                {
                    $this->courses = Course::pluck('course_name','id');
                }elseif($this->is_sem)
                {
                    $this->semesters = Semester::where('status', 1)->pluck('semester','id');
                    $this->patternclasses =Classview::select('id','classyear_name', 'course_name', 'pattern_name')->where('status',1)->get();
                }
            }

            if($this->mode=="edit")
            {   
                $this->formtypes=Formtypemaster::pluck('form_name','id');
                $this->patternclasses = Classview::select('id','classyear_name', 'course_name', 'pattern_name')->where('status',1)->get();
                
                if($this->is_sem)
                {
                    $this->semesters = Semester::where('status', 1)->pluck('semester','id');
                }
            }
        }
        
        $examfeecourses=Examfeecourse::select('id','fee','sem','approve_status','patternclass_id','examfees_id','active_status','deleted_at')
        ->with(['patternclass.pattern:pattern_name,id','examfee:fee_name,id','patternclass.courseclass.classyear:classyear_name,id','patternclass.courseclass.course:course_name,id'])
        ->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.exam-fee-course.all-exam-fee-course',compact('examfeecourses'))->extends('layouts.user')->section('user');
    }
}
