<?php

namespace App\Exports\Faculty\InternalAudit\HodSubjectTool;

use App\Models\Facultysubjecttool;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class HodSubjectToolExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    protected $search;
    protected $sortColumn;
    protected $sortColumnBy;

    public function collection()
    {
        return Facultysubjecttool::with(['subject', 'academicyear', 'departmenthead', 'internaltoolmaster','facultysubjecttools'])
            ->where('departmenthead_id',Auth::guard('faculty')->user()->id)
            ->get(['id', 'academicyear_id', 'subject_id', 'internaltoolmaster_id',]);
    }

    public function headings(): array
    {
        return ['ID', 'Academic Year', 'Subject Name', 'Pattern Name', 'Tool Name', 'Document Name', 'Document Uploaded'];
    }

    public function map($row): array
    {
        $rows = [];

        foreach ($row->facultysubjecttools as $faculty_subject_tool) {
            $documentName = isset($faculty_subject_tool->internaltooldocument->internaltooldocumentmaster->doc_name) ? $faculty_subject_tool->internaltooldocument->internaltooldocumentmaster->doc_name : '';

            $patternClassName = isset($row->subject->patternclass->courseclass->classyear->classyear_name) ? $row->subject->patternclass->courseclass->classyear->classyear_name : '';
            $patternClassName .= ' ';
            $patternClassName .= isset($row->subject->patternclass->courseclass->course->course_name) ? $row->subject->patternclass->courseclass->course->course_name : '';
            $patternClassName .= ' ';
            $patternClassName .= isset($row->subject->patternclass->pattern->pattern_name) ? $row->subject->patternclass->pattern->pattern_name : '';

            $documentStatus = !is_null($faculty_subject_tool->document_filePath) ? 'Y' : 'N';

            $rows[] = [
                $row->id,
                (isset($row->academicyear->year_name) ?  $row->academicyear->year_name : ''),
                (isset($row->subject->subject_name) ?  $row->subject->subject_name : ''),
                $patternClassName,
                (isset($row->internaltoolmaster->toolname) ?  $row->internaltoolmaster->toolname : ''),
                $documentName,
                $documentStatus
            ];
        }

        return $rows;
    }


}
