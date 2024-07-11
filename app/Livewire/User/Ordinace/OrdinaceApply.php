<?php

namespace App\Livewire\User\Ordinace;

use Mpdf\Mpdf;
use App\Models\Exam;
use Livewire\Component;
use App\Models\Cgparesult;
use App\Models\Gradepoint;
use App\Models\Studentmark;
use Livewire\WithPagination;
use App\Models\Studentresult;
use App\Models\Exampatternclass;
use App\Models\Examstudentseatno;
use App\Models\Studentordinace163;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Jobs\User\Ordinace\OrdinaceApplyJob;

class OrdinaceApply extends Component
{
    # By Ashutosh
    use WithPagination;
    public $perPage = 10;
    public $search = '';
    public $sortColumn = "id";
    public $sortColumnBy = "DESC";
    public $ext;


    public $exam;

    public function sort_column($column)
    {
        if( $this->sortColumn === $column)
        {
            $this->sortColumnBy=($this->sortColumnBy=="ASC")?"DESC":"ASC";
            return;
        }
        $this->sortColumn=$column;
        $this->sortColumnBy=="ASC";
    }

    //Result generation loginc 
    #[Renderless]
    public function generate_final_result(Exampatternclass $exampatternclass)
    {   
        // $exampatternclass = Exampatternclass::find(5);
        OrdinaceApplyJob::dispatch($exampatternclass);

        $this->dispatch('alert',type:'success',message:'Ordinace Apply Job Running !!');
    } 


    #[Renderless]
    public function apply_ordinace_two(Exampatternclass $exampatternclass) 
    {   
        // $exampatternclass = Exampatternclass::find(5);

        $course_type = $exampatternclass->patternclass->courseclass->course->course_type;

        $student_seatnos = Examstudentseatno::where('exam_patternclasses_id', $exampatternclass->id)->get();

        foreach ($student_seatnos as $student_seatno) 
        {
            $studentresult= Studentresult::with('student')
            ->where('exam_patternclasses_id','<=',$exampatternclass->id)
            ->where('student_id', $student_seatno->student_id)
            // ->where('extracreditsstatus',1)     
            ->select(['student_id', DB::raw('min(sem) as minsem'), DB::raw('max(sem) as maxsem'),])    
            ->groupBy('student_id')     
            ->first();
            
            if($studentresult)
            {
                $allsem = range( $studentresult->minsem,$studentresult->maxsem);

                $result = $this->student_sem_result( $allsem,$student_seatno,$exampatternclass);

                $student_seatno->grade=$result['grade'];
    
                if ($course_type == "PG") 
                {
                    $student_seatno->special_subject=strtoupper($exampatternclass->patternclass->courseclass->course->special_subject);
                }
    
                if ($course_type == "UG") 
                {
                    $student_seatno->special_subject=$student_seatno->student->checkspecial($exampatternclass->exam);
                }
            }
        }

        $student_certificates=$student_seatnos->whereNotNull('grade');

        $exam= $exampatternclass->exam;

        $html = view('pdf.student.result.passing_certificate', compact('student_certificates', 'exam', 'exampatternclass'))->render();

        $mpdf = new Mpdf(['default_font' => 'sans-serif']);
        $mpdf->WriteHTML($html);

        if ($student_certificates->count() > 0) {
            $filename = str_replace(' ', '_', get_pattern_class_name($exampatternclass->patternclass_id)) . '_' . $exam->exam_name . '_Passing_Certificate.pdf';
        } else {
            $filename = "Empty_Passing_Certificate.pdf";
        }

        return response()->streamDownload(function() use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $filename);
    
    }

    protected function student_sem_result($allsem,$student_seatno,$exampatternclass)
    {
        $reject_result=0;
        $sem_total_credits=0;
        $sem_total_credit_points=0;
        $direct_second_year=0;

        $is_oridnance_one_four= $this->check_ordinace_one_four($student_seatno,$exampatternclass);
        
        foreach($allsem as $sem)
        {
                
            $studentresult= Studentresult::where('exam_patternclasses_id','<=',$exampatternclass->id)
            ->where('student_id', $student_seatno->student_id) 
            ->where('sem',$sem) 
            // ->whereNot('sgpa',0)
            // ->where('extracreditsstatus',1)
            ->orderByDesc('id')
            ->first() ;

            if ($studentresult) 
            {
                $sem_total_credits= $sem_total_credits + $studentresult->semtotalcredit;
                $sem_total_credit_points = $sem_total_credit_points + $studentresult->semtotalcreditpoints;

                if($allsem['0']!=1)
                {
                    
                    $direct_second_year=1;
              
                }

                if($direct_second_year==1)
                {
                    $grade= ['grade'=>'Pass' ,'cgpa'=>0];;
                }   
                else
                {
                    $grade= $this->calculateCGPA($sem_total_credit_points,$sem_total_credits,$is_oridnance_one_four);

                }

               return $grade;
                
            }
            else
            {
                return null;
            }

        } 
    }

    protected function check_ordinace_one_four($student_seatno,$exampatternclass)
    {

        $sm=Studentmark::where('student_id',$student_seatno->student_id)
        ->where('exam_id','<=',$exampatternclass->exam_id)
        ->select([
            'student_id', DB::raw('sum(ext_ordinance_one_marks) as ext_ordinace_flag'),
            'student_id', DB::raw('sum(int_ordinance_one_marks) as int_ordinace_flag'),
            'student_id', DB::raw('sum(total_ordinance_one_marks) as total_ordinance_one_marks'),
            'student_id', DB::raw('sum(practical_ordinance_one_marks) as practical_ordinance_one_marks'),
            'student_id', DB::raw('sum(total_ordinancefour_marks) as total_ordinancefour_marks'),
        ])        
        ->groupBy('student_id')
        ->get() ;

        return ($sm->sum('ext_ordinace_flag')+$sm->sum('int_ordinace_flag')+$sm->sum('total_ordinance_one_marks')+$sm->sum('practical_ordinance_one_marks')+$sm->sum('total_ordinancefour_marks'));             
    }

    protected function calculateCGPA($sem_total_credit_points,$sem_total_credits,$is_oridnance_one_four)
    {   
        $cgpa= 0;

        if($sem_total_credits >0)
        {
            $cgpa=  round($sem_total_credit_points/$sem_total_credits,2);
        }
        
 
        $final_grade=null;
        $difference;

        if($cgpa>=9.50)
        {
            $final_grade="O";
        }
        else  if($cgpa>=8.25 && $cgpa<9.50)
        {
            $short_fall=$sem_total_credits*9.50 ;
            
            $difference=$short_fall-$sem_total_credit_points;
                                                      
            if($difference<=10 && $is_oridnance_one_four==0)
            {
                $final_grade="O $ 2";  $cgpa=9.50;
            }
            else  
            {
                $final_grade="A+";
            }
        }
        else  if($cgpa>=6.75 && $cgpa<8.25)
        {
            $short_fall=$sem_total_credits*8.25 ;
            $difference=$short_fall-$sem_total_credit_points;
                    
            if($difference<=10 && $is_oridnance_one_four==0)
            {
                $final_grade="A+ $ 2";  $cgpa=8.25;
            }
            else 
            {
                $final_grade="A";
            } 
        }  
        else  if($cgpa>=5.75 && $cgpa<6.75)
        {
            $short_fall=$sem_total_credits*6.75 ;
            $difference=$short_fall-$sem_total_credit_points;

            if($difference<=10 && $is_oridnance_one_four==0)
            {
                $final_grade="A $ 2";  $cgpa=6.75;
            }
            else  
            {
                $final_grade="B+";
            }
        }  
        else  if($cgpa>=5.25 && $cgpa<5.75)
        {
            $short_fall=$sem_total_credits*5.75 ;
            $difference=$short_fall-$sem_total_credit_points;
            if($difference<=10 && $is_oridnance_one_four==0)
            {
                $final_grade="B+ $ 2";  $cgpa=5.75;
            }
            else 
            {
                $final_grade="B";
            } 
        }
        else  if($cgpa>=4.75 && $cgpa<5.25)
        {
            $short_fall=$sem_total_credits*5.25 ;
            $difference=$short_fall-$sem_total_credit_points;
            
            if($difference<=10 && $is_oridnance_one_four==0)
            {
                $final_grade="B $ 2";  $cgpa=5.25;
            }
            else  
            {
                $final_grade="C";
            }
        }
        else  if($cgpa>=4. && $cgpa<4.75)
        {           
            $final_grade="D";
        }

        return ['grade'=>$final_grade ,'cgpa'=>$cgpa];
    }

    public function mount()
    {
        $this->exam = Exam::where('status', 1)->first();
    }

    public function render()
    {
        $exam_pattern_classes = Exampatternclass::select('id', 'exam_id', 'patternclass_id', 'deleted_at')
            ->with(['exam:exam_name,id', 'patternclass.courseclass.course:course_name,id', 'patternclass.courseclass.classyear:classyear_name,id', 'patternclass.pattern:pattern_name,id'])
            ->where('exam_id', $this->exam->id)
            ->when($this->search, function ($query, $search) {
                $query->search($search); })
            ->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.ordinace.ordinace-apply', compact('exam_pattern_classes'))->extends('layouts.user')->section('user');

    }
}
