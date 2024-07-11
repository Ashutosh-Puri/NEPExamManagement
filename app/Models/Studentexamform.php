<?php

namespace App\Models;

use App\Models\Exam;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Examformmaster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Studentexamform extends Model
{
    use HasFactory;
    protected $table='student_examforms';
    protected $fillable=[
        'int_status',
        'int_practical_status',
        'ext_status',
        'grade_status',
        'practical_status',
        'project_status',
        'oral_status',
        'exam_id',
        'student_id',
        'subject_id',
        'examformmaster_id',
        'college_id',
        'is_backlog',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class,'exam_id','id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function subject()
    {   
        return $this->belongsTo(Subject::class,'subject_id','id');
    }

    public function examformmasters()
    {         
        return $this->belongsTo(Examformmaster::class,'examformmaster_id','id');

    }
    
    public function examformmaster()
    {         
        return $this->belongsTo(Examformmaster::class,'examformmaster_id','id');

    }
}
