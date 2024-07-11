<?php

namespace App\Exports\User\Block;

use App\Models\Block;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportBlock implements FromCollection , WithHeadings, ShouldAutoSize, WithMapping
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
        return Block::search($this->search)
        ->orderBy($this->sortColumn, $this->sortColumnBy)
        ->select('id','building_id','classname','block','capacity','noofblocks', 'status')
        ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Building Name', 'Class Name','Block','Capacity','No of Blocks', 'Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            isset($row->building->building_name)?$row->building->building_name:'',
            $row->classname,
            $row->block,
            $row->capacity,
            $row->noofblocks,
            $row->status == 1 ? 'Active' : 'Inactive',
        ];
    }

}
