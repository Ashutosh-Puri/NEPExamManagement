<?php

namespace App\Exports\User\User;

use App\Models\User;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportUser implements FromCollection, WithHeadings, WithMapping
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
        return User::
        search($this->search)->
       orderBy($this->sortColumn, $this->sortColumnBy)
       ->select('id', 'name','email','user_contact_no','college_id','department_id','is_active','role_id')
       ->get();
    }

    public function headings(): array
    {
        return ['ID', ' Name',' Email','Contact No','College','Department','Role','Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->name,
            $row->email,
            $row->user_contact_no,
            isset($row->college->colllege_name)?$row->college->colllege_name:'',
            isset($row->department->dept_name)?$row->department->dept_name:'',
            isset($row->role->role_name)?$row->role->role_name:'',
           
        ];
    }
}
