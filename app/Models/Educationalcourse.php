<?php

namespace App\Models;

use App\Models\Programme;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Educationalcourse extends Model
{
    use HasFactory , SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table='educationalcourses';
    protected $fillable=[
        'course_name',
        'programme_id',
        'is_active',
    ];

    public function programme():BelongsTo
    {
    return $this->belongsTo(Programme::class,'programme_id','id')->withTrashed();
    }

    public function scopeSearch(Builder $query,string $search)
    {
        return $query->with('programme:programme_name,id')
        ->where('course_name', 'like', "%{$search}%");
        $subquery->orWhereHas('programme', function ($subQuery) use ($search) {
            $subQuery->where('programme_name', 'like', "%{$search}%");
        });
    }
   
}
