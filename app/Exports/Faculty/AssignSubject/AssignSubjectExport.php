<?php

namespace App\Exports\Faculty\AssignSubject;

use App\Models\Subjectbucket;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AssignSubjectExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Subjectbucket::with(['department', 'patternclass', 'subjectvertical', 'subject', 'academicyear'])->whereNotIn('subjectvertical_id', [1])->search($this->search)->orderBy($this->sortColumn, $this->sortColumnBy)
         ->get(['id', 'academicyear_id', 'department_id', 'patternclass_id', 'subjectvertical_id', 'subject_id', 'status',]);
    }

    public function headings(): array
    {
        return ['ID', 'Academic Year Name', 'Department Name', 'Pattern', 'Class Year', 'Course Name', 'Subject Name', 'Subject Vertical Name', 'Subject Vertical Short Name', 'Status',];
    }

    public function map($row): array
    {
        return [
            $row->id,
            (isset($row->academicyear->year_name) ? $row->academicyear->year_name : ''),
            (isset($row->department->dept_name) ? $row->department->dept_name : ''),
            (isset($row->patternclass->pattern->pattern_name) ?  $row->patternclass->pattern->pattern_name : ''),
            (isset($row->patternclass->courseclass->classyear->classyear_name) ?  $row->patternclass->courseclass->classyear->classyear_name : ''),
            (isset($row->patternclass->courseclass->course->course_name) ?  $row->patternclass->courseclass->course->course_name : ''),
            (isset($row->subject->subject_name) ?  $row->subject->subject_name : ''),
            (isset($row->subjectvertical->subject_vertical) ?  $row->subjectvertical->subject_vertical : ''),
            (isset($row->subjectvertical->subjectvertical_shortname) ?  $row->subjectvertical->subjectvertical_shortname : ''),
            $row->status == 1 ? 'Active' : 'Inactive',
        ];
    }
}
