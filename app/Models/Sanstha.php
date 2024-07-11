<?php

namespace App\Models;

use App\Models\College;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sanstha extends Model
{
    use HasFactory,SoftDeletes; 
    protected $dates = ['deleted_at'];
    protected $table='sansthas';
    protected $fillable = [
        'sanstha_name',
        'sanstha_address',
        'sanstha_chairman_name',
        'sanstha_website_url',
        'sanstha_contact_no',
        'status',
    ];
    
    public function colleges(): HasMany
    {
        return $this->hasMany(College::class,'sanstha_id','id')->withTrashed();
    }

    public function scopeSearch(Builder $query,string $search)
    {
        return $query->where('sanstha_name', 'like', "%{$search}%");
    }
}
