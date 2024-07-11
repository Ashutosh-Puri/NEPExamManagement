<?php

namespace App\Exports\Faculty\InternalAudit\AssignTool;

use App\Models\Facultysubjecttool;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AssignToolExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Facultysubjecttool::with(['faculty','subject','academicyear','departmenthead','verifybyfaculty','internaltoolmaster','facultysubjecttools'])
        ->search($this->search)->orderBy($this->sortColumn, $this->sortColumnBy)
        ->where('departmenthead_id',Auth::guard('faculty')->user()->id)
        ->get(['id','faculty_id', 'internaltoolmaster_id', 'subject_id', 'academicyear_id', 'departmenthead_id', 'freeze_by_faculty', 'freeze_by_hod', 'verifybyfaculty_id',]);
    }

    public function headings(): array
    {
        return ['ID', 'Academic Year', 'Subject Name', 'Pattern Name', 'Class Year', 'Course Name', 'Tool Name', ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            (isset($row->academicyear->year_name) ?  $row->academicyear->year_name : ''),
            (isset($row->subject->subject_name) ?  $row->subject->subject_name : ''),
            (isset($row->subject->patternclass->pattern->pattern_name) ?  $row->subject->patternclass->pattern->pattern_name : ''),
            (isset($row->subject->patternclass->courseclass->classyear->classyear_name) ?  $row->subject->patternclass->courseclass->classyear->classyear_name : ''),
            (isset($row->subject->patternclass->courseclass->course->course_name) ?  $row->subject->patternclass->courseclass->course->course_name : ''),
            (isset($row->internaltoolmaster->toolname) ?  $row->internaltoolmaster->toolname : ''),
        ];
    }
}
