<?php

namespace App\Exports\User\HodAppointSubject;

use App\Models\Hodappointsubject;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class HodAppointSubjectExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Hodappointsubject::with(['faculty','subject', 'patternclass', 'user'])->search($this->search)->orderBy($this->sortColumn, $this->sortColumnBy)
         ->get(['id','faculty_id','subject_id', 'patternclass_id', 'appointby_id','status']);
    }

    public function headings(): array
    {
        return ['ID', 'Faculty Name', 'Subject Name', 'Pattern Name', 'Class Year', 'Course Name', 'Appoint By Name','Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            (isset($row->faculty->faculty_name) ? $row->faculty->faculty_name : ''),
            (isset($row->subject->subject_name) ? $row->subject->subject_name : ''),
            (isset($row->patternclass->pattern->pattern_name) ?  $row->patternclass->pattern->pattern_name : ''),
            (isset($row->patternclass->courseclass->classyear->classyear_name) ?  $row->patternclass->courseclass->classyear->classyear_name : ''),
            (isset($row->patternclass->courseclass->course->course_name) ?  $row->patternclass->courseclass->course->course_name : ''),
            (isset($row->user->name) ? $row->user->name : ''),
            $row->status == 1 ? 'Active' : 'Inactive',
        ];
    }
}
