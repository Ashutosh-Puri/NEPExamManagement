<?php

namespace App\Livewire\Faculty\InternalAssessment;

use Excel;
use Livewire\Component;
use App\Models\Academicyear;
use Livewire\WithPagination;
use App\Models\Hodappointsubject;
use App\Models\Facultysubjecttool;
use Livewire\Attributes\Renderless;
use Illuminate\Support\Facades\Auth;
use App\Exports\Faculty\InternalAssesment\HodIntAssessmentExport;

class PendingHodIntAssessment extends Component
{

    use WithPagination;
    public $perPage=10;
    public $search='';
    public $sortColumn="subject_id";
    public $sortColumnBy="DESC";
    public $ext;
    public $acdemicyears;
    public $acdemicyear_id;

    public function reset_input()
    {
        $this->acdemicyear_id=$this->acdemicyear_id =$this->acdemicyears->where('active',1)->first()->id;
    }

    #[Renderless]
    public function export()
    {
        try
        {
            set_time_limit(600);
            ini_set('memory_limit', '1024M');

            $filename="HOD_Subject_Internal_Assessment_Report_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new HodIntAssessmentExport($this->acdemicyear_id), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new HodIntAssessmentExport($this->acdemicyear_id), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new HodIntAssessmentExport($this->acdemicyear_id), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'HOD Subject Internal Assessment Exported Successfully !!');

            return $response;
        }
        catch (\Exception $e)
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export HOD Subject Internal Assessment Exported  !!');
        }
    }

    public function mount()
    {
        $this->acdemicyears = Academicyear::all();
        $this->reset_input();
    }

    public function render()
    {
        // $documents = Facultysubjecttool::with('subject','academicyear')->where('faculty_id', Auth::guard('faculty')->user()->id)
        // ->when($this->acdemicyear_id, function($query){ $query->where('academicyear_id',$this->acdemicyear_id); })
        // ->withCount([ 'facultysubjecttools as facultyinternaldocuments_count' => function ($query) {   $query->whereNotNull('document_filePath'); } ]);

        $documents = Facultysubjecttool::with('subject', 'academicyear')
        ->whereHas('facultysubjecttools', function ($query) {
            $query->where('departmenthead_id', Auth::guard('faculty')->user()->id)
                ->whereNotNull('document_filePath'); // If you want to include this condition
        })
        ->when($this->acdemicyear_id, function ($query) {
            $query->whereHas('academicyear', function ($subQuery) {
                $subQuery->where('id', '<=', $this->acdemicyear_id);
            });
        });

        $uploaded_documents = $documents->distinct(['subject_id'])->orderBy('subject_id','DESC')->paginate($this->perPage);


        $not_uploaded_documents = Hodappointsubject::with('subject','subject.academicyear')->select('id','subject_id')->where('faculty_id', Auth::guard('faculty')->user()->id)
        ->whereNotIn('subject_id',$documents->distinct(['subject_id'])->pluck('subject_id'))->whereHas('subject', function ($subQuery)  { $subQuery->where('academicyear_id','<=',$this->acdemicyear_id); })
        ->orderBy('subject_id','DESC')
        ->paginate($this->perPage);




        return view('livewire.faculty.internal-assessment.pending-hod-int-assessment',compact('uploaded_documents','not_uploaded_documents'))->extends('layouts.faculty')->section('faculty');
    }
}
