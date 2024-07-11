<?php

namespace App\Exports\User\Classroom;

use App\Models\Classroom;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportClassroom implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Classroom::search($this->search)
            ->orderBy($this->sortColumn, $this->sortColumnBy)
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Building name',
            'Classname',
            'No of Benches',
            'Status',
        ];
    }

    public function map($row): array
    {   
        return [
            $row->id,
            isset($row->building->building_name)?$row->building->building_name:'',
            $row->class_name,
            $row->noofbenches,
            $row->status == 1 ? 'Active' : 'Inactive' ,
        ];
    }
}
