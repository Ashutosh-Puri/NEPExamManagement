<?php

namespace App\Livewire\User\ExamFormStatistics;

use Excel;
use App\Models\Exam;
use Livewire\Component;
use App\Models\Exampatternclass;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Exports\User\ExamFormStatistics\ExamFormStatisticsExport;

class ExamFormStatistics extends Component
{   
    # By Ashutosh
    public $exam;
    public $ext;

    #[Renderless]
    public function export()
    {   
        try 
        {
            set_time_limit(600);
            ini_set('memory_limit', '1024M');

            $filename="Exam_Form_Statistics_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new ExamFormStatisticsExport(), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new ExamFormStatisticsExport(), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new ExamFormStatisticsExport(), $filename.'.pdf', \Maatwebsite\Excel\Excel::MPDF);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Exam Form Statistics Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {   
            $this->dispatch('alert',type:'error',message:'Failed To Export Exam Form Statistics !!');
        }
    }

    public function mount()
    {
        $this->exam = Exam::where('status', 1)->first();
    }

    public function render()
    {

        $statistics = Exampatternclass::with(['patternclass.courseclass.classyear:id,classyear_name','patternclass.courseclass.course:id,course_name', 'patternclass.pattern:id,pattern_name'])
        ->selectRaw('*, getTotalStudents(patternclass_id,' . $this->exam->academicyear_id . ') AS total_students ,getIncompleteForms(patternclass_id, exam_id) AS incomplete_forms, getYetToInwardForms(patternclass_id, exam_id) AS yet_to_inward_forms ,getInwardCompletedForms(patternclass_id, exam_id) AS inward_completed_forms , getTotalFeeReceived(patternclass_id, exam_id) AS total_fee_received ')
        ->where('exam_id', $this->exam->id)
        ->get();

        return view('livewire.user.exam-form-statistics.exam-form-statistics',compact('statistics'))->extends('layouts.user')->section('user');
    }
}