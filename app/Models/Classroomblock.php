<?php

namespace App\Models;

use App\Models\Classroom;
use App\Models\Blockmaster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classroomblock extends Model
{
    use HasFactory ,SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table='classroomblocks';
    protected $fillable=[
       'classroom_id',
       'blockmaster_id',
       'status',
    ];

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'classroom_id', 'id');
    }

    public function blockmaster(): BelongsTo
    {
        return $this->belongsTo(Blockmaster::class, 'blockmaster_id', 'id');
    }

    public function scopeSearch(Builder $query, string $search)
    {
        return $query
        ->with('blockmaster:block_name,id')
            ->where(function ($query) use ($search) { 
            $query->orWhereHas('blockmaster', function ($subQuery) use ($search) {
                $subQuery->where('block_name', 'like', "%{$search}%");
            });      
        });
    }
}
