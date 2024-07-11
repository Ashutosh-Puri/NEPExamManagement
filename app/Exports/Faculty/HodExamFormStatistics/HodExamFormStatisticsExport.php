<?php

namespace App\Exports\Faculty\HodExamFormStatistics;

use App\Models\Subject;
use App\Models\Examformmaster;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class HodExamFormStatisticsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        $hod_subject_pc = Subject::whereHas('hodappointsubjects', function ($query) {
            $query->where('faculty_id', Auth::guard('faculty')->user()->id);
        })->pluck('patternclass_id');

        $exam_form_masters = Examformmaster::whereIn('patternclass_id', $hod_subject_pc)
            ->with('exam.academicyear', 'patternclass.courseclass.classyear', 'patternclass.courseclass.course', 'patternclass.pattern')
            ->get();

        $groupedExamFormMasters = $exam_form_masters->groupBy('patternclass_id');


        return $groupedExamFormMasters;
    }

    public function headings(): array
    {
        return ['ID', 'Academic Year', 'Exam', 'Pattern Class', 'Total Forms', 'Inward Forms', 'Not Inward Forms'];
    }

    public function map($row): array
    {
        return [
            $row->first()->id ?? '-',
            isset($row->first()->exam->academicyear->year_name)?$row->first()->exam->academicyear->year_name:'',
            isset($row->first()->exam->exam_name)?$row->first()->exam->exam_name:'',
            (isset($row->first()->patternclass->courseclass->classyear->classyear_name) ? $row->first()->patternclass->courseclass->classyear->classyear_name : '-').' '.(isset($row->first()->patternclass->courseclass->course->course_name) ? $row->first()->patternclass->courseclass->course->course_name : '-').' '.(isset($row->first()->patternclass->pattern->pattern_name) ? $row->first()->patternclass->pattern->pattern_name : '-'),
            $row->count(),
            $row->where('inwardstatus',1)->count(),
            $row->where('inwardstatus',0)->count(),
        ];
    }
}
