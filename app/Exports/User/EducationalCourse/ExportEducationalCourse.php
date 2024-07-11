<?php

namespace App\Exports\User\EducationalCourse;

use App\Models\Educationalcourse;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportEducationalCourse implements FromCollection, WithHeadings, WithMapping
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
         return Educationalcourse::
         search($this->search)->
         orderBy($this->sortColumn, $this->sortColumnBy)
         ->select('id', 'course_name','programme_id','is_active')
         ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Course Name','Programme Name','Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->course_name,
            isset($row->programme->programme_name)?$row->programme->programme_name:'',
            $row->is_active == 1 ? 'Active' : 'Inactive',
        ];
    }
}
