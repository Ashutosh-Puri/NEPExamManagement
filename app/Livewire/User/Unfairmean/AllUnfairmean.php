<?php

namespace App\Livewire\User\Unfairmean;

use PDF;
use Excel;
use App\Models\Exam;
use App\Models\Student;
use App\Models\Subject;
use Livewire\Component;
use App\Models\Unfairmeans;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use App\Models\Exampatternclass;
use App\Models\Examstudentseatno;
use App\Models\Unfairmeansmaster;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Jobs\User\SendUnfairmeanEmailJob;
use App\Exports\User\Unfairmean\ExportUnfairmean;
use App\Exports\User\Unfairmeans\ExportUnfairmeans;

class AllUnfairmean extends Component
{   
    ## By Ashutosh
    use WithPagination;
    protected $listeners = ['delete-confirmed'=>'forcedelete'];

    public $per_page = 10;
    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;
    public $exam;
    
    public $exam_patternclasses_id;
    public $exam_studentseatnos_id;
    public $student_id;
    public $unfairmeansmaster_id;
    public $subject_id;
    public $punishment;
    public $status;
    public $email;
    public $mem_id;
   
    #[Locked]
    public $exampatternclasses;
    #[Locked]
    public $upsert_id;
    #[Locked]
    public $seatnos=[];
    #[Locked]
    public $students;
    #[Locked]
    public $unfairmeans;
    #[Locked]
    public $subjects=[];
    #[Locked]
    public $mode='all';
    #[Locked]
    public $unfairmeans_id;
    #[Locked]
    public $delete_id;

    public function rules()
    {
        return [
        'exam_patternclasses_id' => ['required',Rule::exists(Exampatternclass::class,'id')],
        'exam_studentseatnos_id' => ['required',Rule::exists(Examstudentseatno::class,'id')],
        'unfairmeansmaster_id' => ['required',Rule::exists(Unfairmeansmaster::class,'id')],
        'subject_id' => ['required',Rule::exists(Subject::class,'id')],
        ];
    }

    public function messages()
    {
        $messages = [
        'exam_patternclasses_id.exists' => 'The selected exam pattern class does not exist.',
        'exam_studentseatnos_id.exists' => 'The selected exam student seat number does not exist.',
        'student_id.exists' => 'The selected student does not exist.',
        'unfairmeansmaster_id.exists' => 'The selected unfair means master does not exist.',
        'subject_id.exists' => 'The selected subject does not exist.',
       
        ];
        return $messages;
    }

    public function resetInput()
    {
        $this->reset([
            'exam_patternclasses_id',
            'exam_studentseatnos_id',
            'student_id',
            'unfairmeansmaster_id',
            'subject_id',
            'punishment',
            'status',
        ]);
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

    public function Status(Unfairmeans $unfairmean)
    {
        DB::beginTransaction();

        try 
        {   
            if($unfairmean->paid_status)
            {
                $unfairmean->paid_status=0;
            }
            else
            {
                $unfairmean->paid_status=1;
            }
            $unfairmean->update();

            DB::commit();
            $this->dispatch('alert',type:'success',message:'Status Updated Successfully !!'  );
        }catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Status  !!');
        }
    }

    public function save()  
    
    { 
        $this->validate();

        DB::beginTransaction();

        try 
        {
            $examstudentseatno=Examstudentseatno::find($this->exam_studentseatnos_id);
            
            $student=Student::find($examstudentseatno->student_id);
        
            if($student)
            {
                $unfairmeans = Unfairmeans::create([
                'exam_patternclasses_id' => $this->exam_patternclasses_id,
                'exam_studentseatnos_id' => $this->exam_studentseatnos_id,
                'student_id' => $student->id ,
                'memid'=>$student->memid,
                'unfairmeansmaster_id' => $this->unfairmeansmaster_id,
                'subject_id' => $this->subject_id,
                'punishment' => (is_array($this->subject_id) ? count($this->subject_id) : 1) * 1000,
                'status' => 1,
                'paid_status' => 0,
                'email' => 0
                ]);
            

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Unfairmeans Created Successfully !!');
            }

        } catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Unfairmeans !!');
        }
    }


    public function sendmail()
    {
        $allunfaircases = Unfairmeans::where('email', 0)->get()->groupBy('student_id');
      
        if( !empty($allunfaircases))
        {
            foreach ($allunfaircases as $student_id => $unfaircases ) {

                SendUnfairmeanEmailJob::dispatch($unfaircases, $this->exam);
                $unfaircases->toquery()->update(['email'=>1]);
            }

            $this->dispatch('alert',type:'success',message:'Unfairmeans Mail Sent Successfully !!'  );
        } else {
              
            $this->dispatch('alert',type:'info',message: 'No Unfairmeans Cases Found !! ');
           
        }

    }

    public function edit(Unfairmeans $unfairmean)
    {
        $this->resetinput();
        $this->unfairmeans_id=$unfairmean->id;
        $this->exam_patternclasses_id = $unfairmean->exam_patternclasses_id;
        $this->exam_studentseatnos_id = $unfairmean->exam_studentseatnos_id;
        $this->unfairmeansmaster_id = $unfairmean->unfairmeansmaster_id;
        $this->subject_id = $unfairmean->subject_id;
        $this->mode='edit';
    }


    public function update(Unfairmeans  $unfairmean)
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {
            $examstudentseatno=Examstudentseatno::find($this->exam_studentseatnos_id);
            if($examstudentseatno)
            {        
                $unfairmean->exam_patternclasses_id= $this->exam_patternclasses_id;
                $unfairmean->exam_studentseatnos_id= $this->exam_studentseatnos_id;
                $unfairmean->unfairmeansmaster_id= $this->unfairmeansmaster_id;
                $unfairmean->subject_id= $this->subject_id;
                $unfairmean->student_id= $examstudentseatno->student_id;
                $unfairmean->memid= $examstudentseatno->student->memid;
                $unfairmean->update();
            } 
            DB::commit();

            $this->resetinput();

            $this->mode='all';

            $this->dispatch('alert',type:'success',message:'Unfairmeans Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Unfairmeans !!');
        }
    }

    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }

   
    public function delete(Unfairmeans  $unfairmeans)
    {  
        DB::beginTransaction();

        try 
        {
            $unfairmeans->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Unfairmeans Soft Deleted Successfully !!');

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Unfairmeans !!');
        }
    }

    public function restore($id)
    {   
       
        DB::beginTransaction();

        try
        {
            $unfairmeans = Unfairmeans::withTrashed()->findOrFail($id);

            $unfairmeans->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Unfairmeans Restored Successfully !!');

        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Unfairmeans !!');
        }
    }

    public function forcedelete()
    {   

        DB::beginTransaction();

        try 
        { 
            $unfairmeans = Unfairmeans::withTrashed()->find($this->delete_id);
            $unfairmeans->forceDelete();

            $this->delete_id=null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Unfairmeans Deleted Successfully !!');

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
                $this->dispatch('alert',type:'error',message:'Failed To Delete Unfairmeans !!');
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

    
    #[Renderless]
    public function export()
    {   
        try 
        {
            set_time_limit(600);
            ini_set('memory_limit', '1024M');

            $filename="Unfairmean_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new ExportUnfairmean($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new ExportUnfairmean($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new ExportUnfairmean($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }
            $this->dispatch('alert',type:'success',message:'Unfairmeans Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Unfairmeans !!');
        }

    }

    public function mount()
    {
        $this->exam = Exam::where('status',1)->first();
    }
    public function render()
    {
        if($this->mode!=='all')
        {   
            $this->students=Student::pluck('student_name','id');
            $this->unfairmeans=Unfairmeansmaster::where('status',1)->pluck('location','id');

            $this->exampatternclasses=Exampatternclass::where('exam_id',$this->exam->id)->select('patternclass_id','id')->with(['patternclass.pattern:id,pattern_name','patternclass.courseclass.course:id,course_name','patternclass.courseclass.classyear:id,classyear_name',])->get();
            
            if($this->exam_patternclasses_id)
            {
                $this->seatnos = Examstudentseatno::where('exam_patternclasses_id', $this->exam_patternclasses_id)->pluck('seatno', 'id');
            }      


            if($this->exam_patternclasses_id)
            {
                $exampatternclass=Exampatternclass::find($this->exam_patternclasses_id);
                if($exampatternclass)
                {
                    $this->subjects= Subject::where('patternclass_id',$exampatternclass->patternclass_id)->pluck('subject_name','id');
                }
            }
        }


        $unfairmeanss= Unfairmeans::select('id','exam_patternclasses_id' ,'exam_studentseatnos_id' ,'student_id' ,'unfairmeansmaster_id' ,'subject_id' ,'punishment' ,'status' ,'paid_status','email','deleted_at')
        ->with(['exampatternclass.patternclass.courseclass.course:course_name,id','examstudentseatno:seatno,id','student:memid,student_name,id','unfairmeans:location,id','subject:subject_name,id'])
        ->withTrashed()
        ->get();


        $groupedUnfairmeans = $unfairmeanss->groupBy('student_id');

        return view('livewire.user.unfairmean.all-unfairmean',compact('unfairmeanss','groupedUnfairmeans'))->extends('layouts.user')->section('user');
    }
}
