<?php

namespace App\Livewire\User\Cap;

use Mpdf\Mpdf;
use Carbon\Carbon;
use App\Models\Exam;
use App\Models\Faculty;
use Livewire\Component;
use Carbon\CarbonPeriod;
use App\Models\Department;
use App\Models\Capattendance;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class RecordCapAttendance extends Component
{   
    # By Ashutosh
    
    public $exam;
    public $dates = [];
    public $faculties = null;
    public $supervisionchart = [];
    public $sessions;
    public $sessiontype = null;
    public $allsession = null;
    public $departments = null;
    public $selecteddepartment = null;
    public $selectedallsession = null;

    


    public function checkmydate($date)
    {
        $tempDate = explode('-', $date);

        if (empty($tempDate[0]))
        {
            return false;
        }
        else
        {
            if (checkdate($tempDate[1], $tempDate[2], $tempDate[0]))
            {
                return true;
            }
        }
    }

    public function updatedSelecteddepartment($id)
    {
        $this->faculties = Faculty::where('college_id',1)->where('department_id', $id)->get();
    }

    public function updated($key, $value)
    {
        try 
        {
            $explod = explode('.', $key);
           
            DB::beginTransaction();
            
            if($value && !empty($value))
            {
                if ($explod[0] === 'supervisionchart' && is_array($value) && $this->checkmydate(array_key_first($value))) 
                {
        
                    Capattendance::create([
                        'faculty_id' => $explod[1],
                        'cap_date' => array_key_first($value),
                        'exam_id' => $this->exam->id,
                        'user_id' => Auth::user()->id,
                    ]);

                    $this->dispatch('alert',type:'success',message:'Attendance Recorded Successfully !!');
                } else if($explod[0] === 'supervisionchart'  && $this->checkmydate($explod[2]))
                {
                    Capattendance::create([
                        'faculty_id' => $explod[1],
                        'cap_date' => $explod[2],
                        'exam_id' => $this->exam->id,
                        'user_id' => Auth::user()->id,
                    ]);

                    $this->dispatch('alert',type:'success',message:'Attendance Recorded Successfully !!');
                }
            }
            else
            {   
                if ($explod[0] === 'supervisionchart' && !empty($explod[2])) 
                {
                    $examsupr = Capattendance::where('faculty_id', $explod[1])->where('exam_id', $this->exam->id)->where('cap_date', $explod[2]);
                    $examsupr->delete();
                    $this->dispatch('alert',type:'success',message:'Attendance Revert Successfully !!');
                }
            }
            $value=null;
            $explod=[];
            $this->supervisionchart=[];

            DB::commit();
            

        } catch (\Exception $e) 
        {
            DB::rollBack();
            $this->dispatch('alert',type:'error',message:'Failed To Record Attendance !!');
        }

       
    }

    #[Renderless]
    public function attendance_report()
    { 
        $cap_attendance = Capattendance::where('exam_id', $this->exam->id)->with('faculty')->select('faculty_id', 'cap_date')->get()->groupBy('faculty_id');

        $dates = Capattendance::where('exam_id',$this->exam->id)->select('cap_date')->distinct()->pluck('cap_date')->sort();
   
        $attendanceData = [];
        foreach ($cap_attendance as $faculty_id => $attendance) 
        {

            $attendanceData[$faculty_id] = [
                'name' => $attendance->first()->faculty->faculty_name,
                'dates' => []
            ];
            foreach ($dates as $date) 
            {
                $attendanceData[$faculty_id]['dates'][$date] = $attendance->contains('cap_date', $date) ? 'Y' : '';
            }
        }

        $pdfContent = View::make('pdf.user.exam_marks.date_wise_cap_attendance_report', compact('attendanceData', 'dates'))->render();

        $mpdf = new Mpdf(['orientation' => 'L']);

        $mpdf->WriteHTML($pdfContent);

        return response()->streamDownload(function () use ($mpdf) { $mpdf->Output(); }, 'date_wise_cap_attendance_report.pdf');

    }


    public function mount()
    {
        $this->departments = Department::all();
        $this->exam = Exam::where('status', 1)->first();
        $this->allsession = $this->exam->examsessions->unique('from_date', 'to_date');
        $this->faculties = Faculty::where('college_id', 1)->whereIn('department_id',$this->departments->pluck('id'))->get();
    }

    public function render()
    {   

        $period = CarbonPeriod::create(Carbon::today(), Carbon::today());
      
        $i = 0;

        foreach ($period as $date) 
        {

            $this->dates[$i++] = $date->format('Y-m-d');
        }

        $examsupervisions = Capattendance::where('exam_id', $this->exam->id)->select('faculty_id', 'cap_date')->get();

        foreach ($examsupervisions->groupBy(['faculty_id', 'cap_date'], true) as $key => $data) 
        {
            foreach ($data as $k => $valuedata) 
            {   
                $this->supervisionchart[$key]["$k"] = true;
            }
        }

        return view('livewire.user.cap.record-cap-attendance')->extends('layouts.user')->section('user');
    }
}
