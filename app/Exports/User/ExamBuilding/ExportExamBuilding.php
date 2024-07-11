<?php

namespace App\Exports\User\ExamBuilding;

use App\Models\Exambuilding;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportExamBuilding implements FromCollection, WithHeadings, WithMapping
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
        return Exambuilding::
        search($this->search)->
       orderBy($this->sortColumn, $this->sortColumnBy)
       ->select('id', 'exam_id','building_id','status')
       ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Exam Name','Building Name','Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            isset($row->exam->exam_name)?$row->exam->exam_name:'',
            isset($row->building->building_name)?$row->building->building_name:'',
            $row->status == 1 ? 'Active' : 'Inactive',
        ];
    }

}
