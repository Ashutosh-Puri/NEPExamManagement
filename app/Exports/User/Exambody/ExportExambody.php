<?php

namespace App\Exports\User\Exambody;

use App\Models\Exambody;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportExambody implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Exambody::search($this->search)
            ->orderBy($this->sortColumn, $this->sortColumnBy)
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Faculty',
            'Role',
           
            'Status',
        ];
    }

    public function map($row): array
    {   
        return [
            $row->id,
            isset($row->faculty->faculty_name)?$row->faculty->faculty_name:'',
            isset($row->role->role_name)?$row->role->role_name:'',
            $row->status == 1 ? 'Active' : 'Inactive' ,
        ];
    }
}
