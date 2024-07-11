<?php

namespace App\Models;

use App\Models\College;
use App\Models\Courseclass;
use App\Models\Patternclass;
use App\Models\Departmentprefix;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pattern extends Model
{
    use HasFactory , SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table='patterns';
    protected $fillable=[
        'id',
        'pattern_name',
        'pattern_startyear',
        'pattern_valid',
        'status',
        'college_id',
    ];

    public function course_classes(): BelongsToMany
    {
        return $this->belongsToMany(Courseclass::class,'pattern_classes','pattern_id', 'class_id','id')
        ->withPivot('status','sem1_total_marks',
        'sem2_total_marks',
        'sem1_credits',
        'sem2_credits',
        'totalnosubjects',
        'id')
        ->wherePivot('status','1')->withTrashed();
    }

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class,'college_id','id')->withTrashed();
    }

    public function patternclass()
    {
        return $this->hasMany(Patternclass::class,'pattern_id','id')->withTrashed();
    }

    public function colleges()
    {
        return $this->hasMany(College::class,'college_id','id')->withTrashed();
    }

    public function prefixes()
    {
        return $this->hasMany(Departmentprefix::class,'pattern_id','id')->withTrashed();
    }


    public function scopeSearch(Builder $query,string $search)
    {
        return  $query->where('pattern_name', 'like', "%{$search}%");
    }
}