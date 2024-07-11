<?php

namespace App\Exports\Faculty\Subject;

use App\Models\Subject;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SubjectExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Subject::with(['subjectvertical','subjectcategory','subjecttype','patternclass', 'classyear', 'department', 'college', ])->search($this->search)->orderBy($this->sortColumn, $this->sortColumnBy)
        ->get(['id','subject_sem', 'subjectvertical_id', 'subject_order', 'subject_code', 'subject_name_prefix', 'subject_name', 'subject_type','subjectcategory_id', 'subject_credit', 'subject_maxmarks', 'subject_maxmarks_int', 'subject_maxmarks_intpract', 'subject_maxmarks_ext', 'subject_totalpassing', 'subject_intpassing', 'subject_intpractpassing', 'subject_extpassing', 'patternclass_id', 'classyear_id', 'faculty_id', 'department_id', 'college_id', 'status',]);
    }

    public function headings(): array
    {
        return ['ID', 'Subject Semester', 'Subject Vertical', 'Subject Order', 'Subject Code', 'Subject Name Prefix', 'Subject Name', 'Subject Category', 'Subject Type', 'Subject Credit', 'Subject Maximum Marks', 'Subject Maximum Internal Marks', 'Subject Maximum Internal Practical Marks', 'Subject Maximum External Marks', 'Subject Total Passing Marks', 'Subject Internal Passing', 'Subject Internal Passing Marks', 'Subject External Passing Marks', 'Pattern', 'Class Year', 'Course Name', 'Faculty Name', 'Department', 'College Name','Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->subject_sem,
            (isset($row->subjectvertical->subject_vertical) ?  $row->subjectvertical->subject_vertical : ''),
            $row->subject_order,
            $row->subject_code,
            $row->subject_name_prefix,
            $row->subject_name,
            (isset($row->subjectcategory->subjectcategory) ?  $row->subjectcategory->subjectcategory : ''),
            (isset($row->subjecttypes->type_name) ?  $row->subjecttypes->type_name : ''),
            $row->subject_credit,
            $row->subject_maxmarks,
            $row->subject_maxmarks_int,
            $row->subject_maxmarks_intpract,
            $row->subject_maxmarks_ext,
            $row->subject_totalpassing,
            $row->subject_intpassing,
            $row->subject_intpractpassing,
            $row->subject_extpassing,
            (isset($row->patternclass->pattern->pattern_name) ?  $row->patternclass->pattern->pattern_name : ''),
            (isset($row->patternclass->courseclass->classyear->classyear_name) ?  $row->patternclass->courseclass->classyear->classyear_name : ''),
            (isset($row->patternclass->courseclass->course->course_name) ?  $row->patternclass->courseclass->course->course_name : ''),
            (isset($row->faculty->faculty_name) ?  $row->faculty->faculty_name : ''),
            (isset($row->department->dept_name) ?  $row->department->dept_name : ''),
            (isset($row->college->college_name) ?  $row->college->college_name : ''),
            $row->status == 1 ? 'Active' : 'Inactive',
        ];
    }
}
