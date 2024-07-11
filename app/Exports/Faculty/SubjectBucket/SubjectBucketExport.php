<?php

namespace App\Exports\Faculty\SubjectBucket;

use App\Models\Subjectbucket;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SubjectBucketExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Subjectbucket::with(['department', 'patternclass', 'academicyear', 'subjectvertical', 'subject',])->search($this->search)->orderBy($this->sortColumn, $this->sortColumnBy)
        ->get(['id', 'subject_id', 'department_id', 'patternclass_id', 'subject_division', 'subjectvertical_id', 'academicyear_id', 'status', ]);
    }

    public function headings(): array
    {
        return ['ID', 'Subject Name', 'Department Name', 'Pattern Name',
        'Course Name', 'Class Year', 'Academic Year', 'Subject Division', 'Subject Vertical', 'Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            (isset($row->subject->subject_name) ? $row->subject->subject_name : ''),
            (isset($row->department->dept_name) ?  $row->department->dept_name : ''),
            (isset($row->patternclass->pattern->pattern_name) ? $row->patternclass->pattern->pattern_name : ''),
            (isset($row->patternclass->courseclass->course->course_name) ?  $row->patternclass->courseclass->course->course_name : ''),
            (isset($row->patternclass->courseclass->classyear->classyear_name) ?  $row->patternclass->courseclass->classyear->classyear_name : '').' '.(isset($row->patternclass->courseclass->course->course_name) ? $row->patternclass->courseclass->course->course_name : ''),
            (isset($row->academicyear->year_name) ? $row->academicyear->year_name : ''),
            $row->subject_division,
            (isset($row->subjectvertical->subject_vertical) ? $row->subjectvertical->subject_vertical : ''),
            $row->status == 1 ? 'Active' : 'Inactive',
        ];
    }
}
