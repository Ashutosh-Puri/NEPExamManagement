<?php

namespace App\Exports\User\InternalAudit\InternalTool;

use App\Models\Internaltoolmaster;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InternalToolExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Internaltoolmaster::search($this->search)->orderBy($this->sortColumn, $this->sortColumnBy)->get(['id','toolname', 'course_type', 'status']);
    }

    public function headings(): array
    {
        return ['ID', 'Tool Name', 'Course Type', 'Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->toolname,
            $row->course_type,
            $row->status == 1 ? 'Active' : 'Inactive',
        ];
    }
}