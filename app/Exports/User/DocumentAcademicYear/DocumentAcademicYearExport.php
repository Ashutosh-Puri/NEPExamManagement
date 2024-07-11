<?php

namespace App\Exports\User\DocumentAcademicYear;

use App\Models\Documentacademicyear;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DocumentAcademicYearExport implements FromCollection ,WithHeadings, ShouldAutoSize, WithMapping
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
        return Documentacademicyear::search($this->search)
        ->orderBy($this->sortColumn, $this->sortColumnBy)
        ->select('id', 'year_name','start_date','end_date','description','active')
        ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Academic Year', 'Start Date','End Date','Description','Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->year_name,
            $row->start_date,
            $row->end_date,
            $row->description,
            $row->active == 1 ? 'Active' : 'Inactive',
        ];
    }
}
