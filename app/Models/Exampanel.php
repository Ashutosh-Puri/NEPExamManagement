<?php

namespace App\Models;

use App\Models\Faculty;
use App\Models\Subject;
use App\Models\Examorderpost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Exampanel extends Model
{
    use HasFactory,SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table='exampanels';
    protected $fillable=[
        'user_id',
        'faculty_id',
        'examorderpost_id',
        'subject_id',
        'description',
        'active_status'
    ];

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class,'faculty_id','id')->withTrashed();
    }

    public function examorderpost(): BelongsTo
    {
        return $this->belongsTo(Examorderpost::class,'examorderpost_id','id')->withTrashed();
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class,'subject_id','id')->withTrashed();
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class,'subject_id','id')->withTrashed();
    }

    public function scopeSearch(Builder $query, string $search)
    {
        return $query->with('faculty:faculty_name,id', 'examorderpost:post_name,id', 'subject:subject_name,id')->where(function ($subquery) use ($search) {
            $subquery->orWhereHas('faculty', function ($subQuery) use ($search) {
                $subQuery->where('faculty_name', 'like', "%{$search}%");
            })->orWhereHas('examorderpost', function ($subQuery) use ($search) {
                $subQuery->where('post_name', 'like', "%{$search}%");
            })->orWhereHas('subject', function ($subQuery) use ($search) {
                $subQuery->where('subject_name', 'like', "%{$search}%");
            });
        });
    }
}
