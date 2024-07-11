<?php

namespace App\Exports\User\Blockmaster;


use App\Models\Blockmaster;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportBlockMaster implements FromCollection , WithHeadings, ShouldAutoSize, WithMapping
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
        return Blockmaster::search($this->search)
            ->orderBy($this->sortColumn, $this->sortColumnBy)
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Block Name',
            'Block Size',
            'Status',
        ];
    }

    public function map($row): array
    {   
        return [
            $row->id,
            $row->block_name,
            $row->block_size,
            $row->status == 1 ? 'Active' : 'Inactive' ,
        ];
    }
}

