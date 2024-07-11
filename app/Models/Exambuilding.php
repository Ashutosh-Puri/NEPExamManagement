<?php

namespace App\Models;

use App\Models\Exam;
use App\Models\Building;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Exambuilding extends Model
{
    use HasFactory,SoftDeletes; 
    protected $dates = ['deleted_at'];
    protected $table='exambuildings';
    protected $fillable=[
        'exam_id',
        'building_id',
        'status'
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class,'exam_id','id')->withTrashed();
    }
    
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class,'building_id','id')->withTrashed();
    }

    public function scopeSearch(Builder $query, string $search)
    {
        return $query->with('exam:exam_name,id', 'building:building_name,id',)->where(function ($subquery) use ($search) {
            $subquery->orWhereHas('exam', function ($subQuery) use ($search) {
                $subQuery->where('exam_name', 'like', "%{$search}%");
            })->orWhereHas('building', function ($subQuery) use ($search) {
                $subQuery->where('building_name', 'like', "%{$search}%");
           
            });
        });
    }
}
