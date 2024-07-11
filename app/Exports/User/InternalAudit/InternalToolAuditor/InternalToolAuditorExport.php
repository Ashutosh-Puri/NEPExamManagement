<?php

namespace App\Exports\User\InternalAudit\InternalToolAuditor;

use App\Models\Internaltoolauditor;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InternalToolAuditorExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Internaltoolauditor::with(['academicyear', 'faculty', 'patternclass',])->search($this->search)->orderBy($this->sortColumn, $this->sortColumnBy)
        ->get(['id','patternclass_id', 'faculty_id', 'academicyear_id', 'evaluationdate', 'status']);
    }

    public function headings(): array
    {
        return ['ID', 'Academic Year', 'Pattern Name', 'Class Year', 'Course Name', 'Faculty Name', 'Evaluation Date', 'Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            isset($row->academicyear->year_name) ? $row->academicyear->year_name : '',
            isset($row->patternclass->pattern->pattern_name) ? $row->patternclass->pattern->pattern_name : '',
            isset($row->patternclass->courseclass->classyear->classyear_name) ? $row->patternclass->courseclass->classyear->classyear_name : '',
            isset($row->patternclass->courseclass->course->course_name) ? $row->patternclass->courseclass->course->course_name : '',
            isset($row->faculty->faculty_name) ? $row->faculty->faculty_name : '',
            isset($row->evaluationdate) ? date('d-m-Y', strtotime($row->evaluationdate)) : '',
            $row->status == 1 ? 'Active' : 'Inactive',
        ];
    }
}