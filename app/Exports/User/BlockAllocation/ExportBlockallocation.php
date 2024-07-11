<?php

namespace App\Exports\User\BlockAllocation;

use App\Models\Blockallocation;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportBlockallocation implements FromCollection,WithHeadings, WithMapping
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
        return Blockallocation::search($this->search)
            ->orderBy($this->sortColumn, $this->sortColumnBy)
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Block',
            'Class',
            'Exam Pattern Class',
            'Subject',
            'Faculty',
            'Status',
        ];
    }

    public function map($row): array
    {   
        return [
            $row->id,
            isset($row->block->block_name)?$row->block->block_name:'',
            isset($row->classroom->class_name)?$row->classroom->class_name:'',
            (isset($row->exampatternclass->exam->exam_name) ? $row->exampatternclass->exam->exam_name : '-').' '. (isset($row->exampatternclass->patternclass->pattern->pattern_name) ? $row->exampatternclass->patternclass->pattern->pattern_name : '-').' '.(isset($row->exampatternclass->patternclass->courseclass->classyear->classyear_name) ? $row->exampatternclass->patternclass->courseclass->classyear->classyear_name : '-').''.(isset($row->exampatternclass->patternclass->courseclass->course->course_name) ? $row->exampatternclass->patternclass->courseclass->course->course_name : '-'),
            isset($row->subject->subject_name)?$row->subject->subject_name:'',
            isset($row->faculty->faculty_name)?$row->faculty->faculty_name:'',
            $row->status == 1 ? 'Active' : 'Inactive' ,
        ];
    }
}