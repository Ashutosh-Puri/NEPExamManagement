<?php

namespace App\Models;

use App\Models\Exam;
use App\Models\User;
use App\Models\Faculty;
use App\Models\Subject;
use App\Models\Questionpaperbank;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Papersubmission extends Model
{
    use HasFactory,SoftDeletes; 
    protected $dates = ['deleted_at'];
    protected $table='papersubmissions';
    protected $fillable=[
        'exam_id',   
        'subject_id',
        'noofsets',
        'chairman_id',
        'user_id',
        'status',
        'is_confirmed',
        'is_online',
    ];

    
    public function questionbanks(): HasMany
    {
        return $this->hasMany(Questionpaperbank::class, 'papersubmission_id', 'id');
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class,'exam_id','id')->withTrashed();
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class,'subject_id','id')->withTrashed();
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class,'chairman_id','id')->withTrashed();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id')->withTrashed();
    }

    public function scopeSearch(Builder $query,string $search)
    {
        return $query->with('exam:exam_name,id','subject:subject_name,id','faculty:faculty_name,id','user:name,id')
        ->where('noofsets', 'like', "%{$search}%")
        ->orWhere(function ($subquery) use ($search) {
            $subquery->orWhereHas('exam', function ($subQuery) use ($search) {
                $subQuery->where('exam_name', 'like', "%{$search}%");
            });
            $subquery->orWhereHas('subject', function ($subQuery) use ($search) {
                $subQuery->where('subject_name', 'like', "%{$search}%");
            });
            $subquery->orWhereHas('faculty', function ($subQuery) use ($search) {
                $subQuery->where('faculty_name', 'like', "%{$search}%");
            });
            $subquery->orWhereHas('user', function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', "%{$search}%");
            });
        });
    }
}
