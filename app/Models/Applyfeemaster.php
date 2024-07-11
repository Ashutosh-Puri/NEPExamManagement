<?php

namespace App\Models;

use App\Models\Examfeemaster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Applyfeemaster extends Model
{
    use HasFactory ,SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table='applyfeemasters';
    protected $fillable=[
        'name', 
    ];

    public function examfees():HasMany
    {
        return $this->hasMany(Examfeemaster::class,'apply_fee_id','id')->withTrashed();
    }

    public function scopeSearch(Builder $query,string $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }
}
