<?php

namespace App\Exports\User\CourseClass;

use App\Models\Courseclass;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CourseClassExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Courseclass::with([
            'college',
            'course',
            'classyear',
            'courseclass'
            ])
            ->search($this->search)
            ->orderBy($this->sortColumn, $this->sortColumnBy)
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Class',
            'Next Class',
            'Collge',
        ];
    }

    public function map($row): array
    {   
        return [
            $row->id,
            (isset($row->classyear->classyear_name) ? $row->classyear->classyear_name : '-').''.(isset($row->course->course_name) ? $row->course->course_name : '-'),
            (isset($row->courseclass->classyear->classyear_name) ? $row->courseclass->classyear->classyear_name : '-').''.(isset($row->courseclass->course->course_name) ? $row->courseclass->course->course_name : '-'),
            $row->college->college_name,
        ];
    }
}
