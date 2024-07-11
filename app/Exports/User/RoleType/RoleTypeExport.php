<?php

namespace App\Exports\User\RoleType;

use App\Models\Roletype;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RoleTypeExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Roletype::search($this->search)->orderBy($this->sortColumn, $this->sortColumnBy)->get(['id','roletype_name', 'status']);
    }

    public function headings(): array
    {
        return ['ID', 'Roletype Name', 'Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->roletype_name,
            $row->status == 1 ? 'Active' : 'Inactive',
        ];
    }
}
