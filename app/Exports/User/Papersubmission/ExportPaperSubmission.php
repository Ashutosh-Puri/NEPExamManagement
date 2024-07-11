<?php

namespace App\Exports\User\Papersubmission;

use App\Models\Papersubmission;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportPaperSubmission implements FromCollection, WithHeadings, WithMapping
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
        return Papersubmission::
         search($this->search)->
        orderBy($this->sortColumn, $this->sortColumnBy)
        ->select('id', 'exam_id','faculty_id','subject_id','user_id','noofsets','status')
        ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Exam ','Faculty ','Subject','User','No of Set','Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            isset($row->exam->exam_name)?$row->exam->exam_name:'',
            isset($row->faculty->faculty_name)?$row->faculty->faculty_name:'',
            isset($row->subject->subject_name)?$row->subject->subject_name:'',
            isset($row->user->name)?$row->user->name:'',
            $row->noofsets,
            $row->status == 1 ? 'Active' : 'Inactive',
        ];
    }

}
