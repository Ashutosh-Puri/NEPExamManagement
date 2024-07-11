<?php

namespace App\Exports\User\ClassYear;

use App\Models\Classyear;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ClassYearExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Classyear::search($this->search)
        ->orderBy($this->sortColumn, $this->sortColumnBy)
        ->select('id', 'classyear_name','class_degree_name', 'status')
        ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Class Year Name', 'Class Degree Name', 'Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->classyear_name,
            $row->class_degree_name,
            $row->status == 1 ? 'Active' : 'Inactive',
        ];
    }
}