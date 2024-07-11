<?php

namespace App\Http\Controllers\User\Barcode;

use PDF;
use Excel;
use App\Models\Exam;
use App\Models\Exambarcode;
use Illuminate\Http\Request;
use App\Models\Examtimetable;
use App\Models\Timetableslot;
use App\Models\Paperassesment;
use App\Http\Controllers\Controller;
use App\Exports\User\Cap\SealbagReportExport;

class BarcodeController extends Controller
{

  public function marklist()
  {
    return view('livewire.user.cap.marklist');
  }

  public function generate_marklist(Request $request)
  { 

    $request->validate(['dates'=> 'required',"allsession" =>'required']);
    
    $exam=Exam::where('status',1)->first();
    
    $pages=collect();
    if($request->input('reexam')=="reexam")
    {
    
      $examtimetable=Examtimetable::whereIn('id',Exambarcode::where('reexam_status',1)->whereIn('exam_patternclasses_id',$exam->exampatternclasses->pluck('id'))
      ->distinct('exam_timetable_id')->pluck('exam_timetable_id'))
      ->get();
      foreach($examtimetable as $subjectsdata)
      {
      
        $barcodedata=Exambarcode::where('exam_patternclasses_id',$subjectsdata->exam_patternclasses_id)
        ->where('subject_id',$subjectsdata->subject_id)
        ->where('status',0)
        ->where('reexam_status',1)
        ->whereNull('paperassesment_id')
        ->get();
          
        foreach($barcodedata->chunk(60) as $lot)
        {
              
          $paperassesment = Paperassesment::create([
            'exam_id '=>Exam::where('status',1)->first()->id,
            'subject_id'=>$subjectsdata->subject_id,
            'total_papers'=>$barcodedata->count(),
          ]);
            
          foreach ($lot as $user) 
          {
            $user->update(['paperassesment_id' => $paperassesment->id  ]);
          }
        }   
        $barcodedata=Exambarcode::where('exam_patternclasses_id',$subjectsdata->exam_patternclasses_id)
        ->where('subject_id',$subjectsdata->subject_id)
        ->where('status',0)
        ->where('reexam_status',1)
        ->get() ;
    
        $pages->add(['barcode'=>$barcodedata->chunk(60),'subject'=>$subjectsdata]);
      }
    }
    else
    {
      $examtimetable=Examtimetable::where('examdate',$request->input('dates'))->get();


      foreach($examtimetable as $subjectsdata)
      {
        
        $barcodedata=Exambarcode::where('exam_patternclasses_id',$subjectsdata->exam_patternclasses_id)
        ->where('subject_id',$subjectsdata->subject_id)
        ->where('status',0)
        ->whereNull('paperassesment_id')
        ->get();

                
        foreach($barcodedata->chunk(60) as $lot)
        {
                    
          $paperassesment = Paperassesment::create([
            'exam_id'=> $exam->id,
            'subject_id'=>$subjectsdata->subject_id,
            'total_papers'=>$barcodedata->count(),
          ]);

          foreach ($lot as $user) 
          { 
             $user->update(['paperassesment_id' => $paperassesment->id  ]);
          }
  
        }
    
        $barcodedata=Exambarcode::where('exam_patternclasses_id',$subjectsdata->exam_patternclasses_id)
        ->where('subject_id',$subjectsdata->subject_id)
        ->where('status',0)
        ->get() ;
  
  
        $pages->add(['barcode'=>$barcodedata->chunk(60),'subject'=>$subjectsdata])  ;
      }
    }
    
    $pdf = PDF::loadView('pdf.user.barcode.mark_list', compact('pages'))->setPaper('a4', 'portrait');
    
    return $pdf->download('Markslist_'.$request->input('dates').'.pdf');

  }


  public function generate_barcode($examdate, $timeslot_id)
  {
    $exam = Exam::where('status', 1)->first();
    $timeslot = Timetableslot::find($timeslot_id);

    
    $examtimetable = Examtimetable::where('examdate', $examdate)->where('timeslot_id', $timeslot->id)->where('status', 1)->get();
    foreach ($examtimetable as $ett) 
    {

      $seatno = collect();
      $seatno1 = collect();

      $examform = $ett->subject->studentexamforms->where('exam_id', $exam->id)->where('ext_status',1);
      
      if ($examform->count() != 0) 
      {
        $lotcount = 1;
        foreach ($examform as $data) 
        { 
          if ($data->examformmaster->inwardstatus == 1)
          {
            $seatno->add( [
              'exam_studentseatnos_id' => $data->student->examstudentseatnos->last()->id,
              'exam_patternclasses_id' => $ett->exam_patternclasses_id,
              'subject_id' => $ett->subject_id,
              'exam_timetable_id' => $ett->id,
            ]);
          }
        }

        $seatno1 = $seatno->sortBy([['exam_studentseatnos_id', 'asc'],]);
        try 
        {
          foreach ($seatno1 as $sn) 
          {
            $sn += ['lotnumber' => $lotcount++];
            Exambarcode::create($sn);
          }
        } 
        catch (Exception $e) 
        {
          return redirect()->back()->with('alert', ['type' => 'error', 'message' => 'Failed To Genrate Barcode !!']);
        }
      }
    }

    return redirect()->back()->with('alert', ['type' => 'success', 'message' => 'Barcode Genrated Successfully !!']);
  }


  public function download_barcode($examdate, $timeslot_id)
  {
    ini_set('max_execution_time', 3000);
    ini_set('memory_limit', '2048M');

    $timeslot = Timetableslot::find($timeslot_id);
    $exam = Exam::where('status', 1)->first(); 
    $exampapers = Examtimetable::where('examdate', $examdate)->where('timeslot_id', $timeslot->id)->where('status', 1)->get();
    
    $a = array(array());
    $i = 0;
    $j = 0;
    foreach ($exampapers as $data) 
    {
      
      $examform = $data->subject->studentexamforms->where('exam_id', $exam->id)->where('ext_status', 1);
      
      if ($examform->count() != 0) 
      {
        $inwardformstatus = false;

        foreach ($examform as $data1) 
        { 
          if ($data1->examformmaster->inwardstatus == 1) 
          {
            $inwardformstatus = true;
            break;
          }
        }

        if ($inwardformstatus == true) 
        {
          $totalcount = 0;

          $a[$i][$j] = "1" . "," . $data->subject->patternclass_id . "," . str_replace(array(",")," ",$data->subject->subject_name) . "," .$data->subject->patternclass->courseclass->classyear->classyear_name." ".$data->subject->patternclass->courseclass->course->course_name . "," . $data->examdate . "," . $data->subject->subject_code . "," . $data->subject->subject_sem. "," .$timeslot->timeslot;
          if ($j < 11)
          {
            $j++;
          }

          if ($j == 11) 
          {
            $i++;
            $j = 0;
          }
         
          foreach ($data->exambarcodes as $bcdata) 
          {
            $a[$i][$j] = "0" . "," . $data->subject->patternclass_id . "," . str_replace(array(",")," ",$data->subject->subject_name) . "," . $bcdata->exam_studentseatnos->seatno . "," . $bcdata->id . "," . $data->subject->subject_code . "," . $data->subject->subject_sem. "," .$timeslot->timeslot;
            if ($j < 11)
            {
              $j++;
            }

            if ($j == 11) 
            {
              $i++;
              $j = 0;
            }

            $totalcount++;

            if ($totalcount == 30 || $totalcount%30==0) 
            {
              $a[$i][$j] = "1" . "," . $data->subject->patternclass_id . "," . str_replace(array(",")," ",$data->subject->subject_name) . "," .$data->subject->patternclass->courseclass->classyear->classyear_name." ".$data->subject->patternclass->courseclass->course->course_name. "," . $data->examdate . "," . $data->subject->subject_code . "," . $data->subject->subject_sem. "," .$timeslot->timeslot;
              if ($j < 11)
              {
                $j++;
              }

              if ($j == 11) 
              {
                $i++;
                $j = 0;
              }
            }
          }       
        }
      }
    }

    if ($j < 11)
    {
      $i++;
    }

    $n = $i;

    $pdf = PDF::loadView('pdf.user.barcode.final_barcode', compact('exampapers', 'exam', 'a', 'n'))->setPaper('a4', 'portrait');
    
    return $pdf->download("Barcode_Sticker_".date('d-m-Y', strtotime($examdate))."_" .$timeslot->timeslot.'.pdf');
  }


  public function seal_bag_report()
  {
    return view('livewire.user.cap.sealbagreport');
  }
  
  public function seal_bag_report_create(Request $request)
  {
    ini_set('max_execution_time', 5000);
    ini_set('memory_limit', '4048M');

    $request->validate([
      'dates' => 'required',
      "allsession" => 'required',
    ]);

    $pages = Examtimetable::withCount(['exambarcodes as total_absent'=>function($query){ $query->where('status',1); }, 'exambarcodes as total_copycase'=>function($query){ $query->where('status',2); },'exambarcodes as total_present'=>function($query){   $query->where('status',0); }, 'exambarcodes as total_students'=>function($query){ $query->whereIn('status',[0,1,2]);  }, ])
    ->where('examdate', $request->input('dates'))
    ->orderBy('timeslot_id')
    ->get()->groupby(['exampatternclass.capmaster_id','timeslot_id']);
       
       
    if($request->input('dnpdf')=="pdf")
    {   
      
      $pdf = PDF::loadView('pdf.user.barcode.seal_bag_pdf_report', compact('pages'))->setPaper('a4', 'portrait');

      return $pdf->download('Seal_Bag_Report_' . $request->input('dates') . '.pdf');

    }
    else if($request->input('dnpdf')=="excel")
    {
      ob_end_clean();

      return Excel::download(new SealbagReportExport($pages), 'Seal_Bag_Report_' . $request->input('dates') . '.xlsx');
  
    }
  }

}