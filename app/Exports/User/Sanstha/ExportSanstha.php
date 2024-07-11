<?php

namespace App\Exports\User\Sanstha;

use App\Models\Sanstha;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportSanstha implements FromCollection, WithHeadings, WithMapping
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
        return Sanstha::
         search($this->search)
        ->orderBy($this->sortColumn, $this->sortColumnBy)
        ->select('id', 'sanstha_name','sanstha_chairman_name','sanstha_address','sanstha_website_url', 'status')
        ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Sanstha Name','Sanstha Chairman Name','Sanstha Address','Sanstha website url','status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->sanstha_name,
            $row->sanstha_chairman_name,
            $row->sanstha_address,
            $row->sanstha_website_url,
            $row->status == 1 ? 'Active' : 'Inactive',
        ];
    }
}
