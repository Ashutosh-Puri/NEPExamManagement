<?php

namespace App\Exports\User\College;


use App\Models\College;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
class CollegeExport implements  FromCollection, WithHeadings, WithMapping
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
        return College::
        search($this->search)->
        orderBy($this->sortColumn, $this->sortColumnBy)
        ->select('id', 'college_name','college_email','college_address','college_contact_no','sanstha_id','university_id','status')
        ->get();
    }

    public function headings(): array
    {
        return ['ID', 'College Name','College Email','College Address','Contact Number','Sanstha','University','Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->college_name,
            $row->college_email,
            $row->college_address,
            $row->college_contact_no,
            isset($row->sanstha->sanstha_name)?$row->sanstha->sanstha_name:'',
            isset($row->university->university_name)?$row->university->university_name:'',
            $row->university?->university_name,
            $row->status == 1 ? 'Active' : 'Inactive',
        ];
    }

}
