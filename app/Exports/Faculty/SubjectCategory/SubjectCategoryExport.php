<?php

namespace App\Exports\Faculty\SubjectCategory;

use App\Models\Subjectcategory;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SubjectCategoryExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Subjectcategory::search($this->search)->orderBy($this->sortColumn, $this->sortColumnBy)->get(['id','subjectcategory', 'subjectcategory_shortname', 'active']);
    }

    public function headings(): array
    {
        return ['ID', 'Subject Category', 'Subject Category Shortname', 'Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->subjectcategory,
            $row->subjectcategory_shortname,
            $row->active == 1 ? 'Active' : 'Inactive',
        ];
    }
}
