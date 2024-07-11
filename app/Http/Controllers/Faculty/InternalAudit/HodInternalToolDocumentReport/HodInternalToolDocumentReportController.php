<?php

namespace App\Http\Controllers\Faculty\InternalAudit\HodInternalToolDocumentReport;

use PDF;
use setasign\Fpdi\Fpdi;
use App\Models\Academicyear;
use Illuminate\Http\Request;
use App\Models\Hodappointsubject;
use App\Models\Facultysubjecttool;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Facultyinternaldocument;

class HodInternalToolDocumentReportController extends Controller
{
    public $subject_id;

    public function download_subject_internal_tool_document_report(Request $request)
    {
        $this->subject_id = $request->subject_id;

        $records = Facultysubjecttool::with(['facultysubjecttools','academicyear','subject','departmenthead'])
        ->whereHas('facultysubjecttools',function($query){
            $query->where('subject_id', $this->subject_id);
        })->get();


        if ($records) {

            $academic_year = $records->first()->academicyear->year_name;
            $faculty = $records->first()->faculty->faculty_name;
            $subject = $records->first()->subject->subject_name;
            $department_head = $records->first()->departmenthead->faculty_name;

            // $grouped_internal_tools_docs = Facultyinternaldocument::where('facultysubjecttool_id', $record->facultysubjecttool_id)
            //     ->with('internaltooldocument.internaltoolmaster')
            //     ->get()
            //     ->groupBy(function ($item) {
            //         return $item->internaltooldocument->internaltoolmaster->toolname;
            //     });

            $grouped_internal_tools_docs = Facultyinternaldocument::whereHas('facultysubjecttool',function($query){
                $query->where('subject_id', $this->subject_id);
            })->get()
            ->groupBy(function ($item) {
                return $item->internaltooldocument->internaltoolmaster->toolname;
            });

            $pdf = new FPDI();
            $pdf->AddPage();
            $pageWidth = $pdf->GetPageWidth();
            $imageWidth = 25;
            $imageX = ($pageWidth - $imageWidth) / 2;
            $pdf->Image('img/logo.jpg', $imageX, 2, $imageWidth);

            $pdf->Ln(7);

            $pdf->SetFont('Arial', '', 10);
            $cellWidth = 190;
            $pdf->Cell($cellWidth, 0, '', 0, 1, 'C');
            $content = "Sangamner Nagarpalika Arts, D.J. Malpani Commerce and B.N. Sarda Science College (Autonomous) Sangamner\n(Affiliated to Savitribai Phule Pune University)";
            $pdf->MultiCell($cellWidth, 5, $content, 0, 'C');

            $pdf->Ln(10);

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50, 10, strtoupper("Academic Year"), 'LTRB', 0, 'L');
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 10, $academic_year, 'LTRB', 1, 'L');

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50, 10, strtoupper("Faculty"), 'LTRB', 0, 'L');
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 10, $faculty, 'LTRB', 1, 'L');

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50, 10, strtoupper("Subject"), 'LTRB', 0, 'L');
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 10, $subject, 'LTRB', 1, 'L');

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50, 10, strtoupper("Department Head"), 'LRB', 0, 'L');
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 10, $department_head, 'LRB', 1, 'L');

            $pdf->Ln(10);

            $pdf->SetFont('Arial', 'B', 12);

            $pdf->Cell(70, 10, 'Tool Name', 'LTB', 0, 'C');
            $pdf->Cell(70, 10, 'Document Name', 1, 0, 'C');
            $pdf->Cell(50, 10, 'Status', 1, 1, 'C');

            $pdf->SetFont('Arial', '', 12);

            foreach ($grouped_internal_tools_docs as $tool_name => $tool_and_documents) {
                $rowCount = count($tool_and_documents);
                $toolPrinted = false;

                foreach ($tool_and_documents as $index => $tool_and_document) {
                    if (!$toolPrinted) {
                        $pdf->Cell(70, $rowCount * 10, $tool_name, 'LRB', 0, 'C');
                        $toolPrinted = true;
                    } else {
                        $pdf->Cell(70, 10, '', '', 0, 'C');
                    }

                    $document_name = $tool_and_document->internaltooldocument->internaltooldocumentmaster->doc_name;

                    $pdf->Cell(70, 10, $document_name, 'B', 0, 'C');

                    $statusText = $tool_and_document->document_fileName && $tool_and_document->document_filePath ? 'Uploaded' : 'Not Uploaded';

                    $pdf->Cell(50, 10, $statusText, 'LRB', 1, 'C');
                }
            }

            foreach ($grouped_internal_tools_docs as $tool_name => $tool_and_documents) {
                foreach ($tool_and_documents as $index => $tool_and_document) {
                    $pdf->AddPage();
                    $pdf->SetFont('Arial', '', 10);
                    $pdf->Cell(0, 10, $tool_and_document->internaltooldocument->internaltooldocumentmaster->doc_name, 0, 1, 'L');

                    if ($tool_and_document->document_filePath && file_exists(public_path($tool_and_document->document_filePath))) {

                        if (pathinfo($tool_and_document->document_filePath, PATHINFO_EXTENSION) === 'pdf') {
                            $pageCount = $pdf->setSourceFile(public_path($tool_and_document->document_filePath));
                            for ($pageNumber = 1; $pageNumber <= $pageCount; $pageNumber++) {

                                $templateId = $pdf->importPage($pageNumber);
                                $size = $pdf->getTemplateSize($templateId);

                                $customWidth = 190;
                                $customHeight = 290;

                                $x = ($pdf->GetPageWidth() - $customWidth) / 2;
                                $y = ($pdf->GetPageHeight() - $customHeight) / 2;

                                $pdf->useTemplate($templateId, $x, $y, $customWidth, $customHeight);

                                if ($pageNumber < $pageCount) {
                                    $pdf->AddPage();
                                }
                            }
                        } else {
                            $pdf->Image(public_path($tool_and_document->document_filePath), 10, 20, 190, 260);
                        }
                    }
                }
            }

            $pdfContent = $pdf->Output('S');

            // return response($pdfContent, 200)->header('Content-Type', 'application/pdf');
            return response($pdfContent, 200)->header('Content-Type', 'application/pdf')->header('Content-Disposition', "attachment; filename=\"{$subject}_internal_report.pdf\"");
        }
        return redirect()->back()->with('alert', ['type' => 'info', 'message' => 'Data Not Exists For This Selected Subject.']);
    }
}
