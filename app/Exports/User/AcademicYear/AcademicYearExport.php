<?php

namespace App\Exports\User\AcademicYear;

use App\Models\Academicyear;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AcademicYearExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Academicyear::search($this->search)
        ->orderBy($this->sortColumn, $this->sortColumnBy)
        ->select('id', 'year_name', 'active')
        ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Academic Year', 'Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->year_name,
            $row->active == 1 ? 'Active' : 'Inactive',
        ];
    }
}