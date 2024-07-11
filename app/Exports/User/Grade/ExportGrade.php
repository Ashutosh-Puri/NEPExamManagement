<?php

namespace App\Exports\User\Grade;

use App\Models\Gradepoint;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportGrade implements FromCollection ,WithHeadings, WithMapping
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
        return Gradepoint::
        search($this->search)
      ->orderBy($this->sortColumn, $this->sortColumnBy)
      ->select('id', 'max_percentage','min_percentage','grade_point','grade_name','description','is_active')
      ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Max Percentage','Min Percentage','Grade Point','Grade Name','Description','Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->max_percentage,
            $row->min_percentage ,
            $row->grade_point ,
            $row->grade_name ,
            $row->description ,
            $row->is_active == 1 ? 'Active' : 'Inactive',
        ];
    }
}
