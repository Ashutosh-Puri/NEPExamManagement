<?php

namespace App\Livewire\User\ExamMarks;

use Mpdf\Mpdf;
use Carbon\Carbon;
use App\Models\Exam;
use App\Models\Faculty;
use Livewire\Component;
use App\Models\Exampanel;
use App\Models\Exambarcode;
use Livewire\WithPagination;
use App\Models\Paperassesment;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ExtMarkPaperIssue extends Component
{   
    # By Ashutosh

    use WithPagination;   
    public $lotnumber=null;
    #[Locked]
    public $examiner=[];
    #[Locked]
    public $moderator=[];
    public $selectedexaminer;
    public $selectedmoderator;
    public $examinername;
    public $moderatorname;
    public $exam;
    protected $rules = ['lotnumber'=>'required',];

    public function updatedLotnumber()
    {
        $this->validate();

        $paperassessment=Paperassesment::find($this->lotnumber);
 

        if(is_null($paperassessment)||($paperassessment->exambarcodes->count()==0))
        {
            $this->dispatch('alert',type:'info',message:'Invalid Lot Number !!');
        }
        else
        {   
            $subject_ids=Exambarcode::where('paperassesment_id',$this->lotnumber)->distinct('subject_id')->pluck('subject_id');

            if(!empty($subject_ids))
            {
                $faculty_ids=Exampanel::whereIn('subject_id',$subject_ids)->where('active_status',1)->distinct('faculty_id')->pluck('faculty_id');

                if(!empty($faculty_ids))
                {

                    $this->examiner=Faculty::whereIn('id',$faculty_ids)->get();
                    $this->moderator=Faculty::whereIn('id',$faculty_ids)->where('id','!=', $this->selectedexaminer)->get();
                }
            }
         

            $this->selectedexaminer=$paperassessment->examinerfaculty_id;

            
            $this->examinername=$paperassessment->examinerfaculty_id;

            $this->selectedmoderator=$paperassessment->moderatorfaculty_id;

        }    
      
        $this->resetPage();
    }
   
   
    public function addexaminer_moderator()
    { 
        DB::beginTransaction();

        try 
        {
            $paperassessment=Paperassesment::find($this->lotnumber);

            if (!$paperassessment) 
            {
                $this->dispatch('alert',type:'info',message:'Paper assessment not found.!!');
            }
            else
            {
                $paperassessment->update([
                    'examinerfaculty_id'=>$this->selectedexaminer,
                    'moderatorfaculty_id'=>$this->selectedmoderator,
                    'exam_id'=>$this->exam->id,
                    'user_id'=>Auth::user()->id,
                ]);
        
                DB::commit();
                
                $this->lotnumber=null;
                $this->selectedexaminer=null;
                $this->selectedmoderator=null;
                $this->dispatch('alert',type:'success',message:'Record Added Successfully !!');
            }
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();
            $this->dispatch('alert',type:'error',message:'Failed To Add Record !!');
        }
    }

    #[Renderless]
    public function user_wise_report()
    {   
        $exam=Exam::where('status',1)->first();

        $user_wise_papaer_issue = Paperassesment::select('user_id',\DB::raw('SUM(total_papers) as total_papers'))
        ->whereNotNull('examinerfaculty_id')
        ->where('exam_id', $exam->id)
        ->groupBy('user_id')
        ->get();

        $count1=1;

        $userWiseReport = $user_wise_papaer_issue->map(function ($issue) use (&$count1) {
            return [
                'id' => $count1++,
                'user_name' => $issue->user->name ?? '',
                'total_papers' => $issue->total_papers,
            ];
        });

        $pdfContent = View::make('pdf.user.exam_marks.user_wise_papaer_issue_report',compact('userWiseReport','exam'))->render();


        $mpdf = new Mpdf();
        $mpdf->WriteHTML($pdfContent);
        return response()->streamDownload(function () use ($mpdf) {
            $mpdf->Output();
        }, 'user_wise_papaer_issue_report.pdf');

    }

    #[Renderless]
    public function date_wise_report()
    {   
        $exam=Exam::where('status',1)->first();

        $date_wise_papaer_issue = Paperassesment::select('bill_date',\DB::raw('SUM(total_papers) as total_papers'))
        ->whereNotNull('examinerfaculty_id')
        ->where('exam_id', $exam->id)
        ->groupBy('bill_date')
        ->get();

        $count=1;
        $userWiseReport = $date_wise_papaer_issue->map(function ($issue ) use (&$count) {
            return [
                'id' => $count++,
                'date' => $issue->bill_date ?? '',
                'total_papers' => $issue->total_papers,
            ];
        });

        $pdfContent = View::make('pdf.user.exam_marks.date_wise_papaer_issue_report',compact('userWiseReport','exam'))->render();


        $mpdf = new Mpdf();
        $mpdf->WriteHTML($pdfContent);
        return response()->streamDownload(function () use ($mpdf) {
            $mpdf->Output();
        }, 'date_wise_papaer_issue_report.pdf');

    }


    public function mount()
    {   
        $this->exam=Exam::where('status',1)->first();
    }

    public function render()
    {   
        $allpaperissue =Paperassesment::whereNotNull('examinerfaculty_id')->where('exam_id',$this->exam->id)->whereDate('updated_at',Carbon::today())->get();
        return view('livewire.user.exam-marks.ext-mark-paper-issue',compact('allpaperissue'))->extends('layouts.user')->section('user');
    }
}
