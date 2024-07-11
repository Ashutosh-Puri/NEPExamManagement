<?php

namespace App\Models;

use App\Models\User;
use App\Models\Block;
use App\Models\College;
use App\Models\Faculty;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\Blockmaster;
use App\Models\Exampatternclass;
use App\Models\Studentblockallocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blockallocation extends Model
{
    use HasFactory ,SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table='blockallocations';
    protected $fillable=[
     'block_id',
     'classroom_id',
     'exampatternclass_id',
     'subject_id',
     'faculty_id',
     'user_id',
     'college_id',
     'noofabsent',
     'status',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function block(): BelongsTo
    {
        return $this->belongsTo(Blockmaster::class, 'block_id', 'id');
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'classroom_id', 'id');
    }

    public function exampatternclass(): BelongsTo
    {
        return $this->belongsTo(Exampatternclass::class, 'exampatternclass_id', 'id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class, 'faculty_id', 'id');
    }

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class, 'college_id', 'id');
    }

    public function studentblockallocations()
    {
        return $this->hasMany(Studentblockallocation::class,'bloackallocation_id','id')->withTrashed();
    }

    public function scopeSearch(Builder $query, string $search)
    {
        return $query
        ->with('classroom:class_name,id','block:block_name,id','subject:subject_name,id','exampatternclass.exam:exam_name,id')
        ->where(function ($query) use ($search) { 
            $query->where('noofabsent', 'like', "%{$search}%")
            ->orWhereHas('block', function ($subQuery) use ($search) {
                $subQuery->where('block_name', 'like', "%{$search}%");
            })
            ->orWhereHas('classroom', function ($subQuery) use ($search) {
                $subQuery->where('class_name', 'like', "%{$search}%");
            })
            ->orWhereHas('subject', function ($subQuery) use ($search) {
                $subQuery->where('subject_name', 'like', "%{$search}%");
            })
            ->orWhereHas('exampatternclass.exam', function ($subQuery) use ($search) {
                $subQuery->where('exam_name', 'like', "%{$search}%");
            });
        });
    }
}
