<?php

namespace App\Models;

use App\Models\Student;
use App\Models\Studentresult;
use App\Models\Exampatternclass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Examstudentseatno extends Model
{
    use HasFactory;
    protected $table='exam_studentseatnos';
    protected $fillable=[
        'prn',
        'seatno',
        'student_id',
        'exam_patternclasses_id',
        'printstatus',
        'college_id'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class,'student_id','id')->withTrashed();
    }

    public function exampatternclass(): BelongsTo
    {
        return $this->belongsTo(Exampatternclass::class,'exam_patternclasses_id','id')->withTrashed();
    }

    public function studentresults()
    {
        return $this->hasMany(Studentresult::class, 'student_id', 'student_id');
    }
}
