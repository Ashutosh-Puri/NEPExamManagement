<?php

namespace App\Livewire\User\PaperSubmission;

use Excel;
use App\Models\Exam;
use App\Models\User;
use App\Models\Faculty;
use App\Models\Subject;
use Livewire\Component;
use App\Models\Classview;
use App\Models\Department;
use App\Models\Patternclass;
use Livewire\WithPagination;
use App\Models\Papersubmission;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use App\Mail\User\PaperSubmitted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Jobs\User\PaperSubmissionJob;
use App\Exports\User\Papersubmission\ExportPaperSubmission;

class AllPaperSubmission extends Component
{   ## By Ashutosh
    use WithPagination;
    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;

    public $exam_id;
    public $subject_id;
    public $noofsets=3;
    public $faculty_id;
    public $department_id;
    public $user_id;
    public $status;
    public $patternclass_id;
    public $facultydata;

    #[Locked]
    public $exams;
    #[Locked]
    public $patternclasses;
    #[Locked]
    public $subjects=[];
    #[Locked]
    public $faculties=[];
    #[Locked]
    public $users;
    #[Locked]
    public $departments;
    #[Locked]
    public $paper_id;
    #[Locked]
    public $delete_id;

    protected function rules()
    {
        return [
            'subject_id' => ['required',Rule::exists(Subject::class,'id')],
            'faculty_id' => ['required',Rule::exists(Faculty::class,'id')],
            'noofsets' => ['required','numeric'],
         ];
    }

    public function resetinput()
    {
        $this->reset([
            'subject_id',
            'noofsets',
            'patternclass_id',
        ]);      
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save(Papersubmission $papersubmission )
    { 
        $this->validate();

        DB::beginTransaction();

        try 
        {
            $exam=Exam::where('status',1)->first();
            if( $exam)
            {
                $papersubmission->exam_id= $exam->id;
                $papersubmission->subject_id= $this->subject_id;
                $papersubmission->noofsets= $this->noofsets;
                $papersubmission->user_id=  Auth::guard('user')->user()->id;
                $papersubmission->status=  0;
                $papersubmission->is_online=  0;
                $papersubmission->chairman_id=  $this->faculty_id;
                $papersubmission->save();           
            }
    
            $details = [
                'subject' => 'Hello',
                'title' => 'Acknowledgment regarding manuscript submission (Sangamner College Mail Notification)',
                'body' => 'This is sample content we have added for this test mail',
                'papersubmission' => $papersubmission->id,
            ];

            PaperSubmissionJob::dispatch($papersubmission);

            DB::commit();

            $this->resetinput();

            $this->dispatch('alert',type:'success',message:'Paper Submission Created Successfully !!');

        } catch (\Exception $e) 
        {
            dd($e);
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Paper Submission !!');
        }
    }

    public function update_status(Papersubmission $papersubmission)
    {
        DB::beginTransaction();

        try 
        {   
            if($papersubmission->status)
            {
                $papersubmission->status=0;
            }
            else
            {
                $papersubmission->status=1;
            }
            $papersubmission->update();

            DB::commit();
            $this->dispatch('alert',type:'success',message:'Status Updated Successfully !!'  );
        }catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Status  !!');
        }
    }

    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }

    public function delete(Papersubmission $papersubmission)
    {  
        DB::beginTransaction();

        try 
        {
            $papersubmission->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:' Paper Submission Soft Deleted Successfully !!');

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete  Paper Submission !!');
        }
    }
    

    public function restore($paper_id)
    {   
       
        DB::beginTransaction();

        try
        {
            $papersubmission = Papersubmission::withTrashed()->findOrFail($paper_id);

            $papersubmission->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Paper Submission Restored Successfully !!');

        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Paper Submission !!');
        }
    }

    public function forcedelete()
    {   

        DB::beginTransaction();

        try 
        { 
            $papersubmission = Papersubmission::withTrashed()->find($this->delete_id);
            $papersubmission->forceDelete();

            $this->delete_id=null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Paper Submission Deleted Successfully !!');

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
                $this->dispatch('alert',type:'error',message:'Failed To Delete Paper Submission !!');
            }
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

    public function export()
    {   
        try 
        {
            set_time_limit(600);
            ini_set('memory_limit', '1024M');

            $filename="Paper_Submission_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new ExportPaperSubmission($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new ExportPaperSubmission($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new ExportPaperSubmission($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            } 
            $this->dispatch('alert',type:'success',message:'Paper Submission Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Paper Submission !!');
        }
    }
  
    public function render()
    {   
        $this->faculties= Faculty::where('active',1)->pluck('faculty_name','id');
        $this->exams=Exam::where('status',1)->pluck('exam_name','id');
        $this->patternclasses = Classview::select('id','classyear_name', 'course_name', 'pattern_name')->where('status',1)->get();  
        
        
        if($this->patternclass_id)
        {
            $this->subjects = Subject::where('status', 1)->where('patternclass_id', $this->patternclass_id)->pluck('subject_name', 'id');
        }
        if($this->subject_id)
        {
           $subject= Subject::find($this->subject_id);
           if($subject)
            {
                $this->facultydata = $subject->exampanels->where('examorderpost_id', '1')->where('active_status', '1')->first();
            }
        }
 
        $papersubmissions=Papersubmission::select('id','exam_id','subject_id','chairman_id','user_id','noofsets','status','deleted_at')
        ->with(['exam:exam_name,id','subject:subject_name,id','faculty:faculty_name,id','user:name,id'])
        ->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.paper-submission.all-paper-submission',compact('papersubmissions'))->extends('layouts.user')->section('user');
    }
}
