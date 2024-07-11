<?php

namespace App\Models;

use App\Models\Subject;
use App\Models\Department;
use App\Models\Academicyear;
use App\Models\Patternclass;
use App\Models\Subjectvertical;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subjectbucket extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table='subjectbuckets';
    protected $fillable=[
        'department_id',
        'patternclass_id',
        'subjectvertical_id',
        'subject_division',
        'subject_id',
        'academicyear_id',
        'status',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class,'department_id','id')->withTrashed();
    }

    public function patternclass(): BelongsTo
    {
        return $this->belongsTo(Patternclass::class,'patternclass_id','id')->withTrashed();
    }

    public function subjectvertical(): BelongsTo
    {
        return $this->belongsTo(Subjectvertical::class,'subjectvertical_id','id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class,'subject_id','id')->withTrashed();
    }

    public function academicyear(): BelongsTo
    {
        return $this->belongsTo(Academicyear::class,'academicyear_id','id')->withTrashed();
    }

    public function scopeSearch(Builder $query, string $search)
    {
        return $query->with('subject:subject_name,id', 'department:dept_name,id')
        ->WhereHas('subject', function ($query) use ($search) {
                $query->where('subject_name', 'like', "%{$search}%");
        })
        ->orWhereHas('department', function ($query) use ($search) {
                $query->where('dept_name', 'like', "%{$search}%");
        });
    }

}
