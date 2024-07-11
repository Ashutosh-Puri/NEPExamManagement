<?php

namespace App\Models;

use App\Models\User;
use App\Models\College;
use App\Models\Faculty;
use App\Models\Student;
use App\Models\Classyear;
use App\Models\Exampanel;
use App\Models\Department;
use App\Models\Studentmark;
use App\Models\Subjecttype;
use App\Models\Academicyear;
use App\Models\Patternclass;
use App\Models\Subjectbucket;
use App\Models\Papersubmission;
use App\Models\Studentexamform;
use App\Models\Subjectcategory;
use App\Models\Subjectvertical;
use App\Models\Exampatternclass;
use App\Models\Hodappointsubject;
use App\Models\Internalmarksbatch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\BelongsToManyRelationship;

class Subject extends Model
{
    use HasFactory,SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table='subjects';
    protected $fillable=[
        'academicyear_id',
        'subject_sem',
        'subjectvertical_id',
        'subject_order',
        'subject_code',
        'subject_name_prefix',
        'subject_name',
        'subjectcategory_id',
        'subject_type',
        'subject_credit',
        'subject_maxmarks',
        'subject_maxmarks_int',
        'subject_maxmarks_intpract',
        'subject_maxmarks_ext',
        'is_panel',
        'no_of_sets',
        'subject_totalpassing',
        'subject_intpassing',
        'subject_intpractpassing',
        'subject_extpassing',
        'subject_optionalgroup',
        'patternclass_id',
        'classyear_id',// fy or sy or ty
        'user_id',// user who add
        'faculty_id',// faculty who add
        'department_id',
        'college_id',
        'status',
    ];


    public function academicyear()
    {
        return $this->belongsTo(Academicyear::class, 'academicyear_id','id');
    }

    public function examPatternClass()
    {
        return $this->belongsTo(Exampatternclass::class, 'patternclass_id');
    }

    public function patternclasses(): BelongsToMany
    {
        return $this->belongsToMany(Patternclass::class,'patternclass_id','id')->withTrashed();
    }

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class, 'college_id', 'id')->withTrashed();
    }

    public function subjectcategory(): BelongsTo
    {
     return $this->belongsTo(Subjectcategory::class,'subjectcategory_id','id')->withTrashed(); // ok
    }

    public function subjectvertical(): BelongsTo
    {
     return $this->belongsTo(Subjectvertical::class,'subjectvertical_id','id')->withTrashed(); //ok
    }

    public function department(): BelongsTo
    {
     return $this->belongsTo(Department::class,'department_id','id')->withTrashed();
    }

    public function subjecttype(): BelongsTo
    {
     return $this->belongsTo(Subjecttype::class,'subject_type','type_name')->withTrashed(); //ok
    }

    public function patternclass(): BelongsTo
    {
     return $this->belongsTo(Patternclass::class,'patternclass_id','id')->withTrashed();
    }

    public function classyear(): BelongsTo
    {
     return $this->belongsTo(Classyear::class,'classyear_id','id')->withTrashed();
    }

    public function subjectbuckets():HasMany
    {
        return $this->hasMany(Subjectbucket::class,'subject_id','id')->withTrashed();
    }

    public function hodappointsubjects():HasMany
    {
        return $this->hasMany(Hodappointsubject::class,'subject_id','id')->withTrashed();
    }

    public function internalmarksbatches():HasMany
    {
        return $this->hasMany(Internalmarksbatch::class,'subject_id','id');
    }

    public function studentexamforms():HasMany
    {
        return $this->hasMany(Studentexamform::class,'subject_id','id');
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class,'faculty_id','id')->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id')->withTrashed();
    }

    public function exampanels()
    {
        return $this->hasMany(Exampanel::class,'subject_id','id')->withTrashed();
    }

    public function papersubmissions()
    {
        return $this->hasMany(Papersubmission::class,'subject_id','id')->withTrashed();
    }

    public function scopeSearch(Builder $query, string $search)
    {
        return $query->where('subject_name', 'like', "%{$search}%")->orWhere('subject_code', 'like', "%{$search}%");
    }

    public function studentmarks()
    {
        return $this->hasMany(Studentmark::class,'subject_id','id');
    }
}
