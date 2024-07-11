<?php

namespace App\Exports\User\Paperset;

use App\Models\Paperset;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportPaperSet implements FromCollection, WithHeadings, WithMapping

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
        return Paperset::
         search($this->search)->
        orderBy($this->sortColumn, $this->sortColumnBy)
        ->select('id', 'set_name')
        ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Set Name'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->set_name,           
        ];
    }
}
