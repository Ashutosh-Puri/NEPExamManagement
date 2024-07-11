<?php

namespace App\Exports\User\ExamOrder;

use App\Models\Examorder;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportExamOrder implements FromCollection,WithHeadings, WithMapping
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
        return Examorder::
        search($this->search)
      ->orderBy($this->sortColumn, $this->sortColumnBy)
      ->select('id', 'exampanel_id','exam_patternclass_id','description','email_status',)
      ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Exam Panel','Exam Pattern Class',' Description', 'Email Sent'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            (isset($row->exampanel->faculty->faculty_name) ? $row->exampanel->faculty->faculty_name : '-').' '. (isset($row->exampanel->subject->subject_name) ? $row->exampanel->subject->subject_name : '-').' '.(isset($row->exampanel->examorderpost->post_name) ? $row->exampanel->examorderpost->post_name : '-'),
            (isset($row->exampatternclass->exam->exam_name) ? $row->exampatternclass->exam->exam_name : '-').' '. (isset($row->exampatternclass->patternclass->pattern->pattern_name) ? $row->exampatternclass->patternclass->pattern->pattern_name : '-').' '.(isset($row->exampatternclass->patternclass->courseclass->classyear->classyear_name) ? $row->exampatternclass->patternclass->courseclass->classyear->classyear_name : '-').''.(isset($row->exampatternclass->patternclass->courseclass->course->course_name) ? $row->exampatternclass->patternclass->courseclass->course->course_name : '-'),
            $row->description,   
            $row->email_status == 1 ? 'Sent' : 'Not Sent',
        ];
    }
}
