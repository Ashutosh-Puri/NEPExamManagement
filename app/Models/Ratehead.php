<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ratehead extends Model
{
    use HasFactory,SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table='rateheads';
    protected $fillable=[
        'headname',
        'type',
        'noofcredit',
        'course_type',
        'amount',
        'status'
    ];

    public function scopeSearch(Builder $query,string $search)
    {
        return $query->where('headname', 'like', "%{$search}%")
        ->orWhere('type', 'like', "%{$search}%")      
        ->orWhere('course_type', 'like', "%{$search}%")     
        ->orWhere('amount', 'like', "%{$search}%");       
    }
}
