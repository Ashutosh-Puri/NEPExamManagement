<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gradepoint extends Model
{
    use HasFactory,SoftDeletes; 
    protected $table='gradepoints';
    protected $dates = ['deleted_at'];
    protected $guarded=[];
    protected $fillable=[
       ' max_percentage',
        'min_percentage',
        'grade_point',
        'description',
        'grade_name',
    ];

    public function scopeSearch(Builder $query,string $search)
    {
        return $query->where('max_percentage', 'like', "%{$search}%")
        ->orWhere('min_percentage', 'like', "%{$search}%")
        ->orWhere('grade_name', 'like', "%{$search}%");
    }
}
