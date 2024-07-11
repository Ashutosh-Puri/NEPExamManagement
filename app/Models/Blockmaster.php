<?php

namespace App\Models;

use App\Models\Blockallocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blockmaster extends Model
{
    use HasFactory,SoftDeletes; 
    protected $dates = ['deleted_at'];
    protected $table='blockmasters';
    protected $fillable=[
        'block_name',
        'block_size',
        'status',
    ];

    public function blockallocations()
    {
        return $this->hasMany(Blockallocation::class,'block_id','id');
    }

    public function scopeSearch(Builder $query,string $search)
    {
        return $query
        ->where('block_name', 'like', "%{$search}%")
        ->orWhere('block_size', 'like', "%{$search}%");   
    }
}
