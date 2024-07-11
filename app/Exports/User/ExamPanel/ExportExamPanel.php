<?php

namespace App\Exports\User\ExamPanel;

use App\Models\Exampanel;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportExamPanel implements FromCollection, WithHeadings, WithMapping
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
        return Exampanel::
         search($this->search)->
        orderBy($this->sortColumn, $this->sortColumnBy)
        ->select('id', 'faculty_id','examorderpost_id','subject_id', 'description','active_status')
        ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Faculty Name',' Exam Order Post Name',' Subject Name','Description','Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            isset($row->faculty->faculty_name)?$row->faculty->faculty_name:'',
            isset($row->examorderpost->post_name)?$row->examorderpost->post_name:'',
            isset($row->subject->subject_name)?$row->subject->subject_name:'',
            $row->description,
            $row->active_status == 1 ? 'Active' : 'Inactive',
        ];
    }


   
}
