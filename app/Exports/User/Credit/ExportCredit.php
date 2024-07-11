<?php

namespace App\Exports\User\Credit;

use App\Models\Subjectcredit;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportCredit implements FromCollection , WithHeadings, WithMapping
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
        return Subjectcredit::
        search($this->search)
      ->orderBy($this->sortColumn, $this->sortColumnBy)
      ->select('id', 'credit','marks','passing')
      ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Credit','Marks','Passing'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->credit,
            $row->marks,
            $row->passing,
        ];
    }
}
