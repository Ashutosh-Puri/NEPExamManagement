<?php

namespace App\Exports\User\Exam;

use App\Models\Exam;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportExam implements FromCollection, WithHeadings, WithMapping
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
        return Exam::
        search($this->search)
      ->orderBy($this->sortColumn, $this->sortColumnBy)
      ->select('id', 'exam_name','academicyear_id','exam_sessions','status')
      ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Exam Name','Academic Year','Exam Session','status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->exam_name,
            isset($row->academicyear->year_name)?$row->academicyear->year_name:'',
            $row->exam_sessions == 1 ? 'Session 1' : 'Session 2',
            $row->status == 1 ? 'Active' : 'Inactive',
        ];
    }
}
