<?php

namespace App\Exports\User\DepartmentType;

use App\Models\Departmenttype;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportDepartmentType implements FromCollection , WithHeadings, WithMapping
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
        return Departmenttype::
        search($this->search)
      ->orderBy($this->sortColumn, $this->sortColumnBy)
      ->select('id', 'departmenttype','description','status')
      ->get();
    }

    public function headings(): array
    {
        return ['ID','Department Type', 'Description','Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->departmenttype,
            $row->description,
            $row->status == 1 ? 'Active' : 'Inactive',
        ];
    }
}
