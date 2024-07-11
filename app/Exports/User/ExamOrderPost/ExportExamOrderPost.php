<?php

namespace App\Exports\User\ExamOrderPost;

use App\Models\Examorderpost;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportExamOrderPost implements FromCollection,WithHeadings, WithMapping
{ protected $search;
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
        return Examorderpost::
        search($this->search)->
       orderBy($this->sortColumn, $this->sortColumnBy)
       ->select('id', 'post_name','status')
       ->get();
    }

    public function headings(): array
    {
        return ['ID', ' Post Name','Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->post_name,
            $row->status == 1 ? 'Active' : 'Inactive',
        ];
    }
}
