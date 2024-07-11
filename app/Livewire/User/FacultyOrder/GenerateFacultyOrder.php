<?php

namespace App\Livewire\User\FacultyOrder;

use Carbon\Carbon;
use App\Models\Exam;
use App\Models\Faculty;
use App\Models\Subject;
use Livewire\Component;
use App\Mail\MyTestMail;
use App\Models\Classview;
use App\Models\Examorder;
use App\Models\Exampanel;
use App\Models\Department;
use Illuminate\Support\Str;
use App\Models\Patternclass;
use Livewire\WithPagination;
use App\Models\Examorderpost;
use App\Models\Subjectcategory;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use App\Models\Exampatternclass;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Jobs\User\SendExamOrderEmailJob;

class GenerateFacultyOrder extends Component
{   
    ## By Ashutosh
    use WithPagination;
    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;
    public $department_id;
    public $faculty_id;
    public $patternclass_id;
    public $subject_id;
    public $examorderpost_id;
    public $exam_patternclass_id;
    public $description;

    #[Locked] 
    public $departments=[];
    #[Locked] 
    public $faculties=[];
    #[Locked] 
    public $patternclasses=[];
    #[Locked] 
    public $examorderposts=[];
    #[Locked] 
    public $exampatternclasses=[];
    #[Locked] 
    public $subjects=[];


    protected function rules()
    {
        return [
            'department_id' => ['required',Rule::exists(Department::class,'id')],
            'faculty_id' => ['required',Rule::exists(Faculty::class,'id')],
            'patternclass_id' => ['required',Rule::exists(Patternclass::class,'id')],
            'examorderpost_id' => ['required',Rule::exists(Examorderpost::class,'id')],
            'description' => ['nullable'],
        ];
    }

    public function resetinput()
    {
        $this->reset([
            'faculty_id',
            'examorderpost_id',
            'subject_id',
            'department_id',
            'patternclass_id',
            'description',
        ]);
          
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

    #[Renderless]
    public function add()
    {
        $this->validate();

        DB::beginTransaction();

        try
        {   
            $exampanel= Exampanel::create([
                'faculty_id' => $this->faculty_id,
                'examorderpost_id' => $this->examorderpost_id,
                'subject_id' => $this->subject_id,
                'description' => $this->description,
                'active_status' => 0,
            ]);
    
    
    
            if ($exampanel->subject->patternclass_id)  
            {
                $exam = Exam::where('status',1)->first();
            
                $exampatternclasses= Exampatternclass::where('exam_id',$exam->id)->where('patternclass_id',$exampanel->subject->patternclass_id)->where('launch_status', 1)->get();
    
                $user_id = Auth::guard('user')->user()->id;
    
                $exam_order_data = [];
    
                foreach($exampatternclasses as $exampatternclass)
                {     
    
                    $token = Str::random(30);
    
                    $exam_order_data[]  = [
                        'user_id'=>$user_id,
                        'exampanel_id' => $exampanel->id,
                        'exam_patternclass_id' => $exampatternclass->id,
                        'email_status' => 1,
                        'description' => '',
                        'token'=>  $token,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
    
                if(!empty($exam_order_data))
                {
                    Examorder::insert($exam_order_data);
                }
    
            
                foreach ($exampatternclasses as $exampatternclass) 
                {
    
                    if ($exampatternclass->examorders->isNotEmpty())            
                    {
                        foreach ($exampatternclass->examorders as $examorder) 
                        {
                            
                            $url = url('user/exam/order/'.$examorder->id.'/'.$examorder->token);
    
                            $details = [
                                'subject'=>'Hello',
                                'title' => 'Your Appoinment for Examination Work (Sangamner College Mail Notification)',
                                'body' => 'This is sample content we have added for this test mail',
                                'examorder_id'=> $examorder->id,
                                'url'=>$url,
                                'email' => trim($examorder->exampanel->faculty->email)
                            ];
    
                            SendExamOrderEmailJob::dispatch($details);
    
                        }
                    }
                }
      
                $this->dispatch('alert',type:'success',message:'Emails have been sent successfully !!'  );
                $this->resetinput();
                $this->resetValidation();
            }
            DB::commit();

            $this->dispatch('alert',type:'success',message:'Faculty Order Created Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Create Faculty Order !!');
        }
    }


    public function mount()
    {  
        $this->departments = Department::where('status', 1)->pluck('dept_name', 'id');
        $this->patternclasses = Classview::select('id','classyear_name', 'course_name', 'pattern_name')->where('status',1)->get();
        $this->examorderposts = Examorderpost::where('status', 1)->pluck('post_name', 'id');
    }

    public function render()
    {  

        if ($this->department_id) 
        {
            $this->faculties = Faculty::where('active', 1)->where('department_id', $this->department_id)->pluck('faculty_name', 'id');
        }
        
        if ($this->patternclass_id) 
        {
            $this->subjects = Subject::where('status', 1)->where('patternclass_id', $this->patternclass_id)->pluck('subject_name', 'id');
        }

       

        $panels=Exampanel::select('id','faculty_id','subject_id','examorderpost_id','description','active_status','deleted_at')
        ->with(['faculty:faculty_name,id','subject:subject_name,id','examorderpost:post_name,id'])
        ->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

    
        return view('livewire.user.faculty-order.generate-faculty-order',compact('panels'))->extends('layouts.user')->section('user');
    }
}



