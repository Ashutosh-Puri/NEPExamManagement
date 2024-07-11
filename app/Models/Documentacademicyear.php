<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Documentacademicyear extends Model
{
    use HasFactory,SoftDeletes;
    protected $dates = ['start_date','end_date','deleted_at'];
    protected $table='document_academic_years';
    protected $fillable=[
        'year_name',
        'active',
        'start_date',
        'end_date',
    ];

    public function scopeSearch(Builder $query, string $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('year_name', 'like', "%{$search}%");
        });
    }
}
