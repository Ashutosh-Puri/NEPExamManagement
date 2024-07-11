<?php

namespace App\Exports\User\Ordinace;

use App\Models\Ordinace163master;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class Ordinace163Export  implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Ordinace163master::search($this->search)
        ->orderBy($this->sortColumn, $this->sortColumnBy)
        ->select('id', 'activity_name','ordinace_name', 'status')
        ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Activity Name', 'Ordinace Name', 'Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->activity_name,
            $row->ordinace_name,
            $row->status == 1 ? 'Active' : 'Inactive',
        ];
    }
}