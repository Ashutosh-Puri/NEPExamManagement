<?php

namespace App\Http\Controllers\User\Cap;

use App\Models\Exam;
use Illuminate\Http\Request;
use App\Models\Examtimetable;
use App\Models\Exampatternclass;
use App\Http\Controllers\Controller;

class CapController extends Controller
{   

    public function index()
    {
        $exam=Exam::where('status',1)->get();
       
        $epc=Exampatternclass::where('exam_id',$exam->first()->id)->pluck('id');
        Examtimetable::query()->update(['status'=>0]);
    
        Examtimetable::whereIn('exam_patternclasses_id',$epc)->update(['status'=>1]);

        $examtimetable=Examtimetable::whereIn('exam_patternclasses_id',$epc)->whereYear('examdate','!=','0001')->whereHas('subject',function($query){$query->whereIn('subject_type',['IE','IEP']); })->orderby('examdate')->orderby('timeslot_id')->get();
        return view('rnd',compact('examtimetable'));
    }

}
