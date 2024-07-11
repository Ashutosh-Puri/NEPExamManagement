<?php

namespace App\Exports\User\HelplineDocumnet;

use App\Models\Studenthelplinedocument;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class HelplineDocumentExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Studenthelplinedocument::join('student_helpline_queries', 'student_helpline_documents.student_helpline_query_id', '=', 'student_helpline_queries.id')
        ->with('studenthelplinequery')->search($this->search)
        ->orderBy($this->sortColumn, $this->sortColumnBy)
        ->select('student_helpline_documents.id', 'student_helpline_documents.document_name', 'student_helpline_documents.is_active','student_helpline_queries.query_name')
        ->get();
    }

    public function headings(): array
    {
        return ['ID','Query', 'Document Name', 'Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->query_name,
            $row->document_name,
            $row->is_active == 1 ? 'Active' : 'Inactive',
        ];
    }
}
