<?php

namespace App\Exports\User\ExamFeeCourse;

use App\Models\Examfeecourse;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExamFeeCourseExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Examfeecourse::search($this->search)
        ->orderBy($this->sortColumn, $this->sortColumnBy)
        ->select('id', 'fee','sem','patternclass_id','examfees_id','approve_status','active_status')
        ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Fee','SEM', 'Pattern Class','Fee Name','Approved','Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->fee,
            isset($row->sem)?$row->sem:'',
            (isset($row->patternclass->pattern->pattern_name) ? $row->patternclass->pattern->pattern_name : '-') . ' '.
            (isset($row->patternclass->courseclass->classyear->classyear_name) ? $row->patternclass->courseclass->classyear->classyear_name : '-') . 
            (isset($row->patternclass->courseclass->course->course_name) ? $row->patternclass->courseclass->course->course_name : '-'),
            (isset($row->examfee->fee_name) ? $row->examfee->fee_name : '-'),
            $row->approve_status == 1 ? 'Yes' : 'No',
            $row->active_status == 1 ? 'Active' : 'Inactive',
        ];
    }
}