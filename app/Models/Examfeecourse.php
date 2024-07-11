<?php

namespace App\Models;

use App\Models\Patternclass;
use App\Models\Examfeemaster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Examfeecourse extends Model
{
    use HasFactory,SoftDeletes; 
    protected $dates = ['deleted_at'];
    protected $table='examfeecourses';
    protected $fillable=[
        'fee',
        'sem',
        'patternclass_id',
        'examfees_id',
        'active_status',
        'approve_status',
    ];


    public function patternclass(): BelongsTo
    {
        return $this->belongsTo(Patternclass::class, 'patternclass_id', 'id')->withTrashed();
    }

    public function examfee(): BelongsTo
    {
        return $this->belongsTo(Examfeemaster::class, 'examfees_id', 'id')->withTrashed();
    }

    public function scopeSearch(Builder $query, string $search)
    {
        return $query->with('examfee:fee_name,id')
        ->where('fee', 'like', "%{$search}%")
        ->orWhere('sem', 'like', "%{$search}%")
        ->orWhere(function ($subquery) use ($search) {
            $subquery->orWhereHas('examfee', function ($subQuery) use ($search) {
                $subQuery->where('fee_name', 'like', "%{$search}%");
            });
        });
    }
}
