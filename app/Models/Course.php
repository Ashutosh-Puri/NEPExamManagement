<?php

namespace App\Models;

use App\Models\College;
use App\Models\Programme;
use App\Models\Courseclass;
use App\Models\Coursecategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory ,SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table='courses';
    protected $fillable=[
        'course_code',
        'course_name',
        'fullname',
        'special_subject',
        'course_type',
        'course_category_id',
        'college_id',
        'programme_id',
    ];

    public function courseclasses():HasMany
    {
        return $this->hasMany(Courseclass::class,'course_id','id');
    }

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class, 'college_id', 'id');
    }

    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class, 'programme_id', 'id');
    }

    public function coursecategory(): BelongsTo
    {
        return $this->belongsTo(Coursecategory::class, 'course_category_id', 'id');
    }

    public function scopeSearch(Builder $query,string $search)
    {
        return $query->where(function ($subquery) use ($search) {
            $subquery->where('course_code', 'like', "%{$search}%")
            ->orWhere('course_name', 'like', "%{$search}%")
            ->orWhere('fullname', 'like', "%{$search}%");
        });
    }
    
}
