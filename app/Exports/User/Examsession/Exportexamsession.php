<?php

namespace App\Exports\User\Examsession;

use App\Models\Examsession;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class Exportexamsession implements FromCollection ,  WithHeadings, ShouldAutoSize, WithMapping
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
        return Examsession::search($this->search)
            ->orderBy($this->sortColumn, $this->sortColumnBy)
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'From Date',
            'To Date',
            'From Time',
            'To Time',
            'Session Type',
        ];
    }

    public function map($row): array
    {   
        return [
            $row->id,
            isset($row->from_date)?date('Y-m-d', strtotime($row->from_date)):'',
            isset($row->to_date)?date('Y-m-d', strtotime($row->to_date)):'',
            $row->from_time,
            $row->to_time,
            $row->session_type,
        ];
    }
}

