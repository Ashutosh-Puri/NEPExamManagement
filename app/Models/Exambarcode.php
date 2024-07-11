<?php

namespace App\Models;

use App\Models\Subject;
use App\Models\Paperassesment;
use App\Models\Exampatternclass;
use App\Models\Examstudentseatno;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Exambarcode extends Model
{
    use HasFactory;
    protected $table="exambarcodes";
    protected $fillable = [
        'exam_studentseatnos_id',
        'exam_patternclasses_id',
        'subject_id',
        'lotnumber',
        'exam_timetable_id',
        'status',
        'paperassesment_id',
        'examiner_marks',
        'moderator_marks',
        'reexam_status',
        'reexam_printbarcode',
        'verified_marks',
    ];
        
    public function exampatternclass()
    {
        return $this->belongsTo(Exampatternclass::class,'exam_patternclasses_id','id');
    }

    public function exam_studentseatnos()
    {
        return $this->belongsTo(Examstudentseatno::class,'exam_studentseatnos_id','id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function paperassesment()
    {
        return $this->belongsTo(Paperassesment::class, 'paperassesment_id', 'id');
    }
    
}
