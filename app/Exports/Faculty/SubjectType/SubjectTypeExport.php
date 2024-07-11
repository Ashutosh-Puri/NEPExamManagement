<?php

namespace App\Exports\Faculty\SubjectType;

use App\Models\Subjecttype;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SubjectTypeExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Subjecttype::search($this->search)->orderBy($this->sortColumn, $this->sortColumnBy)->get(['id','type_name', 'description', 'is_active']);
    }

    public function headings(): array
    {
        return ['ID', 'Type Name', 'Description', 'Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->type_name,
            $row->description,
            $row->is_active == 1 ? 'Active' : 'Inactive',
        ];
    }
}
