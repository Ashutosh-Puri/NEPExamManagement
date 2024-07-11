<?php

namespace App\Exports\User\Role;

use App\Models\Role;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RoleExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Role::with(['roletype','college',])->search($this->search)->orderBy($this->sortColumn, $this->sortColumnBy)
         ->get(['id','role_name','roletype_id','college_id',]);
    }

    public function headings(): array
    {
        return ['ID', 'Role Name', 'Roletype Name', 'College Name'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->role_name,
            (isset($row->roletype->roletype_name) ? $row->roletype->roletype_name : ''),
            (isset($row->college->college_name) ? $row->college->college_name : ''),
        ];
    }
}

