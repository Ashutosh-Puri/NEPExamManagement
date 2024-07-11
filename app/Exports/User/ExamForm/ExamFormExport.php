<?php

namespace App\Exports\User\ExamForm;

use Carbon\Carbon;
use App\Models\Examformmaster;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExamFormExport implements  FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return  Examformmaster::with('transaction')
        ->leftJoin('transactions', 'examformmasters.transaction_id', '=', 'transactions.id')->search($this->search)
        ->orderBy($this->sortColumn, $this->sortColumnBy)
        ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Academic Year','Exam','Pattern Class','PRN','Member ID','Student Name','Amount','Fee Paid','Inward','Payment ID','Payment Date','Form Date'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->exam->academicyear->year_name??'-',
            isset($row->exam->exam_name)?$row->exam->exam_name:'',
            (isset($row->patternclass->courseclass->classyear->classyear_name) ? $row->patternclass->courseclass->classyear->classyear_name : '-').' '.(isset($row->patternclass->courseclass->course->course_name) ? $row->patternclass->courseclass->course->course_name : '-').' '.(isset($row->patternclass->pattern->pattern_name) ? $row->patternclass->pattern->pattern_name : '-'),
            isset($row->student->prn)?$row->student->prn:'',
            isset($row->student->memid)?$row->student->memid:'',
            isset($row->student->student_name)?$row->student->student_name:'',
            $row->totalfee??'-',
            ($row->feepaidstatus == 1 ? 'Paid ' . (isset($row->transaction->status) ? ($row->transaction->status == 'captured'? 'Online' : 'Offline') : '') : 'Not Paid'),
            $row->inwardstatus == 1 ? 'Yes' : 'No',
            (isset($row->transaction->status) ? ($row->transaction->status == 'captured' ? $row->transaction->razorpay_payment_id : '') : ''),
            (isset($row->transaction->status) ? ($row->transaction->status == 'captured' ? Carbon::parse($row->transaction->payment_date)->format('Y-m-d h:i:s A')  : '') : ''),
            isset($row->created_at)?$row->created_at->format('Y-m-d'):'',
        ];
    }
}