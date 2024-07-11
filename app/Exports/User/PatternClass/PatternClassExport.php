<?php

namespace App\Exports\User\PatternClass;

use App\Models\Patternclass;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PatternClassExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Patternclass::with('pattern','courseclass')->search($this->search)
        ->orderBy($this->sortColumn, $this->sortColumnBy)
        ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Pattern', 'Course Class','Sem 1 Total Marks','Sem 2 Total Mark','Sem 1 Credits','Sem 2 Credits','ATKT Credit','Sem 1 Total Subjects','Sem 2 Total Subjects', 'Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->pattern->pattern_name??'-',
            (isset($row->courseclass->classyear->classyear_name) ? $row->courseclass->classyear->classyear_name : '-').''.(isset($row->courseclass->course->course_name) ? $row->courseclass->course->course_name : '-'),
            $row->sem1_total_marks,
            $row->sem2_total_marks,
            $row->sem1_credits,
            $row->sem2_credits,
            $row->credit,
            $row->sem1_totalnosubjects,
            $row->sem2_totalnosubjects,
            $row->status == 1 ? 'Active' : 'Inactive',
        ];
    }
}