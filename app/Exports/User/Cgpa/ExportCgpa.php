<?php

namespace App\Exports\User\Cgpa;

use App\Models\Cgpa;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection ;

class ExportCgpa implements  FromCollection, WithHeadings, WithMapping
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
        return Cgpa::
         search($this->search)->
        orderBy($this->sortColumn, $this->sortColumnBy)
        ->select('id', 'max_gp','min_gp','grade', 'description')
        ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Max Grade Points','Min Grade Points','Grades','Description'];
    }

    
    public function map($row): array
    {
        return [
            $row->id,
            $row->max_gp,
            $row->min_gp,
            $row->grade,
            $row->description,
           
        ];
    }
}
