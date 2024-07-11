<?php

namespace App\Exports\User\Ordinace;

use App\Models\Studentordinace163;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class Ordinace163StudentExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Studentordinace163::search($this->search)
        ->orderBy($this->sortColumn, $this->sortColumnBy)
        ->get();
    }

    public function headings(): array
    {
        return ['ID',"Seatno", 'Student','Exam','Class','Activity Name', 'Marks','Marks Used','Fee','Fee Paid','Transaction ID','Paid Date','Apply','Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->seatno,
            $row->student->student_name,
            $row->exam->exam_name,
            (isset($row->patternclass->pattern->pattern_name) ? $row->patternclass->pattern->pattern_name : '-').' '.(isset($row->patternclass->courseclass->classyear->classyear_name) ? $row->patternclass->courseclass->classyear->classyear_name : '-').''.(isset($row->patternclass->courseclass->course->course_name) ? $row->patternclass->courseclass->course->course_name : '-'),
            $row->ordinace163master->activity_name,
            $row->marks,
            $row->marksused,
            $row->fee,
            $row->is_fee_paid == 1 ? 'Y' : 'N',
            $row->transaction_id ,
            $row->payment_date ,
            $row->is_applicable == 1 ? 'Y' : 'N',
            $row->status == 1 ? 'Active' : 'Inactive',
        ];
    }
}