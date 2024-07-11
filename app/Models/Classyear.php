<?php

namespace App\Models;

use App\Models\Subject;
use App\Models\Courseclass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classyear extends Model
{
    use HasFactory , SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table='classyears';
    protected $fillable=[
        'classyear_name',
        'class_degree_name',
        'status',
    ];

    public function subject():HasMany
    {
        return $this->hasMany(Subject::class,'classyear_id','id')->withTrashed();
    }

    public function course_classes():HasMany
    {
        return $this->hasMany(Courseclass::class,'classyear_id','id')->withTrashed();
    }

    public function scopeSearch(Builder $query,string $search)
    {
        return $query->where('classyear_name', 'like', "%{$search}%")->orWhere('class_degree_name', 'like', "%{$search}%");    
    }
}
