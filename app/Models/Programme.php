<?php

namespace App\Models;

use App\Models\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Programme extends Model
{
    use HasFactory , SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table='programmes';
    protected $fillable=[
        'programme_name',
        'active',
    ];


    public function scopeSearch(Builder $query,string $search)
    {
        return $query->where('programme_name', 'like', "%{$search}%");
    }

    public function courses():HasMany
    {
        return $this->hasMany(Course::class,'programme_id','id')->withTrashed();
    }

}
