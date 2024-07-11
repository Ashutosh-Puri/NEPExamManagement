<?php

namespace App\Models;

use App\Models\Student;
use App\Models\Patternclass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currentclassstudent extends Model
{
    use HasFactory , SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table='currentclass_students';
    protected $fillable=[
        'sem',
        'pfstatus',
        'isregular',
        'is_directadmission',
        'patternclass_id',
        'student_id',
        'college_id',
        'academicyear_id',
        'markssheetprint_status',
    ];


    public function patternclass()
    {
        return $this->belongsTo(Patternclass::class,'patternclass_id','id')->withTrashed();
    }

    public function student()
    {
        return $this->belongsTo(Student::class,'student_id','id')->withTrashed();
    }
}
