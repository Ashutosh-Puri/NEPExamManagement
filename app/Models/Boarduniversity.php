<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Boarduniversity extends Model
{
    use HasFactory ,SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table='boarduniversities';
    protected $fillable=[
        'boarduniversity_name',
        'is_active'

    ];

    public function scopeSearch(Builder $query,string $search)
    {
        return $query->where(function ($subquery) use ($search) {
            $subquery->where('boarduniversity_name', 'like', "%{$search}%");
           
        });
    }
}
