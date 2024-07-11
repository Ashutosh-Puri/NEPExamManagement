<?php

namespace App\Models;

use App\Models\Exam;
use App\Models\Course;
use App\Models\Pattern;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Courseclass;
use App\Models\Admissiondata;
use App\Models\Examfeecourse;
use App\Models\Subjectbucket;
use App\Models\Examformmaster;
use App\Models\Exampatternclass;
use App\Models\Hodappointsubject;
use App\Models\Internaltoolauditor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Patternclass extends Model
{
    use HasFactory , SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table='pattern_classes';
    protected $fillable=[
        'class_id',
        'pattern_id',
        'status',
        'sem1_total_marks',
        'sem2_total_marks',
        'sem1_credits',
        'sem2_credits',
        'credit',
        'sem1_totalnosubjects',
        'sem2_totalnosubjects',
    ];

    public function subjects():HasMany
    {
        return $this->hasMany(Subject::class,'patternclass_id','id')->withTrashed();
    }

    public function examformmasters():HasMany
    {
        return $this->hasMany(Examformmaster::class,'patternclass_id','id');
    }

    public function admissiondatas():HasMany
    {
        return $this->hasMany(Admissiondata::class,'patternclass_id','id');
    }

    public function hodappointsubjects():HasMany
    {
        return $this->hasMany(Hodappointsubject::class,'patternclass_id','id')->withTrashed();
    }

    public function pattern()
    {
        return $this->belongsTo(Pattern::class,'pattern_id','id')->withTrashed();
    }

    public function courseclass()
    {
         return $this->belongsTo(Courseclass::class,'class_id','id')->withTrashed();
    }

    public function subjectbuckets():HasMany
    {
        return $this->hasMany(Subjectbucket::class,'patternclass_id','id')->withTrashed();
    }

    public function exams()
    {
        return $this->belongsToMany(
            Exam::class,           // Related model class (Exam)
            'exam_patternclasses', // Pivot table name ('exam_patternclasses')
            'patternclass_id',     // Foreign key on the current model ('patternclass_id')
            'exam_id'              // Foreign key on the related model ('exam_id')
        )->withPivot('launch_status', 'start_date');
    }

    public function exampatternclasses(): HasMany
    {
        return $this->hasMany(Exampatternclass::class, 'patternclass_id', 'id');
    }

    public function examfeecourses()
    {
        return $this->hasMany(Examfeecourse::class,'patternclass_id','id');
    }

    public function internaltoolauditors():HasMany
    {
        return $this->hasMany(Internaltoolauditor::class,'patternclass_id','id')->withTrashed();
    }


    public function scopeSearch(Builder $query, string $search)
    {
        return $query->with('courseclass.course:course_name,id', 'courseclass.classyear:classyear_name,id', 'pattern:pattern_name,id')
        ->where(function ($subquery) use ($search) {
            $subquery->whereHas('courseclass.course', function ($subQuery) use ($search) {
                $subQuery->where('course_name', 'like', "%{$search}%");
            })->orWhereHas('pattern', function ($subQuery) use ($search) {
                $subQuery->where('pattern_name', 'like', "%{$search}%");
            })->orWhereHas('courseclass.classyear', function ($subQuery) use ($search) {
                $subQuery->where('classyear_name', 'like', "%{$search}%");
            });
        });

    }
}
