<?php

namespace App\Livewire\User\SubjectOrder;

use App\Models\Exam;
use App\Models\Faculty;
use App\Models\Subject;
use Livewire\Component;
use App\Models\Classview;
use App\Models\Examorder;
use App\Models\Exampanel;
use App\Models\Department;
use Illuminate\Support\Str;
use App\Models\Patternclass;
use App\Models\Examorderpost;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Jobs\User\SendExamOrderEmailJob;

class GenerateSubjectOrder extends Component
{   
    ## By Ashutosh
    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;
    public $department_id;
    public $departments;
    public $faculty_ids=[];
    public $faculties=[];
    public $patternclass_id;
    public $patternclasses;
    public $subject_id;
    public $subjects=[];
    public $examorderpost_id;
    public $examorderposts;
    public $exam_patternclass_id;
    public $exampatternclasses;
    public $description;
    #[Locked]
    public $mode='add';

    public function resetinput()
    {   
        $this->reset([
            'patternclass_id',
            'subjects',
            'faculties',
            'department_id',       
        ]);
    }

    public function add()
    {          
        DB::beginTransaction();

        try
        {   
            $subject_order =[];
            foreach( $this->faculty_ids as  $examorderpost_id => $faculty_id)
            {
                $subject_order[]=[
                    'subject_id'=>$this->subject_id,
                    'examorderpost_id'=>$examorderpost_id,
                    'faculty_id'=>$faculty_id,
                    'description'=>'', 
                    'active_status'=>0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $insertResult = Exampanel::insert($subject_order);

            $user_id=Auth::guard('user')->user()->id;
            if ($insertResult) 
            {
                $examids = Exam::where('status',1)->pluck('id')->toArray();

                $exampanels = Exampanel::whereIn('examorderpost_id', array_keys($this->faculty_ids))->where('subject_id', $this->subject_id)->get();
    
                foreach ($exampanels as $exampanel) 
                {
                    $exampatternclasses = $exampanel->subject->patternclass->exampatternclasses->whereIn('exam_id',$examids);

                    $exam_order_data = [];

                    foreach($exampatternclasses as $exampatternclass)
                    {
                        $token = Str::random(30);
                        $exam_order_data[] = [
                            'user_id'=> $user_id,
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

                        foreach ($exampatternclasses as $exampatternclass)
                        {   
                            if ($exampatternclass->examorders->isNotEmpty())            
                            {
                                foreach ($exampatternclass->examorders as $examorder) 
                                {
                                    $details = [
                                        'subject'=>'Hello',
                                        'title' => 'Your Appoinment for Examination Work (Sangamner College Mail Notification)',
                                        'body' => 'This is sample content we have added for this test mail',
                                        'examorder_id'=> $examorder->id,
                                        'url'=>url('user/exam/order/'.$examorder->id.'/'.$examorder->token),
                                        'email' => trim($examorder->exampanel->faculty->email)
                                    ];
                
                                    SendExamOrderEmailJob::dispatch($details);
                                }
                            }
                        }
                    }
                }
            }

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Subject Order Created & Email Send Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Create Subject Order !!');
        }
    }


    
    public function render()
    {
        if($this->mode=='add')
        {
            $this->patternclasses=Classview::select('id','classyear_name', 'course_name', 'pattern_name')->where('status',1)->get();
            $this->departments=Department::where('status',1)->pluck('dept_name','id');
            $this->examorderposts = Examorderpost::select('id', 'post_name')->where('status', 1)->get();

            if($this->patternclass_id)
            {
                $this->subjects = Subject::where('status', 1)->where('patternclass_id', $this->patternclass_id)->pluck('subject_name', 'id');
            }

            if($this->department_id)
            {
                $this->faculties=Faculty::where('active',1)->where('department_id',$this->department_id)->pluck('faculty_name','id');
            }

        }

        $panels=Exampanel::select('id','faculty_id','subject_id','examorderpost_id','description','active_status','deleted_at')
        ->with(['faculty:faculty_name,id','subject:subject_name,id','examorderpost:post_name,id'])
        ->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->paginate($this->perPage);

        return view('livewire.user.subject-order.generate-subject-order')->extends('layouts.user')->section('user');
    }
}
