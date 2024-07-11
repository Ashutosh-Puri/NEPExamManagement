<?php

namespace App\Models;

use App\Models\User;
use App\Models\Faculty;
use App\Models\Subject;
use App\Models\Patternclass;
use App\Models\Facultyinternaldocument;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hodappointsubject extends Model
{
    use HasFactory,SoftDeletes;
    protected $table='hodappointsubjects';
    protected $fillable=[
        'faculty_id',
        'subject_id',
        'patternclass_id',
        'appointby_id',
    ];

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class,'faculty_id','id')->withTrashed();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'appointby_id','id')->withTrashed();
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class,'subject_id','id')->withTrashed();
    }

    public function patternclass(): BelongsTo
    {
        return $this->belongsTo(Patternclass::class,'patternclass_id','id')->withTrashed();
    }

    public function facultyinternaldocument():HasMany
    {
        return $this->hasMany(Facultyinternaldocument::class,'departmenthead_id','id')->withTrashed();
    }

    public function scopeSearch(Builder $query, string $search)
    {
        return $query->with('faculty:faculty_name,id', 'subject:subject_name,id',)
        ->orWhere(function ($subquery) use ($search) {
            $subquery->orWhereHas('faculty', function ($subQuery) use ($search) {
                $subQuery->where('faculty_name', 'like', "%{$search}%");
            })->orWhereHas('subject', function ($subQuery) use ($search) {
                $subQuery->where('subject_name', 'like', "%{$search}%");
            });
        });
    }

}

