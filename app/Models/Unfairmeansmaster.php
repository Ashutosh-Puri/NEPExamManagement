<?php

namespace App\Models;

use App\Models\Exam;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unfairmeansmaster extends Model
{
    use HasFactory,SoftDeletes; 
    protected $dates = ['deleted_at'];
    protected $table='unfairmeansmasters';
    protected $fillable=[
        'location',
        'date',
        'time',
        'exam_id',
        'status'
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class,'exam_id','id')->withTrashed();
    }

    public function scopeSearch(Builder $query,string $search)
    {
        return $query->with('exam:exam_name,id')
        ->where('location', 'like', "%{$search}%")
        ->orWhere('date', 'like', "%{$search}%")
        ->orWhere('time', 'like', "%{$search}%")
        ->orWhere(function ($subquery) use ($search) {
            $subquery->orWhereHas('exam', function ($subQuery) use ($search) {
                $subQuery->where('exam_name', 'like', "%{$search}%");
            });
        });
    }
}
