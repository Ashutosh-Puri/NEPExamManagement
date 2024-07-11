<?php

namespace App\Models;

use App\Models\Exampanel;
use App\Models\Exampatternclass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Examorder extends Model
{
    use HasFactory,SoftDeletes; 
    protected $dates = ['deleted_at'];
    protected $table='examorders';
    protected $fillable=[
        'user_id',
        'exampanel_id',
        'exam_patternclass_id',
        'description',
        'token',
        'email_status'
    ];

    public function exampanel(): BelongsTo
    {
        return $this->belongsTo(Exampanel::class,'exampanel_id','id')->withTrashed();
    }

    public function exampatternclass(): BelongsTo
    {
        return $this->belongsTo(Exampatternclass::class, 'exam_patternclass_id', 'id')->withTrashed();
    }

    public function scopeSearch(Builder $query, string $search)
    {
        return $query->with('exampatternclass.exam:exam_name,id','exampanel.faculty:faculty_name,id','exampanel.subject:subject_name,id')
        ->where(function ($subquery) use ($search) {
            $subquery->whereHas('exampatternclass.exam', function ($subQuery) use ($search) {
                $subQuery->where('exam_name', 'like', "%{$search}%");
             })->orWhereHas('exampanel.faculty', function ($subQuery) use ($search) {
                $subQuery->where('faculty_name', 'like', "%{$search}%");
             })->orWhereHas('exampanel.subject', function ($subQuery) use ($search) {
                $subQuery->where('subject_name', 'like', "%{$search}%");
             });
        });

    }
}
