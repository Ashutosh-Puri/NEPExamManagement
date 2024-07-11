<?php

namespace App\Models;

use App\Models\Subjectbucket;
use App\Models\Internaltoolauditor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Academicyear extends Model
{
    use HasFactory ,SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table='academicyears';
    protected $fillable=[
        'year_name',
        'active'
    ];

    public function subjectbuckets():HasMany
    {
        return $this->hasMany(Subjectbucket::class,'academicyear_id','id')->withTrashed();
    }

    public function internaltoolauditors():HasMany
    {
        return $this->hasMany(Internaltoolauditor::class,'academicyear_id','id')->withTrashed();
    }

    // public function scopeSearch(Builder $query, string $search)
    // {
    //     return $query->where(function ($query) use ($search) {
    //         $query->where('year_name', 'like', "%{$search}%");
    //     });
    // }
}
