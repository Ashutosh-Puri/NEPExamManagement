<?php

namespace App\Models;

use App\Models\Exam;
use App\Models\User;
use App\Models\Faculty;
use App\Models\Paperset;
use App\Models\Papersubmission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Questionpaperbank extends Model
{
    use HasFactory,SoftDeletes; 
    protected $dates = ['deleted_at'];
    protected $table='questionpaperbanks';
    protected $fillable=[
        'id',
        'papersubmission_id',
        'exam_id',
        'set_id',
        'file_path',
        'file_name',
        'user_id',
        'chairman_id',
        'is_used',
        'print_date',
        'exam_date',
        'password',
    ];

    public function papersubmission(): BelongsTo
    {
        return $this->belongsTo(Papersubmission::class,'papersubmission_id','id')->withTrashed();
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class,'exam_id','id')->withTrashed();
    }

    public function paperset(): BelongsTo
    {
        return $this->belongsTo(Paperset::class,'set_id','id')->withTrashed();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id')->withTrashed();
    }
    
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class,'chairman_id','id')->withTrashed();
    }

    public function scopeSearch(Builder $query,string $search)
    {
        return $query->with('papersubmission.subject:subject_name,id','exam:exam_name,id','user:name,id','faculty:faculty_name,id')
        ->where('set', 'like', "%{$search}%")
        ->orWhere('file_name', 'like', "%{$search}%")
        ->orWhere(function ($subquery) use ($search) {
            $subquery->orWhereHas('exam', function ($subQuery) use ($search) {
                $subQuery->where('exam_name', 'like', "%{$search}%");
            });
            $subquery->orWhereHas('user', function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', "%{$search}%");
            });
            $subquery->orWhereHas('faculty', function ($subQuery) use ($search) {
                $subQuery->where('faculty_name', 'like', "%{$search}%");
            });
            $subquery->orWhereHas('papersubmission.subject', function ($subQuery) use ($search) {
                $subQuery->where('subject_name', 'like', "%{$search}%");
            });
        });
    }
}
