<?php

namespace App\Exports\User\Building;

use App\Models\Building;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportBuilding implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Building::
         search($this->search)->
        orderBy($this->sortColumn, $this->sortColumnBy)
        ->select('id', 'building_name','Priority', 'status')
        ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Building Name','Priority','Status'];
    }

    
    public function map($row): array
    {
        return [
            $row->id,
            $row->building_name,
            $row->Priority,
            $row->status == 1 ? 'Active' : 'Inactive',
        ];
    }
}
