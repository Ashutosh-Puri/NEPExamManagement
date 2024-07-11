<?php

namespace App\Models;

use App\Models\Exam;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Examsession extends Model
{
    use HasFactory,SoftDeletes; 
    protected $dates = ['deleted_at'];
    protected $table='examsessions';
    protected $fillable=[
        'from_date',
        'to_date',
        'session_type',
        'from_time',
        'to_time',
        'exam_id',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'exam_id', 'id');
    }

    public function scopeSearch(Builder $query, string $search)
    {
        return $query
        ->where(function ($query) use ($search) {
            $query->where('from_date', 'like', "%{$search}%")
                ->orWhere('to_date', 'like', "%{$search}%")   
                ->orWhere('session_type', 'like', "%{$search}%")   
                ->orWhere('from_time', 'like', "%{$search}%")   
                ->orWhere('to_time', 'like', "%{$search}%");   
        });
    }
}
