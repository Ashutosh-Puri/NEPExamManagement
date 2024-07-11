<?php

namespace App\Exports\User\InternalAudit\InternalToolDocument;

use App\Models\Internaltooldocument;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InternalToolDocumentExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        return Internaltooldocument::with(['internaltooldocumentmaster', 'internaltoolmaster'])->search($this->search)->orderBy($this->sortColumn, $this->sortColumnBy)
        ->get(['id', 'internaltoolmaster_id', 'internaltooldoc_id', 'is_multiple', 'status']);
    }

    public function headings(): array
    {
        return ['ID', 'Tool Name', 'Tool Document Name', 'Is Multiple', 'Status'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            (isset($row->internaltoolmaster->toolname) ? $row->internaltoolmaster->toolname : ''),
            (isset($row->internaltooldocumentmaster->doc_name) ?  $row->internaltooldocumentmaster->doc_name : ''),
            $row->is_multiple == 1 ? 'Yes' : 'No',
            $row->status == 1 ? 'Active' : 'Inactive',
        ];
    }
}