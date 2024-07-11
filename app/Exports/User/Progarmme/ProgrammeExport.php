<?php

namespace App\Exports\User\Progarmme;

use App\Models\Programme;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProgrammeExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Programme::search($this->search)
        ->orderBy($this->sortColumn, $this->sortColumnBy)
        ->select('id', 'programme_name', 'active')
        ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Programme Name', 'Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->programme_name,
            $row->is_active == 1 ? 'Active' : 'Inactive',
        ];
    }
}
