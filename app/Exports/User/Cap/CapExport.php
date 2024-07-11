<?php

namespace App\Exports\User\Cap;

use App\Models\Capmaster;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CapExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Capmaster::search($this->search)
            ->orderBy($this->sortColumn, $this->sortColumnBy)
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Cap Name',
            'Place',
            'Exam',
            'College',
            'Status',
        ];
    }

    public function map($row): array
    {   
        return [
            $row->id,
            $row->cap_name,
            isset($row->place)?$row->place:'',
            isset($row->exam->exam_name)?$row->exam->exam_name:'',
            isset($row->college->college_name)?$row->college->college_name:'',
            $row->status == 1 ? 'Active' : 'Inactive' ,
        ];
    }
}