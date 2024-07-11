<?php

namespace App\Models;

use App\Models\Building;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Block extends Model
{
    use HasFactory , SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table='blocks';
    protected $fillable=
    [
        'id',
        'building_id',
        'classname',
        'block',
        'capacity',
        'noofblocks',
        'status'
    ];
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class,'building_id','id')->withTrashed();
    }

    public function scopeSearch(Builder $query,string $search)
    {
        return $query->with('building:building_name,id')->where(function ($subquery) use ($search) {
            $subquery->orWhereHas('building', function ($subQuery) use ($search) {
                $subQuery->where('building_name', 'like', "%{$search}%");
             }) ->orWhere('block', 'like', "%{$search}%")
        ->orWhere('capacity', 'like', "%{$search}%");
        });
        }  
    
}


