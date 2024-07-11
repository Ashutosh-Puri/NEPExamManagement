<?php

namespace App\Exports\Faculty\InternalAudit\HodAssignTool;

use Illuminate\Support\Collection;
use App\Models\Facultyinternaldocument;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class HodAssignToolExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        $documents = Facultyinternaldocument::with(['facultysubjecttool.subject:id,subject_name', 'facultysubjecttool.academicyear:id,year_name'])
        ->search($this->search)
        ->orderBy($this->sortColumn, $this->sortColumnBy)
        ->get(['id', 'facultysubjecttool_id', 'document_fileName', 'document_filePath']);

    $documentsCollection = collect($documents);

    $groupedDocuments = $documentsCollection->groupBy(['facultysubjecttool.academicyear.year_name', 'facultysubjecttool.subject.subject_name']);

    $counter = 1;

    $mappedRows = new Collection();
    foreach ($groupedDocuments as $academicyearName => $group) {
        $mappedRow = [
            'year_name' => $academicyearName,
            'subjects' => []
        ];

        foreach ($group as $subjectName => $documents) {

            $uploadedDocumentsCount = $documents->filter(function ($document) {
                return $document->document_fileName !== null && $document->document_filePath !== null;
            })->count();

            $notUploadedDocumentsCount = $documents->filter(function ($document) {
                return $document->document_fileName === null || $document->document_filePath === null;
            })->count();

            $mappedRow['subjects'][] = [
                'id' => $counter++,
                'subject_name' => $subjectName ?? '',
                'not_uploaded_documents' => $notUploadedDocumentsCount,
                'uploaded_documents' => $uploadedDocumentsCount,
                'total_documents' => $documents->count(),
            ];
        }

        $mappedRows->push($mappedRow);
    }

    return $mappedRows;
    }

    public function headings(): array
    {
        return ['ID','Academic Year', 'Subject Name', 'Total Required Documents', 'Total Uploaded Documents', 'Total Remaining Documents' ];
    }

    public function map($row): array
    {
        $mappedRows = [];

        foreach ($row['subjects'] as $subject) {
            $mappedRows[] = [
                'ID' => $subject['id'],
                'Academic Year' => $row['year_name'],
                'Subject Name' => $subject['subject_name'],
                'Total Required Documents' => $subject['total_documents'] === 0 ? '0' : $subject['total_documents'],
                'Total Uploaded Documents' => $subject['uploaded_documents'] === 0 ? '0' : $subject['uploaded_documents'],
                'Total Remaining Documents' => $subject['not_uploaded_documents'] === 0 ? '0' : $subject['not_uploaded_documents'],
            ];
        }
        return $mappedRows;
    }
}
