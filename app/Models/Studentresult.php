<?php

namespace App\Models;

use App\Models\Student;
use App\Models\Studentresult;
use App\Models\Exampatternclass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Studentresult extends Model
{
    use HasFactory;
    protected $table="studentresults";
    protected $fillable = [
        'student_id',
        'exam_patternclasses_id',
        'seatno',
        'sem',
        'isregular',
        'sgpa',
        'semcreditearned',
        'semtotalcredit',
        'totalmarks',
        'totaloutofmarks',
        'semtotalcreditpoints',
        'resultstatus',
        'extracreditsstatus',
        'noncgpasubjecttotal',
        'ordinance_163_marks',
    ];

 
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function exampatternclass(){

        return $this->belongsTo(Exampatternclass::class,'exam_patternclasses_id','id');
    }
    
    public function ordinance163($exam_id)
    {

        $studord163=Studentordinace163::where('exam_id',$exam_id)->where('student_id',$this->student_id)->get();
        if(!$studord163->isEmpty())
        {
            return($studord163->last()->marks-$studord163->last()->marksused);
        }
        else
        {
            return -1;
        }
    }
}
