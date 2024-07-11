<?php

namespace App\Models;

use App\Models\Exam;
use App\Models\Subject;
use App\Models\Capmaster;
use App\Models\Examorder;
use App\Models\Patternclass;
use App\Models\Examtimetable;
use App\Models\Subjectbucket;
use App\Models\Blockallocation;
use App\Models\Examstudentseatno;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Exampatternclass extends Model
{
    use HasFactory , SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table='exam_patternclasses';
    protected $fillable=[
        'exam_id',
        'patternclass_id',
        'result_date',
        'launch_status',
        'start_date',
        'end_date',
        'latefee_date',
        'intmarksstart_date',
        'intmarksend_date',
        'finefee_date',
        'capmaster_id',
        'capscheduled_date',
        'papersettingstart_date',
        'papersubmission_date',
        'placeofmeeting',
        'description',
    ];


    public function subjectbuckets()
    {
        return $this->hasMany(Subjectbucket::class, 'subjectbucket_id', 'id')->withTrashed();   
    }
    
    public function patternclass(): BelongsTo
    {   
        return $this->belongsTo(Patternclass::class, 'patternclass_id', 'id')->withTrashed();
    }
    
    public function examorders()
    {
        return $this->hasMany(Examorder::class,'exam_patternclass_id','id')->withTrashed();
    }

    public function examtimetables()
    {
        return $this->hasMany(Examtimetable::class,'exam_patternclasses_id','id');
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'exam_id', 'id')->withTrashed();
    }

    public function capmaster(): BelongsTo
    {
        return $this->belongsTo(Capmaster::class, 'capmaster_id', 'id')->withTrashed();
    }

    public function examstudentseatnos()
    {
        return $this->hasMany(Examstudentseatno::class, 'exam_patternclasses_id');
    }

    public function blockallocations()
    {
        return $this->hasMany(Blockallocation::class,'exampatternclass_id','id');
    }

    public function scopeSearch(Builder $query, string $search)
    {
        return $query->with('patternclass.courseclass.course:course_name,id', 'patternclass.courseclass.classyear:classyear_name,id', 'patternclass.pattern:pattern_name,id','exam:exam_name,id')
        ->where(function ($subquery) use ($search) {
            $subquery->orWhereHas('patternclass.courseclass.course', function ($subQuery) use ($search) {
                $subQuery->where('course_name', 'like', "%{$search}%");
            })->orWhereHas('patternclass.pattern', function ($subQuery) use ($search) {
                $subQuery->where('pattern_name', 'like', "%{$search}%");
            })->orWhereHas('patternclass.courseclass.classyear', function ($subQuery) use ($search) {
                $subQuery->where('classyear_name', 'like', "%{$search}%");
            })->orWhereHas('exam', function ($subQuery) use ($search) {
                $subQuery->where('exam_name', 'like', "%{$search}%");
            });
        });

    }
}
