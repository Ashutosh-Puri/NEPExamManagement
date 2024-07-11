<?php

namespace App\Exports\User\Ratehead;

use App\Models\Ratehead;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportRatehead implements FromCollection, WithHeadings, WithMapping
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
        return Ratehead::
         search($this->search)->
        orderBy($this->sortColumn, $this->sortColumnBy)
        ->select('id', 'headname','type','noofcredit','status','course_type','amount')
        ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Head Name','Type','No of Credit','Course Type','Amount','Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->headname,
            $row->type,
            $row->noofcredit,
            $row->course_type,
            $row->amount,
            $row->status == 1 ? 'Active' : 'Inactive',
        ];
    }
}
