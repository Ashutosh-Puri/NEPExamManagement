<?php

namespace App\Exports\User\Pattern;

use App\Models\Pattern;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportPattern implements FromCollection, WithHeadings, WithMapping
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
        return Pattern::
        search($this->search)
      ->orderBy($this->sortColumn, $this->sortColumnBy)
      ->select('id', 'pattern_name','pattern_startyear','pattern_valid','college_id','status')
      ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Pattern Name','Pattern Start Year','Pattern Valid','College ID','status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->pattern_name,
            $row->pattern_startyear,
            $row->pattern_valid,
            isset($row->college->college_name)?$row->college->college_name:'',
            $row->status == 1 ? 'Active' : 'Inactive',
        ];
    }
}
