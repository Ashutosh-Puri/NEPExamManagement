<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cgpa extends Model
{
    use HasFactory,SoftDeletes; 
    protected $dates = ['deleted_at'];
    protected $table="cgpas";
    protected $fillable = [
        'max_gp',
        'min_gp',
        'grade',
        'description',
    ];

    public function scopeSearch(Builder $query,string $search)
    {
        return $query->where('max_gp', 'like', "%{$search}%")
        ->orWhere('min_gp', 'like', "%{$search}%")
        ->orWhere('grade', 'like', "%{$search}%");
        
    }
}
