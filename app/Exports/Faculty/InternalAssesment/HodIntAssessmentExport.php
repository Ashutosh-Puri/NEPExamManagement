<?php

namespace App\Exports\Faculty\InternalAssesment;

use App\Models\Hodappointsubject;
use App\Models\Facultysubjecttool;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class HodIntAssessmentExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    protected $acdemicyear_id;

    public function __construct($acdemicyear_id)
    {
        $this->acdemicyear_id = $acdemicyear_id;
    }

    public function collection()
    {
        $documents = Facultysubjecttool::with('subject','academicyear')->where('faculty_id', Auth::guard('faculty')->user()->id)
        ->when($this->acdemicyear_id, function($query){ $query->where('academicyear_id',$this->acdemicyear_id); })
        ->withCount([ 'facultysubjecttools as facultyinternaldocuments_count' => function ($query) {   $query->whereNotNull('document_filePath'); } ]);

        $uploaded_documents = $documents->distinct(['subject_id'])->orderBy('subject_id','DESC')->get();


        $not_uploaded_documents = Hodappointsubject::with('subject','subject.academicyear')->select('id','subject_id')->where('faculty_id', Auth::guard('faculty')->user()->id)
        ->whereNotIn('subject_id',$documents->distinct(['subject_id'])->pluck('subject_id'))->whereHas('subject', function ($subQuery)  { $subQuery->where('academicyear_id','<=',$this->acdemicyear_id); })
        ->orderBy('subject_id','DESC')
        ->get();


        $uploaded_documents_mapped = $uploaded_documents->map(function ($item) {
            return [
                'subject_id' => $item->subject_id,
                'academic_year' => $item->academicyear->year_name,
                'subject_name' => $item->subject->subject_code.' '.$item->subject->subject_name,
                'status' => 'Assessed'
            ];
        });

        $not_uploaded_documents_mapped = $not_uploaded_documents->map(function ($item) {
            return [
                'subject_id' => $item->subject->id,
                'academic_year' => $item->subject->academicyear->year_name,
                'subject_name' => $item->subject->subject_code.' '.$item->subject->subject_name,
                'status' => 'Not Assessed'
            ];
        });

        return  $merged_documents = $uploaded_documents_mapped->concat($not_uploaded_documents_mapped);
    }

    public function headings(): array
    {
        return ['ID','Academic Year', 'Subject Name', 'Assessment' ];
    }

    public function map($row): array
    {
        return [
            $row['subject_id'],
            $row['academic_year'],
            $row['subject_name'],
            $row['status'],
        ];
    }
}
