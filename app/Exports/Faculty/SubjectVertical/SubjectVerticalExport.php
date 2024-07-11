<?php

namespace App\Exports\Faculty\SubjectVertical;

use App\Models\Subjectvertical;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SubjectVerticalExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Subjectvertical::search($this->search)->orderBy($this->sortColumn, $this->sortColumnBy)->get(['id','subject_vertical', 'subjectvertical_shortname', 'subjectbuckettype_id', 'is_active']);
    }

    public function headings(): array
    {
        return ['ID', 'Subject Vertical', 'Subject Vertical Shortname', 'Subject Bucket Type', 'Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->subject_vertical,
            $row->subjectvertical_shortname,
            (isset($row->buckettype->buckettype_name) ? $row->buckettype->buckettype_name : ''),
            $row->is_active == 1 ? 'Active' : 'Inactive',
        ];
    }
}
