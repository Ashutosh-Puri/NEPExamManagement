<?php

namespace App\Exports\User\Unfairmeans;

use App\Models\Unfairmeansmaster;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportUnfairmeans implements FromCollection, WithHeadings, WithMapping
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
        return Unfairmeansmaster::
         search($this->search)->
        orderBy($this->sortColumn, $this->sortColumnBy)
        ->select('id', 'location','date','time','exam_id','status')
        ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Place of Meeting','Date','Time','Exam','Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->location,
            $row->date,
            $row->time,
            isset($row->exam->exam_name)?$row->exam->exam_name:'',
            $row->status == 1 ? 'Active' : 'Inactive',
        ];
    }
}
