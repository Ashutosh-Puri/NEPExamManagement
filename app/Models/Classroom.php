<?php

namespace App\Models;

use App\Models\Building;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classroom extends Model
{
    use HasFactory ,SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table='classrooms';
    protected $fillable=[
       'building_id',
       'class_name',
       'noofbenches',
       'status',
    ];


    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class, 'building_id', 'id');
    }

    public function scopeSearch(Builder $query, string $search)
    {
        return $query
        ->with('building:building_name,id',)
        ->where(function ($query) use ($search) {
            $query->where('class_name', 'like', "%{$search}%")
            ->orWhereHas('building', function ($subQuery) use ($search) {
                 $subQuery->where('building_name', 'like', "%{$search}%");
            });      
        });
    }
}
