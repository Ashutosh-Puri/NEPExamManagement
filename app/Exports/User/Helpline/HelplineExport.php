<?php

namespace App\Exports\User\Helpline;

use App\Models\Studenthelpline;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class HelplineExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Studenthelpline::with([
            'studenthelplinequery',
            'student',
            'verified',
            // 'approved'
            ])
            ->search($this->search)
            ->orderBy($this->sortColumn, $this->sortColumnBy)
            ->get([
                'id',
                'student_id',
                'student_helpline_query_id',
                'status',
                // 'old_query',
                'new_query',
                // 'remark',
                // 'approve_by',
                'verified_by',
            ]);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Student',
            'Query Name',
            // 'Old Query',
            'New Query',
            // 'Remark',
            'Verified By',
            // 'Approved By',
            'Status'
        ];
    }

    public function map($row): array
    {   
        switch ($row->status) {
            case 0:
                $statusLabel = 'Pending';
                break;
            case 1:
                $statusLabel = 'Verified';
                break;
            case 2:
                $statusLabel = 'Approved';
                break;
            case 3:
                $statusLabel = 'Canceled';
                break;
            default:
                $statusLabel = 'Rejected';
        }

        return [
            $row->id,
            $row->student->student_name,
            $row->studenthelplinequery->query_name,
            // $row->old_query,
            $row->new_query,
            // $row->remark,
            $row->verified->name,
            // $row->approved->name,
            $statusLabel,
        ];
    }
}
