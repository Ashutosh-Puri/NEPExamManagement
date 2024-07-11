<?php

namespace App\Livewire\Student\Home;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Exampatternclass;

class StudentTimeTable extends Component
{   
    use WithPagination;

    public function render()
    {   
        $active_exam_date=Exampatternclass::select('patternclass_id','start_date','end_date','latefee_date','finefee_date')
        ->with('patternclass.courseclass.course','patternclass.courseclass.classyear','patternclass.pattern')->where('launch_status',1)->orderBy('start_date','asc')->paginate(23);
        return view('livewire.student.home.student-time-table',compact('active_exam_date'));
    }
}
