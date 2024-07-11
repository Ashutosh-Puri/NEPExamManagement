<?php

namespace App\Livewire\User\QuestionPaperBank;

use Excel;
use App\Models\Exam;
use Livewire\Component;
use App\Models\Paperset;
use Livewire\WithPagination;
use App\Models\Examformmaster;
use Livewire\Attributes\Renderless;
use Illuminate\Support\Facades\View;
use App\Exports\User\QuestionPaperBank\QuestionPaperBankExport;

class QuestionPaperBankReport extends Component
{   
    use WithPagination;
  
    
    public $perPage=10;
    public $ext;

    
    #[Renderless]
    public function export()
    {

        try 
        {
            set_time_limit(600);
            ini_set('memory_limit', '1024M');

            $filename="Question_Paper_Bank_Report_".now();
            
            $response = null;

            switch ($this->ext) 
            {
                case 'xlsx':
                    $response = Excel::download(new QuestionPaperBankExport, $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new QuestionPaperBankExport, $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new QuestionPaperBankExport, $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,['landscape' => true]);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Question Paper Bank Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            \Log::error($e);

            $this->dispatch('alert',type:'error',message:'Failed To Export Question Paper Bank  !!');
        }
    }

    public function render()
    {   
        $papersets=Paperset::get();
        $exam=Exam::where('status',1)->first();
        // $exam_form_masters =Examformmaster::where('exam_id',$exam->id)->paginate($this->perPage);
        $exam_form_masters =Examformmaster::where('exam_id',$exam->id)->get();
        return view('livewire.user.question-paper-bank.question-paper-bank-report',compact('papersets','exam','exam_form_masters'))->extends('layouts.user')->section('user');
    }
}
