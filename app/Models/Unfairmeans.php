<?php

namespace App\Models;

use App\Models\Student;
use App\Models\Subject;
use App\Models\Exampatternclass;
use App\Models\Examstudentseatno;
use App\Models\Unfairmeansmaster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Unfairmeans extends Model
{
    use HasFactory,SoftDeletes; 
    protected $dates = ['deleted_at'];
    protected $table='unfairmeans';
    protected $fillable=[
        'exam_patternclasses_id',
        'exam_studentseatnos_id',
        'student_id',
        'unfairmeansmaster_id',
        'mem_id',
        'subject_id',
        'punishment',
        'status',
        'email'
    ];

    public function exampatternclass(): BelongsTo
    {
        return $this->belongsTo(Exampatternclass::class,'exam_patternclasses_id','id')->withTrashed();
    }

    public function examstudentseatno(): BelongsTo
    {
        return $this->belongsTo(Examstudentseatno::class,'exam_studentseatnos_id','id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class,'student_id','id')->withTrashed();
    }

    public function unfairmeans(): BelongsTo
    {
        return $this->belongsTo(Unfairmeansmaster::class,'unfairmeansmaster_id','id')->withTrashed();
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class,'subject_id','id')->withTrashed();
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class,'subject_id','id')->withTrashed();
    }

    public function scopeSearch(Builder $query,string $search)
    {
        return $query->with('exampatternclass.exam:exam_name,id','examstudentseatno:seatno,id','student:student_name,memid,id','subject:subject_name,id')
        ->orWhere(function ($subquery) use ($search) {
            $subquery->orWhereHas('exampatternclass.exam', function ($subQuery) use ($search) {
                $subQuery->where('exam_name', 'like', "%{$search}%"); 
            })->orWhereHas('examstudentseatno', function ($subQuery) use ($search) {
                $subQuery->where('seatno', 'like', "%{$search}%");
            })->orWhereHas('student', function ($subQuery) use ($search) {
                $subQuery->where('student_name', 'like', "%{$search}%");
            })->orWhereHas('student', function ($subQuery) use ($search) {
                $subQuery->where('memid', 'like', "%{$search}%");
            })->orWhereHas('subject', function ($subQuery) use ($search) {
                $subQuery->where('subject_name', 'like', "%{$search}%");
            });
        });
    }

}
