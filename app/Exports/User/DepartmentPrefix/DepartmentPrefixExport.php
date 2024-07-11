<?php

namespace App\Exports\User\DepartmentPrefix;

use App\Models\Departmentprefix;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DepartmentPrefixExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Departmentprefix::with(['department', 'pattern',])->search($this->search)->orderBy($this->sortColumn, $this->sortColumnBy)
        ->get(['id', 'dept_id', 'pattern_id', 'prefix', 'postfix', ]);
    }

    public function headings(): array
    {
        return ['ID', 'Department Name', 'Pattern Name', 'Prefix', 'Postfix'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            (isset($row->department->dept_name) ? $row->department->dept_name : ''),
            (isset($row->pattern->pattern_name) ?  $row->pattern->pattern_name : ''),
            $row->prefix,
            $row->postfix,
        ];
    }
}