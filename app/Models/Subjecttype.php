<?php

namespace App\Models;

use App\Models\Subject;
use App\Models\Subjecttypemaster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subjecttype extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table='subjecttypes';
    protected $fillable=[
        'type_name',
        'description',
        'active',
    ];

    public function subjects():HasMany
    {
        return $this->hasMany(Subject::class,'subject_type','type_name')->withTrashed();
    }

    public function subjecttypes():HasMany
    {
        return $this->hasMany(Subjecttypemaster::class,'subjecttype_id','id')->withTrashed();
    }

    public function scopeSearch(Builder $query,string $search)
    {
        return $query->where('type_name', 'like', "%{$search}%");
    }
}
