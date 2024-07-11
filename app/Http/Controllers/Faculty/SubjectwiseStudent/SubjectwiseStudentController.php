<?php

namespace App\Http\Controllers\Faculty\SubjectwiseStudent;

use App\Models\Subject;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Faculty\SubjectwiseStudent\SubjectwiseStudentExcelReport;

class SubjectwiseStudentController extends Controller
{
    public function download_subjectwise_student_report(Request $request)
    {
        // $subject = Subject::select('subject_code', 'subject_name', 'patternclass_id')->where('id', $request->subject_report_id)->firstOrFail();

        $subject_with_students = Subject::select('id', 'subject_sem', 'subject_code', 'subject_name', 'patternclass_id')
        ->where('id', $request->subject_report_id)
        ->with(['studentExamforms' => function ($query) {
            $query->distinct('student_id');
        }])->firstOrFail();

        // dd($subject_with_students);

        if (isset($subject_with_students->studentExamforms) && count($subject_with_students->studentExamforms) > 1)
        {
            $file_name = trim(str_replace('_', ' ', $subject_with_students->subject_name . ' ' . get_pattern_class_name($subject_with_students->patternclass_id)));

            view()->share('pdf.faculty.subjectwise_student.subjectwise_student_pdf');

            $total_pages = Pdf::getCanvas()->get_page_count();

            $pdf = Pdf::loadView('pdf.faculty.subjectwise_student.subjectwise_student_pdf',compact(['subject_with_students','total_pages']))->setOptions(['defaultFont' => 'sans-serif']);

            // return $pdf->stream($file_name . ' Student_list.pdf');

            return $pdf->download($file_name.'_Student_list.pdf');

        } else {
            return redirect()->back()->with('alert', ['type' => 'info','message' => 'No Students Available For This Subject!!']);
        }

    }

    public function download_subjectwise_student_excel_report(Request $request)
    {
        $subject_with_students = Subject::with('studentExamforms')->findOrFail($request->subject_report_id);

        if ($subject_with_students->studentExamforms->count() > 1) {
            $file_name = trim(str_replace('_', ' ', $subject_with_students->subject_name . ' ' . get_pattern_class_name($subject_with_students->patternclass_id))) . ' Student_list.xlsx';

            return Excel::download(new SubjectwiseStudentExcelReport($subject_with_students), $file_name);
        } else {
            return redirect()->back()->with('alert', ['type' => 'info','message' => 'No Students Available For This Subject!!']);
        }
    }

}
