<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paperset extends Model
{
    use HasFactory ,SoftDeletes; 
    protected $dates = ['deleted_at'];
    protected $table='papersets';
    protected $fillable=[
        'set_name',   
    ];

    public function scopeSearch(Builder $query,string $search)
    {
        return $query->where('set_name', 'like', "%{$search}%");
    }
}
