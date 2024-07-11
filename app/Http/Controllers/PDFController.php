<?php

namespace App\Http\Controllers;

use App\Exports\abcid\StudentresultabcidExport;
use Illuminate\Support\Facades\Storage;
use  PDF;
use App\Models\CourseClass;
use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\ExamPatternclass;
use App\Models\ExamStudentseatno;
use Illuminate\Support\Str;
use App\Exports\ResultAnalysisExcel;
use App\Jobs\GenerateUGLedgerPDF;
 
use App\Http\Controllers\Exception;
use App\Models\Studentmark;
use App\Models\Studentresult;
use App\Models\Subject;
use App\Models\Gradepoint;
use App\Exports\MeritlistExport;
use App\Exports\ConvocationlistExport;
use App\Exports\ConvocationlistunipuneExport;

use App\Jobs\UGResultPrintJob;
use App\Models\Exambarcode;
use App\Models\Examformmaster;
use App\Models\Internalmarksextracreditbatch;
use App\Models\Intextracreditbatchseatnoallocation;
use App\Models\Student;
use App\Models\Studentordinace163;
use App\Models\Studentphd;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use ZipArchive;

 
class PDFController extends Controller
{
  public $admissioncanceldata = [
   // '1062102902', '1052103357', '1052102976', '1042102598', '1082103334', '1012104593', '1022103776', '1032102625', '1032103404', '1032104087', '1032104371', '1032104404', '1032104571', '1032104630', '1032104711',
    //'1012103932', '1012103746', '1032207921', '1032207622', '1032207618', '1032206161', '1032207861', '1032207712', '1032207197', '1032207940', '1032207938' //TYBA extra subject case
  ];
  public function resultanalysis()
  {
    $exam = Exam::where('status', '1')->get();
    return view('exampatternclass.resultexampatternclassanalysis', compact('exam'));
  }
  public function passingcertificateprint($id) //passing certificate PG 2022 sem II
  {
    ini_set('max_execution_time', 5000);
    ini_set('memory_limit', '4048M');
    //$id=40;

    $exampatternclass = ExamPatternclass::find($id);
    $course_type = $exampatternclass->patternclass->getclass->course->course_type;

    $exam_id = $exampatternclass->exam_id;
    $patternclass_id = $exampatternclass->patternclass_id;
    
    $Seatnos = ExamStudentseatno::
    where('exam_patternclasses_id', $id)
    ->whereNotIn('prn', $this->admissioncanceldata)
    ->orderBy('seatno')    
    ->get();
    
    $results = collect();
    $currentexam = $exampatternclass->exam;  
      
    $results = [];

foreach ($Seatnos as $Seatno) {
    $sr = Studentresult::with('student')
        ->where('exam_patternclasses_id', '<=', $id)
        ->where('student_id', $Seatno->student_id)
        ->where('sgpa', '!=', '0')
        ->where('extraCreditsStatus', '1')
              
        ->groupBy('sem')
        ->get();
        

    foreach ($sr->unique('sem') as $item) {
       
        if (!isset($results[$Seatno->student_id])) {
            $results[$Seatno->student_id] = [
                'total' => 0,
                'semtotalcredit' => 0,
                'semtotalcreditpoints' => 0,
            ];
        }

        $results[$Seatno->student_id]['total'] += $item->total;
        $results[$Seatno->student_id]['semtotalcredit'] += $item->semtotalcredit;
        $results[$Seatno->student_id]['semtotalcreditpoints'] += $item->semtotalcreditpoints;
    }
}

// $results now contains the sum of 'total', 'semtotalcredit', and 'semtotalcreditpoints' grouped by 'sem'
dd($results);

    




     $sr= Studentresult::with('student')
      ->where('exam_patternclasses_id','<=',$id)
      ->whereIn('student_id', $Seatnos->pluck('student_id'))//->sortKeys())   
      ->where('sgpa', '!=', '0')
      ->where('extraCreditsStatus', '1')    
    
      ->orderBy('seatno')  
        
      ->select([
        'student_id', DB::raw('count(sgpa) as total'),
        'student_id', DB::raw('sum(semtotalcredit) as semtotalcredit'),
        'student_id', DB::raw('sum(semtotalcreditpoints) as semtotalcreditpoints'),
        'student_id', DB::raw('min(sem) as minSem'),
        'student_id', DB::raw('max(sem) as MaxSem'),
          
        ])      
      ->groupby(['student_id'])
     
      ->get();
      
     

      $ordinace = Studentmark::whereIn('student_id', $Seatnos->pluck('student_id'))
        ->whereNotIn('grade', ['F', '-1', 'Ab'])

        ->select([
          'student_id', DB::raw('sum(int_ordinace_flag) as int_ordinace_flag'),
          'student_id', DB::raw('sum(ext_ordinace_flag) as ext_ordinace_flag'),
          'student_id', DB::raw('sum(total_ordinace_flag) as total_ordinace_flag'),
          'student_id', DB::raw('sum(practical_ordinace_flag) as practical_ordinace_flag')

        ])
        ->groupby('student_id')
        ->get();

      $ordinace4 = Studentmark::whereIn('student_id', $Seatnos->pluck('student_id'))
        ->where('total_ordinancefour_marks', '!=', '0')

        ->select([
          'student_id', DB::raw('count(total_ordinancefour_marks) as total_ordinancefour_marks'),

        ])
        ->groupby('student_id')
        ->get();
      //dd($ordinace->pluck('ext_ordinace_flag','student_id'));
      if ($Seatnos->first()->student->currentclassstudent->where('markssheetprint_status', '-1')->pluck('sem')->first() == 2)
      //"The student has been admitted to second year directly for this programme."
      {
        $data = $sr->filter(function ($query) { //dd($query);
          return $query->total > 3;
        });
      } else if ($Seatnos->first()->student->currentclassstudent->where('markssheetprint_status', '-1')->pluck('sem')->first() == 4)
      //"The student has been admitted to third year directly for this programme."
      {
        $data = $sr->filter(function ($query) {
          return $query->total > 1;
        });
      } else {

        $data = $sr->filter(function ($query) {
           
          return $query->total > 5;
        });
      }
    //dd($data);
      view()->share('result.passingcertificateUGJuly2023', $data); //for PG new format used in march 2022(July 2022) sem -II exam rule 
    
    if ($course_type == "PG")
      $pdf = PDF::loadView('result.passingcertificatePGJuly2022', compact('data'), compact('currentexam', 'course_type', 'exampatternclass'))->setPaper('a4')->setOptions(['defaultFont' => 'sans-serif']);;
    if ($course_type == "UG")
      $pdf = PDF::loadView('result.passingcertificateUGJuly2023', compact('data'), compact('currentexam', 'course_type', 'exampatternclass', 'ordinace', 'ordinace4'))->setPaper('a4')->setOptions(['defaultFont' => 'sans-serif']);;

    $canvas = $pdf->getDomPDF()->getCanvas();
    //$height = $canvas->get_height();
    //$width = $canvas->get_width();
    //$canvas->set_opacity(.2,"Multiply"); this
    $canvas->page_text(10, 10, "Report Page: {PAGE_NUM} of {PAGE_COUNT}", $pdf->getFontMetrics()->getFont('serif', 'normal'), 12, array(0, 0, 0));

    if ($data->count() > 0) {
      $fname = $exampatternclass->patternclass->pattern->pattern_name;

      $coursename = $exampatternclass->patternclass->coursepatternclasses->class_name;

      $filename = $coursename . '_' . $fname . '_' . $currentexam->exam_name . '_passingcertificate' . '.pdf';
    } else $filename = "Emptyledger.pdf";
    //return view('result.resultPGJuly2022', compact('data'), compact('currentexam','course_type','exampatternclass'));

    //  return $pdf->stream($filename);  
    return $pdf->download($filename);



    //   ini_set('max_execution_time', 5000);
    //   ini_set('memory_limit', '4048M');
    // //$id=40;

    //   $exampatternclass=ExamPatternclass::find($id);
    //   $course_type=$exampatternclass->patternclass->getclass->course->course_type;

    //   $exam_id =$exampatternclass->exam_id; 
    //   $patternclass_id =$exampatternclass->patternclass_id; 
    //   //dd($patternclass_id);
    //   $studdata=Studentmark::where('exam_id',$exam_id)->where('patternclass_id',$patternclass_id)->pluck('student_id','seatno')->unique();


    // //dd($studdata->sortBy('seatno'));
    //   $epc=ExamPatternclass::where('exam_id',$exam_id)->pluck('id');

    //   $data = ExamStudentseatno::whereIn('exam_patternclasses_id',$epc)->whereIn('student_id',$studdata)
    //     //->where('prn','=','104200383')

    //   ->whereNotIn('prn',$this->admissioncanceldata)
    //        ->orderBy('seatno')
    //       //->take(2)
    //        ->get();
    //       //dd($data) ; 

    //   if ($data->count() > 0)
    //        $data->toQuery()->update(array("printstatus" => "1"));
    //        $currentexam = $exampatternclass->exam;//Exam::Where('status', '1')->get();

    //        $stud_marks = new StudentmarkController;


    //   if($course_type=="PG")
    //   view()->share('result.passingcertificatePGJuly2022', $data);//for PG new format used in march 2022(July 2022) sem -II exam rule 
    //                                         //rule =>passing on total marks circular
    //   if($course_type=="UG")
    //   {
    //     //  dd($studdata->sortKeys());
    //     $sr=Studentresult::with('student')->whereIn('student_id',$studdata->sortKeys())
    //    ->where('sgpa','!=','0')
    //   ->select(['student_id',DB::raw('count(sgpa) as total'),
    //   'student_id',DB::raw('sum(semtotalcredit) as semtotalcredit'),
    //   'student_id',DB::raw('sum(semtotalcreditpoints) as semtotalcreditpoints')])

    //   ->groupby('student_id')
    //   ->get();
    //  //dd($sr);
    //   $ordinace=Studentmark::whereIn('student_id',$studdata)
    //   ->whereNotIn('grade',['F','-1','Ab'])

    //   ->select(['student_id',DB::raw('sum(int_ordinace_flag) as int_ordinace_flag'),
    //   'student_id',DB::raw('sum(ext_ordinace_flag) as ext_ordinace_flag'),
    //   'student_id',DB::raw('sum(total_ordinace_flag) as total_ordinace_flag'),
    //   'student_id',DB::raw('sum(practical_ordinace_flag) as practical_ordinace_flag')

    //   ])
    //   ->groupby('student_id')
    //   ->get();

    //   $ordinace4=Studentmark::whereIn('student_id',$studdata)
    //   ->where('total_ordinancefour_marks','!=','0')

    //   ->select(['student_id',DB::raw('count(total_ordinancefour_marks) as total_ordinancefour_marks'),

    //   ])
    //   ->groupby('student_id')
    //   ->get();
    //   //dd($ordinace->pluck('ext_ordinace_flag','student_id'));

    //   $data=$sr->filter(function($query){
    //      return $query->total >5;
    //   });
    //   view()->share('result.passingcertificateUGJuly2023', $data);//for PG new format used in march 2022(July 2022) sem -II exam rule 
    //   }                                                                           //rule =>passing on total marks circular

    //   if($course_type=="PG")
    //     $pdf = PDF::loadView('result.passingcertificatePGJuly2022', compact('data'), compact('currentexam','course_type','exampatternclass'))->setPaper('a4')->setOptions(['defaultFont' => 'sans-serif']);;

    //   $canvas = $pdf->getDomPDF()->getCanvas();
    //   //$height = $canvas->get_height();
    //   //$width = $canvas->get_width();
    //   //$canvas->set_opacity(.2,"Multiply"); this
    //   $canvas->page_text(10, 10, "Report Page: {PAGE_NUM} of {PAGE_COUNT}", $pdf->getFontMetrics()->getFont('serif','normal'), 12, array(0, 0, 0));

    //   if ($data->count() > 0) { 
    //     $fname = $exampatternclass->patternclass->pattern->pattern_name;

    //     $coursename = $exampatternclass->patternclass->coursepatternclasses->class_name;

    //     $filename = $coursename . '_' . $fname . '_' . $currentexam->exam_name . '_passingcertificate' . '.pdf';
    //   } else $filename = "Emptyledger.pdf";
    //   //return view('result.resultPGJuly2022', compact('data'), compact('currentexam','course_type','exampatternclass'));

    // //  return $pdf->stream($filename);  
    //   return $pdf->download($filename);
  }
  public function allresult()
  {

    return view('exampatternclass.resultexampatternclass');
  }
  public function prn_wise_result()
  {

    return view('exampatternclass.prnwiseresult');
  }
  public function prn_result(Request $request)
  {
    $request->validate([
      'exam_id' => 'required|numeric',
      'exampatternclass_id' => 'required|numeric',
      'prn' => 'required|numeric',
      'document_type' => 'required|numeric'
    ]);
    $exampatternclass_id = $request->input('exampatternclass_id');
    $prn = $request->input('prn');
    $document_type = $request->input('document_type');

    ini_set('max_execution_time', 5000);
    ini_set('memory_limit', '4048M');
    $exampatternclass = ExamPatternclass::find($exampatternclass_id);
      $course_type = $exampatternclass->patternclass->getclass->course->course_type;

      $exam_id = $exampatternclass->exam_id;

      $patternclass_id = $exampatternclass->patternclass_id;
      $epc = ExamPatternclass::where('exam_id', $exam_id)->pluck('id');

      $year = $exampatternclass->patternclass->getclass->year;

      $currentexam = $exampatternclass->exam; //Exam::Where('status', '1')->get();
    if ($document_type == 1) //ledger
    {
       $data = ExamStudentseatno::whereIn('exam_patternclasses_id', $epc)
        ->where('prn', $prn)
        ->get();
    
      if ($data->isEmpty())
        return redirect()->back()->with('success', 'Invalid PRN!!!!!!!');

      if ($course_type == "PG")
        view()->share('result.ledgerPGJuly2022', $data); //for PG new format used in march 2022(July 2022) sem -II exam rule 
      //rule =>passing on total marks circular
      if ($course_type == "UG")
        view()->share('result.ledgerUGJuly2022', $data); //for UG new format used in march 2022(July 2022) sem -II exam rule 
      //rule =>passing on total marks circular

      //return view('result.ledgerUG', compact('data'), compact('currentexam'));
      //$pdf = PDF::loadView('result.ledgerUG', compact('data'), compact('currentexam','course_type'))->setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'sans-serif']);;
      if ($course_type == "PG")
        $pdf = PDF::loadView('result.ledgerPGJuly2022', compact('data', 'exampatternclass', 'currentexam', 'course_type'))->setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'sans-serif']);;
      if ($course_type == "UG")
        $pdf = PDF::loadView('result.ledgerUGJuly2022', compact('data', 'exampatternclass', 'currentexam', 'course_type'))->setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'sans-serif']);;

      $canvas = $pdf->getDomPDF()->getCanvas();
      //$height = $canvas->get_height();
      //$width = $canvas->get_width();
      //$canvas->set_opacity(.2,"Multiply");
      $canvas->page_text(10, 10, "Report Page: {PAGE_NUM} of {PAGE_COUNT}",  $pdf->getFontMetrics()->getFont('serif', 'normal'), 12, array(0, 0, 0));


      $filename =  $currentexam->exam_name . '_' . $prn . '_ledger' . '.pdf';

      //return $pdf->stream($filename);
      return $pdf->download($filename);
    } //ledger
    if ($document_type == 2) //result
    {
      

      // $studdata = Studentmark::where('exam_id', $exam_id)
      //   ->where('prn', $prn)
      //   ->where('patternclass_id', '<=', $patternclass_id)
      //   ->pluck('student_id', 'seatno')->unique();
      // if ($studdata->isEmpty()) {
      //   $studdata = Intextracreditbatchseatnoallocation::where('exam_id', $exam_id)
      //     ->where('patternclass_id', $patternclass_id)
      //     ->pluck('student_id', 'seatno')->unique();
      //   //  dd( $studdata);
      // }
      $data = ExamStudentseatno::whereIn('exam_patternclasses_id', $epc)
       // ->whereIn('student_id', $studdata)
        ->where('prn', $prn)
        ->get();
      // $data = ExamStudentseatno::where('exam_patternclasses_id', $exampatternclass_id)
      //   ->where('prn', $prn)
      //   ->get();
    //dd($data);
      if ($data->isEmpty())
        return redirect()->back()->with('success', 'Invalid PRN!!!!!!!');


      if ($course_type == "PG")
        view()->share('result.resultPGJuly2022', $data); //for PG new format used in march 2022(July 2022) sem -II exam rule 

      if ($course_type == "UG")
        view()->share('result.resultUGJuly2022', $data); //for UG new format used in march 2022(July 2022) sem -II exam rule 

      if ($course_type == "PG")
        $pdf = PDF::loadView('result.resultPGJuly2022', compact('data', 'currentexam', 'course_type', 'exampatternclass'))->setPaper('a4')->setOptions(['defaultFont' => 'sans-serif']);;
      if ($course_type == "UG")
        $pdf = PDF::loadView('result.resultUGJuly2022', compact('data', 'currentexam', 'course_type', 'exampatternclass', 'year', 'epc'))->setPaper('a4')->setOptions(['defaultFont' => 'sans-serif']);;

      $canvas = $pdf->getDomPDF()->getCanvas();

      $filename =  $currentexam->exam_name . '_' . $prn . '_result' . '.pdf';
      return $pdf->download($filename);
    }
    if ($document_type == 3) //passing certicicate
    {
     
      $data = ExamStudentseatno::where('exam_patternclasses_id', $exampatternclass_id)
        ->where('prn', $prn)
        ->get();
      //dd($data->first()->student);
      if ($data->isEmpty())
        return redirect()->back()->with('success', 'Invalid PRN!!!!!!!');


     
      //dd($patternclass_id);
      $studdata = Studentmark::where('exam_id', $exam_id)
        ->where('patternclass_id', '<=', $patternclass_id)
        ->where('prn', '=', $prn)
        ->pluck('student_id', 'seatno')->unique();

     
      if ($course_type == "PG")
        view()->share('result.passingcertificatePGJuly2022', $data); //for PG new format used in march 2022(July 2022) sem -II exam rule 
      //rule =>passing on total marks circular
      if ($course_type == "UG") {
        //  dd($studdata->sortKeys());
        $sr = Studentresult::with('student')->whereIn('student_id', $studdata->sortKeys())
          ->where('sgpa', '!=', '0')
          ->select([
            'student_id', DB::raw('count(sgpa) as total'),
            'student_id', DB::raw('sum(semtotalcredit) as semtotalcredit'),
            'student_id', DB::raw('sum(semtotalcreditpoints) as semtotalcreditpoints'),
            'student_id', DB::raw('max(sem) as maxsem')
          ])

          ->groupby('student_id')
          ->get();
        //     foreach($sr as $s)
        //     Log::debug($s->student->student_name." Total ".$s->semtotalcreditpoints." semtotalcredit  ".$s->semtotalcredit);

        // dd($sr->first()->maxsem);
        if ($sr->first()->maxsem != '6')
          return redirect()->back()->with('success', 'Not Allowed!!!!!!!');
        $ordinace = Studentmark::whereIn('student_id', $studdata)
          ->whereNotIn('grade', ['F', '-1', 'Ab'])

          ->select([
            'student_id', DB::raw('sum(int_ordinace_flag) as int_ordinace_flag'),
            'student_id', DB::raw('sum(ext_ordinace_flag) as ext_ordinace_flag'),
            'student_id', DB::raw('sum(total_ordinace_flag) as total_ordinace_flag'),
            'student_id', DB::raw('sum(practical_ordinace_flag) as practical_ordinace_flag')

          ])
          ->groupby('student_id')
          ->get();

        $ordinace4 = Studentmark::whereIn('student_id', $studdata)
          ->where('total_ordinancefour_marks', '!=', '0')

          ->select([
            'student_id', DB::raw('count(total_ordinancefour_marks) as total_ordinancefour_marks'),

          ])
          ->groupby('student_id')
          ->get();
        //dd($ordinace->pluck('ext_ordinace_flag','student_id'));
        if ($data->first()->student->currentclassstudent->where('markssheetprint_status', '-1')->pluck('sem')->first() == 2)
        //"The student has been admitted to second year directly for this programme."
        {
          $data = $sr->filter(function ($query) { //dd($query);
            return $query->total > 3;
          });
        } else if ($data->first()->student->currentclassstudent->where('markssheetprint_status', '-1')->pluck('sem')->first() == 4)
        //"The student has been admitted to third year directly for this programme."
        {
          $data = $sr->filter(function ($query) {
            return $query->total > 1;
          });
        } else {
          $data = $sr->filter(function ($query) {
            return $query->total > 5;
          });
        }
        view()->share('result.passingcertificateUGJuly2023', $data); //for PG new format used in march 2022(July 2022) sem -II exam rule 
      }                                                                           //rule =>passing on total marks circular

      if ($course_type == "PG")
        $pdf = PDF::loadView('result.passingcertificatePGJuly2022', compact('data'), compact('currentexam', 'course_type', 'exampatternclass'))->setPaper('a4')->setOptions(['defaultFont' => 'sans-serif']);;
      if ($course_type == "UG")
        $pdf = PDF::loadView('result.passingcertificateUGJuly2023', compact('data'), compact('currentexam', 'course_type', 'exampatternclass', 'ordinace', 'ordinace4'))->setPaper('a4')->setOptions(['defaultFont' => 'sans-serif']);;

      $canvas = $pdf->getDomPDF()->getCanvas();

      //$canvas->page_text(10, 10, "Report Page: {PAGE_NUM} of {PAGE_COUNT}", $pdf->getFontMetrics()->getFont('serif', 'normal'), 12, array(0, 0, 0));

      $filename =  $currentexam->exam_name . '_' . $prn . '_passingcertificate' . '.pdf';
      return $pdf->download($filename);
    }
  }
  public function allclasses_abcid()
  {

    return view('abcid.resultexampatternclass');
  }

  public function convocationPG()
  {
    //  dd("ddd");
    //$exam = Exam::where('status', '1')->get();
    $exam = Exam::where('id', 9)->where('status', '0')->get();
    // dd($exam->first()->exam_sessions);
    return view('exampatternclass.pgconvocationlistclass', compact('exam'));
  }
  public function internalfail()
  {
    $patternclass = 39;
    $data = Exam::find(1)->patternclasses->find($patternclass)->subjects->where('subject_sem', '1');
    //return view('reports.internal', compact('data'));

    view()->share('reports.internal', $data);

    $pdf = PDF::loadView('reports.internal', compact('data'))->setOptions(['defaultFont' => 'sans-serif']);;

    // $fname = $data->first()->exampatternclasses->patternclass->pattern->pattern_name;
    $filename = Exam::find(1)->patternclasses->find($patternclass)->getclass->class_name . 'absent_int' . '.pdf';; //$data->first()->exampatternclasses->patternclass->coursepatternclasses->class_name . '_' . $fname . 'ledger' . '.pdf';
    //$filename="sss.pdf";
    return $pdf->download($filename);
  }
  public function generateanalysis($id)   //fy sem I ledger 
  {


    ini_set('max_execution_time', 5000);
    ini_set('memory_limit', '4048M');

    $data = ExamStudentseatno::where('exam_patternclasses_id', $id)

      ->whereNotIn('prn', $this->admissioncanceldata)

      ->get(); //->get();//BCS


    // share data to view
    $currentexam = Exam::Where('status', '1')->get();
    $stud_marks = new StudentmarkController;


    $exampatternclass = ExamPatternclass::find($id);


    if ($data->count() > 0) { //$fname = $data->first()->exampatternclasses->patternclass->pattern->pattern_name;
      // $filename = $data->first()->exampatternclasses->patternclass->coursepatternclasses->class_name . '_' . $fname . 'ledger' . '.pdf';
      $fname = $data->first()->exampatternclasses->patternclass->pattern->pattern_name;

      $coursename = $data->first()->exampatternclasses->patternclass->coursepatternclasses->class_name;

      if (Str::contains($coursename, 'B.Voc')) {
        if ($currentexam->first()->exam_sessions == 2) {
          $coursename = str_replace('Certificate', 'Diploma', $coursename);
        }
      }

      $filename = $coursename . '_' . $fname . '_' . $currentexam->first()->exam_name . '_ResultAnalysis' . '.xlsx';
    } else $filename = "Emptyleger.xlsx";



    return Excel::download(new ResultAnalysisExcel($data, $currentexam, $exampatternclass), $filename);



    view()->share('result.ledger', $data);

    $pdf = PDF::loadView('result.marksledger', compact('data'), compact('currentexam', 'exampatternclass'))->setPaper('a4')->setOptions(['defaultFont' => 'sans-serif']);;

    $canvas = $pdf->getDomPDF()->getCanvas();
    //$height = $canvas->get_height();
    //$width = $canvas->get_width();
    //$canvas->set_opacity(.2,"Multiply");
    $canvas->page_text(10, 10, "Report Page: {PAGE_NUM} of {PAGE_COUNT}", null, 12, array(0, 0, 0));

    if ($data->count() > 0) { //$fname = $data->first()->exampatternclasses->patternclass->pattern->pattern_name;
      // $filename = $data->first()->exampatternclasses->patternclass->coursepatternclasses->class_name . '_' . $fname . 'ledger' . '.pdf';
      $fname = $data->first()->exampatternclasses->patternclass->pattern->pattern_name;

      $coursename = $data->first()->exampatternclasses->patternclass->coursepatternclasses->class_name;

      if (Str::contains($coursename, 'B.Voc')) {
        if ($currentexam->first()->exam_sessions == 2) {
          $coursename = str_replace('Certificate', 'Diploma', $coursename);
        }
      }

      $filename = $coursename . '_' . $fname . '_' . $currentexam->first()->exam_name . '_ledger' . '.pdf';
    } else $filename = "Emptyleger.pdf";

    return $pdf->stream('result.pdf');
    //$filename="sss.pdf";
    return $pdf->download($filename);
  }
  //Merit list 
  public function meritlist($id)
  {
    ob_clean();
    ini_set('max_execution_time', 5000);
    ini_set('memory_limit', '4048M');
    $exampatternclass = ExamPatternclass::find($id);
    $course_type = $exampatternclass->patternclass->getclass->course->course_type;
    // dd( $course_type);
    $data = ExamStudentseatno::where('exam_patternclasses_id', $id) //->whereIn('seatno',range(3061,3100))
      //->where('prn','1022002201')
      ->whereNotIn('prn', $this->admissioncanceldata)
      // ->where('printstatus','0')
      // ->take(100)
      ->get();
    //dd($data);
    if ($data->count() > 0)
      $data->toQuery()->update(array("printstatus" => "1"));
    //$currentexam = Exam::Where('status', '1')->first();//->get();
    // $currentexam =  Exam::where('id',5)->first();;//->get();
    $currentexam = $exampatternclass->exam;
    // $stud_marks = new StudentmarkController;
    // 
    // 273
    if ($data->count() > 0) {
      $fname = $data->first()->exampatternclasses->patternclass->pattern->pattern_name;

      $coursename = $data->first()->exampatternclasses->patternclass->coursepatternclasses->class_name;

      if (Str::contains($coursename, 'B.Voc')) {

        if ($currentexam->exam_sessions == 2) {
          $coursename = str_replace('Certificate', 'Diploma', $coursename);
        }
      }

      $filename = $coursename . '_' . $fname . '_' . $currentexam->exam_name . '_ledger' . '.xlsx';
    } else $filename = "Emptyledger.xlsx";
    ob_end_clean();
    return Excel::download(new MeritlistExport($data, $currentexam, $course_type), $filename);



    // dd( $currentexam);
    //view()->share('result.ledgerUG', $data);//old format used in OCT-NOV 2021(JAN 2022) sem -I exam
    // if($course_type=="PG")
    //  view()->share('result.ledgerPGJuly2022', $data);//for PG new format used in march 2022(July 2022) sem -II exam rule 
    //rule =>passing on total marks circular
    if ($course_type == "UG")
      view()->share('result.meritlist', $data); //for UG new format used in march 2022(July 2022) sem -II exam rule 
    //rule =>passing on total marks circular

    //return view('result.ledgerUG', compact('data'), compact('currentexam'));
    //$pdf = PDF::loadView('result.ledgerUG', compact('data'), compact('currentexam','course_type'))->setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'sans-serif']);;
    //if($course_type=="PG")
    // $pdf = PDF::loadView('result.ledgerPGJuly2022', compact('data'), compact('currentexam','course_type'))->setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'sans-serif']);;
    if ($course_type == "UG")
      $pdf = PDF::loadView('result.meritlist', compact('data'), compact('currentexam', 'course_type'))->setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'sans-serif']);;

    $canvas = $pdf->getDomPDF()->getCanvas();
    //$height = $canvas->get_height();
    //$width = $canvas->get_width();
    //$canvas->set_opacity(.2,"Multiply");
    $canvas->page_text(10, 10, "Report Page: {PAGE_NUM} of {PAGE_COUNT}", null, 12, array(0, 0, 0));

    if ($data->count() > 0) {
      $fname = $data->first()->exampatternclasses->patternclass->pattern->pattern_name;

      $coursename = $data->first()->exampatternclasses->patternclass->coursepatternclasses->class_name;

      if (Str::contains($coursename, 'B.Voc')) {

        if ($currentexam->exam_sessions == 2) {
          $coursename = str_replace('Certificate', 'Diploma', $coursename);
        }
      }

      $filename = $coursename . '_' . $fname . '_' . $currentexam->exam_name . '_ledger' . '.pdf';
    } else $filename = "Emptyledger.pdf";
    return $pdf->stream($filename);
    return $pdf->download($filename);
  }
  //...................UG Ledger Create at 16 April 2022..................
  // public function UGledgerprint($id)   //final ledger print
  // {
  //   ini_set('max_execution_time', 5000);
  //   ini_set('memory_limit', '4048M');
  //   $exampatternclass=ExamPatternclass::find($id);
  //   $course_type=$exampatternclass->patternclass->getclass->course->course_type;
  //  // dd( $course_type);
  //   $data = ExamStudentseatno::where('exam_patternclasses_id', $id)//->whereIn('seatno',range(3061,3100))
  //   // ->whereIn('prn',["1012103922", "1012104370"])
  //   //->where('prn','1212205575')
  //   //->whereNotIn('prn',$this->admissioncanceldata)
  //     ->where('printstatus','0')
  //     ->take(50)
  //   ->get();

  //   // foreach($data as $seatnodata)
  //   //       $seatnodata->update(['token'=>Str::random(80)]) ;

  //   if ($data->count() > 0)
  //        $data->toQuery()->update(array("printstatus" => "1"));
  //        //$currentexam = Exam::Where('status', '1')->first();//->get();
  //       // $currentexam =  Exam::where('id',5)->first();;//->get();
  //       $currentexam = $exampatternclass->exam;
  //        $stud_marks = new StudentmarkController;

  //       // dd( $currentexam);
  //   //view()->share('result.ledgerUG', $data);//old format used in OCT-NOV 2021(JAN 2022) sem -I exam
  //   if($course_type=="PG")
  //     view()->share('result.ledgerPGJuly2022', $data);//for PG new format used in march 2022(July 2022) sem -II exam rule 
  //                                           //rule =>passing on total marks circular
  //   if($course_type=="UG")
  //   view()->share('result.ledgerUGJuly2022', $data);//for UG new format used in march 2022(July 2022) sem -II exam rule 
  //                                           //rule =>passing on total marks circular

  //                                           //return view('result.ledgerUG', compact('data'), compact('currentexam'));
  //   //$pdf = PDF::loadView('result.ledgerUG', compact('data'), compact('currentexam','course_type'))->setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'sans-serif']);;
  //   if($course_type=="PG")
  //   $pdf = PDF::loadView('result.ledgerPGJuly2022', compact('data','exampatternclass','currentexam','course_type'))->setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'sans-serif']);;
  //   if($course_type=="UG")
  //   $pdf = PDF::loadView('result.ledgerUGJuly2022', compact('data','exampatternclass','currentexam','course_type'))->setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'sans-serif']);;

  //   $canvas = $pdf->getDomPDF()->getCanvas();
  //   //$height = $canvas->get_height();
  //   //$width = $canvas->get_width();
  //   //$canvas->set_opacity(.2,"Multiply");
  //   $canvas->page_text(10, 10, "Report Page: {PAGE_NUM} of {PAGE_COUNT}", $pdf->getFontMetrics()->getFont('serif','normal'), 12, array(0, 0, 0));

  //   if ($data->count() > 0) { 
  //     $fname = $data->first()->exampatternclasses->patternclass->pattern->pattern_name;

  //     $coursename = $data->first()->exampatternclasses->patternclass->coursepatternclasses->class_name;

  //     if (Str::contains($coursename, 'B.Voc')) {

  //       if ($currentexam->exam_sessions == 2) { 
  //         $coursename = str_replace('Certificate', 'Diploma', $coursename);

  //       }
  //     }

  //     $filename = $coursename . '_' . $fname . '_' . $currentexam->exam_name . '_ledger' . '.pdf';
  //   } else $filename = "Emptyledger.pdf";
  //   //return $pdf->stream($filename);
  //   return $pdf->download($filename);
  // }
  //...................UG Ledger Create at 16 April 2022..................

  public function generateLedger($id)
  {
    
      GenerateUGLedgerPDF::dispatch($id);

      return response()->json(['message' => 'UG Ledger generation job dispatched.']);
  }
  public function generate_Result($id)
  {
    ini_set('max_execution_time', 5000);
    ini_set('memory_limit', '8048M');

    $exampatternclass = ExamPatternclass::find($id);
 
    $course_type = $exampatternclass->patternclass->getclass->course->course_type;

    $exam_id = $exampatternclass->exam_id;
    $patternclass_id = $exampatternclass->patternclass_id;

    $studdata = Studentmark::where('exam_id', $exam_id)
        ->where('patternclass_id', $patternclass_id)
       // ->where('student_id','56')
        ->pluck('student_id', 'seatno')->unique();
 ;
    $studdata1 = Intextracreditbatchseatnoallocation::where('exam_id', $exam_id)
        ->where('patternclass_id', $patternclass_id)
        //->where('student_id','56')
        ->pluck('student_id', 'seatno')->unique();
        $studdata= $studdata->merge($studdata1);
        //dd($studdata);
        $studdata= $studdata->unique();
       // dd($studdata);
    $epc = ExamPatternclass::where('exam_id', $exam_id)->pluck('id');

    $data = null;
    $year = $exampatternclass->patternclass->getclass->year;

    if ($course_type == "PG") {
        $data = ExamStudentseatno::whereIn('exam_patternclasses_id', $epc)
            //->whereNotIn('prn', $this->admissioncanceldata)
            ->whereIn('student_id', $studdata)
            ->orderBy('seatno')
            // ->take(100)
            ->get();
            
    } else {
        $data = ExamStudentseatno::whereIn('exam_patternclasses_id', $epc)
            ->whereIn('student_id', $studdata)
            
            //->whereNotIn('prn', $this->admissioncanceldata)
            ->orderBy('seatno')
            // ->take(100)
            ->get();
            
    }

    // if ($data->count() > 0) {
    //     $data->each(function ($item) {
    //         $item->update(['printstatus' => 1]);
    //     });
    // }

    $currentexam = $exampatternclass->exam;
    $pdf = null;

    if ($course_type == "PG") {
        view()->share('result.resultPGJuly2022', $data);
        $pdf = PDF::loadView('result.resultPGJuly2022', compact('data', 'currentexam', 'course_type', 'exampatternclass'))->setPaper('a4')->setOptions(['defaultFont' => 'sans-serif']);
    } elseif ($course_type == "UG") {
        view()->share('result.resultUGJuly2022', $data);
        $pdf = PDF::loadView('result.resultUGJuly2022', compact('data', 'currentexam', 'course_type', 'exampatternclass', 'year', 'epc'))->setPaper('a4')->setOptions(['defaultFont' => 'sans-serif']);
    }

    if ($pdf) {
        $filename = "Emptyresult.pdf";
        if ($data->count() > 0) {
            $fname = $exampatternclass->patternclass->pattern->pattern_name;
            $coursename = $exampatternclass->patternclass->coursepatternclasses->class_name;

            if (Str::contains($coursename, 'B.Voc')) {
                if ($currentexam->exam_sessions == 2) {
                    $coursename = str_replace('Certificate', 'Diploma', $coursename);
                }
            }

            $filename = $coursename . '_' . $fname . '_' . $currentexam->exam_name . '_result.pdf';
        }
// Store PDF in storage (optional)
//Storage::put('pdf/' . $filename, $pdf->output());

// // Return or download the PDF
return $pdf->download($filename);     }
     //UGResultPrintJob::dispatch($id);

      //return response()->json(['message' => 'UG Result generation job dispatched.']);
  }
  // public function UGledgerprint($id)   //final ledger print
  // {
  //   ini_set('max_execution_time', 5000);
  //   ini_set('memory_limit', '4048M');
  //   $exampatternclass = ExamPatternclass::find($id);
  //   $course_type = $exampatternclass->patternclass->getclass->course->course_type;
  //   //dd( $exampatternclass);
  //   $datacount = ExamStudentseatno::where('exam_patternclasses_id', $id)
  //     //->whereIn('seatno',[1981])
  //    // ->where('printstatus', '1')
  //     ->count();


  //   $data = ExamStudentseatno::where('exam_patternclasses_id', $id)     
       
  //     ->get(); //
  //    // dd($data);

  //   // foreach($data as $seatnodata)
  //   //       $seatnodata->update(['token'=>Str::random(80)]) ;

  //   if ($data->count() > 0)
  //     $data->toQuery()->update(array("printstatus" => "1"));

  //   //$currentexam = Exam::Where('status', '1')->first();//->get();
  //   // $currentexam =  Exam::where('id',5)->first();;//->get();
  //   $currentexam = $exampatternclass->exam;
  //   $stud_marks = new StudentmarkController;

  //   //dd( $data);
  //   //view()->share('result.ledgerUG', $data);//old format used in OCT-NOV 2021(JAN 2022) sem -I exam
  //   if ($course_type == "PG")
  //     view()->share('result.ledgerPGJuly2022', $data); //for PG new format used in march 2022(July 2022) sem -II exam rule 
  //   //rule =>passing on total marks circular
  //   if ($course_type == "UG")
  //     view()->share('result.ledgerUGJuly2022', $data); //for UG new format used in march 2022(July 2022) sem -II exam rule 
  //   //rule =>passing on total marks circular

  //   //return view('result.ledgerUG', compact('data'), compact('currentexam'));
  //   //$pdf = PDF::loadView('result.ledgerUG', compact('data'), compact('currentexam','course_type'))->setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'sans-serif']);;
  //   if ($course_type == "PG")
  //     $pdf = PDF::loadView('result.ledgerPGJuly2022', compact('data', 'exampatternclass', 'currentexam', 'course_type'))->setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'sans-serif']);;
  //   if ($course_type == "UG")
  //     $pdf = PDF::loadView('result.ledgerUGJuly2022', compact('data', 'exampatternclass', 'currentexam', 'course_type'))->setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'sans-serif']);;

  //   $canvas = $pdf->getDomPDF()->getCanvas();
  //   //$height = $canvas->get_height();
  //   //$width = $canvas->get_width();
  //   //$canvas->set_opacity(.2,"Multiply");
  //   $canvas->page_text(10, 10, "Report Page: {PAGE_NUM} of {PAGE_COUNT}",  $pdf->getFontMetrics()->getFont('serif', 'normal'), 12, array(0, 0, 0));

  //   if ($data->count() > 0) {
  //     $fname = $data->first()->exampatternclasses->patternclass->pattern->pattern_name;

  //     $coursename = $data->first()->exampatternclasses->patternclass->coursepatternclasses->class_name;

  //     if (Str::contains($coursename, 'B.Voc')) {

  //       if ($currentexam->exam_sessions == 2) {
  //         $coursename = str_replace('Certificate', 'Diploma', $coursename);
  //       }
  //     }

  //     $filename = $coursename . '_' . $fname . '_' . $currentexam->exam_name . '_ledger';
  //   } else {
  //     $filename = "Emptyledger.pdf";
  //     return $pdf->download($filename);
  //   }
  //   //return $pdf->stream($filename);
  //   return $pdf->download($filename . ($data->first()->seatno) . 'To' . ($data->last()->seatno) . '.pdf');
  // }
  //...................Create at 16 April 2022..................
  //   public function UGresultprint($id)//UG  result print
  //   { 
  //     ini_set('max_execution_time', 5000);
  //     ini_set('memory_limit', '4048M');
  // //$id=40;
  //     $exampatternclass=ExamPatternclass::find($id);
  //     $course_type=$exampatternclass->patternclass->getclass->course->course_type;

  //     $exam_id =$exampatternclass->exam_id; 

  //     $patternclass_id =$exampatternclass->patternclass_id; 
  //     // dd($patternclass_id);
  //     $studdata=Studentmark::where('exam_id',$exam_id)->where('patternclass_id',$patternclass_id)->pluck('student_id','seatno')->unique();


  //   //dd($studdata->sortBy('seatno'));
  //     $epc=ExamPatternclass::where('exam_id',$exam_id)->pluck('id');
  // $data=null;
  //     if($course_type=="PG")
  //    { 
  //     $data=$exampatternclass->examstudentseatnos
  //    // $data = ExamStudentseatno::whereIn('exam_patternclasses_id',$epc)
  //     // ->where('prn','=','1032002869')
  //     // ->whereIn('prn',["1032002869", "12021156152","1022104325"])
  //       ->whereNotIn('prn',$this->admissioncanceldata)
  //         ->sortBy('seatno');
  //          //->take(1);
  //           // ->get();

  //      }else
  //      {
  //     $data = ExamStudentseatno::whereIn('exam_patternclasses_id',$epc)
  //     ->whereIn('student_id',$studdata)
  //      //->where('prn','=','1042104626')
  //     // ->whereIn('prn',["1032002869", "12021156152","1022104325"])
  //     ->whereNotIn('prn',$this->admissioncanceldata)
  //          ->orderBy('seatno')
  //       //  ->take(1)
  //          ->get();
  //         //dd($data); 
  //         $year=$exampatternclass->patternclass->getclass->year;

  //      }
  //     // dd($data);
  //     if ($data->count() > 0)
  //          $data->toQuery()->update(array("printstatus" => "1"));
  //          $currentexam = $exampatternclass->exam;//Exam::Where('status', '1')->get();

  //          $stud_marks = new StudentmarkController;

  //      /*  ------OLD format sem 1 exam  2021-22   
  //          view()->share('result.resultUG', $data);//old format used in OCT-NOV 2021(JAN 2022) sem -I exam
  //        //result only grade
  //         // $pdf = PDF::loadView('result.resultUG', compact('data'), compact('currentexam','exampatternclass'))->setPaper('a4')->setOptions(['defaultFont' => 'sans-serif']);;
  //         //result with marks int ext grade cgpa
  //         ////old format used in OCT-NOV 2021(JAN 2022) sem -I exam
  //         $pdf = PDF::loadView('result.resultFinal', compact('data'), compact('currentexam','exampatternclass'))->setPaper('a4')->setOptions(['defaultFont' => 'sans-serif']);;
  //      */
  //     //dd($data);
  //     $studcount=Student::whereIn('id',$data->pluck('student_id'))
  //     //->whereNull('eligibilityno')
  //     //->where('eligibilityno','0')
  //     ->get();
  //     if($studcount->whereNull('eligibilityno')->count()>0 && $studcount->where('eligibilityno','0')->count()>0)
  //       return back()->with('success','please upload all eligibility numbers....');
  //     if($course_type=="PG")
  //     view()->share('result.resultPGJuly2022', $data);//for PG new format used in march 2022(July 2022) sem -II exam rule 
  //                                           //rule =>passing on total marks circular
  //     if($course_type=="UG")
  //     view()->share('result.resultUGJuly2022', $data);//for UG new format used in march 2022(July 2022) sem -II exam rule 
  //                                           //rule =>passing on total marks circular
  //     if($course_type=="PG")
  //     $pdf = PDF::loadView('result.resultPGJuly2022', compact('data','currentexam','course_type','exampatternclass'))->setPaper('a4')->setOptions(['defaultFont' => 'sans-serif']);;
  //     if($course_type=="UG")
  //     $pdf = PDF::loadView('result.resultUGJuly2022', compact('data','currentexam','course_type','exampatternclass','year'))->setPaper('a4')->setOptions(['defaultFont' => 'sans-serif']);;

  //     $canvas = $pdf->getDomPDF()->getCanvas();
  //     //$height = $canvas->get_height();
  //     //$width = $canvas->get_width();
  //     //$canvas->set_opacity(.2,"Multiply");
  //     //$canvas->page_text(10, 10, "", $pdf->getFontMetrics()->getFont('serif','normal'), 12, array(0, 0, 0));

  //     if ($data->count() > 0) { 
  //       $fname = $exampatternclass->patternclass->pattern->pattern_name;

  //       $coursename = $exampatternclass->patternclass->coursepatternclasses->class_name;

  //       if (Str::contains($coursename, 'B.Voc')) {
  //         if ($currentexam->exam_sessions == 2) {
  //           $coursename = str_replace('Certificate', 'Diploma', $coursename);
  //         }
  //       }

  //       $filename = $coursename . '_' . $fname . '_' . $currentexam->exam_name . '_result' . '.pdf';
  //     } else $filename = "Emptyledger.pdf";
  //     //return view('result.resultPGJuly2022', compact('data'), compact('currentexam','course_type','exampatternclass'));
  //     //return $pdf->stream($filename);
  //     return $pdf->download($filename);
  //   }
  //...................Create at 16 April 2022..................
  // public function UGresultprint($id) //UG  result print
  // {
  //   ini_set('max_execution_time', 5000);
  //   ini_set('memory_limit', '4048M');
  //   //$id=40;
  //   $exampatternclass = ExamPatternclass::find($id);
  //   $course_type = $exampatternclass->patternclass->getclass->course->course_type;

  //   $exam_id = $exampatternclass->exam_id;

  //   $patternclass_id = $exampatternclass->patternclass_id;
  //   $datacount = ExamStudentseatno::where('exam_patternclasses_id', $id) //->whereIn('seatno',range(3061,3100))
  //     //->where('printstatus', '1')
  //     ->count();

  //   // dd($patternclass_id);
  //   $studdata = Studentmark::where('exam_id', $exam_id)
  //     ->where('patternclass_id', $patternclass_id)
  //     ->pluck('student_id', 'seatno')->unique();

  //   //dd($studdata->sortBy('seatno'));
  //   $epc = ExamPatternclass::where('exam_id', $exam_id)->pluck('id');
  //   $data = null;
  //   $year = $exampatternclass->patternclass->getclass->year;

  //   if ($course_type == "PG") {
  //     $data = $exampatternclass->examstudentseatnos
  //       // $data = ExamStudentseatno::whereIn('exam_patternclasses_id',$epc)
  //       // ->where('prn','=','10320029971')
  //       // ->whereIn('prn',[1022002518,1022002441,1022104962,1022002673,1022002361,1022002570,1032002808,1022002729,1022002591,1032002856,1022002394,1022002414,1022002635,1022002293,1232105239,1272105073,1022002280,1012001684,1272105200,1022002602,1022002631,1022002485,1022002702,1282205749,1022002704,1152105147,1022002552,1032002908,1282105505,1032002947,1012001832,1022002693,1112000711,1022002447,1022002202,1302205412,1022002215,1062104995,1022002211,1022002261,1022002263,1022002288,1022002324,1022002400,1022002687,1022002600,1022002616,1022002587,1302105144,1032002982,1022002207,1022002410,1022002639,1262205263,1032003027,1022002341,1012002023,1022002628,1012001664,1032002899,1022002456,1032003055,1012001612,1262205046,1292105635,1022002648,1032002927,1302205400,1012002012,1012001620,1302105130,1032003023,1022002417,1032002993,1312205331,1292105447,1012001803,1022002498,1012001892,1022002484,1312205662,1012104968,1022002255,1182205558,1022002438,1012001677,1012001956,1012001689,1012001693,])

  //       ->whereNotIn('prn', $this->admissioncanceldata)
  //       ->sortBy('seatno')
  //       ->take(100)
  //       // ->get()
  //     ;
  //     //dd($data) ;
  //   } else {
  //     $data = ExamStudentseatno::whereIn('exam_patternclasses_id', $epc)
  //       ->whereIn('student_id', $studdata)
  //       //->where('prn','=','1022103272')
  //       // ->whereIn('prn',[1022002518,1022002441,1022104962,1022002673,1022002361,1022002570,1032002808,1022002729,1022002591,1032002856,1022002394,1022002414,1022002635,1022002293,1232105239,1272105073,1022002280,1012001684,1272105200,1022002602,1022002631,1022002485,1022002702,1282205749,1022002704,1152105147,1022002552,1032002908,1282105505,1032002947,1012001832,1022002693,1112000711,1022002447,1022002202,1302205412,1022002215,1062104995,1022002211,1022002261,1022002263,1022002288,1022002324,1022002400,1022002687,1022002600,1022002616,1022002587,1302105144,1032002982,1022002207,1022002410,1022002639,1262205263,1032003027,1022002341,1012002023,1022002628,1012001664,1032002899,1022002456,1032003055,1012001612,1262205046,1292105635,1022002648,1032002927,1302205400,1012002012,1012001620,1302105130,1032003023,1022002417,1032002993,1312205331,1292105447,1012001803,1022002498,1012001892,1022002484,1312205662,1012104968,1022002255,1182205558,1022002438,1012001677,1012001956,1012001689,1012001693,])

  //       ->whereNotIn('prn', $this->admissioncanceldata)
  //       ->orderBy('seatno')
  //       //->where('printstatus', '0')
  //       ->take(100)
  //       ->get();
  //     //dd($data); 
  //   }
  //   // dd($data);
  //   if ($data->count() > 0)
  //     $data->toQuery()->update(array("printstatus" => "1"));
  //   $currentexam = $exampatternclass->exam; //Exam::Where('status', '1')->get();

  //   $stud_marks = new StudentmarkController;

  //   /*  ------OLD format sem 1 exam  2021-22   
  //        view()->share('result.resultUG', $data);//old format used in OCT-NOV 2021(JAN 2022) sem -I exam
  //      //result only grade
  //       // $pdf = PDF::loadView('result.resultUG', compact('data'), compact('currentexam','exampatternclass'))->setPaper('a4')->setOptions(['defaultFont' => 'sans-serif']);;
  //       //result with marks int ext grade cgpa
  //       ////old format used in OCT-NOV 2021(JAN 2022) sem -I exam
  //       $pdf = PDF::loadView('result.resultFinal', compact('data'), compact('currentexam','exampatternclass'))->setPaper('a4')->setOptions(['defaultFont' => 'sans-serif']);;
  //    */
  //   //$studcount = Student::whereIn('id', $data->pluck('student_id'))
  //   //->whereNull('eligibilityno')
  //   //->where('eligibilityno','0')
  //   //->get();
  //   //dd($studcount->whereNull('eligibilityno')->count()>0 || $studcount->where('eligibilityno','0')->count()>0);
  //   // if($studcount->whereNull('eligibilityno')->count()>0 || $studcount->where('eligibilityno','0')->count()>0)
  //   //   return back()->with('success','please upload all eligibility numbers....');

  //   if ($course_type == "PG")
  //     view()->share('result.resultPGJuly2022', $data); //for PG new format used in march 2022(July 2022) sem -II exam rule 
  //   //rule =>passing on total marks circular
  //   if ($course_type == "UG")
  //     view()->share('result.resultUGJuly2022', $data); //for UG new format used in march 2022(July 2022) sem -II exam rule 
  //   //rule =>passing on total marks circular
  //   if ($course_type == "PG")
  //     $pdf = PDF::loadView('result.resultPGJuly2022', compact('data', 'currentexam', 'course_type', 'exampatternclass'))->setPaper('a4')->setOptions(['defaultFont' => 'sans-serif']);;
  //   if ($course_type == "UG")
  //     $pdf = PDF::loadView('result.resultUGJuly2022', compact('data', 'currentexam', 'course_type', 'exampatternclass', 'year', 'epc'))->setPaper('a4')->setOptions(['defaultFont' => 'sans-serif']);;

  //   $canvas = $pdf->getDomPDF()->getCanvas();
  //   //$height = $canvas->get_height();
  //   //$width = $canvas->get_width();
  //   //$canvas->set_opacity(.2,"Multiply");
  //   // $canvas->page_text(10, 10, " {PAGE_NUM} of {PAGE_COUNT}",  $pdf->getFontMetrics()->getFont('serif','normal'), 12, array(0, 0, 0));

  //   if ($data->count() > 0) {
  //     $fname = $exampatternclass->patternclass->pattern->pattern_name;

  //     $coursename = $exampatternclass->patternclass->coursepatternclasses->class_name;

  //     if (Str::contains($coursename, 'B.Voc')) {
  //       if ($currentexam->exam_sessions == 2) {
  //         $coursename = str_replace('Certificate', 'Diploma', $coursename);
  //       }
  //     }


  //     $filename = $coursename . '_' . $fname . '_' . $currentexam->exam_name . '_result';
  //   } else {
  //     $filename = "Emptyresult.pdf";
  //     return $pdf->download($filename);
  //   }
  //   //return view('result.resultPGJuly2022', compact('data'), compact('currentexam','course_type','exampatternclass'));
  //   //return $pdf->stream($filename);
  //   return $pdf->download($filename . ($data->first()->seatno) . 'To' . ($data->last()->seatno) . '.pdf');
  // }
  //............................................................................
  //............................................................................


  public function getAllsubjects($id)   //fy sem I ledger 
  {

 
    $allbarcode = Exambarcode::where('status', '!=', '0')
      ->where('exam_patternclasses_id', $id)
      ->get();
    if ($allbarcode->where('verified_marks', '!=', '-1')->count() > 0) {

      foreach ($allbarcode as $barcodefinal) {


        Studentmark::upsert([
          'prn' => trim($barcodefinal->exam_studentseatnos->student->prn),
          'seatno' => trim($barcodefinal->exam_studentseatnos->seatno),
          'student_id' => trim($barcodefinal->exam_studentseatnos->student_id),
          'subject_id' => trim($barcodefinal->subject_id),
          'sem' => trim($barcodefinal->subject->subject_sem),
          'exam_id' => trim($barcodefinal->exampatternclasses->exam_id),
          'patternclass_id' => trim($barcodefinal->exampatternclasses->patternclass_id),
          'performancecancel' => ($barcodefinal->status == 2 ? "1" : "0"),
          'ext_marks' => $barcodefinal->status != 0 ? "-1" : "0",
          // 'performancecancel'=>(strtoupper(trim($row['ext_marks']))=='PC'?"1":"0"),
          //student_id,subject_id,seatno,examid                
        ], ['student_id', 'subject_id', 'seatno', 'exam_id']);

        $barcodefinal->update([
          'verified_marks' => '-1',
        ]);
      }
    }
      

    $this->generateFinalResult($id);
    // return view('testfont');
    // dd("ok");
    // return back();
    return back()->with('success', 'Ordinance Successfully applied  !!!!!');

    ini_set('max_execution_time', 5000);
    ini_set('memory_limit', '4048M');



    $data = ExamStudentseatno::where('exam_patternclasses_id', $id)
      ->whereIn('prn', [1022002518, 1022002441, 1022104962, 1022002673, 1022002361, 1022002570, 1032002808, 1022002729, 1022002591, 1032002856, 1022002394, 1022002414, 1022002635, 1022002293, 1232105239, 1272105073, 1022002280, 1012001684, 1272105200, 1022002602, 1022002631, 1022002485, 1022002702, 1282205749, 1022002704, 1152105147, 1022002552, 1032002908, 1282105505, 1032002947, 1012001832, 1022002693, 1112000711, 1022002447, 1022002202, 1302205412, 1022002215, 1062104995, 1022002211, 1022002261, 1022002263, 1022002288, 1022002324, 1022002400, 1022002687, 1022002600, 1022002616, 1022002587, 1302105144, 1032002982, 1022002207, 1022002410, 1022002639, 1262205263, 1032003027, 1022002341, 1012002023, 1022002628, 1012001664, 1032002899, 1022002456, 1032003055, 1012001612, 1262205046, 1292105635, 1022002648, 1032002927, 1302205400, 1012002012, 1012001620, 1302105130, 1032003023, 1022002417, 1032002993, 1312205331, 1292105447, 1012001803, 1022002498, 1012001892, 1022002484, 1312205662, 1012104968, 1022002255, 1182205558, 1022002438, 1012001677, 1012001956, 1012001689, 1012001693])
      ->whereNotIn('prn', $this->admissioncanceldata)

      ->get();
    dd($data);
    //->get();//BCS
    if ($data->count() > 0)
      $data->toQuery()->update(array("printstatus" => "1"));
    // share data to view
    $currentexam = Exam::Where('status', '1')->get();
    $stud_marks = new StudentmarkController;
    $stud_marks->collectintextmarks($data, $currentexam->first()->id);
    //$stud_marks->applyordinanceone($data);
    $stud_marks->applyordinanceonesem2($data, $currentexam->first()->id);

    view()->share('result.ledger', $data);

    $pdf = PDF::loadView('result.ledger', compact('data'), compact('currentexam'))->setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'sans-serif']);;

    $canvas = $pdf->getDomPDF()->getCanvas();
    //$height = $canvas->get_height();
    //$width = $canvas->get_width();
    //$canvas->set_opacity(.2,"Multiply");
    $canvas->page_text(10, 10, "Report Page: {PAGE_NUM} of {PAGE_COUNT}", null, 12, array(0, 0, 0));

    if ($data->count() > 0) { //$fname = $data->first()->exampatternclasses->patternclass->pattern->pattern_name;
      // $filename = $data->first()->exampatternclasses->patternclass->coursepatternclasses->class_name . '_' . $fname . 'ledger' . '.pdf';
      $fname = $data->first()->exampatternclasses->patternclass->pattern->pattern_name;

      $coursename = $data->first()->exampatternclasses->patternclass->coursepatternclasses->class_name;

      if (Str::contains($coursename, 'B.Voc')) {
        if ($currentexam->first()->exam_sessions == 2) {
          $coursename = str_replace('Certificate', 'Diploma', $coursename);
        }
      }

      $filename = $coursename . '_' . $fname . '_' . $currentexam->first()->exam_name . '_ledger' . '.pdf';
    } else $filename = "Emptyleger.pdf";


    //$filename="sss.pdf";


    return $pdf->download($filename);

    //return $pdf->stream('result.pdf');


    // download PDF file with download method
    // return $pdf->download('pdf_file.pdf');
    //$pdf = PDF::loadView('result.showresult',compact('data'))->setOptions(['defaultFont' => 'sans-serif']); ;
    // return $pdf->stream('result.pdf');
    // download PDF file with download method
    //return $pdf->download('subjectpdf_file.pdf');




  }

  // Result generation logic
  public function getsgpa($d, $subjects, $exampatternclasss_id, $sem, $currentexam)
  {
    //  dd($d->seatno);
    $pf = 0;
    $total_credit_point = 0;
    $total_subject_credit = 0;
    $Absentcnt = 0;
    $subjectCnt = 0;
    $total_credit_earned = 0.0;
    $totalMarks = 0;
    $totalOutofMarks = 0;
    $passfail = 1;
    $pfAbStatus = 1;

    $extracreditsubpassfail = 1;



    // $studentResult->seatno=$d->seatno;
    foreach ($subjects as $sub) {

      //for previous result
      // if($currentexam==1)   //logic for bca scienc
      // {
      // if($sub->id=='382' || $sub->id=='386' || $sub->id=='390')
      //    $newcurrentexam=3;
      //    else
      //    $newcurrentexam=$currentexam;
      // }
      // else if($currentexam==3)
      // {
      //    if($sub->id=='251' || $sub->id=='255' || $sub->id=='260')
      //    $newcurrentexam=1;
      //    else
      //    $newcurrentexam=$currentexam;
      // }
      // else 
      $newcurrentexam = $currentexam;

      $v = $d->student->getmarks($sub->id, $newcurrentexam);
      // dd($v);
      if ($v != '-5') {  //Student Subject Data  -5 =>Empty
        if (!($v->subject->subject_type == 'G' or $v->subject->subject_type == 'IG' or $v->subject->subject_type == 'IEG')) {
          $totalOutofMarks += $v->subject->subject_maxmarks;
          // dd($v->subject->subject_credit." ".$v->grade_point);
          $subjectCnt++;

          $total_credit_point = $total_credit_point + ($v->subject->subject_credit * $v->grade_point);
          $total_subject_credit = $total_subject_credit + $v->subject->subject_credit;

          if ($v->total != -1)
            $totalMarks += $v->total;

          if ($v->grade_point != 0)
            $total_credit_earned = $total_credit_earned + $v->subject->subject_credit;
          else $passfail = 0;

          if ($v->subject_grade == -1 || $v->subject_grade == 'Ab')
            $Absentcnt++;
        } else {
          if (($v->subject->subject_type == "G" and $v->grade == 'F') or ($v->subject->subject_type == "IG" and $v->grade == 'F') or ($v->subject->subject_type == "IEG" and $v->grade == 'F'))
            $extracreditsubpassfail = 0;
          if (($v->subject->subject_type == "G" and $v->grade == -1) or ($v->subject->subject_type == "IG" and $v->grade == 'F') or ($v->subject->subject_type == "IEG" and $v->grade == 'F'))
            $extracreditsubpassfail = 0;
        }
        if (($v->subject->subject_type == "G" and $v->grade == 'F') or ($v->subject->subject_type == "IG" and $v->grade == 'F') or ($v->subject->subject_type == "IEG" and $v->grade == 'F'))
          $extracreditsubpassfail = 0;
      } //if
    } //inner for Sem Subjects

    //dd( $total_credit_earned);
    if ($passfail == 0) //Any Subject Fail 
    {
      $pfAbStatus = 0;
    } else if ($passfail == 1) {
      $pfAbStatus = 1;
      try {
        if ($total_credit_earned != 0)
          $pf = number_format(round($total_credit_point / $total_credit_earned, 3), 3);
        else
          $pf = 0;
      } //try
      catch (Exception $e) {
      } //catch
    } //else if passfail

    // $studentResult->sgpa=

    if ($subjectCnt === $Absentcnt) {
      //Add Absent Entry
      $pfAbStatus = 3;
      // $studentResult->seatno

    }

    

    Studentresult::upsert([
      'student_id' => $d->student_id,
      'totalMarks' => $totalMarks,
      'totalOutofMarks' => $totalOutofMarks,
      'seatno' => $d->seatno,
      'exam_patternclasses_id' => $exampatternclasss_id,
      'sem' => $sem,
      'sgpa' => $pf,
      'semcreditearned' =>  $total_credit_earned,
      'semtotalcredit' => $total_subject_credit,
      'semtotalcreditpoints' => $total_credit_point,
      'resultstatus' => $pfAbStatus, //0 => fail in any one subject 1=>Pass in all subject 3 =>absent in all subject
      'extraCreditsStatus' => $extracreditsubpassfail,
      'isregular'=>$isregular,

    ], ['student_id', 'sem', 'exam_patternclasses_id']);


    $currentclass = $d->student->currentclassstudent->where('sem', $sem)->first();

    if ($sem == 1 || $sem == 3 || $sem == 5) {
      // if($currentclass->pfstatus!=1)
      $currentclass->update(['pfstatus' => $pfAbStatus == 0 ? 2 : $pfAbStatus]);
    }
  }
  //Ordinace Function 
  public function generateFinalResult($id)   //fy sem I ledger Apply Result
  {
   
    //  $ids=range(1,12);//fy exam id=1 sem=1
    // $sem=1;
    //  $ids=range(31,42);//fy exam id=3 sem=2
    //   $sem=2;

    // $ids=range(13,30);//pg-1 year exam id=2 sem=1
    //  $sem=1;
    //  $ids=range(43,60);//pg-1 year exam id=4 sem=2
    //  $sem=2;
    //sem=3 range epc 91 to 126
    //  foreach($ids as $id)//for previous result generation
    {

      $exampatternclass = ExamPatternclass::find($id);

      $course_type = $exampatternclass->patternclass->getclass->course->course_type;
      $currentexam = $exampatternclass->exam_id;
      //dd($id);
      ini_set('max_execution_time', 5000);
      ini_set('memory_limit', '4048M');

      $data = ExamStudentseatno::where('exam_patternclasses_id', $id)
        //->where('prn', '1012103914') //FYBBA CA student web tech paper issue =>khemnar sir
        //    ->whereIn('prn',[1022002518,1022002441,1022104962,1022002673,1022002361,1022002570,1032002808,1022002729,1022002591,1032002856,1022002394,1022002414,1022002635,1022002293,1232105239,1272105073,1022002280,1012001684,1272105200,1022002602,1022002631,1022002485,1022002702,1282205749,1022002704,1152105147,1022002552,1032002908,1282105505,1032002947,1012001832,1022002693,1112000711,1022002447,1022002202,1302205412,1022002215,1062104995,1022002211,1022002261,1022002263,1022002288,1022002324,1022002400,1022002687,1022002600,1022002616,1022002587,1302105144,1032002982,1022002207,1022002410,1022002639,1262205263,1032003027,1022002341,1012002023,1022002628,1012001664,1032002899,1022002456,1032003055,1012001612,1262205046,1292105635,1022002648,1032002927,1302205400,1012002012,1012001620,1302105130,1032003023,1022002417,1032002993,1312205331,1292105447,1012001803,1022002498,1012001892,1022002484,1312205662,1012104968,1022002255,1182205558,1022002438,1012001677,1012001956,1012001689,1012001693])

        //  ->whereIn('prn',['1012103914','1012103675','1012103889'
        //  ,'1012103934','1012103733',
        //  ])
         //->where('prn','1032002876')
        //reval change cases march 2023 exam
        // ->whereIn('prn',['1022104324','1032104012','1032002847',
        // '1012001780','1012001942','1012001709',
        // '1022002492','1022002722','1022002201',
        // '1022002413','1022002596','1022002214',
        // '1022002670','1022002616','1282105497',
        // '1012001832'])
        //->where('printstatus','0')
        //->take(50)
        ->whereNotIn('prn', $this->admissioncanceldata)

        ->get(); //->get();//BCS
        // dd($data);
      //if ($data->count() > 0)
      // $data->toQuery()->update(array("printstatus" => "1"));

      $stud_marks = new StudentmarkController;
      $stud_marks->collectintextmarks($data, $currentexam);

      // $stud_marks->applyordinanceone($data);
      $stud_marks->applyordinanceonesem2($data, $currentexam);
      //  dd("second");
      // new rule int + ext =total apply ordinance on total and check passing on total only
      //$stud_marks->applyordinanceoneontotal($data,$currentexam);

      // $currentexam = Exam::Where('status', '1')->get();


      if ($data->count() > 0) {

        $cnt = 0;

        foreach ($data as $d) {

          $datasem = $d->student->currentclassstudent; //->where('sem','<=',$sem); //unique('sem');

          // $datasem = $d->student->currentclassstudent; 

          foreach ($datasem  as $data2) {

            if ($data2->markssheetprint_status != -1) //direct admission to second year 
            { //dd($datasem->count());
              // if($data2->pfstatus==1 and  $data2->sem==1)
              // {}
              // else
              if ($datasem->count() == 2) {
                // if($data2->sem==2 or $data2->sem==4 or $data2->sem==6)//fy backlog student
                //       $data2->update(['ordinace2_flag'=>5]);

                //  if(!is_null($d->student->studentresults->where('sem','1')))
                //  { 

                //   $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject
                //   $this->getsgpa($d,$subjects,$id, $data2->sem, $currentexam );
                //  } 
                // if($data2->sem==2)
                //  {

                //   $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject
                //   $this->getsgpa($d,$subjects,$id, $data2->sem, $currentexam );
                //  }

                // if($data2->pfstatus==1 and  $data2->sem==1)
                // {
                // }
                if ($data2->sem == 1) {
                  $studresult = $d->student->studentresults->where('sem', '1')->last();

                  if (is_null($studresult)) {
                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject
                    // 
                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  } else if ($studresult->sgpa == 0 || $studresult->extraCreditsStatus == 0) {
                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject
                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  }
                } else if ($data2->sem == 2) {
                  $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject
                  $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                } else if ($data2->sem == 3) {
                  $studresult = $d->student->studentresults->where('sem', '3')->last();
                  //dd($studresult->sgpa);
                  if (is_null($studresult)) {
                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  } else if ($studresult->sgpa == 0 || $studresult->extraCreditsStatus == 0) {
                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject
                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  }
                } else if ($data2->sem == 4) {

                  $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject
                  $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                } else if ($data2->sem == 5) //ty
                {
                  $studresult = $d->student->studentresults->where('sem', '3')->last();
                  //dd($studresult->sgpa);
                  if (is_null($studresult)) {
                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  } else if ($studresult->sgpa == 0 || $studresult->extraCreditsStatus == 0) {
                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject
                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  }
                } else if ($data2->sem == 6) //ty
                {

                  $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject
                  $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                }
              } else if ($datasem->count() == 1) //direct fy, sy,ty admission
              {

                $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject
                $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
              } //if
              else  if ($datasem->count() == 3) // sy or direct to ty
              {
                //dd($data2->sem);
                if ($data2->sem == 1 || $data2->sem == 2) {
                  $studresult = $d->student->studentresults->where('sem', $data2->sem)->last();
                  //dd($studresult->sgpa);
                  if ($studresult->resultstatus != 1) {

                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  }
                } else  if ($data2->sem == 3 || $data2->sem == 5) {
                  $studresult = $d->student->studentresults->where('sem', $data2->sem)->last();
                  if (is_null($studresult) || $studresult->resultstatus != 1) {
                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  }
                } else  if ($data2->sem == 4 || $data2->sem == 6) {
                  $studresult = $d->student->studentresults->where('sem', $data2->sem)->last();
                  if (is_null($studresult) || $studresult->resultstatus != 1) {
                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  }
                }
              } else  if ($datasem->count() == 4) // sy 
              { 
                if ($data2->sem == 1 || $data2->sem == 2 || $data2->sem == 3 || $data2->sem == 4 || $data2->sem == 5 || $data2->sem == 6) {
                  $studresult = $d->student->studentresults->where('sem', $data2->sem)->last();
                  //dd($studresult->sgpa);
                 
                  if (is_null($studresult)) {
                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  } else if ($studresult->resultstatus != 1 || $studresult->extraCreditsStatus == 0) {

                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  } else if ($studresult->sgpa == 0 || $studresult->extraCreditsStatus == 0) {

                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  }
                }
                // else  if($data2->sem==3)
                // {
                //   $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                //   $this->getsgpa($d,$subjects,$id, $data2->sem, $currentexam );

                // }
                else if ($data2->sem == 4) {
                  $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                  $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                }
              } else  if ($datasem->count() == 5) // ty 
              {
                if ($data2->sem == 1 || $data2->sem == 2 || $data2->sem == 3 || $data2->sem == 4 || $data2->sem == 5) {
                  $studresult = $d->student->studentresults->where('sem', $data2->sem)->last();
                  //dd($studresult->sgpa);
                  if (is_null($studresult)) {
                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  } else if ($studresult->resultstatus != 1 || $studresult->extraCreditsStatus == 0) {

                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  } else if ($studresult->sgpa == 0 || $studresult->extraCreditsStatus == 0) {

                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  }
                }

                // else  if($data2->sem==3)
                // {
                //   $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                //   $this->getsgpa($d,$subjects,$id, $data2->sem, $currentexam );

                // }
                else if ($data2->sem == 6) {
                  $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                  $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                }
              } else  if ($datasem->count() == 6) // ty 
              {
                if ($data2->sem == 1 || $data2->sem == 2 || $data2->sem == 3 || $data2->sem == 4 || $data2->sem == 5) {
                  $studresult = $d->student->studentresults->where('sem', $data2->sem)->last();
                  //dd($studresult->sgpa);
                  if (is_null($studresult)) {
                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  } else if ($studresult->resultstatus != 1 || $studresult->extraCreditsStatus == 0) {

                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  } else if ($studresult->sgpa == 0 || $studresult->extraCreditsStatus == 0) {

                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  }
                } else if ($data2->sem == 6) {
                  $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                  $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                }
              }
            } //if markssheetprint_status
          } //inner for Sem Data current class


          foreach ($datasem  as $data2) {
            if ($data2->markssheetprint_status != -1) {

              if ($data2->pfstatus != 1) {
                switch ($data2->sem) {
                  case 2:
                  case 4:
                    //For PG final rsesult ordinance logic

                  case 6: {
                      $pfAbStatus = 1;

                      $Sem1Data = Studentresult::where('student_id', $d->student_id)->where('sem', $data2->sem - 1)->get()->last();
                      // $Sem1Data= $d->student->studentresults->where('sem',$data2->sem-1)->last() ;
                      //$Sem2Data= $d->student->studentresults->where('sem',$data2->sem)->last() ;
                      $Sem2Data = Studentresult::where('student_id', $d->student_id)->where('sem', $data2->sem)->get()->last();

                      $x = $Sem1Data->semcreditearned + $Sem2Data->semcreditearned;
                      $y = $Sem1Data->semtotalcredit + $Sem2Data->semtotalcredit;

                      // dd($x."  ".$y);
                      //dd($data2->patternclass->credit);
                      $z = $data2->patternclass->credit;

                      if ($x >= $z and $x < $y)
                        $pfAbStatus = 2; //{{"Result : Fail A.T.K.T." }} 
                      else if ($x < $z and $x >= 0) {
                        $pfAbStatus = 0;
                      } //{{"Result : Fail " }}
                      if ($pfAbStatus == 1 and ($Sem1Data->extraCreditsStatus == 0 or $Sem2Data->extraCreditsStatus == 0))
                        $pfAbStatus = 2; //{{"Result : Fail A.T.K.T." }}

                      //  dd( $Sem1Data->extraCreditsStatus==0 or $Sem2Data->extracreditsubpassfail==0);
                      // dd( $d->student->currentclassstudent->where('sem',$datasem->count())->last())
                      if ($pfAbStatus == 0)
                        $d->student->currentclassstudent->where('sem', $data2->sem)->last()->update([
                          'pfstatus' => $pfAbStatus,
                          'isregular' => 0,
                        ]);
                      else
                        $d->student->currentclassstudent->where('sem', $data2->sem)->last()->update([
                          'pfstatus' => $pfAbStatus,
                          'isregular' => 1,
                        ]);
                      break;
                    }
                }
              }
            } //if marksheetprint status 
          }
          if ($course_type == "PG") {
            if ($d->student->currentclassstudent->last()->sem == 4) //for PG only
            {
              $this->applyordinance_four_new_int_ext($d, $id, $currentexam); // 

              // $this->applyordinance_four($d,$id,$currentexam);//

            }
          }
          if ($course_type == "UG") {
            if ($d->student->currentclassstudent->last()->sem == 6) //for UG only
            {

              $this->applyordinance_four_new_int_ext($d, $id, $currentexam); // 
              //   $this->applyordinance_four($d,$id,$currentexam);//
            }
          }
        } //main for Data

      } //count
    } //for ids
  }
  //Result generation loginc end here 
  public function applyordinance_four($studseatno, $exampatternclass_id, $currentexam)
  {
    $count = 0;
    $studres = null;
    $countsubfail = 0;
    $subjectdata = null;
    //dd($studseatno->studentresult->groupby('sem'));
    foreach ($studseatno->studentresult->groupby('sem') as $data) {
      if ($data->last()->sgpa == 0 || $data->last()->extraCreditsStatus == 0) {
        $count++;
        $studres = $data;
      }
    }
    //dd($count);
    if ($count == 1) {
      //$subject=Subject::where('patternclass_id',$studres->last()->exampatternclass->patternclass)
      //                     ->get();
      //dd($studres->last()->exampatternclass->patternclass);
      $studentmarks = $studres->last()->student->studentmarks->where('patternclass_id', $studres->last()->exampatternclass->patternclass->id);
      foreach ($studentmarks->groupby('subject_id') as $data1) {

        if ($data1->last()->grade == 'F' || $data1->last()->grade == 'Ab' || $data1->last()->grade == '-1') { // dd($data1->last()->performancecancel);
          //dd(!($data1->last()->int_marks==-1 ||  $data1->last()->ext_marks==-1));//performancecancel
          //if($data1->last()->performancecancel=='1')
          {
            if (!($data1->last()->int_marks == -1 ||  $data1->last()->ext_marks == -1 || $data1->last()->performancecancel == '1')) {

              $countsubfail++;
              $subjectdata = $data1;
            }
          }
        }
      }
      //previous ordinance logic

      if ($countsubfail == 1) {
        // dd($countsubfail."PP");
        //apply ordinance 4 logic

        $ordinancelimit = 10;
        $prevordinaceone = $subjectdata->last()->int_ordinance_one_marks + $subjectdata->last()->practical_ordinance_one_marks + $subjectdata->last()->ext_ordinance_one_marks + $subjectdata->last()->total_ordinance_one_marks;


        //dd("Ordinace 1 is Used".$ordinancelimit-$prevordinaceone);


        $newordinacelimit = $ordinancelimit - $subjectdata->last()->int_ordinance_one_marks + $subjectdata->last()->practical_ordinance_one_marks + $subjectdata->last()->ext_ordinance_one_marks + $subjectdata->last()->total_ordinance_one_marks;
        //  dd( $marksshortfall=$data1->first()->subject->subject_totalpassing-$data1->first()->total);
        // dd($subjectdata);
        if (($subjectdata->last()->subject->subject_totalpassing - $subjectdata->last()->total) <= $newordinacelimit
          && ($subjectdata->last()->subject->subject_totalpassing - $subjectdata->last()->total) > 0
        )
          $subjectdata->last()->update(['grade_point' => 4, 'total' => $subjectdata->last()->total + $subjectdata->last()->subject->subject_totalpassing - $subjectdata->last()->total, 'grade' => 'D', 'total_ordinancefour_marks' => $subjectdata->last()->subject->subject_totalpassing - $subjectdata->last()->total]);
        else {
          if ($subjectdata->last()->subject->subject_totalpassing <= $subjectdata->last()->total) {
            $per = (($subjectdata->last()->total) / ($subjectdata->last()->subject->subject_maxmarks)) * 100;
            $gp = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade')->get();
            foreach ($gp as $g) {
              //$v->grade = $g->grade;
              // $v->grade_point = $g->grade_point;

              $subjectdata->last()->update(['grade_point' => $g->grade_point, 'total' => $subjectdata->last()->total, 'grade' => $g->grade, 'total_ordinancefour_marks' => '-1']);
            }
          }
        }
        //  dd( $subjectdata->first());
        $subjects = $studseatno->student->getsubjects($studres->last()->exampatternclass->patternclass->id, $subjectdata->last()->subject->subject_sem); // get sem1 subject
        // dd($subjects) ; 
        $this->getsgpa($studseatno, $subjects, $exampatternclass_id, $subjectdata->first()->subject->subject_sem, $currentexam);
      }
    }


    //dd("Ordinace s4 is Applied");

  }
  public function generateresult() //fy sem I result
  {
    ini_set('max_execution_time', 3000);
    ini_set('memory_limit', '2048M');
    $data = ExamStudentseatno::where('exam_patternclasses_id', '1')->get(); //arts
    // $data= ExamStudentseatno::where('exam_patternclasses_id','2')->get(); //comm
    //$data= ExamStudentseatno::where('exam_patternclasses_id','3')->get();//FYBBA (CA)

    //$data= ExamStudentseatno::where('exam_patternclasses_id','4')->get();//FYBBA

    //$data= ExamStudentseatno::where('exam_patternclasses_id','5')->get();//BCS



    //$data= ExamStudentseatno::where('exam_patternclasses_id','6')->get();// fybsc
    // $data= ExamStudentseatno::where('exam_patternclasses_id','7')->get();//BCA under science

    //$data = ExamStudentseatno::where('exam_patternclasses_id', '8')->get(); //BVoc SD

    //$data= ExamStudentseatno::where('exam_patternclasses_id','9')->get();//Bvoc HT

    //$data= ExamStudentseatno::where('exam_patternclasses_id','10')->get();//BVoc DPP

    //$data= ExamStudentseatno::where('exam_patternclasses_id','11')->get();//Bvoc AT

    //$data= ExamStudentseatno::where('exam_patternclasses_id','12')->get(); //bvoc ASS


    $stud_marks = new StudentmarkController;

    // $stud_marks->applyordinanceone($data);
    $currentexam = Exam::Where('status', '1')->get();
    view()->share('result.result', $data);
    $fname = $data->first()->exampatternclasses->patternclass->pattern->pattern_name;
    $filename = $data->first()->exampatternclasses->patternclass->coursepatternclasses->class_name . '_' . $fname . '_Result' . '.pdf';
    $pdf = PDF::loadView('result.result', compact('data'), compact('currentexam'))->setOptions(['defaultFont' => 'sans-serif']);
    //$filename=->$data->exampatternclasses->patternclass->coursepatternclasses->class_name;
    //  return $pdf->stream('result.pdf');
    return $pdf->download($filename);
  }
  //sem 2 result 
  public function generateresultsem2()
  {
    ini_set('max_execution_time', 3000);
    ini_set('memory_limit', '2048M');

    //BCS patterclass=10
    //$data= ExamStudentseatno::where('exam_patternclasses_id','31')->get();
    //BCA Science patterclass=13
    //$data= ExamStudentseatno::where('exam_patternclasses_id','32')->get();
    //B.Voc SD patterclass=22
    //$data= ExamStudentseatno::where('exam_patternclasses_id','33')->get();
    //B.Voc AT patterclass=31
    // $data= ExamStudentseatno::where('exam_patternclasses_id','34')->get();//->where('prn','=','1112000702')->get();//->where('prn','<','1042003203')->get();
    //B.Voc HT patterclass=25
    // $data= ExamStudentseatno::where('exam_patternclasses_id','35')->get();//->where('prn','=','1112000702')->get();//->where('prn','<','1042003203')->get();
    //B.Voc DPP patterclass=28
    //$data= ExamStudentseatno::where('exam_patternclasses_id','36')->get();//->where('prn','=','1112000702')->get();//->where('prn','<','1042003203')->get();
    //B.Voc ASS patterclass=34
    //$data= ExamStudentseatno::where('exam_patternclasses_id','37')->get();//->where('prn','=','1122001309')->get();//->where('prn','<','1042003203')->get();

    // BBA-CA patterclass=19
    //$data= ExamStudentseatno::where('exam_patternclasses_id','38')->get();//->where('prn','<','1042003203')->get();
    // BBA patterclass=16
    // $data= ExamStudentseatno::where('exam_patternclasses_id','39')->get();//->where('prn','=','1112000702')->get();//->where('prn','<','1042003203')->get();

    //Bcom patterclass=4
    //   $data= ExamStudentseatno::where('exam_patternclasses_id','40')->get();
    //BA patterclass=1
    // $data= ExamStudentseatno::where('exam_patternclasses_id','41')->get();
    //BSc patterclass=7
    $data = ExamStudentseatno::where('exam_patternclasses_id', '42')->get();
    $currentexam = Exam::Where('status', '1')->get();
    $stud_marks = new StudentmarkController;
    //$stud_marks->collectintextmarks($data,$currentexam->first()->id);
    // $stud_marks->applyordinanceonesem2($data,$currentexam->first()->id);

    view()->share('result.resultsem2', $data);
    $fname = $data->first()->exampatternclasses->patternclass->pattern->pattern_name;
    $coursename = $data->first()->exampatternclasses->patternclass->coursepatternclasses->class_name;

    if (Str::contains($coursename, 'B.Voc')) {
      if ($currentexam->first()->exam_sessions == 2) {
        $coursename = str_replace('Certificate', 'Diploma', $coursename);
      }
    }
    $filename = $coursename . '_' . $fname . '_' . $currentexam->first()->exam_name . '_Result' . '.pdf';
    $pdf = PDF::loadView('result.resultsem2', compact('data'), compact('currentexam'))->setOptions(['defaultFont' => 'sans-serif']);
    //$filename=->$data->exampatternclasses->patternclass->coursepatternclasses->class_name;
    // return $pdf->stream($filename);
    return $pdf->download($filename);
  }
  //sem 2 ledger UG
  public function generateledgersem2()
  {
    ini_set('max_execution_time', 5000);
    ini_set('memory_limit', '8096M');
    //BCS patterclass=10
    $data = ExamStudentseatno::where('exam_patternclasses_id', '31')->get();

    //BCA Science patterclass=13
    //  $data= ExamStudentseatno::where('exam_patternclasses_id','32')->get();
    // dd($data);
    //B.Voc SD patterclass=22
    //$data= ExamStudentseatno::where('exam_patternclasses_id','33')->get();
    //B.Voc AT patterclass=31
    //$data= ExamStudentseatno::where('exam_patternclasses_id','34')->get();//->where('prn','=','1112000702')->get();//->where('prn','<','1042003203')->get();
    //B.Voc HT patterclass=25
    //  $data= ExamStudentseatno::where('exam_patternclasses_id','35')->get();//->where('prn','=','1112000702')->get();//->where('prn','<','1042003203')->get();
    //B.Voc DPP patterclass=28
    // $data= ExamStudentseatno::where('exam_patternclasses_id','36')->get();//->where('prn','=','1112000702')->get();//->where('prn','<','1042003203')->get();
    //B.Voc ASS patterclass=34
    //$data= ExamStudentseatno::where('exam_patternclasses_id','37')->get();//->where('prn','=','1122001303')->get();//->where('prn','<','1042003203')->get();

    // BBA-CA patterclass=19
    //$data= ExamStudentseatno::where('exam_patternclasses_id','38')->get();//->where('prn','<','1042003203')->get();
    // BBA patterclass=16
    //$data= ExamStudentseatno::where('exam_patternclasses_id','39')->get();//->where('prn','=','1112000702')->get();//->where('prn','<','1042003203')->get();
    //Bcom patterclass=4
    //   $data= ExamStudentseatno::where('exam_patternclasses_id','40')->get();
    //BA patterclass=1
    // $data= ExamStudentseatno::where('exam_patternclasses_id','41')->get();
    //BSc patterclass=7
    // $data= ExamStudentseatno::where('exam_patternclasses_id','42')->get();

    //dd($data);
    // share data to viewquit
    $currentexam = Exam::Where('status', '1')->get();
    // dd($currentexam->first()->id);
    $stud_marks = new StudentmarkController;
    $stud_marks->collectintextmarks($data, $currentexam->first()->id);
    //$stud_marks = new StudentmarkController;
    $stud_marks->applyordinanceonesem2($data, $currentexam->first()->id);

    view()->share('result.ledgersem2', $data);

    $pdf = PDF::loadView('result.ledgersem2', compact('data'), compact('currentexam'))->setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'sans-serif']);;

    $fname = $data->first()->exampatternclasses->patternclass->pattern->pattern_name;

    $coursename = $data->first()->exampatternclasses->patternclass->coursepatternclasses->class_name;

    if (Str::contains($coursename, 'B.Voc')) {
      if ($currentexam->first()->exam_sessions == 2) {
        $coursename = str_replace('Certificate', 'Diploma', $coursename);
      }
    }

    $filename = $coursename . '_' . $fname . '_' . $currentexam->first()->exam_name . '_ledger' . '.pdf';
    //$filename="sss.pdf";
    return $pdf->download($filename);
  }
  //sem 2 ledger PG
  public function generateledgerpgsem2()
  {
    ini_set('max_execution_time', 5000);
    ini_set('memory_limit', '8096M');

    //MA sanskrit patterclass=49
    // $data= ExamStudentseatno::where('exam_patternclasses_id','48')->get();//->where('prn','=','1212004603')->get();

    //MA POLY patterclass=47
    //$data= ExamStudentseatno::where('exam_patternclasses_id','47')->get();//->where('prn','=','1212004603')->get();

    //MA MARATHI patterclass=
    //$data= ExamStudentseatno::where('exam_patternclasses_id','46')->get();//->where('prn','=','1212004603')->get();

    //MA HINDI patterclass=43
    //$data= ExamStudentseatno::where('exam_patternclasses_id','45')->get();
    //MA English=41
    //$data= ExamStudentseatno::where('exam_patternclasses_id','44')->get();
    //MA economics=39
    //$data= ExamStudentseatno::where('exam_patternclasses_id','43')->get();
    //MCS patterclass=53
    //  $data= ExamStudentseatno::where('exam_patternclasses_id','50')->get();//->where('prn','=','1212004603')->get();
    //MSc elec patterclass=59
    //$data= ExamStudentseatno::where('exam_patternclasses_id','53')->get();//->where('prn','=','1212004603')->get();
    //Msc ZOO patterclass=51
    //$data= ExamStudentseatno::where('exam_patternclasses_id','49')->get();//->where('prn','=','1212004603')->get();
    //MSc botany patterclass=55
    //  $data= ExamStudentseatno::where('exam_patternclasses_id','51')->get();//->where('prn','=','1212004603')->get();
    //Msc Analytical patterclass=57
    //$data= ExamStudentseatno::where('exam_patternclasses_id','52')->get();//->where('prn','=','1212004603')->get();
    //MSc Geography patterclass=61
    //$data= ExamStudentseatno::where('exam_patternclasses_id','54')->get();//->where('prn','=','1212004603')->get();
    //MSc Math patterclass=63
    // $data= ExamStudentseatno::where('exam_patternclasses_id','55')->get();//->where('prn','=','1212004603')->get();
    //MSc Organic patterclass=65 k
    //$data= ExamStudentseatno::where('exam_patternclasses_id','56')->get();//->where('prn','=','1212004603')->get();
    //MSc PHY patterclass=67 k
    //$data= ExamStudentseatno::where('exam_patternclasses_id','57')->get();//->where('prn','=','1212004603')->get();
    //Mcom cost patterclass=69
    //$data= ExamStudentseatno::where('exam_patternclasses_id','58')->get();//->where('prn','=','1212004603')->get();
    //Mcom  A/c patterclass=71
    //$data= ExamStudentseatno::where('exam_patternclasses_id','59')->get();//->where('prn','=','1212004603')->get();
    //Mcom Admin patterclass=73
    $data = ExamStudentseatno::where('exam_patternclasses_id', '60')->get(); //->where('prn','=','1212004603')->get();

    //dd($data);
    // share data to viewquit
    $currentexam = Exam::Where('status', '1')->get();
    // dd($currentexam->first()->id);
    $stud_marks = new StudentmarkController;
    $stud_marks->collectintextmarks($data, $currentexam->first()->id);
    //$stud_marks = new StudentmarkController;
    $stud_marks->applyordinanceonesem2($data, $currentexam->first()->id);

    view()->share('result.ledgerpgsem2', $data);

    $pdf = PDF::loadView('result.ledgerpgsem2', compact('data'), compact('currentexam'))->setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'sans-serif']);;

    $fname = $data->first()->exampatternclasses->patternclass->pattern->pattern_name;

    $coursename = $data->first()->exampatternclasses->patternclass->coursepatternclasses->class_name;


    $filename = $coursename . '_' . $fname . '_' . $currentexam->first()->exam_name . '_ledger' . '.pdf';
    //$filename="sss.pdf";
    //return $pdf->stream($filename);
    return $pdf->download($filename);
  }
  //sem 2 result PG
  public function generateresultpgsem2()
  {
    ini_set('max_execution_time', 3000);
    ini_set('memory_limit', '2048M');
    //MA sanskrit patterclass=49
    //$data= ExamStudentseatno::where('exam_patternclasses_id','48')->get();//->where('prn','=','1212004603')->get();

    //MA POLY patterclass=47
    //$data= ExamStudentseatno::where('exam_patternclasses_id','47')->get();//->where('prn','=','1212004603')->get();

    //MA MARATHI patterclass=
    //$data= ExamStudentseatno::where('exam_patternclasses_id','46')->get();//->where('prn','=','1212004603')->get();

    //MA HINDI patterclass=43
    //$data= ExamStudentseatno::where('exam_patternclasses_id','45')->get();
    //MA English=41
    //$data= ExamStudentseatno::where('exam_patternclasses_id','44')->get();
    //MA economics=39
    //$data= ExamStudentseatno::where('exam_patternclasses_id','43')->get();
    //MCS patterclass=53
    // $data= ExamStudentseatno::where('exam_patternclasses_id','50')->get();//->where('prn','=','1212004603')->get();
    //MSc elec patterclass=59
    //$data= ExamStudentseatno::where('exam_patternclasses_id','53')->get();//->where('prn','=','1212004603')->get();
    //Msc ZOO patterclass=51
    // $data= ExamStudentseatno::where('exam_patternclasses_id','49')->get();//->where('prn','=','1212004603')->get();
    //MSc botany patterclass=55
    //  $data= ExamStudentseatno::where('exam_patternclasses_id','51')->get();//->where('prn','=','1212004603')->get();
    //Msc Analytical patterclass=57
    //$data= ExamStudentseatno::where('exam_patternclasses_id','52')->get();//->where('prn','=','1212004603')->get();
    //MSc Geography patterclass=61
    //$data= ExamStudentseatno::where('exam_patternclasses_id','54')->get();//->where('prn','=','1212004603')->get();
    //MSc Math patterclass=63
    //$data= ExamStudentseatno::where('exam_patternclasses_id','55')->get();//->where('prn','=','1212004603')->get();
    //MSc Organic patterclass=65 k
    //$data= ExamStudentseatno::where('exam_patternclasses_id','56')->get();//->where('prn','=','1212004603')->get();
    //MSc PHY patterclass=67 k
    //$data= ExamStudentseatno::where('exam_patternclasses_id','57')->get();//->where('prn','=','1212004603')->get();
    //Mcom cost patterclass=69
    //$data= ExamStudentseatno::where('exam_patternclasses_id','58')->get();//->where('prn','=','1212004603')->get();
    //Mcom  A/c patterclass=71
    //$data= ExamStudentseatno::where('exam_patternclasses_id','59')->get();//->where('prn','=','1212004603')->get();
    //Mcom Admin patterclass=73
    $data = ExamStudentseatno::where('exam_patternclasses_id', '60')->get(); //->where('prn','=','1212004603')->get();

    $currentexam = Exam::Where('status', '1')->get();
    $stud_marks = new StudentmarkController;
    $stud_marks->collectintextmarks($data, $currentexam->first()->id);
    $stud_marks->applyordinanceonesem2($data, $currentexam->first()->id);

    view()->share('result.resultpgsem2', $data);
    $fname = $data->first()->exampatternclasses->patternclass->pattern->pattern_name;
    $coursename = $data->first()->exampatternclasses->patternclass->coursepatternclasses->class_name;


    $filename = $coursename . '_' . $fname . '_' . $currentexam->first()->exam_name . '_Result' . '.pdf';
    $pdf = PDF::loadView('result.resultpgsem2', compact('data'), compact('currentexam'))->setOptions(['defaultFont' => 'sans-serif']);
    //$filename=->$data->exampatternclasses->patternclass->coursepatternclasses->class_name;
    //return $pdf->stream($filename);
    return $pdf->download($filename);
  }
  //PG Logic
  public function getAllpgsubjects()
  {
    ini_set('max_execution_time', 1000);
    ini_set('memory_limit', '4048M');

    $data = ExamStudentseatno::where('exam_patternclasses_id', '13')->get(); //M.COM (Cost)
    //$data = ExamStudentseatno::where('exam_patternclasses_id', '14')->get(); //M.COM (A/C)
    //$data = ExamStudentseatno::where('exam_patternclasses_id', '15')->get(); //M.COM (Administration )
    //$data = ExamStudentseatno::where('exam_patternclasses_id', '16')->get(); //M.A ( Eco)
    //$data = ExamStudentseatno::where('exam_patternclasses_id', '17')->get(); //M.A ( Eng)
    //$data = ExamStudentseatno::where('exam_patternclasses_id', '18')->get(); //M.A ( Hindi)
    //$data = ExamStudentseatno::where('exam_patternclasses_id', '19')->get(); //M.A ( Marathi)
    // $data = ExamStudentseatno::where('exam_patternclasses_id', '20')->get(); //M.A ( Poly)
    // $data = ExamStudentseatno::where('exam_patternclasses_id', '21')->get(); //M.A ( Sanskrit)
    // $data = ExamStudentseatno::where('exam_patternclasses_id', '22')->get(); //M.Sc (zoo )
    //$data = ExamStudentseatno::where('exam_patternclasses_id', '23')->get(); //M.Sc. (CS)
    // $data = ExamStudentseatno::where('exam_patternclasses_id', '24')->get(); //M.Sc. (BOTANY)
    //$data = ExamStudentseatno::where('exam_patternclasses_id', '25')->get(); //M.Sc. (Analytical)
    //$data = ExamStudentseatno::where('exam_patternclasses_id', '26')->get(); //M.Sc. (Electronics)
    //$data = ExamStudentseatno::where('exam_patternclasses_id', '27')->get(); //M.Sc. (Geography)
    //$data = ExamStudentseatno::where('exam_patternclasses_id', '28')->get(); //M.Sc. (Math)
    //$data = ExamStudentseatno::where('exam_patternclasses_id', '29')->get(); //M.Sc. (Organic)
    //   $data = ExamStudentseatno::where('exam_patternclasses_id', '30')->get(); //M.Sc. (Physics)

    //$data= ExamStudentseatno::where('exam_patternclasses_id','10')->get();//BVoc DPP
    $data = ExamStudentseatno::where('exam_patternclasses_id', '3')->where('prn', '=', '1072003528')->get();
    // share data to view
    $stud_marks = new StudentmarkController;
    $stud_marks->applypgordinanceone($data);
    $currentexam = Exam::Where('status', '1')->get();
    view()->share('result.ledgerpg', $data);

    $pdf = PDF::loadView('result.ledgerpg', compact('data'), compact('currentexam'))->setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'sans-serif']);;

    $fname = $data->first()->exampatternclasses->patternclass->pattern->pattern_name;
    $filename = $data->first()->exampatternclasses->patternclass->coursepatternclasses->class_name . '_' . $fname . 'ledger' . '.pdf';
    //$filename="sss.pdf";
    //$pdf->save("pdf/$filename");
    //return $pdf->download($filename);
    // Storage::put('public/pdf/invoice.pdf', $pdf->output());
    return $pdf->stream('result.pdf');
  }
  public function generatepgresult()
  {
    ini_set('max_execution_time', 3000);
    ini_set('memory_limit', '2048M');
    //$data = ExamStudentseatno::where('exam_patternclasses_id', '13')->get(); //M.COM (Cost)
    //$data = ExamStudentseatno::where('exam_patternclasses_id', '14')->get(); //M.COM (A/C)
    //$data = ExamStudentseatno::where('exam_patternclasses_id', '15')->get(); //M.COM (Administration )
    //$data = ExamStudentseatno::where('exam_patternclasses_id', '16')->get(); //M.A ( Eco)
    //$data = ExamStudentseatno::where('exam_patternclasses_id', '17')->get(); //M.A ( Eng)
    //$data = ExamStudentseatno::where('exam_patternclasses_id', '18')->get(); //M.A ( Hindi)
    //$data = ExamStudentseatno::where('exam_patternclasses_id', '19')->get(); //M.A ( Marathi)
    // $data = ExamStudentseatno::where('exam_patternclasses_id', '20')->get(); //M.A ( Poly)
    $data = ExamStudentseatno::where('exam_patternclasses_id', '21')->get(); //M.A ( Sanskrit)
    //  $data = ExamStudentseatno::where('exam_patternclasses_id', '22')->get(); //M.Sc (zoo )
    //  $data = ExamStudentseatno::where('exam_patternclasses_id', '23')->get(); //M.Sc. (CS)
    //$data = ExamStudentseatno::where('exam_patternclasses_id', '24')->get(); //M.Sc. (BOTANY)
    //$data = ExamStudentseatno::where('exam_patternclasses_id', '25')->get(); //M.Sc. (Analytical)
    // $data = ExamStudentseatno::where('exam_patternclasses_id', '26')->get(); //M.Sc. (Electronics)
    //$data = ExamStudentseatno::where('exam_patternclasses_id', '27')->get(); //M.Sc. (Geography)
    //$data = ExamStudentseatno::where('exam_patternclasses_id', '28')->get(); //M.Sc. (Math)
    //$data = ExamStudentseatno::where('exam_patternclasses_id', '29')->get(); //M.Sc. (Organic)
    // $data = ExamStudentseatno::where('exam_patternclasses_id', '30')->get(); //M.Sc. (Physics)

    $stud_marks = new StudentmarkController;

    $stud_marks->applypgordinanceone($data);
    $currentexam = Exam::Where('status', '1')->get();
    view()->share('result.resultpg', $data);
    $fname = $data->first()->exampatternclasses->patternclass->pattern->pattern_name;
    $filename = $data->first()->exampatternclasses->patternclass->coursepatternclasses->class_name . '_' . $fname . '_Result' . '.pdf';
    $pdf = PDF::loadView('result.resultpg', compact('data'), compact('currentexam'))->setOptions(['defaultFont' => 'sans-serif']);
    //$filename=->$data->exampatternclasses->patternclass->coursepatternclasses->class_name;
    //return $pdf->stream('result.pdf');
    return $pdf->download($filename);
  }

  public function getAllcourses()
  {

    $courseclasss = CourseClass::all();
    return view('courseclass.show', compact('courseclasss'));
    // $courseclasss = CourseClass::all()->paginate(15);
    // return view('courseclass.index', compact('courseclasss'));
  }





  public function testPDF()
  {

    return view('test');
    view()->share('test');

    $pdf = PDF::loadView('test')->setPaper('a4', 'landscape');

    return $pdf->stream('test.pdf');
  }
  // Generate PDF
  public function createPDF()
  {
    // retreive all records from db
    //  $courseclasss= CourseClass::all()->paginate(15);
    $courseclasss = CourseClass::all();
    // share data to view

    $pdf = PDF::loadView('courseclass.show', compact('courseclasss'))->setOptions(['defaultFont' => 'sans-serif']);;

    // download PDF file with download method
    return $pdf->download('pdf_file.pdf');
  }
  //ledger view
  public function lsession1()
  {
    $exam = Exam::where('exam_sessions', '2');
    return view("result.lsession1", compact('exam'));
  }
  public function lsession2()
  {
  }
  //result view
  public function rsession1()
  {
  }
  public function rsession2()
  {
  }
  //convocation excel list unipune 27 column
  //convocation_excel_listunipune
  function convocation_excel_listunipune($id)
  {
    ini_set('max_execution_time', 5000);
    ini_set('memory_limit', '4048M');

    $exampatternclass = ExamPatternclass::find($id);
    $course_type = $exampatternclass->patternclass->getclass->course->course_type;
    if ($course_type == "PG") {
      $studresult = Studentresult::where('exam_patternclasses_id', $id)
        ->where('sem', '4')->pluck('student_id')
        //->get()
      ;
    } else if ($course_type == "UG") {
      $studresult = Studentresult::where('exam_patternclasses_id', $id)
        ->where('sem', '6')->pluck('student_id')
        //->get()
      ;
    }
    //dd($studresult);
    $data = ExamStudentseatno::where('exam_patternclasses_id', $id) //->whereIn('seatno',range(3061,3100))
      // ->whereIn('prn',["1012103922", "1012104370"])
      // ->where('prn','1272004713')
      ->whereIn('student_id', $studresult)
      ->whereNotIn('prn', $this->admissioncanceldata)
      // ->where('printstatus','0')
      // ->take(100)
      ->get();
    //dd($data);
    $exam_id = $exampatternclass->exam_id;
    $patternclass_id = $exampatternclass->patternclass_id;
    // dd($patternclass_id);


    if ($data->count() > 0)
      $data->toQuery()->update(array("printstatus" => "1"));
    //$currentexam = $exampatternclass->exam;//Exam::Where('status', '1')->get();
    $currentexam = $exampatternclass->exam; //Exam::where('id',8)->where('status', '0')->first();
    //dd( $currentexam);
    $stud_marks = new StudentmarkController;

    if ($data->count() > 0) {
      $fname = $exampatternclass->patternclass->pattern->pattern_name;

      $coursename = $exampatternclass->patternclass->coursepatternclasses->class_name;

      if (Str::contains($coursename, 'B.Voc')) {
        if ($currentexam->exam_sessions == 2) {
          $coursename = str_replace('Certificate', 'Diploma', $coursename);
        }
      }
      $filename = $coursename . '_' . $fname . '_' . $currentexam->exam_name . '_convovationlist' . '.xlsx';
    } else $filename = 'emptyfile.xlsx';
    ob_end_clean();
    if ($course_type == "PG")
      // view()->share('result.convocationPGJuly2022', $data);//for PG new format used in march 2022(July 2022) sem -II exam rule 
      return Excel::download(new ConvocationlistunipuneExport($data, $currentexam, $course_type, $exampatternclass), $filename);
    //rule =>passing on total marks circular
    if ($course_type == "UG")
      return Excel::download(new ConvocationlistunipuneExport($data, $currentexam, $course_type, $exampatternclass), $filename);

    //view()->share('result.convocationPGJuly2022', $data);//for UG new format used in march 2022(July 2022) sem -II exam rule 
    //rule =>passing on total marks circular
    // if ($course_type == "PG")
    //   $pdf = PDF::loadView('result.convocationUniversityPGJuly2022', compact('data'), compact('currentexam', 'course_type', 'exampatternclass'))->setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'sans-serif']);;
    // if ($course_type == "UG")
    //   $pdf = PDF::loadView('result.convocationUGJuly2022', compact('data'), compact('currentexam', 'course_type', 'exampatternclass'))->setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'sans-serif']);;

    // $canvas = $pdf->getDomPDF()->getCanvas();
    //$height = $canvas->get_height();
    //$width = $canvas->get_width();
    //$canvas->set_opacity(.2,"Multiply");
    // $canvas->page_text(10, 10, "Report Page: {PAGE_NUM} of {PAGE_COUNT}", null, 12, array(0, 0, 0));

    // if ($data->count() > 0) {
    //   $fname = $exampatternclass->patternclass->pattern->pattern_name;

    //   $coursename = $exampatternclass->patternclass->coursepatternclasses->class_name;

    //   if (Str::contains($coursename, 'B.Voc')) {
    //     if ($currentexam->exam_sessions == 2) {
    //       $coursename = str_replace('Certificate', 'Diploma', $coursename);
    //     }
    //   }

    //   $filename = $coursename . '_' . $fname . '_' . $currentexam->exam_name . '_convovationlist' . '.pdf';
    // } else $filename = "Emptyconvocation.pdf";

    // //return view('result.resultPGJuly2022', compact('data'), compact('currentexam','course_type','exampatternclass'));
    // //return $pdf->stream($filename);
    // return $pdf->download($filename);
  }
  //convocation excel list
  function convocation_excel_list($id)
  {
    ini_set('max_execution_time', 5000);
    ini_set('memory_limit', '4048M');

    $exampatternclass = ExamPatternclass::find($id);
    $course_type = $exampatternclass->patternclass->getclass->course->course_type;
    if ($course_type == "PG") {
      $studresult = Studentresult::where('exam_patternclasses_id', $id)
        ->where('sem', '4')->pluck('student_id')
        //->get()
      ;
    } else if ($course_type == "UG") {
      $studresult = Studentresult::where('exam_patternclasses_id', $id)
        ->where('sem', '6')->pluck('student_id')
        //->get()
      ;
    }
    //dd($studresult);
    $data = ExamStudentseatno::where('exam_patternclasses_id', $id) //->whereIn('seatno',range(3061,3100))
      // ->whereIn('prn',["1012103922", "1012104370"])
      // ->where('prn','1272004713')
      ->whereIn('student_id', $studresult)
      ->whereNotIn('prn', $this->admissioncanceldata)
      // ->where('printstatus','0')
      // ->take(100)
      ->get();
    //dd($data);
    $exam_id = $exampatternclass->exam_id;
    $patternclass_id = $exampatternclass->patternclass_id;
    // dd($patternclass_id);


    if ($data->count() > 0)
      $data->toQuery()->update(array("printstatus" => "1"));
    //$currentexam = $exampatternclass->exam;//Exam::Where('status', '1')->get();
    $currentexam = $exampatternclass->exam; //Exam::where('id',8)->where('status', '0')->first();
    //dd( $currentexam);
    $stud_marks = new StudentmarkController;

    if ($data->count() > 0) {
      $fname = $exampatternclass->patternclass->pattern->pattern_name;

      $coursename = $exampatternclass->patternclass->coursepatternclasses->class_name;

      if (Str::contains($coursename, 'B.Voc')) {
        if ($currentexam->exam_sessions == 2) {
          $coursename = str_replace('Certificate', 'Diploma', $coursename);
        }
      }

      $filename = $coursename . '_' . $fname . '_' . $currentexam->exam_name . '_convovationlist' . '.xlsx';
    } else $filename = 'emptyfile.xlsx';
    ob_end_clean();
    if ($course_type == "PG")
      // view()->share('result.convocationPGJuly2022', $data);//for PG new format used in march 2022(July 2022) sem -II exam rule 
      return Excel::download(new ConvocationlistExport($data, $currentexam, $course_type, $exampatternclass), $filename);
    //rule =>passing on total marks circular
    if ($course_type == "UG")
      return Excel::download(new ConvocationlistExport($data, $currentexam, $course_type, $exampatternclass), $filename);

    //view()->share('result.convocationPGJuly2022', $data);//for UG new format used in march 2022(July 2022) sem -II exam rule 
    //rule =>passing on total marks circular
    // if ($course_type == "PG")
    //   $pdf = PDF::loadView('result.convocationUniversityPGJuly2022', compact('data'), compact('currentexam', 'course_type', 'exampatternclass'))->setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'sans-serif']);;
    // if ($course_type == "UG")
    //   $pdf = PDF::loadView('result.convocationUGJuly2022', compact('data'), compact('currentexam', 'course_type', 'exampatternclass'))->setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'sans-serif']);;

    // $canvas = $pdf->getDomPDF()->getCanvas();
    //$height = $canvas->get_height();
    //$width = $canvas->get_width();
    //$canvas->set_opacity(.2,"Multiply");
    // $canvas->page_text(10, 10, "Report Page: {PAGE_NUM} of {PAGE_COUNT}", null, 12, array(0, 0, 0));

    // if ($data->count() > 0) {
    //   $fname = $exampatternclass->patternclass->pattern->pattern_name;

    //   $coursename = $exampatternclass->patternclass->coursepatternclasses->class_name;

    //   if (Str::contains($coursename, 'B.Voc')) {
    //     if ($currentexam->exam_sessions == 2) {
    //       $coursename = str_replace('Certificate', 'Diploma', $coursename);
    //     }
    //   }

    //   $filename = $coursename . '_' . $fname . '_' . $currentexam->exam_name . '_convovationlist' . '.pdf';
    // } else $filename = "Emptyconvocation.pdf";

    // //return view('result.resultPGJuly2022', compact('data'), compact('currentexam','course_type','exampatternclass'));
    // //return $pdf->stream($filename);
    // return $pdf->download($filename);
  }

//   function  downloadexcelabcidresult_excel_list($id, $sem)
//   {
//     $exampatternclass = ExamPatternclass::find($id);
//     $exam_id = $exampatternclass->exam_id;

//     $ExamStudentseatnos = ExamStudentseatno::where('exam_patternclasses_id', $id) //->whereIn('seatno',range(3061,3100))
//       ->get(); //

//     // Specify the path to the CSV file
//     $csvFilePath = 'file.csv';
//     // Open the file for writing
//     $csvFile = fopen($csvFilePath, 'w');
//     // Write the header row
//     fputcsv($csvFile, ['Subject ID', 'Student ID', 'Marks']);
//     foreach ($ExamStudentseatnos as $ExamStudentseatno) {

//       $currentExamMarks = $ExamStudentseatno->student
//         ->studentmarks
//         ->where('exam_id', $exam_id)
//         ->where('sem',  $sem)
//         ->whereNotIn('subject_type', ['G', 'IG', 'IEG']);

//       // Retrieve marks for the previous exam
//       $previousExamMarks = $ExamStudentseatno->student
//         ->studentmarks

//         ->where('exam_id', '<', $exam_id)
//         ->where('sem',  $sem)
//         ->whereNotIn('subject_type', ['G', 'IG', 'IEG']);
//       $mergedMarks = $currentExamMarks->concat($previousExamMarks);

//         // dd($mergedMarks->groupBy('subject_id'));
//         foreach ($mergedMarks->groupBy('subject_id') as $studentMarks) {
//           foreach ($studentMarks as $studentMark) {
//             $studentID = $studentMark->student_id;
            
//             $marks = $studentMark->total;
//             $subject_id= $studentMark->subject_id;
//             fputcsv($csvFile, [$subject_id, $studentID, $marks]);
//           }
     




//         }
//          // Close the file
//       fclose($csvFile);
//       dd("created");
   


  
//     // dd($data->first()->student->studentmarks->where('exam_id',  $exam_id)->where('sem',  $sem));
//   }
// }
function allexamsemwiseabcid($examid=9,$sem=1)
{
  $allexampatternclasses=ExamPatternclass::where('exam_id',$examid)
  ->pluck('id');
  foreach($allexampatternclasses as $id)
  {
    $this->downloadexcelabcidresult_excel_list($id, $sem);
  }
}
function downloadexcelabcidresult_excel_list($id, $sem)
{  
    $exampatternclass = ExamPatternclass::find($id);
  
    $course_type = $exampatternclass->patternclass->getclass->course->course_type;
    $exam_id = $exampatternclass->exam_id;

    $currentexam=$exampatternclass->exam;

    $year=(explode(' ',  $currentexam->exam_name)[1]);
    $month=$currentexam->month;
    $patternclass_id= $exampatternclass->patternclass_id;

    

    $coursename=strtoupper($exampatternclass->patternclass->coursepatternclasses->class_name) ." ".strtoupper($exampatternclass->patternclass->pattern->pattern_name);
    $coursefullname=strtoupper($exampatternclass->patternclass->coursepatternclasses->course->fullname);
    $studdata = Studentmark::where('exam_id', $exam_id)
      ->where('patternclass_id', $patternclass_id)
     // ->whereIn ('seatno',['3403'])
      ->pluck('student_id', 'seatno')->unique();
      
      $ExamStudentseatnos = ExamStudentseatno::with('exampatternclasses')
      ->whereHas('exampatternclasses', function ($query) use ($exam_id) {
          $query->where('exam_id', $exam_id);
      })
      ->whereIn('student_id', $studdata)
     // ->where('seatno', '3403') // or whereIn('seatno', ['3403']) if multiple seat numbers are expected
      ->get();
//dd( $ExamStudentseatnos->where('seatno', '3403'));
    // Specify the path to the CSV file
    $csvFilePath =  str_replace('.','_',str_replace(' ', '',strtoupper($exampatternclass->patternclass->coursepatternclasses->class_name).'SEM_'.$sem.'_'.$month.'_'.$year)).'.csv';
    ob_clean();
    // Open the file for writing
    $csvFile = fopen($csvFilePath, 'w');
    $rowcount=1;
    //dd($ExamStudentseatnos);
    foreach ($ExamStudentseatnos as $ExamStudentseatno) {
      // dd(Studentresult::where('sem',$sem)->where('student_id',$ExamStudentseatno->student_id));
     //  dd($ExamStudentseatno->exam_patternclasses_id);
      // dd(Studentresult::where('student',))

        $currentExamMarks = $ExamStudentseatno->student
            ->studentmarks
            ->where('exam_id', $exam_id)
            ->where('sem', $sem)
            ->whereNotIn('subject.subject_type', ['G', 'IG', 'IEG']);
           
        // Retrieve marks for the previous exam
        $previousExamMarks = $ExamStudentseatno->student
            ->studentmarks
            ->where('exam_id', '<', $exam_id)
            ->where('sem', $sem)
            ->whereNotIn('subject.subject_type', ['G', 'IG', 'IEG']);
        $mergedMarks = $currentExamMarks->concat($previousExamMarks);
       
        $noncgpaExamMarks = $ExamStudentseatno->student
            ->studentmarks
            ->where('exam_id',$exam_id)
            ->where('sem', $sem)
            ->whereNotIn('subject.subject_type', ['IE', 'IP', 'IEP','E'])
            ;
              // dd( $noncgpaExamMarks); 
        $isFinalYear=0; 
        $finalresult=null;
       // dd($course_type.$sem);
        if (($course_type == "PG" && $sem==4) || ($course_type == "UG" && $sem==6))
         {   
         $isFinalYear=1;
         $finalresult=$this->checkpassfail($ExamStudentseatno->student,$id,$sem);
          // if (($course_type == "PG" &&  $finalresult1['sem']==4) || ($course_type == "UG" &&  $finalresult1['sem']==6))
          //       {$finalresult= $finalresult1; }
        }
       
        foreach ($mergedMarks->sortBy('subject.oral')->sortBy('subject.subject_order')->sortBy('subject_id')->groupBy(['student_id','subject_id']) as $student_id => $studentMarks) {
          $studentresult=$studentMarks->first()->first()->student
          ->studentresults
           ->where('sem', $sem)
           ->where('exam_patternclasses_id', $ExamStudentseatno->exam_patternclasses_id)
           ->first()
          ;
        // dd($studentresult);
          if($studentresult)
          {
              $row['ORG_NAME'] = "SANGAMNER NAGARPALIKA ARTS, D. J. MALPANI COMMERCE AND B. N. SARDA SCIENCE COLLEGE (AUTONOMOUS), SANGAMNER - 422 605, DIST. AHMEDNAGAR, (M.S.)";
              $row['ORG_NAME_L'] = "";//"संगमनेर नगरपालिका कला, दा. ज. मालपाणी वाणिज्य आणि ब. ना. सारडा विज्ञान महाविद्यालय (स्वायत्त), संगमनेर - ४२२ ६०५, जि. अहमदनगर, (महाराष्ट्र)";
              $row['ACADEMIC_COURSE_ID'] = $patternclass_id;
              $row['COURSE_NAME'] = $coursename;
              $row['COURSE_NAME_L'] = $coursefullname;
              $row['STREAM'] = '';//Discipline / Stream in English
              $row['STREAM_L'] = "";//Discipline / Stream in Regional Language
              $row['SESSION'] = "";//Period of Course (Academic Year to be mentioned)
              $row['REGN_NO'] = $studentMarks->first()->first()->student->prn;
              $row['RROLL'] = $ExamStudentseatno->seatno;//Identification Code of Student in Exams. Example - Roll No. or Admit Card No. etc.
              $row['CNAME'] = Str::upper( $studentMarks->first()->first()->student->student_name);//Student's Name
              $row['GENDER'] = Str::upper($studentMarks->first()->first()->student->studentprofile->gender??'');
              $row['DOB'] = $studentMarks->first()->first()->student->aadhaar_dob;
              $row['FNAME'] =  Str::upper($studentMarks->first()->first()->student->studentprofile->father_name??'');//Student's Father Name
              $row['MNAME'] = Str::upper($studentMarks->first()->first()->student->mother_name);//Student's Mother Name
              $row['PHOTO'] = "";//Image file naming convention - (registration/roll/enrollment no_academic year).jpg/jpeg/png. Year wise photographs should be uploaded in NAD
              $row['MRKS_REC_STATUS'] = $previousExamMarks->isEmpty()?'O':'M';//O-Original, M-Modification, C-Cancellation
              $row['RESULT'] =  $isFinalYear==1?($finalresult['percent']?'PASS':'FAIL'):'';//Description of result to be printed on marksheet. For example - PASS, FAIL, QUALIFIED, COMPULSORY REPEAT, ABSENT
              $row['YEAR'] = $year;//Year to be entered in YYYY format in which year the exam was conducted
              $row['MONTH'] = $month;//Month Name to be entered in the format – January, April, October etc
              $row['DIVISION'] = "";//Description of division to be printed on the certificate, in English. For example - FIRST, SECOND etc.
              $row['GRADE'] = $isFinalYear==1?($finalresult['grade']??''):'';//Grade Obtained
              $row['PERCENT'] =$isFinalYear==1?( $finalresult['percent']??''):'';
              $row['SEM'] =$this->convertToRomanNumeral($sem); //I for first semester and II for second semester. It should be in roman letters. (Preferred that this field is used)
              $row['EXAM_TYPE'] = "";//Type of exam as need to be printed on certificate.For example - Regular, Part Time, Correspondence etc
              $row['TOT'] = $studentresult?$studentresult->totalOutofMarks : '';  //TOTAL MAXIMUM MARKS FOR A SEMESTER/YEAR
              $row['TOT_MRKS'] = $studentresult?$studentresult->totalMarks:""; //TOTAL MARKS OBTAINED FOR A SEMESTER/YEAR
              $row['TOT_CREDIT'] = $studentresult?$studentresult->semcreditearned : '';//TOTAL OF CREDIT FOR ONE SEMESTER/YEAR
              $row['TOT_CREDIT_POINTS'] = $studentresult?$studentresult->semtotalcreditpoints : '';//TOTAL OF CREDIT POINTS FOR ONE SEMESTER/YEAR
              $row['TOT_GRADE_POINTS'] = "";//TOTAL GRADE POINTS FOR ONE SEMESTER/YEAR
              $row['GRAND_TOT_MAX'] =  $isFinalYear==1?( $finalresult['grandtotalmax']??''):'';//GRAND TOTAL MAXIMUM MARKS
              $row['GRAND_TOT_CREDIT_POINTS'] = "";//GRAND TOTAL GRADE POINTS
              $row['CGPA'] = $isFinalYear==1?( $finalresult['cgpa']??''):'';
              $row['REMARKS'] = "";//For Further information/clarification.Can be used for added data display like student performance
              $row['SGPA'] = $studentresult?round($studentresult->sgpa,2) : '';
              $row['ABC_ACCOUNT_ID'] =$studentMarks->first()->first()->student->abcid;//12-digit ABC ID without spaces or hyphen
              $row['TERM_TYPE'] = "SEMESTER";//Exam Frequency.Trimester, Semester, Annual, Summer, Winter etc
              $row['TOT_GRADE'] = "";//TOTAL GRADE FOR ONE SEMESTER/YEAR
      
              $row['SUB_COUNTER'] = 1; // Initialize the subject counter
             foreach ( $studentMarks as $subjectMarks) {
                $mark = $subjectMarks->sortByDesc('exam_id')->first(); 
                $row['SUB' . $row['SUB_COUNTER'] . 'NM'] = Str::upper( $mark ->subject->subject_name); //SUBJECT 1 NAME               
                $row['SUB' . $row['SUB_COUNTER']] = Str::upper( $mark ->subject->subject_code);//SUBJECT 1 CODE
                $row['SUB' . $row['SUB_COUNTER'] . '_TH_MAX'] =$mark->subject->subject_type == 'IE'? $mark ->subject->subject_maxmarks_ext:'';//SUBJECT 1 THEORY MAX
                $row['SUB' . $row['SUB_COUNTER'] . '_PR_MAX'] =  $mark ->subject->subject_type == 'IP'?$mark ->subject->subject_maxmarks_ext:($mark ->subject->subject_type == 'IEP'?$mark ->subject->subject_maxmarks_intpract:'');//SUBJECT 1 PRACTICAL MAX
                $row['SUB' . $row['SUB_COUNTER'] . '_CE_MAX'] = $mark ->subject->subject_maxmarks_int;//SUBJECT 1 INTERNAL MAX
                $row['SUB' . $row['SUB_COUNTER'] . '_TH_MRKS'] = $mark->subject->subject_type == 'IE'? ($mark ->ext_marks=='-1'?'AB':$mark ->ext_marks):'';//SUBJECT 1 THEORY MARKS OBTAINED
                $row['SUB' . $row['SUB_COUNTER'] . '_PR_MRKS'] =  $mark ->subject->subject_type == 'IP'?
                                                                   ( $mark ->ext_marks=='-1'?'AB':$mark ->ext_marks) 
                                                                     : ($mark ->subject->subject_type == 'IEP'?($mark ->int_practical_marks=='-1'?'AB':$mark ->int_practical_marks):'');//SUBJECT 1 PRACTICAL MARKS OBTAINED
                $row['SUB' . $row['SUB_COUNTER'] . '_CE_MRKS'] = $mark ->int_marks=='-1'?'AB':$mark ->int_marks;//SUBJECT1 INTERNAL MARKS OBTAINED
                $row['SUB' . $row['SUB_COUNTER'] . '_TOT'] = $mark ->total=='-1'?'AB':$mark ->total;//SUBJECT 1 TOTAL OBTAINED
                $row['SUB' . $row['SUB_COUNTER'] . '_STATUS'] = ($mark->grade == '-1' || $mark->grade == 'Ab' || $mark->grade == 'F' || $mark->grade == '' || is_null($mark->grade)) ? 'FAIL' : 'PASS';//Status (Pass or Fail)
                $row['SUB' . $row['SUB_COUNTER'] . '_GRADE'] = $mark ->grade;//SUBJECT1 GRADE
                $row['SUB' . $row['SUB_COUNTER'] . '_GRADE_POINTS'] = $mark->grade_point;//SUBJECT1 GRADE POINTS
                $row['SUB' . $row['SUB_COUNTER'] . '_CREDIT'] = ($mark->grade == '-1' || $mark->grade == 'Ab' || $mark->grade == 'F' || $mark->grade == '' || is_null($mark->grade)) ? '0' : $mark->subject->subject_credit;
                $row['SUB' . $row['SUB_COUNTER'] . '_CREDIT_POINTS'] =  $mark->grade_point * $mark->subject->subject_credit;
                $row['SUB' . $row['SUB_COUNTER'] . '_REMARKS'] = "";//Extra Remarks to be added
                $row['SUB' . $row['SUB_COUNTER'] . '_CREDIT_ELIGIBILITY'] = "";//Whether subject credits are to be considered. N for don’t consider
                $row['SUB_COUNTER']++;
            }
            foreach ( $noncgpaExamMarks as $mark) {
              
              
              $row['SUB' . $row['SUB_COUNTER'] . 'NM'] = Str::upper( $mark ->subject->subject_name); //SUBJECT 1 NAME               
              $row['SUB' . $row['SUB_COUNTER']] = Str::upper( $mark ->subject->subject_code);//SUBJECT 1 CODE
              $row['SUB' . $row['SUB_COUNTER'] . '_TH_MAX'] ='';//SUBJECT 1 THEORY MAX
              $row['SUB' . $row['SUB_COUNTER'] . '_PR_MAX'] =  '';//SUBJECT 1 PRACTICAL MAX
              $row['SUB' . $row['SUB_COUNTER'] . '_CE_MAX'] = '';//SUBJECT 1 INTERNAL MAX
              $row['SUB' . $row['SUB_COUNTER'] . '_TH_MRKS'] ='';//SUBJECT 1 THEORY MARKS OBTAINED
              $row['SUB' . $row['SUB_COUNTER'] . '_PR_MRKS'] =  '';//SUBJECT 1 PRACTICAL MARKS OBTAINED
              $row['SUB' . $row['SUB_COUNTER'] . '_CE_MRKS'] = '';//SUBJECT1 INTERNAL MARKS OBTAINED
              $row['SUB' . $row['SUB_COUNTER'] . '_TOT'] = '';//SUBJECT 1 TOTAL OBTAINED
              $row['SUB' . $row['SUB_COUNTER'] . '_STATUS'] = ($mark->grade == '-1' || $mark->grade == 'Ab' || $mark->grade == 'F' || $mark->grade == '' || is_null($mark->grade)) ? 'FAIL' : 'PASS';//Status (Pass or Fail)
              $row['SUB' . $row['SUB_COUNTER'] . '_GRADE'] = $mark ->grade;//SUBJECT1 GRADE
              $row['SUB' . $row['SUB_COUNTER'] . '_GRADE_POINTS'] = '';//SUBJECT1 GRADE POINTS
              $row['SUB' . $row['SUB_COUNTER'] . '_CREDIT'] = ($mark->grade == '-1' || $mark->grade == 'Ab' || $mark->grade == 'F' || $mark->grade == '' || is_null($mark->grade)) ? '0' : $mark->subject->subject_credit;
              $row['SUB' . $row['SUB_COUNTER'] . '_CREDIT_POINTS'] =  '';
              $row['SUB' . $row['SUB_COUNTER'] . '_REMARKS'] = "! NON CGPA";//Extra Remarks to be added
              $row['SUB' . $row['SUB_COUNTER'] . '_CREDIT_ELIGIBILITY'] = "N";//Whether subject credits are to be considered. N for don’t consider
              $row['SUB_COUNTER']++;
          }
      
         
           // if(!empty($row))
            { 
          
           if($rowcount==1)
           {
            
            $row['AADHAAR_NAME'] ='';//ROW HEADING INITIALIZATION
            $row['ADMISSION_YEAR'] = '';//ROW HEADING INITIALIZATION
            $headerRow=array();
            foreach($row as $key=>$value){
              $headerRow[]=$key;
            }
           
            fputcsv($csvFile, $headerRow);
            $row['AADHAAR_NAME'] = Str::upper( $studentMarks->first()->first()->student->aadhaar_student_name);
            $row['ADMISSION_YEAR'] =  explode('-',$studentMarks->first()->first()->student->studentadmission->first()->academicyear->year_name)[0];
            fputcsv($csvFile, $row);
           }
           else{

            $row['AADHAAR_NAME'] = Str::upper( $studentMarks->first()->first()->student->aadhaar_student_name);
            $row['ADMISSION_YEAR'] =  explode('-',$studentMarks->first()->first()->student->studentadmission->first()->academicyear->year_name)[0];
            fputcsv($csvFile, $row);
           
           }
           $rowcount++;
            
            }
          }
        }
    }    
   
      $stat = fstat($csvFile);
    $newSize = max(0, $stat['size'] - 1); // Ensure new size is at least 0
    ftruncate($csvFile, $newSize);
    fclose($csvFile);
    ob_end_clean();
     
   // Create a ZIP file
   $zipFilePath = str_replace('.csv','',$csvFilePath . '.zip');
   $zip = new ZipArchive();
   if ($zip->open($zipFilePath, ZipArchive::CREATE) === true) {
       $zip->addFile($csvFilePath, basename($csvFilePath));
       $zip->close();
   } else {
       die('Failed to create ZIP file');
   }
   
   
   // Remove the original CSV file
   unlink($csvFilePath);

   // Download the ZIP file
   return response()->download($zipFilePath)->deleteFileAfterSend(true);
}
function convertToRomanNumeral($semester) {
  switch ($semester) {
      case 1:
          return 'I';
      case 2:
          return 'II';
      case 3:
          return 'III';
      case 4:
          return 'IV';
      case 5:
          return 'V';
      case 6:
          return 'VI';
      default:
          return '';
  }
}

public function checkpassfail($student,$id,$sem)
{
  $cgpa=null;
 
  $studresult=$student->studentresults()
  ->where('exam_patternclasses_id','<=',$id)
  ->where('sgpa', '!=', '0')
  ->where('extraCreditsStatus', '1')
  ->select(['student_id',
    DB::raw('count(sgpa) as total'),
    DB::raw('count(sem) as semtotal'),
    DB::raw('sum(semtotalcredit) as semtotalcredit'),
    DB::raw('sum(semtotalcreditpoints) as semtotalcreditpoints'),
    DB::raw('max(sem) as sem'),
    DB::raw('sum(ordinance_163_marks) as ordinance_163'),   
    DB::raw('sum(totalMarks) as grandtotal'),    
    DB::raw('sum(totalOutofMarks) as totalOutofMarks'),   
    
])
 //->groupBy('student_id')
 //->get()
->first();
;
//dd($studresult);
//dd($studresult->sem!=$sem);
    $exampatternclass = ExamPatternclass::find($id);
    
    $course_type = $exampatternclass->patternclass->getclass->course->course_type;
    if($studresult->sem!=$sem)
    { 
  return [
  'grade' => 'F',
  'cgpa' => '0',
  'sem' => $sem,
  'percent' => '0',
  'grandtotal' => null,
  'grandtotalmax' => null,
        ];
    }
$ordinace =  
       
$student
->studentmarks()->whereNotIn('grade', ['F', '-1', 'Ab'])
->select([
  'student_id',
  DB::raw('sum(int_ordinace_flag) as int_ordinace_flag'),
  DB::raw('sum(ext_ordinace_flag) as ext_ordinace_flag'),
  DB::raw('sum(total_ordinace_flag) as total_ordinace_flag'),
  DB::raw('sum(practical_ordinace_flag) as practical_ordinace_flag'),
 
])
->first();

$ordinace4 =$student
->studentmarks()
->where('total_ordinancefour_marks', '!=', '0')
->select([
  'student_id',
   DB::raw('count(total_ordinancefour_marks) as total_ordinancefour_marks'),

  ])
  ->groupby('student_id')
  ->get();

$oridnance_flag1="";
$oridnance_flag_four="";
if(($ordinace->pluck('ext_ordinace_flag','student_id') )
      ||($ordinace->pluck('int_ordinace_flag','student_id'))
      ||($ordinace->pluck('total_ordinace_flag','student_id'))
      ||($ordinace->pluck('practical_ordinace_flag','student_id')))
          $oridnance_flag1=1;
//if(!($ordinace4->pluck('total_ordinancefour_marks','student_id')->isEmpty()))
      if($ordinace4->pluck('total_ordinancefour_marks','student_id')??0)
          $oridnance_flag_four=1;
         //dd($studresult->semtotal);
    if (($course_type == "PG" && $studresult->semtotal==4) || ($course_type == "UG" && $studresult->semtotal==6)) 
{
  $cgpa=round($studresult->semtotalcreditpoints/$studresult->semtotalcredit,2);

         
$finalgrade = "";
$diff = "";

if ($cgpa >= 9.50) {
    $finalgrade = "O";
} else if ($cgpa >= 8.25 && $cgpa < 9.50) {
    $sf = $studresult->semtotalcredit * 9.50;
    $diff = $sf - $studresult->semtotalcreditpoints;
    if ($diff <= 10 && $oridnance_flag1 == "" && $oridnance_flag_four == "") {
        $finalgrade = "O + 0.2";
        $cgpa = 9.50;
    } else {
        $finalgrade = "A+";
    }
} else if ($cgpa >= 6.75 && $cgpa < 8.25) {
    $sf = $studresult->semtotalcredit * 8.25;
    $diff = $sf - $studresult->semtotalcreditpoints;
    if ($diff <= 10 && $oridnance_flag1 == "" && $oridnance_flag_four == "") {
        $finalgrade = "A+ + 0.2";
        $cgpa = 8.25;
    } else {
        $finalgrade = "A";
    }
} else if ($cgpa >= 5.75 && $cgpa < 6.75) {
    $sf = $studresult->semtotalcredit * 6.75;
    $diff = $sf - $studresult->semtotalcreditpoints;
    if ($diff <= 10 && $oridnance_flag1 == "" && $oridnance_flag_four == "") {
        $finalgrade = "A + 0.2";
        $cgpa = 6.75;
    } else {
        $finalgrade = "B+";
    }
} else if ($cgpa >= 5.25 && $cgpa < 5.75) {
    $sf = $studresult->semtotalcredit * 5.75;
    $diff = $sf - $studresult->semtotalcreditpoints;
    if ($diff <= 10 && $oridnance_flag1 == "" && $oridnance_flag_four == "") {
        $finalgrade = "B+ + 0.2";
        $cgpa = 5.75;
    } else {
        $finalgrade = "B";
    }
} else if ($cgpa >= 4.75 && $cgpa < 5.25) {
    $sf = $studresult->semtotalcredit * 5.25;
    $diff = $sf - $studresult->semtotalcreditpoints;
    if ($diff <= 10 && $oridnance_flag1 == "" && $oridnance_flag_four == "") {
        $finalgrade = "B + 0.2";
        $cgpa = 5.25;
    } else {
        $finalgrade = "C";
    }
} else if ($cgpa >= 4. && $cgpa < 4.75) {
    $finalgrade = "D";
}
}
else
{
  return [
    'grade' => '-',
    'cgpa' => '-',
    'sem' => $studresult->sem,
    'percent' => $studresult->totalOutofMarks != 0 ? round(($studresult->grandtotal / $studresult->totalOutofMarks) * 100, 2) : 0,
    'grandtotal' => $studresult->grandtotal,
    'grandtotalmax' => $studresult->grandtotalmax,
  ];
}

return [
  'grade' => $finalgrade,
  'cgpa' => round($cgpa,2),
  'sem' => $studresult->sem,
  'percent' => $studresult->totalOutofMarks != 0 ? round(($studresult->grandtotal / $studresult->totalOutofMarks) * 100, 2) : 0,
  'grandtotal' => $studresult->grandtotal,
  'grandtotalmax' => $studresult->grandtotalmax,
];

}
  function  downloadexcelabcidresult_excel_list_old($id, $sem)
  {
    //$id=231;
    $subject_sem = $sem;
    ini_set('max_execution_time', 5000);
    ini_set('memory_limit', '4048M');

    $exampatternclass = ExamPatternclass::find($id);

    $course_type = $exampatternclass->patternclass->getclass->course->course_type;
    // dd( $course_type);
    $exam_id = $exampatternclass->exam_id;

    $patternclass_id = $exampatternclass->patternclass_id;

    $datacount = ExamStudentseatno::where('exam_patternclasses_id', $id) //->whereIn('seatno',range(3061,3100))

      // ->where('printstatus','1')
      ->count();


    //dd( $datacount);
    $data = ExamStudentseatno::where('exam_patternclasses_id', $id) //->whereIn('seatno',range(3061,3100))
      // ->whereIn('prn',['1012104913','1012104912'])
      //->whereIn('prn',['1212205780','1212205575'])
      //->whereNotIn('prn',$this->admissioncanceldata)
      // ->where('printstatus','0')
      //->take(100)


      ->get(); //


    // foreach($data as $seatnodata)
    //       $seatnodata->update(['token'=>Str::random(80)]) ;

    if ($data->count() > 0)
      $data->toQuery()->update(array("printstatus" => "1"));

    //$currentexam = Exam::Where('status', '1')->first();//->get();
    // $currentexam =  Exam::where('id',5)->first();;//->get();
    $currentexam = $exampatternclass->exam;
    $stud_marks = new StudentmarkController;
    if ($data->count() > 0) {
      $fname = $data->first()->exampatternclasses->patternclass->pattern->pattern_name;

      $coursename = $data->first()->exampatternclasses->patternclass->coursepatternclasses->course->course_name
        . '_' . $data->first()->exampatternclasses->patternclass->coursepatternclasses->year
        . '_' . $currentexam->month . '_' . $currentexam->year;

      if (Str::contains($coursename, 'B.Voc')) {

        if ($currentexam->exam_sessions == 2) {
          $coursename = str_replace('Certificate', 'Diploma', $coursename);
        }
      }
      $coursename = str_replace(' ', '_', $coursename);
      //$filename =  $coursename . '_' . $fname . '_' . $currentexam->exam_name .'_sem '.$subject_sem.'_resultabcid' . '.csv';
      $filename =  $coursename  . '.csv';
    } else
      $filename = "Empty.csv";
    //dd($coursename);
    ob_end_clean();
    // return view('abcid.studentresultabcid', compact(

    //   'data','currentexam',
    //   'course_type',
    //   'exampatternclass'));
    $year = $exampatternclass->patternclass->getclass->year;
    //dd($subject_sem);
    // if ($currentexam->session == 1) {
    $patternclass =  $data->first()->student->studentmarks
      ->where('sem', $subject_sem)
      ->whereNotIn('subject_type', ['G', 'IG', 'IEG'])->pluck('patternclass_id')
      ->unique('patternclass_id')
      ->first();
    // dd($patternclass);
    $subjectcount =  $data->first()->student->studentmarks->whereIn('subject_id', Subject::where('patternclass_id', $patternclass)
      ->where('subject_sem', $subject_sem)
      ->whereNotIn('subject_type', ['G', 'IG', 'IEG'])->pluck('id'))
      ->unique('subject_id')
      ->count();

    //dd($subjectcount);
    // } else {
    //   $subjectcount = Subject::where('patternclass_id', $exampatternclass->patternclass_id)
    //     ->count();
    // }
    //$subjectcount =24;

    if ($course_type == "PG")
      // view()->share('result.convocationPGJuly2022', $data);//for PG new format used in march 2022(July 2022) sem -II exam rule 
      return Excel::download(new StudentresultabcidExport($data, $currentexam, $course_type, $exampatternclass, $subjectcount, $year, $subject_sem, $patternclass), $filename);
    //rule =>passing on total marks circular
    if ($course_type == "UG")
      return Excel::download(new StudentresultabcidExport($data, $currentexam, $course_type, $exampatternclass, $subjectcount, $year, $subject_sem, $patternclass), $filename);
  }

  //Forcefull Ordinace
  public function ordinaceforcefullentry($prn)   //fy sem I ledger Apply Result
  {



    $exam = 10;
    $sem = 5;
    // $prn = 1012001929;
    $this->ordinaceforcefullentry1($prn, $exam, $sem);
  }

  public function ordinaceforcefullentry1($prn, $exam, $sem)   //fy sem I ledger Apply Result
  {


    $exam = Exam::find($exam);

    $seatnodata = ExamStudentseatno::where('prn', $prn)
      ->whereIn('exam_patternclasses_id', ['342'])
      ->get()->last();

    $id = $seatnodata->exam_patternclasses_id;


    //  foreach($ids as $id)//for previous result generation
    {

      $exampatternclass = ExamPatternclass::find($seatnodata->exam_patternclasses_id);

      $course_type = $exampatternclass->patternclass->getclass->course->course_type;
      $currentexam = $exampatternclass->exam_id;
      // dd($currentexam);
      ini_set('max_execution_time', 5000);
      ini_set('memory_limit', '4048M');

      $data = ExamStudentseatno::where('exam_patternclasses_id', $seatnodata->exam_patternclasses_id)
        ->where('prn', $prn)
        ->get(); //->get();//BCS

      //if ($data->count() > 0)
      // $data->toQuery()->update(array("printstatus" => "1"));

      $stud_marks = new StudentmarkController;

      $stud_marks->collectintextmarks($data, $currentexam);

      // dd($seatnodata->exam_patternclasses_id);
      //  dd(Studentresult::where('student_id',$seatnodata->student_id)
      //  ->where('exam_patternclasses_id', $seatnodata->exam_patternclasses_id)->get());
      //  $sr= Studentresult::where('student_id',$seatnodata->student_id)
      //                  ->where('exam_patternclasses_id', $seatnodata->exam_patternclasses_id)
      //                  ->delete();


      $marks = Studentmark::where('student_id', $seatnodata->student_id)
        ->where('exam_id', $currentexam)
        ->where('grade', 'F')
        ->orWhere('grade', NULL);
      // dd($marks->get());
      $marks->update(
        [
          'total' => '0',
          'gpa' => '0',
          'grade_point' => '0',
          'grade' => NULL
        ]
      );





      // $stud_marks->collectintextmarks($data,$currentexam);
      // $stud_marks->applyordinanceone($data);
      //dd($data);
      $stud_marks->applyordinanceonesem2($data, $currentexam);

      // new rule int + ext =total apply ordinance on total and check passing on total only
      //$stud_marks->applyordinanceoneontotal($data,$currentexam);

      // $currentexam = Exam::Where('status', '1')->get();


      if ($data->count() > 0) {

        $cnt = 0;

        foreach ($data as $d) {

          $datasem = $d->student->currentclassstudent; //->where('sem','<=',$sem); //unique('sem');
          //dd($datasem);
          // $datasem = $d->student->currentclassstudent; 

          foreach ($datasem  as $data2) {

            if ($data2->markssheetprint_status != -1) //direct admission to second year 
            { //dd($datasem->count());
              // if($data2->pfstatus==1 and  $data2->sem==1)
              // {}
              // else
              if ($datasem->count() == 2) {
                // if($data2->sem==2 or $data2->sem==4 or $data2->sem==6)//fy backlog student
                //       $data2->update(['ordinace2_flag'=>5]);

                //  if(!is_null($d->student->studentresults->where('sem','1')))
                //  { 

                //   $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject
                //   $this->getsgpa($d,$subjects,$id, $data2->sem, $currentexam );
                //  } 
                // if($data2->sem==2)
                //  {

                //   $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject
                //   $this->getsgpa($d,$subjects,$id, $data2->sem, $currentexam );
                //  }

                // if($data2->pfstatus==1 and  $data2->sem==1)
                // {
                // }
                if ($data2->sem == 1) {
                  $studresult = $d->student->studentresults->where('sem', '1')->last();
                  //dd($studresult->sgpa);
                  if (is_null($studresult)) {
                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  } else if ($studresult->sgpa == 0 || $studresult->extraCreditsStatus == 0) {
                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject
                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  }
                } else if ($data2->sem == 2) {
                  $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject
                  $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                } else if ($data2->sem == 3) {
                  $studresult = $d->student->studentresults->where('sem', '3')->last();
                  //dd($studresult->sgpa);
                  if (is_null($studresult)) {
                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  } else if ($studresult->sgpa == 0 || $studresult->extraCreditsStatus == 0) {
                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject
                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  }
                } else if ($data2->sem == 4) {
                  $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject
                  $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                } else if ($data2->sem == 5) //ty
                {
                  $studresult = $d->student->studentresults->where('sem', '3')->last();
                  //dd($studresult->sgpa);
                  if (is_null($studresult)) {
                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  } else if ($studresult->sgpa == 0 || $studresult->extraCreditsStatus == 0) {
                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject
                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  }
                } else if ($data2->sem == 6) //ty
                {

                  $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject
                  $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                }
              } else if ($datasem->count() == 1) //direct fy, sy,ty admission
              {

                $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject
                $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
              } //if
              else  if ($datasem->count() == 3) // sy or direct to ty
              {
                //dd($data2->sem);
                if ($data2->sem == 1 || $data2->sem == 2) {
                  $studresult = $d->student->studentresults->where('sem', $data2->sem)->last();
                  //dd($studresult->sgpa);
                  if ($studresult->resultstatus != 1) {

                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  }
                } else  if ($data2->sem == 3 || $data2->sem == 5) {
                  $studresult = $d->student->studentresults->where('sem', $data2->sem)->last();
                  if (is_null($studresult) || $studresult->resultstatus != 1) {
                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  }
                } else  if ($data2->sem == 4 || $data2->sem == 6) {
                  $studresult = $d->student->studentresults->where('sem', $data2->sem)->last();
                  if (is_null($studresult) || $studresult->resultstatus != 1) {
                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  }
                }
              } else  if ($datasem->count() == 4) // sy 
              {
                if ($data2->sem == 1 || $data2->sem == 2 || $data2->sem == 3 || $data2->sem == 4 || $data2->sem == 5 || $data2->sem == 6) {
                  $studresult = $d->student->studentresults->where('sem', $data2->sem)->last();
                  //dd($studresult->sgpa);
                  if (is_null($studresult)) {
                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  } else if ($studresult->resultstatus != 1 || $studresult->extraCreditsStatus == 0) {

                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  } else if ($studresult->sgpa == 0 || $studresult->extraCreditsStatus == 0) {

                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  }
                }
                // else  if($data2->sem==3)
                // {
                //   $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                //   $this->getsgpa($d,$subjects,$id, $data2->sem, $currentexam );

                // }
                else if ($data2->sem == 4) {
                  $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                  $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                }
              } else  if ($datasem->count() == 5) // ty 
              {
                if ($data2->sem == 1 || $data2->sem == 2 || $data2->sem == 3 || $data2->sem == 4 || $data2->sem == 5) {
                  $studresult = $d->student->studentresults->where('sem', $data2->sem)->last();
                  //dd($studresult->sgpa);
                  if (is_null($studresult)) {
                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  } else if ($studresult->resultstatus != 1 || $studresult->extraCreditsStatus == 0) {

                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  } else if ($studresult->sgpa == 0 || $studresult->extraCreditsStatus == 0) {

                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  }
                }

                // else  if($data2->sem==3)
                // {
                //   $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                //   $this->getsgpa($d,$subjects,$id, $data2->sem, $currentexam );

                // }
                else if ($data2->sem == 6) {
                  $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                  $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                }
              } else  if ($datasem->count() == 6) // ty 
              {
                if ($data2->sem == 1 || $data2->sem == 2 || $data2->sem == 3 || $data2->sem == 4 || $data2->sem == 5) {
                  $studresult = $d->student->studentresults->where('sem', $data2->sem)->last();
                  //dd($studresult->sgpa);
                  if (is_null($studresult)) {
                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  } else if ($studresult->resultstatus != 1 || $studresult->extraCreditsStatus == 0) {

                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  } else if ($studresult->sgpa == 0 || $studresult->extraCreditsStatus == 0) {

                    $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                    $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                  }
                } else if ($data2->sem == 6) {
                  $subjects = $d->student->getsubjects($data2->patternclass_id, $data2->sem); // get sem1 subject

                  $this->getsgpa($d, $subjects, $id, $data2->sem, $currentexam);
                }
              }
            } //if markssheetprint_status
          } //inner for Sem Data current class


          foreach ($datasem  as $data2) {
            if ($data2->markssheetprint_status != -1) {

              if ($data2->pfstatus != 1) {
                switch ($data2->sem) {
                  case 2:
                  case 4:
                    //For PG final rsesult ordinance logic

                  case 6: {
                      $pfAbStatus = 1;

                      $Sem1Data = Studentresult::where('student_id', $d->student_id)->where('sem', $data2->sem - 1)->get()->last();
                      // $Sem1Data= $d->student->studentresults->where('sem',$data2->sem-1)->last() ;
                      //$Sem2Data= $d->student->studentresults->where('sem',$data2->sem)->last() ;
                      $Sem2Data = Studentresult::where('student_id', $d->student_id)->where('sem', $data2->sem)->get()->last();

                      $x = $Sem1Data->semcreditearned + $Sem2Data->semcreditearned;
                      $y = $Sem1Data->semtotalcredit + $Sem2Data->semtotalcredit;

                      // dd($x."  ".$y);
                      //dd($data2->patternclass->credit);
                      $z = $data2->patternclass->credit;

                      if ($x >= $z and $x < $y)
                        $pfAbStatus = 2; //{{"Result : Fail A.T.K.T." }} 
                      else if ($x < $z and $x >= 0) {
                        $pfAbStatus = 0;
                      } //{{"Result : Fail " }}
                      if ($pfAbStatus == 1 and ($Sem1Data->extraCreditsStatus == 0 or $Sem2Data->extraCreditsStatus == 0))
                        $pfAbStatus = 2; //{{"Result : Fail A.T.K.T." }}

                      //  dd( $Sem1Data->extraCreditsStatus==0 or $Sem2Data->extracreditsubpassfail==0);
                      // dd( $d->student->currentclassstudent->where('sem',$datasem->count())->last())
                      if ($pfAbStatus == 0)
                        $d->student->currentclassstudent->where('sem', $data2->sem)->last()->update([
                          'pfstatus' => $pfAbStatus,
                          'isregular' => 0,
                        ]);
                      else
                        $d->student->currentclassstudent->where('sem', $data2->sem)->last()->update([
                          'pfstatus' => $pfAbStatus,
                          'isregular' => 1,
                        ]);
                      break;
                    }
                }
              }
            } //if marksheetprint status 
          }
          if ($course_type == "PG") {
            if ($d->student->currentclassstudent->last()->sem == 4) //for PG only
            {
              $this->applyordinance_four($d, $id, $currentexam); //
            }
          }
        } //main for Data

      } //count
    } //for ids
  }
//Create 163 it get record from table 163 to result table

public function applyordinace163() {
  // Get the current exam
  $exam = Exam::where('status', '1')->first();
  if (!$exam) {
      // Handle case where no active exam is found
      dd("No active exam found");
  }
  
  $exam_id = $exam->id;  
  // Fetch student records for Ordinance 163 for the current exam
  $stud_163ordinaces = Studentordinace163::where('exam_id', $exam_id)->get();
  
  foreach ($stud_163ordinaces as $stud_ordinance) {
      // Get the most recent result for this student
      $student_result = Studentresult::where('student_id', $stud_ordinance->student_id)
          ->whereHas('exampatternclass', function($query) use ($exam_id) {
              $query->where('exam_id', $exam_id);
          })
          ->latest() // Assuming you want the latest record based on a timestamp or ID
          ->first();
      
      if ($student_result) {
        
         
          // Update the existing student result with Ordinance 163 marks
          $student_result ->update(['ordinance_163_marks' => $stud_ordinance->marks]);
     
        }  
  }
  
  dd("Records Updated"); // Indicate that records were processed
}

//Ordinace four got but fail in respective Sem
public function afterordinace_four_fail_result(){
//current exam
  $exam=Exam::where('status','1')->first();
  $exam_id=$exam->id;
  $currentexam=$exam->id;
  //data
   $stud_marks=Studentmark::where('total_ordinancefour_marks','!=','0')
                            ->where('exam_id',$exam_id)
                            ->get();
                           // dd( $stud_marks->pluck('student_id'));
                             
    foreach ($stud_marks as $stud_mark) {
   $studentseatno = ExamStudentseatno::whereHas('exampatternclasses', function ($query) use ($exam_id) {
                                  $query->where('exam_id', $exam_id);
                              })->where('student_id', $stud_mark->student_id)
                                ->first(); // or ->first() if expecting a single result
                                   
                      $exampatternclasss_id=   $studentseatno->exam_patternclasses_id; 
                                //$subject=   $stud_mark->subject->patternclass_id;
                                $sem= $stud_mark->subject->subject_sem;
                $subjects= $stud_mark->student->getsubjects($stud_mark->subject->patternclass_id, $sem);
                 
                 $this->getsgpa($studentseatno, $subjects, $exampatternclasss_id, $sem, $currentexam);


                              // Process $studentseatno here
                          }
    
                           
  
                            // public function 
  
dd("OK");



}


  //New Ordinace Four

  public function applyordinance_four_new_int_ext($studseatno, $exampatternclass_id, $currentexam)
  {

    $count = 0;
    $studres = null;
    $countsubfail = 0;
    $subjectdata = null;
    // dd($studseatno->studentresult->groupby('sem'));
    foreach ($studseatno->studentresult->groupby('sem') as $data) {
      if ($data->last()->sgpa == 0 || $data->last()->extraCreditsStatus == 0) {
        $count++;
        $studres = $data;
      }
    }

    //  dd($studres->last()->student->studentmarks->where('patternclass_id', $studres->last()->exampatternclass->patternclass->id));
    if ($count == 1) {

      //$subject=Subject::where('patternclass_id',$studres->last()->exampatternclass->patternclass)
      //                     ->get();



      $studentmarks = $studres->last()->student->studentmarks->where('patternclass_id', '<=', $studres->last()->exampatternclass->patternclass->id);
      Log::debug($studentmarks);
      //dd($studres->last()->exampatternclass->patternclass->id);

      foreach ($studentmarks->groupby('subject_id') as $data1) {
        Log::debug($data1->last()->grade);
        if ($data1->last()->grade == 'F' || $data1->last()->grade == 'Ab' || $data1->last()->grade == '-1' || $data1->last()->performancecancel == '1') { // dd($data1->last()->performancecancel);
          $countsubfail++;
          $subjectdata = $data1;
        }
      }
      //previous ordinance logic
      //dd($countsubfail);
      if ($countsubfail == 1) {


        $ordinancelimit = 10;
        $prevordinaceone = $subjectdata->last()->int_ordinance_one_marks + $subjectdata->last()->practical_ordinance_one_marks + $subjectdata->last()->ext_ordinance_one_marks + $subjectdata->last()->total_ordinance_one_marks;


        $newordinacelimit = $ordinancelimit - $subjectdata->last()->int_ordinance_one_marks + $subjectdata->last()->practical_ordinance_one_marks + $subjectdata->last()->ext_ordinance_one_marks + $subjectdata->last()->total_ordinance_one_marks;
        //  dd( $newordinacelimit );

        if (($subjectdata->last()->subject->subject_totalpassing - $subjectdata->last()->total) <= $newordinacelimit) {
          //  &&($subjectdata->last()->subject->subject_totalpassing-$subjectdata->last()->total)>0
          //Internal Ordinace 
          $int_shortfall = 0;
          $ext_shortfall = 0;
          $intpract_shortfall = 0;
          switch ($subjectdata->last()->subject->subject_type) {
            case 'IE':
            case 'IP':
            case 'IEG': {

                //internal fail Ext Pass

                if (
                  $subjectdata->last()->int_marks < $subjectdata->last()->subject->subject_intpassing
                  && $subjectdata->last()->ext_marks >= $subjectdata->last()->subject->subject_extpassing
                ) {

                  $int_shortfall = $subjectdata->last()->subject->subject_intpassing -  $subjectdata->last()->int_marks;

                  $shortfall =  $int_shortfall + $ext_shortfall;
                  if ($shortfall <=  $newordinacelimit) {
                    $total_marks = $subjectdata->last()->ext_marks + $subjectdata->last()->int_marks + $shortfall;

                    $per = ($total_marks / ($subjectdata->last()->subject->subject_maxmarks)) * 100;

                    $gp = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade')->get();

                    $ordcheck = $subjectdata->last()
                      ->update([
                        'int_marks' => $subjectdata->last()->int_marks + $int_shortfall,
                        'grade_point' => $gp->first()->grade_point,
                        'total' => $total_marks,
                        'grade' => $gp->first()->grade,
                        'int_ordinace_flag' => 4,
                        'int_ordinance_one_marks' => $int_shortfall,
                        'total_ordinancefour_marks' => $subjectdata->last()->total_ordinancefour_marks + $shortfall,
                      ]);
                  }
                }
                //External fail
                if (
                  $subjectdata->last()->ext_marks < $subjectdata->last()->subject->subject_extpassing
                  && $subjectdata->last()->int_marks >= $subjectdata->last()->subject->subject_intpassing
                ) {
                  $ext_shortfall = $subjectdata->last()->subject->subject_extpassing -  $subjectdata->last()->ext_marks;

                  $shortfall =  $int_shortfall + $ext_shortfall;
                  if ($shortfall <=  $newordinacelimit) {
                    $total_marks = $subjectdata->last()->ext_marks + $subjectdata->last()->int_marks + $shortfall;

                    $per = ($total_marks / ($subjectdata->last()->subject->subject_maxmarks)) * 100;

                    $gp = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade')->get();

                    $ordcheck = $subjectdata->last()
                      ->update([
                        'ext_marks' => $subjectdata->last()->ext_marks + $ext_shortfall,
                        'grade_point' => $gp->first()->grade_point,
                        'total' => $total_marks,
                        'grade' => $gp->first()->grade,
                        'ext_ordinace_flag' => 4,
                        'ext_ordinance_one_marks' => $ext_shortfall,
                        'total_ordinancefour_marks' => $subjectdata->last()->total_ordinancefour_marks + $shortfall,
                      ]);
                  }
                }
                //Int & Ext Fails
                if (
                  $subjectdata->last()->ext_marks < $subjectdata->last()->subject->subject_extpassing
                  && $subjectdata->last()->int_marks < $subjectdata->last()->subject->subject_intpassing
                ) {
                  $int_shortfall = $subjectdata->last()->subject->subject_intpassing -  $subjectdata->last()->int_marks;
                  $ext_shortfall = $subjectdata->last()->subject->subject_extpassing -  $subjectdata->last()->ext_marks;

                  $shortfall =  $int_shortfall + $ext_shortfall;

                  if ($shortfall <=  $newordinacelimit) {
                    $total_marks = $subjectdata->last()->ext_marks + $subjectdata->last()->int_marks + $shortfall;

                    $per = ($total_marks / ($subjectdata->last()->subject->subject_maxmarks)) * 100;

                    $gp = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade')->get();

                    $ordcheck = $subjectdata->last()
                      ->update([
                        'int_marks' => $subjectdata->last()->int_marks + $int_shortfall,
                        'ext_marks' => $subjectdata->last()->ext_marks + $ext_shortfall,
                        'grade_point' => $gp->first()->grade_point,
                        'total' => $total_marks,
                        'grade' => $gp->first()->grade,
                        'int_ordinace_flag' => 4,
                        'int_ordinance_one_marks' => $int_shortfall,
                        'ext_ordinace_flag' => 4,
                        'ext_ordinance_one_marks' => $ext_shortfall,
                        'total_ordinancefour_marks' => $subjectdata->last()->total_ordinancefour_marks + $shortfall,
                      ]);
                  }
                }
                break;
              }

            case 'IEP': {

                // Only Internal Fail
                if (
                  $subjectdata->last()->int_marks < $subjectdata->last()->subject->subject_intpassing
                  && $subjectdata->last()->int_practical_marks >= $subjectdata->last()->subject->subject_intpractpassing
                  && $subjectdata->last()->ext_marks >= $subjectdata->last()->subject->subject_extpassing
                ) {

                  $int_shortfall = $subjectdata->last()->subject->subject_intpassing -  $subjectdata->last()->int_marks;

                  $shortfall =  $int_shortfall + $ext_shortfall + $intpract_shortfall;
                  if ($shortfall <=  $newordinacelimit) {
                    $total_marks = $subjectdata->last()->ext_marks + $subjectdata->last()->int_marks + $subjectdata->last()->int_practical_marks + $shortfall;

                    $per = ($total_marks / ($subjectdata->last()->subject->subject_maxmarks)) * 100;

                    $gp = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade')->get();

                    $ordcheck = $subjectdata->last()
                      ->update([
                        'int_marks' => $subjectdata->last()->int_marks + $int_shortfall,
                        'grade_point' => $gp->first()->grade_point,
                        'total' => $total_marks,
                        'grade' => $gp->first()->grade,
                        'int_ordinace_flag' => 4,
                        'int_ordinance_one_marks' => $int_shortfall,
                        'total_ordinancefour_marks' => $subjectdata->last()->total_ordinancefour_marks + $shortfall,
                      ]);
                  }
                }
                //Only Internal Practical fail
                if (
                  $subjectdata->last()->int_marks >= $subjectdata->last()->subject->subject_intpassing
                  && $subjectdata->last()->int_practical_marks < $subjectdata->last()->subject->subject_intpractpassing
                  && $subjectdata->last()->ext_marks >= $subjectdata->last()->subject->subject_extpassing
                ) {

                  $intpract_shortfall = $subjectdata->last()->subject->subject_intpractpassing -  $subjectdata->last()->int_practical_marks;

                  $shortfall =  $int_shortfall + $ext_shortfall + $intpract_shortfall;
                  if ($shortfall <=  $newordinacelimit) {
                    $total_marks = $subjectdata->last()->ext_marks + $subjectdata->last()->int_marks + $subjectdata->last()->int_practical_marks + $shortfall;

                    $per = ($total_marks / ($subjectdata->last()->subject->subject_maxmarks)) * 100;

                    $gp = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade')->get();

                    $ordcheck = $subjectdata->last()
                      ->update([
                        'int_practical_marks' => $subjectdata->last()->int_practical_marks + $intpract_shortfall,
                        'grade_point' => $gp->first()->grade_point,
                        'total' => $total_marks,
                        'grade' => $gp->first()->grade,
                        'practical_ordinace_flag' => 4,
                        'practical_ordinance_one_marks' => $intpract_shortfall,
                        'total_ordinancefour_marks' => $subjectdata->last()->total_ordinancefour_marks + $shortfall,
                      ]);
                  }
                }


                //Only Externalfail  fail
                if (
                  $subjectdata->last()->int_marks >= $subjectdata->last()->subject->subject_intpassing
                  && $subjectdata->last()->int_practical_marks >= $subjectdata->last()->subject->subject_intpractpassing
                  && $subjectdata->last()->ext_marks < $subjectdata->last()->subject->subject_extpassing
                ) {
                  $ext_shortfall = $subjectdata->last()->subject->subject_extpassing -  $subjectdata->last()->ext_marks;



                  $shortfall =  $int_shortfall + $ext_shortfall + $intpract_shortfall;
                  if ($shortfall <=  $newordinacelimit) {
                    $total_marks = $subjectdata->last()->ext_marks + $subjectdata->last()->int_marks + $subjectdata->last()->int_practical_marks + $shortfall;

                    $per = ($total_marks / ($subjectdata->last()->subject->subject_maxmarks)) * 100;

                    $gp = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade')->get();

                    $ordcheck = $subjectdata->last()
                      ->update([
                        'ext_marks' => $subjectdata->last()->ext_marks + $ext_shortfall,
                        'grade_point' => $gp->first()->grade_point,
                        'total' => $total_marks,
                        'grade' => $gp->first()->grade,
                        'ext_ordinace_flag' => 4,
                        'ext_ordinance_one_marks' => $ext_shortfall,
                        'total_ordinancefour_marks' => $subjectdata->last()->total_ordinancefour_marks + $shortfall,
                      ]);
                  }
                }


                //  Internal and External Fail
                if (
                  $subjectdata->last()->int_marks < $subjectdata->last()->subject->subject_intpassing
                  && $subjectdata->last()->int_practical_marks >= $subjectdata->last()->subject->subject_intpractpassing
                  && $subjectdata->last()->ext_marks < $subjectdata->last()->subject->subject_extpassing
                ) {

                  $int_shortfall = $subjectdata->last()->subject->subject_intpassing -  $subjectdata->last()->int_marks;
                  $intpract_shortfall = 0;
                  $ext_shortfall = $subjectdata->last()->subject->subject_extpassing -  $subjectdata->last()->ext_marks;

                  $shortfall =  $int_shortfall + $ext_shortfall + $intpract_shortfall;
                  if ($shortfall <=  $newordinacelimit) {
                    $total_marks = $subjectdata->last()->ext_marks + $subjectdata->last()->int_marks + $subjectdata->last()->int_practical_marks + $shortfall;

                    $per = ($total_marks / ($subjectdata->last()->subject->subject_maxmarks)) * 100;

                    $gp = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade')->get();

                    $ordcheck = $subjectdata->last()
                      ->update([
                        'int_marks' => $subjectdata->last()->int_marks + $int_shortfall,
                        'ext_marks' => $subjectdata->last()->ext_marks + $ext_shortfall,
                        'grade_point' => $gp->first()->grade_point,
                        'total' => $total_marks,
                        'grade' => $gp->first()->grade,
                        'int_ordinace_flag' => 4,
                        'int_ordinance_one_marks' => $int_shortfall,
                        'ext_ordinace_flag' => 4,
                        'ext_ordinance_one_marks' => $ext_shortfall,
                        'total_ordinancefour_marks' => $subjectdata->last()->total_ordinancefour_marks + $shortfall,
                      ]);
                  }
                }

                //  Internal and Practical Fail
                if (
                  $subjectdata->last()->int_marks < $subjectdata->last()->subject->subject_intpassing
                  && $subjectdata->last()->int_practical_marks < $subjectdata->last()->subject->subject_intpractpassing
                  && $subjectdata->last()->ext_marks >= $subjectdata->last()->subject->subject_extpassing
                ) {

                  $int_shortfall = $subjectdata->last()->subject->subject_intpassing -  $subjectdata->last()->int_marks;
                  $intpract_shortfall = $subjectdata->last()->subject->subject_intpractpassing -  $subjectdata->last()->int_practical_marks;
                  $ext_shortfall = 0;

                  $shortfall =  $int_shortfall + $ext_shortfall + $intpract_shortfall;
                  if ($shortfall <=  $newordinacelimit) {
                    $total_marks = $subjectdata->last()->ext_marks + $subjectdata->last()->int_marks + $subjectdata->last()->int_practical_marks + $shortfall;

                    $per = ($total_marks / ($subjectdata->last()->subject->subject_maxmarks)) * 100;

                    $gp = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade')->get();

                    $ordcheck = $subjectdata->last()
                      ->update([
                        'int_marks' => $subjectdata->last()->int_marks + $int_shortfall,
                        'int_practical_marks' => $subjectdata->last()->int_practical_marks + $intpract_shortfall,
                        'grade_point' => $gp->first()->grade_point,
                        'total' => $total_marks,
                        'grade' => $gp->first()->grade,
                        'int_ordinace_flag' => 4,
                        'int_ordinance_one_marks' => $int_shortfall,
                        'practical_ordinace_flag' => 4,
                        'practical_ordinance_one_marks' => $intpract_shortfall,
                        'total_ordinancefour_marks' => $subjectdata->last()->total_ordinancefour_marks + $shortfall,
                      ]);
                  }
                }

                //  External and Int Practical Fail
                if (
                  $subjectdata->last()->int_marks >= $subjectdata->last()->subject->subject_intpassing
                  && $subjectdata->last()->int_practical_marks < $subjectdata->last()->subject->subject_intpractpassing
                  && $subjectdata->last()->ext_marks < $subjectdata->last()->subject->subject_extpassing
                ) {

                  $int_shortfall = 0;
                  $intpract_shortfall = $subjectdata->last()->subject->subject_intpractpassing -  $subjectdata->last()->int_practical_marks;

                  $ext_shortfall = $subjectdata->last()->subject->subject_extpassing -  $subjectdata->last()->ext_marks;

                  $shortfall =  $int_shortfall + $ext_shortfall + $intpract_shortfall;
                  if ($shortfall <=  $newordinacelimit) {
                    $total_marks = $subjectdata->last()->ext_marks + $subjectdata->last()->int_marks + $subjectdata->last()->int_practical_marks + $shortfall;

                    $per = ($total_marks / ($subjectdata->last()->subject->subject_maxmarks)) * 100;

                    $gp = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade')->get();

                    $ordcheck = $subjectdata->last()
                      ->update([
                        'int_practical_marks' => $subjectdata->last()->int_practical_marks + $intpract_shortfall,
                        'ext_marks' => $subjectdata->last()->ext_marks + $ext_shortfall,
                        'grade_point' => $gp->first()->grade_point,
                        'total' => $total_marks,
                        'grade' => $gp->first()->grade,
                        'practical_ordinace_flag' => 4,
                        'practical_ordinance_one_marks' => $intpract_shortfall,
                        'ext_ordinace_flag' => 4,
                        'ext_ordinance_one_marks' => $ext_shortfall,
                        'total_ordinancefour_marks' => $subjectdata->last()->total_ordinancefour_marks + $shortfall,
                      ]);
                  }
                }


                // Internal , External and Int Practical Fails
                if (
                  $subjectdata->last()->int_marks < $subjectdata->last()->subject->subject_intpassing
                  && $subjectdata->last()->int_practical_marks < $subjectdata->last()->subject->subject_intpractpassing
                  && $subjectdata->last()->ext_marks < $subjectdata->last()->subject->subject_extpassing
                ) {

                  $int_shortfall = $subjectdata->last()->subject->subject_intpassing -  $subjectdata->last()->int_marks;

                  $intpract_shortfall = $subjectdata->last()->subject->subject_intpractpassing -  $subjectdata->last()->int_practical_marks;

                  $ext_shortfall = $subjectdata->last()->subject->subject_extpassing -  $subjectdata->last()->ext_marks;

                  $shortfall =  $int_shortfall + $ext_shortfall + $intpract_shortfall;
                  if ($shortfall <=  $newordinacelimit) {
                    $total_marks = $subjectdata->last()->ext_marks + $subjectdata->last()->int_marks + $subjectdata->last()->int_practical_marks + $shortfall;

                    $per = ($total_marks / ($subjectdata->last()->subject->subject_maxmarks)) * 100;

                    $gp = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade')->get();

                    $ordcheck = $subjectdata->last()
                      ->update([
                        'int_marks' => $subjectdata->last()->int_marks + $int_shortfall,
                        'int_practical_marks' => $subjectdata->last()->int_practical_marks + $intpract_shortfall,
                        'ext_marks' => $subjectdata->last()->ext_marks + $ext_shortfall,
                        'grade_point' => $gp->first()->grade_point,
                        'total' => $total_marks,
                        'grade' => $gp->first()->grade,
                        'int_ordinace_flag' => 4,
                        'int_ordinance_one_marks' => $int_shortfall,
                        'practical_ordinace_flag' => 4,
                        'practical_ordinance_one_marks' => $intpract_shortfall,
                        'ext_ordinace_flag' => 4,
                        'ext_ordinance_one_marks' => $ext_shortfall,
                        'total_ordinancefour_marks' => $subjectdata->last()->total_ordinancefour_marks + $shortfall,
                      ]);
                  }
                }




                break;
              }

            case 'I':
            case 'IG': {

                if ($subjectdata->last()->int_marks < $subjectdata->last()->subject->subject_intpassing) {

                  $int_shortfall = $subjectdata->last()->subject->subject_intpassing -  $subjectdata->last()->int_marks;

                  $shortfall =  $int_shortfall;
                  if ($shortfall <=  $newordinacelimit) {
                    $total_marks =  $subjectdata->last()->int_marks + $shortfall;

                    $per = ($total_marks / ($subjectdata->last()->subject->subject_maxmarks)) * 100;

                    $gp = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade')->get();

                    $ordcheck = $subjectdata->last()
                      ->update([
                        'int_marks' => $subjectdata->last()->int_marks + $int_shortfall,
                        'grade_point' => $gp->first()->grade_point,
                        'total' => $total_marks,
                        'grade' => $gp->first()->grade,
                        'int_ordinace_flag' => 4,
                        'int_ordinance_one_marks' => $int_shortfall,
                        'total_ordinancefour_marks' => $subjectdata->last()->total_ordinancefour_marks + $shortfall,
                      ]);
                  }
                }




                break;
              }
          } //switch


        }



        $subjects = $studseatno->student->getsubjects($studres->last()->exampatternclass->patternclass->id, $subjectdata->last()->subject->subject_sem); // get sem1 subject
        // dd($subjects) ; 
        $this->getsgpa($studseatno, $subjects, $exampatternclass_id, $subjectdata->first()->subject->subject_sem, $currentexam);
      }
    }


    // dd("Ordinace s4 is Applied");


  }

  public function completioncertificate($id)
  {

    $exam = Exam::where('status', '1')->get()->first();
    $student = $exam->studentphdmarks
      ->where('patternclass_id', $id)
      ->where('exam_id', $exam->id)
      ->unique('student_id')
      ->pluck('student_id');

    $students = Studentphd::whereIn('id',  $student)->get();


    view()->share('phd.certificate', compact('students'));

    $pdf = PDF::loadView('phd.certificate', compact('students'))->setPaper('a4', 'Portrait');

    return $pdf->download("PHD_Certificate" . $students->first()->patternclass->getclass->course->special_subject . '.pdf');
    // dd($students);




  }
  public function phdordinace($id)
  {

    $exam = Exam::where('status', '1')->get()->first();

    $studentmarks = $exam->studentphdmarks
      ->where('patternclass_id', $id)
      ->where('exam_id', $exam->id)
      ->groupBy('student_id');

    foreach ($studentmarks as $marks) {
      foreach ($marks as $mark) {


        $total_marks =  $mark->int_marks + $mark->ext_marks;

        $per = ($total_marks / ($mark->subject->subject_maxmarks)) * 100;

        $gp = Gradepoint::where('max_percentage', '>=',  round($per))->where('min_percentage', '<=', round($per))->select('grade_point', 'grade')->get();

        $ordcheck = $mark
          ->update([

            'grade_point' => $gp->first()->grade_point,
            'total' => $total_marks,
            'subject_grade' => $gp->first()->grade,
            'credit_point' => $mark->subject->subject_credit * $gp->first()->grade_point,


          ]);
      }

      $cgpa = round($marks->sum('credit_point')   / $marks->sum('subject.subject_credit'), 2);
      $finalgrade = "";;
      if ($cgpa >= 9.50)
        $finalgrade = "O";
      else  if ($cgpa >= 8.25 && $cgpa < 9.50) {

        $finalgrade = "A+";
      } else  if ($cgpa >= 6.75 && $cgpa < 8.25) {
        $finalgrade = "A";
      } else  if ($cgpa >= 5.75 && $cgpa < 6.75) {

        $finalgrade = "B+";
      } else  if ($cgpa >= 5.25 && $cgpa < 5.75) {

        $finalgrade = "B";
      } else  if ($cgpa >= 4.75 && $cgpa < 5.25) {

        $finalgrade = "C";
      } else  if ($cgpa >= 4. && $cgpa < 4.75) {


        $finalgrade = "D";
      }
      $marks->first()->student->update(
        [
          'CGPA' => $cgpa,
          'finalgrade' => $finalgrade,


        ]
      );
    }
    view()->share('phd.result', compact('studentmarks'));

    $pdf = PDF::loadView('phd.result', compact('studentmarks'))->setPaper('a4', 'Portrait');
    return $pdf->download("PHD_Result" . $studentmarks->first()->first()->student->patternclass->getclass->course->special_subject . '.pdf');
    return $pdf->stream('PhdCourseWork.pdf');
    // return view('phd.result',compact('studentmarks'));

    // dd("OK");
  }
  public function createSgpa($id)
  {
    ini_set('max_execution_time', 5000);
    ini_set('memory_limit', '4048M');
    $exampatternclass = ExamPatternclass::find($id);
    $currentexam = $exampatternclass->exam;  
    $course_type = $exampatternclass->patternclass->getclass->course->course_type;
    $seatno_datas = ExamStudentseatno::where('exam_patternclasses_id', $id)
    //->take(1)                              
    ->get();
   foreach($seatno_datas  as $seatno )   
   {   
     $seatno->grade= $this->calculateStudentResult($seatno,$exampatternclass) ;
     if ($course_type == "PG")
        $seatno->special_subject=strtoupper($exampatternclass->patternclass->getclass->course->special_subject);
     if ($course_type == "UG")
        $seatno->special_subject=$seatno->student->checkspecial($currentexam);
   }
      $StudentCertificates=$seatno_datas->whereNotNull('grade');
     

      view()->share('result.passingcertificateFinal', $StudentCertificates); //for PG new format used in march 2022(July 2022) sem -II exam rule 
      $pdf = PDF::loadView('result.passingcertificateFinal', compact('StudentCertificates'), compact('currentexam',  'exampatternclass' ))->setPaper('a4')->setOptions(['defaultFont' => 'sans-serif']);;

   
      if ($StudentCertificates->count() > 0) {
        $fname = $exampatternclass->patternclass->pattern->pattern_name;
  
        $coursename = $exampatternclass->patternclass->coursepatternclasses->class_name;
  
        $filename = $coursename . '_' . $fname . '_' . $currentexam->exam_name . '_passingcertificate' . '.pdf';
      } else $filename = "Emptypassingcertificate.pdf";
    
      return $pdf->download($filename);
    
  }
  
  public function calculateStudentResult($seatno,$exampatternclass){
     
    $currentexam = $exampatternclass->exam;

    $studentresult= Studentresult::with('student')
    ->where('exam_patternclasses_id','<=',$exampatternclass->id)
    ->where('student_id', $seatno->student_id)//->sortKeys())   
    // ->where('sgpa', '!=', '0')
    ->where('extraCreditsStatus', '1')      
      
    ->select([
       'student_id', DB::raw('min(sem) as minSem'),
      'student_id', DB::raw('max(sem) as MaxSem'),
        
      ])         
      ->first();

    
 
     return $this->studentSemResult(range( $studentresult->minSem,$studentresult->MaxSem),$seatno,$exampatternclass);
      
              
      

  }
  public function studentSemResult($allsem,$seatno,$exampatternclass){
   $directsecondyear=0;
    // Initialize an empty collection
$allStudentResults = collect();
//if student fail then ignore result CGPA
$rejectresult=0;
$semtotalcredit=0;
$semtotalcreditpoints=0;

$oridnance_flag1= $this->checkordinace1($seatno,$exampatternclass);
 
    foreach( $allsem as $sem){
          
      $studentresult= Studentresult::with('student')
    ->where('exam_patternclasses_id','<=',$exampatternclass->id)
    ->where('student_id', $seatno->student_id)//->sortKeys())  
     ->where('sem',$sem) 
     // ->where('sgpa', '!=', '0')
    ->where('extraCreditsStatus', '1')
    ->orderByDesc('id')
    ->first() ;
   
    if( $studentresult->sgpa==0)
    {
      $rejectresult=1;
      $semtotalcredit=0;
      $semtotalcreditpoints=0;
      break;
    }

   // Add the result to the collection if found
   if ($studentresult) {
   $semtotalcredit= $semtotalcredit +$studentresult->semtotalcredit;
   $semtotalcreditpoints =$semtotalcreditpoints+$studentresult->semtotalcreditpoints;
   
              //Other college
    if($allsem['0']!=1){
      $extraCreditsNonCGPA=4;
      $directsecondyear=1;

   }else
   $extraCreditsNonCGPA=0;
                           
    $studentresult->extraCreditsNonCGPA=  $sem==2? $seatno->student->getSemwiseNONCGPAtotal($sem)+ $extraCreditsNonCGPA:$seatno->student->getSemwiseNONCGPAtotal($sem) ;

    $allStudentResults->push($studentresult);

    }

   
  } 
  if($rejectresult==1){
    return null;

  }else  //Result Pass
  {
     if($directsecondyear==1)
     $grade='PASSES';
    else
    $grade=$this->calculateCGPA($semtotalcreditpoints,$semtotalcredit,$oridnance_flag1);
    return $grade;
    
    
     

  }
   
 

           
  }
  public function checkordinace1($seatno,$exampatternclass){

    $sm=Studentmark::where('student_id',$seatno->student_id)
    ->where('exam_id','<=',$exampatternclass->exam_id)
    ->select([
      'student_id', DB::raw('sum(ext_ordinance_one_marks) as ext_ordinace_flag'),
      'student_id', DB::raw('sum(int_ordinance_one_marks) as int_ordinace_flag'),
      'student_id', DB::raw('sum(total_ordinance_one_marks) as total_ordinance_one_marks'),
      'student_id', DB::raw('sum(practical_ordinance_one_marks) as practical_ordinance_one_marks'),
      'student_id', DB::raw('sum(total_ordinancefour_marks) as total_ordinancefour_marks'),
     
    ])        
    ->groupBy('student_id')
   -> get() ;

return ($sm->sum('ext_ordinace_flag')
+$sm->sum('int_ordinace_flag')
+$sm->sum('total_ordinance_one_marks')
+$sm->sum('practical_ordinance_one_marks')
+$sm->sum('total_ordinancefour_marks')
);
                


  }
  public function calculateCGPA($totalgradepoint,$totalco,$oridnance_flag1){
    
    $cgpa=  round($totalgradepoint/$totalco,2);
 
                $finalgrade="";
                $diff="";
                if($cgpa>=9.50)
                        $finalgrade="O";
                else  if($cgpa>=8.25 && $cgpa<9.50)
                    {
                        $sf=$totalco*9.50 ;
                        $diff=$sf-$totalgradepoint;
                       
                                    
                    if($diff<=10 && $oridnance_flag1==0)
                       {$finalgrade="O $ 0.2";  $cgpa=9.50;}
                    else  
                       $finalgrade="A+";
                    }
                else  if($cgpa>=6.75 && $cgpa<8.25)
                    {
                     $sf=$totalco*8.25 ;
                     $diff=$sf-$totalgradepoint;
                    
                     if($diff<=10 && $oridnance_flag1==0)
                    {$finalgrade="A+ $ 0.2";  $cgpa=8.25;}
                    else  
                    $finalgrade="A";
                     }  
            else  if($cgpa>=5.75 && $cgpa<6.75)
                     {
                     $sf=$totalco*6.75 ;
                     $diff=$sf-$totalgradepoint;
                     if($diff<=10 && $oridnance_flag1==0)
                    {$finalgrade="A $ 0.2";  $cgpa=6.75;}
                      else  
                      $finalgrade="B+";
                     }  
            else  if($cgpa>=5.25 && $cgpa<5.75)
            {
                     $sf=$totalco*5.75 ;
                     $diff=$sf-$totalgradepoint;
                     if($diff<=10 && $oridnance_flag1==0)
                     {$finalgrade="B+ $ 0.2";  $cgpa=5.75;}
                      else  
                      $finalgrade="B";
                     }
             else  if($cgpa>=4.75 && $cgpa<5.25)
                {
                     $sf=$totalco*5.25 ;
                     $diff=$sf-$totalgradepoint;
                     if($diff<=10 && $oridnance_flag1==0)
                    {$finalgrade="B $ 0.2";  $cgpa=5.25;}
                    else  
                    $finalgrade="C";
                       }
             else  if($cgpa>=4. && $cgpa<4.75)
           {
                   
                    
                    $finalgrade="D";
           }
           return $finalgrade;
  }
}
