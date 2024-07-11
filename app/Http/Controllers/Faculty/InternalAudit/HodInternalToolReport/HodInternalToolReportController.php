<?php

namespace App\Http\Controllers\Faculty\InternalAudit\HodInternalToolReport;

use PDF;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Models\Facultyinternaldocument;

class HodInternalToolReportController extends Controller
{
    public function headwise_internal_tool_report_view(Request $request)
    {
        $records = Facultyinternaldocument::with('academicyear', 'faculty', 'subject', 'departmenthead.faculty')
            ->where('departmenthead_id', $request->head_id)
            ->get();

            if (!$records->isEmpty()) {
            $department_head = $records->first()->departmenthead->faculty->faculty_name;

            $grouped_internal_tools = $records->groupBy(['academicyear_id', 'departmenthead_id']);

            $total_subjects = 0;
            $not_uploaded_documents = 0;
            $total_uploaded_documents = 0;
            $subject_names = new Collection();

            foreach ($grouped_internal_tools as $academic_year_id => $department_heads) {
                foreach ($department_heads as $subject_id => $internal_tool_documents) {
                    $total_subjects += $internal_tool_documents->pluck('subject_id')->unique()->count();
                    $not_uploaded_documents += $internal_tool_documents->whereNull('document_fileName')->whereNull('document_filePath')->count();
                    $total_uploaded_documents += $internal_tool_documents->whereNotNull('document_fileName')->whereNotNull('document_filePath')->count();
                    $subject_ids = $internal_tool_documents->pluck('subject_id')->unique();
                    $subject_names = $subject_names->merge(Subject::whereIn('id', $subject_ids)->pluck('subject_name'));
                }
            }

            $pdf = PDF::loadView('pdf.faculty.internal_audit.headwise_internal_tool_report.headwise_internal_tool_report', compact('department_head', 'total_subjects', 'not_uploaded_documents', 'total_uploaded_documents', 'subject_names'))->setPaper('A4');

            return $pdf->download('Headwise_Tool_Report.pdf');
            
            // return $pdf->stream('Headwise_tool_report.pdf');

        } else {
            return redirect()->back()->with('alert', ['type' => 'info', 'message' => 'Data Not Exists For This Selected Subject.']);
        }
    }
}