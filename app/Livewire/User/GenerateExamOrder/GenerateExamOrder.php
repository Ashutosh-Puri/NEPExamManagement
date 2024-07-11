<?php

namespace App\Livewire\User\GenerateExamOrder;

use Carbon\Carbon;
use App\Models\Exam;
use Livewire\Component;
use App\Models\Semester;
use App\Models\Examorder;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use App\Models\Exampatternclass;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use Illuminate\Support\Facades\Auth;
use App\Jobs\User\SendExamOrderEmailJob;


class GenerateExamOrder extends Component
{   
    ## By Ashutosh
    use WithPagination;
    public $perPage=10;
    public $search='';
    public $sortColumn="exam_id";
    public $sortColumnBy="DESC";
    public $exam;
    
    #[Locked]
    public $semesters=[];
    public $semester=[];


    public function generate_exam_order(Exampatternclass $exampatternclass)
    {     
        $current_time = Carbon::now();
        $exam_order_data = [];
        $user_id=Auth::guard('user')->user()->id;

        $subjects = $exampatternclass->patternclass->subjects;

        if(!empty($this->semester))
        {
            $subjects = $subjects->filter(function ($subject) {
                return in_array($subject->subject_sem, $this->semester);
            });
        }
        
        foreach ($subjects  as $subject) 
        {     
            foreach($subject->exampanels->where('active_status',1) as $pannel )     
            {                   
                $exam_order_data[] = [
                    'user_id'=>$user_id,
                    'exampanel_id' => $pannel->id,
                    'exam_patternclass_id' => $exampatternclass->id,
                    'email_status' =>0,
                    'token'=>  Str::random(30),
                    'created_at' =>$current_time,
                    'updated_at' =>$current_time,                 
                ];
            }        
        }

        try 
        {
            if (!empty($exam_order_data)) 
            {
                DB::transaction(function () use ($exam_order_data) {
                    Examorder::insert($exam_order_data);
                });
            }
    
            $this->dispatch('alert',type:'success',message:'Order Created Successfully !!'  );
        } catch (\Exception $e) {

            $this->dispatch('alert',type:'success',message:'Failed to Create Order !!'  );
        }

        $this->mode='all';
    }

    #[Renderless]
    public function send_mail(ExamPatternclass $exampatterntclass)
    {   
        $emails = $exampatterntclass->examorders->where('email_status' ,0);
        if(count($emails) > 0)
        {
            foreach($emails as $examorder)
            {   
                $mail_data = [
                    'subject'=>'Hello',
                    'title' => 'Your Appoinment for Examination Work (Sangamner College Mail Notification)',
                    'body' => 'This is sample content we have added for this test mail',
                    'examorder_id'=> $examorder->id,
                    'url'=>url('user/exam/order/'.$examorder->id.'/'.$examorder->token),
                    'email' => trim($examorder->exampanel->faculty->email)
                ];
    
                SendExamOrderEmailJob::dispatch($mail_data);
            }
            $this->dispatch('alert',type:'success',message:'Emails have been sent successfully !!'  );
        }else
        {
            $this->dispatch('alert',type:'info',message:'No Exam Orders Left For Send Emails !!'  );
        }

    }

    // public function cancel_exam_order(Exampatternclass $exampatternclass)
    // {    
    //     try 
    //     {

    //         DB::transaction(function () use ($exampatternclass) {
    
    //             if($exampatternclass->examorders()->where('email_status' ,0)->forceDelete())
    //             {

    //                 $this->dispatch('alert',type:'success',message:'Order Deleted Successfully !!'  );
    //             }else
    //             {
    //                 $this->dispatch('alert',type:'info',message:'Cannot Delete Order Emails Sended Successfully !!'  ); 
    //             }
    //         });
    

    //     } catch (\Exception $e) 
    //     {
    //         $this->dispatch('alert',type:'error',message:'Failed to Delete Order !!'  );
    //     }
        
    // }

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


    public function mount()
    {
        $this->exam = Exam::where('status',1)->first();
        $this->semesters=Semester::where('status',1)->pluck('semester','id');
    }

    public function render()
    {
        
        $exam_patternclass_ids=Examorder::withTrashed()->pluck('exam_patternclass_id')->unique();

        $exampatternclasses=Exampatternclass::select('id','exam_id','patternclass_id','deleted_at')
        ->with([ 'patternclass.pattern:id,pattern_name','patternclass.courseclass.course:id,course_name','patternclass.courseclass.classyear:id,classyear_name','exam:exam_name,id'])
        ->where('exam_id', $this->exam->id)    
        ->when($this->search, function ($query, $search) {$query->search($search);})
        ->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        $exampatternclasses->getCollection()->transform(function ($exampatternclass) use ($exam_patternclass_ids) {
            $exampatternclass->is_order = !$exam_patternclass_ids->contains($exampatternclass->id);
            return $exampatternclass;
        });

        return view('livewire.user.generate-exam-order.generate-exam-order',compact('exampatternclasses'))->extends('layouts.user')->section('user');
    }
}
