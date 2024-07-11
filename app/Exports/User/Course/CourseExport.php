<?php

namespace App\Exports\User\Course;

use App\Models\Course;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CourseExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Course::with([
            'college',
            'programme',
            ])
            ->search($this->search)
            ->orderBy($this->sortColumn, $this->sortColumnBy)
            ->get([
                'id',
                'course_name',
                'course_code',
                'fullname',
                'special_subject',
                'course_type',
                'course_category_id',
                'college_id',
                'programme_id',
            ]);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Course',
            'Code',
            'Full Name',
            'Special Subject',
            'Type',
            'Category',
            'College',
            'Programme'
        ];
    }

    public function map($row): array
    {   
        return [
            $row->id,
            $row->course_name,
            $row->course_code,
            $row->fullname,
            $row->special_subject,
            $row->course_type,
            isset($row->coursecategory->course_category)?$row->coursecategory->course_category:'-' ,
            $row->college->college_name,
            $row->programme->programme_name,
        ];
    }
}
