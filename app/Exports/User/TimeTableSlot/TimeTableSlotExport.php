<?php

namespace App\Exports\User\TimeTableSlot;

use App\Models\Timetableslot;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TimeTableSlotExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Timetableslot::search($this->search)
        ->orderBy($this->sortColumn, $this->sortColumnBy)
        ->select('id', 'slot','timeslot', 'isactive')
        ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Time Slot','Slot', 'Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->timeslot,
            $row->slot,
            $row->isactive == 1 ? 'Active' : 'Inactive',
        ];
    }
}