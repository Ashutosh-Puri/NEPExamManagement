<?php

namespace App\Exports\User\University;

use App\Models\University;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportUniversity implements FromCollection, WithHeadings, WithMapping
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
        return University::
          search($this->search)
        ->orderBy($this->sortColumn, $this->sortColumnBy)
        ->select('id', 'university_name','university_email','university_address','university_contact_no','university_website_url', 'status')
        ->get();
    }

    public function headings(): array
    {
        return ['ID', 'University Name','University Email','University Address','University Contact Number','University Website URL','Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->university_name,
            $row->university_email,
            $row->university_address,
            $row->university_contact_no,
            $row->university_website_url,
            $row->status == 1 ? 'Active' : 'Inactive',
        ];
    }

}
