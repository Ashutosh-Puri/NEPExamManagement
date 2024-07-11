<?php

namespace App\Exports\User\Unfairmean;

use App\Models\Unfairmeans;
use App\Models\Unfairmeansmaster;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportUnfairmean implements FromCollection, WithHeadings, WithMapping
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
        return Unfairmeans::
         search($this->search)->
         orderBy($this->sortColumn, $this->sortColumnBy)
        ->select('id', 'exam_patternclasses_id', 'exam_studentseatnos_id','student_id','unfairmeansmaster_id','subject_id','punishment')->get();
    }

    public function headings(): array
    {
        return ['ID', 'Exam Pattern Class','Seat No','Student','Location','Subject','Punishment'];
    }


    public function map($row): array
    {
        return [
            $row->id,
             (isset($row->exampatternclass->exam->exam_name) ? $row->exampatternclass->exam->exam_name : '-').' '. (isset($row->exampatternclass->patternclass->pattern->pattern_name) ? $row->exampatternclass->patternclass->pattern->pattern_name : '-').' '.(isset($row->exampatternclass->patternclass->courseclass->classyear->classyear_name) ? $row->exampatternclass->patternclass->courseclass->classyear->classyear_name : '-').''.(isset($row->exampatternclass->patternclass->courseclass->course->course_name) ? $row->exampatternclass->patternclass->courseclass->course->course_name : '-'),
             isset($row->examstudentseatno->seatno)?$row->examstudentseatno->seatno:'',
             isset($row->student->student_name)?$row->student->student_name:'',
             isset($row->unfairmeans->location)?$row->unfairmeans->location:'',
             isset($row->subject->subject_name)?$row->subject->subject_name:'',
            $row->punishment,
            $row->status == 1 ? 'Active' : 'Inactive',
        ];
    }
}
