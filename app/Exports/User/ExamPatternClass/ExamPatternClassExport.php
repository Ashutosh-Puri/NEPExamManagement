<?php

namespace App\Exports\User\ExamPatternClass;

use App\Models\Exampatternclass;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExamPatternClassExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Exampatternclass::search($this->search)
        ->orderBy($this->sortColumn, $this->sortColumnBy)
        ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Exam', 'Pattern Class','Cap','Result Date','Start Dates','End Date','Late Fee Date','Internal Marks Start Date','Internal Marks End Date','Fine Fee Date','Cap Scheduled Date','Paper Setting Start Date', 'Paper Submission Date','Place Of Meeting','Description','Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->exam->exam_name??'-',
            (isset($row->patternclass->pattern->pattern_name) ? $row->patternclass->pattern->pattern_name : '-').' '.(isset($row->patternclass->courseclass->classyear->classyear_name) ? $row->patternclass->courseclass->classyear->classyear_name : '-').''.(isset($row->patternclass->courseclass->course->course_name) ? $row->patternclass->courseclass->course->course_name : '-'),
            isset($row->capmaster->cap_name)?$row->capmaster->cap_name:'',
            $row->result_date,
            isset($row->start_date)?date('Y-m-d', strtotime($row->start_date)):'',
            isset($row->end_date)?date('Y-m-d', strtotime($row->end_date)):'',
            isset($row->latefee_date)?date('Y-m-d', strtotime($row->latefee_date)):'',
            isset($row->intmarksstart_date)?date('Y-m-d', strtotime($row->intmarksstart_date)):'',
            isset($row->intmarksend_date)?date('Y-m-d', strtotime($row->intmarksend_date)):'',
            isset($row->finefee_date)?date('Y-m-d', strtotime($row->finefee_date)):'',
            isset($row->capscheduled_date)?date('Y-m-d', strtotime($row->capscheduled_date)):'',
            isset($row->papersettingstart_date)?date('Y-m-d', strtotime($row->papersettingstart_date)):'',
            isset($row->papersubmission_date)?date('Y-m-d', strtotime($row->papersubmission_date)):'',
            $row->placeofmeeting,
            $row->description,
            $row->launch_status == 1 ? 'Launched' : 'Not Launched',
        ];
    }
}
