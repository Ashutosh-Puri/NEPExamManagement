<?php

namespace App\Models;

use App\Models\Classroom;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Building extends Model
{
    use HasFactory,SoftDeletes; 
    protected $dates = ['deleted_at'];
    protected $table='buildings';
    protected $fillable=[
        'building_name',
        'Priority',
        'status',
        
    ];

    public function classrooms()
    {
        return $this->hasMany(Classroom::class,'building_id','id');
    }

    public function scopeSearch(Builder $query,string $search)
    {
        return $query->where('building_name', 'like', "%{$search}%");

    }
}
