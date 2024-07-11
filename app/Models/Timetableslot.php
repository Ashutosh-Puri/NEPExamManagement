<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Timetableslot extends Model
{
    use HasFactory ,SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable=[
        'timeslot',
        'end_time',
        'start_time',
        'slot',
        'isactive'
    ];

    public function scopeSearch(Builder $query, string $search)
    {
        return $query->where('timeslot', 'like', "%{$search}%")->orWhere('slot', 'like', "%{$search}%");
    }
}
