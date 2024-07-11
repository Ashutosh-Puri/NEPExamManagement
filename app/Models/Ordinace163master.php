<?php

namespace App\Models;

use App\Models\Studentordinace163;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ordinace163master extends Model
{
    use HasFactory;
    protected $table="ordinace163masters";
    protected $fillable=[
        'activity_name',
        'ordinace_name',
        'status',        
    ];
    
    public function studentordinace163s()
    {
        return $this->hasMany(Studentordinace163::class,'ordinace163master_id','id');
    }


    public function scopeSearch(Builder $query,string $search)
    {
        return $query->where('activity_name', 'like', "%{$search}%")->orWhere('ordinace_name', 'like', "%{$search}%");    
    }
}
