<?php

namespace App\Livewire\User\ExamOrder;

use Excel;
use App\Models\Exam;
use App\Models\Subject;
use Livewire\Component;
use App\Mail\MyTestMail;
use App\Models\Examorder;
use App\Models\Exampanel;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use App\Models\Exampatternclass;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use Illuminate\Support\Facades\Mail;
use App\Jobs\User\SendExamOrderEmailJob;
use App\Jobs\User\CancelExamOrderEmailJob;
use App\Exports\User\ExamOrder\ExportExamOrder;

class AllExamOrder extends Component
{   
    ## By Ashutosh
    use WithPagination;
    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="DESC";
    public $ext;
    public $mode='all';
    public $exampanel_id;
    public $exampatternclasses;
    public $exampanels;
    public $exam_patternclass_id;
    public $description;
    public $email_status;  
    public $exam;  
    #[Locked] 
    public $delete_id;    
    #[Locked] 
    public $edit_id;

    protected function rules()
    {
        return [
        'exampanel_id' => ['required',Rule::exists('exampanels', 'id')],
        'exam_patternclass_id' => ['required',Rule::exists('exam_patternclasses', 'id')],
        'description' => ['required','string','max:50'],     
        ];
    }

    public function messages()
    {   
        $messages = [
            'exampanel_id.required' => 'The exam panel ID is required.',
            'exampanel_id.exists' => 'The selected exam panel ID is invalid.',
            'exam_patternclass_id.required' => 'The exam pattern class ID is required.',
            'exam_patternclass_id.exists' => 'The selected exam pattern class ID is invalid.',
            'description.required' => 'The description is required.',
            'description.string' => 'The description must be a string.',
            'description.max' => 'The description may not be greater than 50 characters.',
        ];
        return $messages;
    }

    public function resetinput()
    {   
        $this->reset([
            'exampanel_id',
            'exam_patternclass_id',
            'description',
            'email_status',
        ]);

    }

    public function updated($property)
    {
        $this->validateOnly($property);
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

    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }

    // public function delete(Examorder  $examorder)
    // {   
    //     DB::beginTransaction();

    //     try 
    //     {
    //         $examorder->delete();

    //         DB::commit();

    //         $this->dispatch('alert',type:'success',message:'Exam Order Soft Deleted Successfully !!');

    //     } 
    //     catch (\Exception $e) 
    //     {

    //         DB::rollBack();

    //         $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Exam Order !!');
    //     }
    // }

    // public function restore($id)
    // {   
    //     DB::beginTransaction();
    //     try
    //     {
    //         $examorder = Examorder::withTrashed()->find($id);
    //         $examorder->restore();
        
    //         DB::commit();
        
    //         $this->dispatch('alert',type:'success',message:'Exam Order Restored Successfully !!');
    //     } 
    //     catch (\Exception $e) 
    //     {
    //         DB::rollBack();

    //         $this->dispatch('alert',type:'error',message:'Failed To Restore Exam Order !!');
    //     }
    // }

    // public function update_status(Examorder $examorder)
    // {
    //     DB::beginTransaction();

    //     try 
    //     {   
    //         if($examorder->email_status)
    //         {
    //             $examorder->email_status=0;
    //         }
    //         else
    //         {
    //             $examorder->email_status=1;
    //         }
    //         $examorder->update();

    //         DB::commit();
    //         $this->dispatch('alert',type:'success',message:'Status Updated Successfully !!'  );
    //     }catch (\Exception $e) 
    //     {
    //         DB::rollBack();

    //         $this->dispatch('alert',type:'error',message:'Failed to Update Status  !!');
    //     }
    // }

    public function forcedelete()
    {   

        DB::beginTransaction();

        try 
        { 
            $examorder = Examorder::withTrashed()->find($this->delete_id);
            $examorder->forceDelete();

            $this->delete_id=null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Exam Order Deleted Successfully !!');

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
                $this->dispatch('alert',type:'error',message:'Failed To Delete Exam Order !!');
            }
        }
    }

    Public function cancel_exam_order(Examorder $examorder)
    {    
        DB::beginTransaction();
        
        try 
        {   
            $url = route('user.cancelorder', ['id' => $examorder->id, 'token' => $examorder->token]);

            $details = [
                'subject' => 'Hello',
                'title' => 'Your Appointment for Cancel Examination Work (Sangamner College Mail Notification)',
                'body' => 'This is sample content we have added for this test mail',
                'examorder_id' => $examorder->id,
                'url' => $url,
                'email' => trim($examorder->exampanel->faculty->email)
            ];

            CancelExamOrderEmailJob::dispatch($details);    

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Cancel Email Send Successfully !!'  );
            
        } catch (\Exception $e) 
        {
          
            DB::rollback();

            $this->dispatch('alert',type:'info',message:'Failed to Send Cancel Email !!'  );
        }
    }

    public function resend_exam_order_mail(Examorder $examorder)
    {
        DB::beginTransaction();
        
        try 
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

            DB::commit();
            $this->dispatch('alert',type:'success',message:'Resend Email Send Successfully !!'  );

        } catch (\Exception $e) 
        {
          
            DB::rollback();

            $this->dispatch('alert',type:'info',message:'Failed to Send Resend Email !!'  );
        }

    }


    public function merge_exam_order_mail(Examorder $examorder)
    {

        $subject_ids= $examorder->exampanel->where('faculty_id',$examorder->exampanel->faculty_id)->pluck('subject_id')->unique();
        
        $subjects =Subject::whereIn('id',$subject_ids)->pluck('subject_name','subject_code','id');

        $subjectsString = $subjects->map(function($value, $key) {
            return "$key $value";
        })->implode(', ');

        
        $url = url('user/merge/order/'.$examorder->id.'/'.$examorder->token);
        
        $details = [
            'subject' => 'Hello',
            'title' => 'Your Appointment for Examination Work (Sangamner College Mail Notification)',
            'body' => 'This is sample content we have added for this test mail. Subjects: ' . $subjectsString,
            'examorder_id' => $examorder->id,
            'url' => $url,
            'email' => trim($examorder->exampanel->faculty->email)
        ];

        DB::beginTransaction();
        
        try 
        {
           
            SendExamOrderEmailJob::dispatch($details);

            DB::commit();
            $this->dispatch('alert',type:'success',message:'Email Merge and Send Successfully !!'  );
        } catch (\Exception $e) 
        {
            DB::rollback();
            $this->dispatch('alert',type:'info',message:'No Subject Found to this Faculty !!'  );
        }
    }

    public function bulk_resend_exam_order_mail()
    {
        
        DB::beginTransaction();
        
        try 
        {
            $examorders = Examorder::withTrashed()->whereIn('exam_patternclass_id',$this->exam->exampatternclasses->pluck('id'))->where('email_status', 1)->get();

            if ($examorders->isNotEmpty()) 
            {
                foreach ($examorders as $examorder) 
                {
                    $url = url('user/exam/order/'.$examorder->id.'/'.$examorder->token);

                    $details = [
                        'subject' => 'Hello',
                        'title' => 'Your Appointment for Examination Work (Sangamner College Mail Notification)',
                        'body' => 'This is sample content we have added for this test mail',
                        'examorder_id' => $examorder->id,
                        'url' => $url,
                        'email' => trim($examorder->exampanel->faculty->email)
                    ];

                    SendExamOrderEmailJob::dispatch($details);
                }

                DB::commit();
                $this->dispatch('alert',type:'success',message:'Resend Emails Send Successfully !!'  );
            } 
            else 
            {
        
                $this->dispatch('alert',type:'info',message:'No Exam Order Found');
            }
        } catch (\Exception $e) 
        {
          
            DB::rollback();

            $this->dispatch('alert',type:'info',message:'Failed to Send Resend Emails !!'  );
        }
    }

    public function bulk_cancel_exam_order()
    {

        DB::beginTransaction();
        
        try 
        {
            $examorders = Examorder::withTrashed()->whereIn('exam_patternclass_id',$this->exam->exampatternclasses->pluck('id'))->where('email_status', 1)->get();

            if ($examorders->isNotEmpty()) 
            {
    
                foreach ($examorders as $examorder) 
                {
                    $url = url('user/exam/order/'.$examorder->id.'/'.$examorder->token);
    
                    $details = [
                        'subject' => 'Hello',
                        'title' => 'Your Appointment for Examination Work (Sangamner College Mail Notification)',
                        'body' => 'This is sample content we have added for this test mail',
                        'examorder_id' => $examorder->id,
                        'url' => $url,
                        'email' => trim($examorder->exampanel->faculty->email)
                    ];
    
                    CancelExamOrderEmailJob::dispatch($details);
                }
    
                DB::commit();
        
                $this->dispatch('alert',type:'success',message:'Cancelation Emails Send Successfully !!'  );
            } 
            else 
            {
                $this->dispatch('alert',type:'info',message:'No Exam Order Found');
            }
           
        } catch (\Exception $e) 
        {
          
            DB::rollback();

            $this->dispatch('alert',type:'info',message:'Failed to Send Cancelation Emails !!'  );
        }
    }


    public function bulk_delete_exam_order()
    {
        DB::beginTransaction();
        
        try 
        {   

            Examorder::withTrashed()->whereIn('exam_patternclass_id',$this->exam->exampatternclasses->pluck('id'))->where('email_status', 0)->forceDelete();
        
            DB::commit(); 

            $this->dispatch('alert', type: 'success', message: 'Exam Orders Deleted Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollback(); 
            $this->dispatch('alert',type:'info',message:'failed To Delete Exam Orders ');
        }
          
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

    #[Renderless]
    public function export()
    {   
        try 
        {
            set_time_limit(600);
            ini_set('memory_limit', '1024M');

            $filename="Exam_Order_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new ExportExamOrder($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new ExportExamOrder($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new ExportExamOrder($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Exam Order Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Exam Order !!');
        }
    }

    public function mount()
    {
        $this->exam=Exam::where('status',1)->first();
    }

    public function render()
    {
        if($this->mode!=='all')
        {
            $this->exampanels = Exampanel::select('id','faculty_id','subject_id','examorderpost_id')->with(['faculty:id,faculty_name','subject:id,subject_name','examorderpost:id,post_name'])->get();
            $this->exampatternclasses = Exampatternclass::select('id','exam_id','patternclass_id')->where('exam_id',$this->exam->id)->get();                    
        }

        $examorders=Examorder::select('id','exampanel_id','exam_patternclass_id','description','email_status','deleted_at')
        ->with(['exampatternclass.patternclass.pattern:id,pattern_name','exampatternclass.patternclass.courseclass.classyear:classyear_name,id','exampatternclass.patternclass.courseclass.course:course_name,id','exampanel.faculty:id,faculty_name','exampanel.subject:id,subject_name','exampanel.examorderpost:id,post_name'])
        ->whereIn('exam_patternclass_id',$this->exam->exampatternclasses->pluck('id'))
        ->when($this->search, function ($query, $search) { $query->search($search); })
        ->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.exam-order.all-exam-order',compact('examorders'))->extends('layouts.user')->section('user');
    }
}
