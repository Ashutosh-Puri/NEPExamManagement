<?php

namespace App\Exports\User\Notice;

use App\Models\Notice;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class NoticeExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Notice::search($this->search)
        ->orderBy($this->sortColumn, $this->sortColumnBy)
        ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Title', 'Start Date','End date','Type','User','Description','Status'];
    }

    public function map($row): array
    {   


        return [
            $row->id,
            $row->title??'-',
            isset($row->start_date)?date('Y-m-d', strtotime($row->start_date)):'',
            isset($row->end_date)?date('Y-m-d', strtotime($row->end_date)):'',
            self::getType($row->type),
            isset($row->user->name)?$row->user->name:'',
            $row->description,
            $row->launch_status == 1 ? 'Active' : 'Inactive',
        ];
    }

    public static function getType($type)
    {
        switch ($type) {
            case 0:
                return 'Guest';
            case 1:
                return 'Student';
            case 2:
                return 'Faculty';
            case 3:
                return 'User';
            case 4:
                return 'All';
        }
    }


}