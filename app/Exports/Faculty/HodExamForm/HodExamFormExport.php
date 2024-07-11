<?php

namespace App\Exports\Faculty\HodExamForm;

use App\Models\Subject;
use App\Models\Examformmaster;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class HodExamFormExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    protected $search;
    protected $sortColumn;
    protected $sortColumnBy;

    public function __construct($search, $sortColumn, $sortColumnBy)
    {
        $this->search = $search;
        $this->sortColumn = $sortColumn;
        $this->sortColumnBy = $sortColumnBy;
    }

    public function collection()
    {
        $hod_subject_pc =Subject::whereHas('hodappointsubjects',function( $query){
            $query->where('faculty_id',Auth::guard('faculty')->user()->id);
        })->pluck('patternclass_id');


        return Examformmaster::whereIn('patternclass_id', $hod_subject_pc)->with(['student', 'exam', 'patternclass'])
        ->when($this->search, function ($query, $search) {
            $query->search($search);
        })->orderBy($this->sortColumn, $this->sortColumnBy)->get();
    }

    public function headings(): array
    {
        return ['ID', 'Academic Year','Exam','Pattern Class', 'Student Name', 'Inward', 'Form Date'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->exam->academicyear->year_name??'-',
            isset($row->exam->exam_name)?$row->exam->exam_name:'',
            (isset($row->patternclass->courseclass->classyear->classyear_name) ? $row->patternclass->courseclass->classyear->classyear_name : '-').' '.(isset($row->patternclass->courseclass->course->course_name) ? $row->patternclass->courseclass->course->course_name : '-').' '.(isset($row->patternclass->pattern->pattern_name) ? $row->patternclass->pattern->pattern_name : '-'),
            isset($row->student->student_name)?$row->student->student_name:'',
            $row->inwardstatus == 1 ? 'Yes' : 'No',
            isset($row->created_at)?$row->created_at->format('Y-m-d'):'',
        ];
    }
}
