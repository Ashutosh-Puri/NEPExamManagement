<?php

namespace App\Exports\User\AdmissionData;

use App\Models\Admissiondata;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AdmissionDataExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        $results = collect([]);

        Admissiondata::with(['patternclass.pattern:pattern_name,id','academicyear:year_name,id', 'patternclass.courseclass.course:course_name,id', 'patternclass.pattern:pattern_name,id','patternclass.courseclass.classyear:classyear_name,id', 'subject:subject_name,id','college:college_name,id','user:name,id'])
        ->search($this->search)
        ->orderBy($this->sortColumn, $this->sortColumnBy)
        ->chunk(100, function ($admissionData) use ($results) {
            $results->push($admissionData);
        });

        return $results->flatten(1);
    }

    public function headings(): array
    {
        return ['ID', 'Member ID', 'Student','Subject','User','Pattern Class','Academic Year','College'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->memid ?? '-',
            $row->stud_name ?? '-',
            isset($row->subject->subject_name) ? $row->subject->subject_name : '-',
            isset($row->user->name) ? $row->user->name : '-',
            (isset($row->patternclass->courseclass->classyear->classyear_name) ? $row->patternclass->courseclass->classyear->classyear_name : '-') . ' ' . (isset($row->patternclass->courseclass->course->course_name) ? $row->patternclass->courseclass->course->course_name : '-'). ' ' . (isset($row->patternclass->pattern->pattern_name) ? $row->patternclass->pattern->pattern_name : '-'),
            isset($row->academicyear->year_name) ? $row->academicyear->year_name : '-',
            isset($row->college->college_name) ? $row->college->college_name : '-',
        ];
    }
}
