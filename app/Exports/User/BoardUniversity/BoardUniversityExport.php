<?php

namespace App\Exports\User\BoardUniversity;

use App\Models\Boarduniversity;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BoardUniversityExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Boarduniversity::search($this->search)
        ->orderBy($this->sortColumn, $this->sortColumnBy)
        ->select('id', 'boarduniversity_name', 'is_active')
        ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Board / University', 'Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->boarduniversity_name,
            $row->is_active == 1 ? 'Active' : 'Inactive',
        ];
    }
}