<?php

namespace App\Exports\Faculty\Faculty;

use App\Models\Faculty;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FacultyExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Faculty::with(['designation','department','college','facultybankaccount'])->search($this->search)->orderBy($this->sortColumn, $this->sortColumnBy)
        ->get(['id', 'prefix', 'faculty_name', 'email', 'mobile_no', 'college_id', 'department_id', 'designation_id', 'date_of_birth', 'gender', 'category', 'pan', 'current_address', 'permanant_address', 'unipune_id', 'qualification']);
    }

    public function headings(): array
    {
        return ['ID', 'Prefix', 'Faculty Name', 'Faculty Email', 'Faculty Mobile Number',
        'College Name', 'Department Name', 'Designation Name', 'Date of birth', 'Gender',
        'Category', 'Pan', 'Current Address', 'Permanant Address','Unipune ID', 'Qualification',];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->prefix,
            $row->faculty_name,
            $row->email,
            $row->mobile_no,
            (isset($row->college->college_name) ? $row->college->college_name : ''),
            (isset($row->department->dept_name) ? $row->department->dept_name : ''),
            (isset($row->designation->designation_name) ? $row->designation->designation_name : ''),
            $row->date_of_birth,
            $row->gender,
            $row->category,
            $row->pan,
            $row->current_address,
            $row->permanant_address,
            $row->unipune_id,
            $row->qualification,
        ];
    }
}
