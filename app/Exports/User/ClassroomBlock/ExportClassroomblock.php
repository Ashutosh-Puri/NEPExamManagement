<?php

namespace App\Exports\User\ClassroomBlock;

use App\Models\Classroomblock;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportClassroomblock implements FromCollection ,WithHeadings, ShouldAutoSize, WithMapping
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
        return Classroomblock::search($this->search)
            ->orderBy($this->sortColumn, $this->sortColumnBy)
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Class Name',
            'Block Name',
            'Status',
        ];
    }

    public function map($row): array
    {   
        return [
            $row->id,
            isset($row->classroom->class_name)?$row->classroom->class_name:'',
            isset($row->blockmaster->block_name)?$row->blockmaster->block_name:'',
            $row->status == 1 ? 'Active' : 'Inactive' ,
        ];
    }
}

